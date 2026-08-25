<?php

namespace Tests\Feature;

use App\Models\OrderReceipt;
use App\Models\User;
use App\Models\WhatsappMessage;
use App\Services\CashierOrderService;
use App\Services\OrderPaymentService;
use App\Services\OrderReceiptService;
use App\Support\ReceiptLogo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class OrderReceiptServiceTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_approve_always_stores_pdf_and_skips_fonnte_without_key(): void
    {
        Storage::fake('local');

        $world = $this->createGuestRestaurant();
        $order = $this->paidGuestOrder($world, 'receipt-no-key');

        $this->assertNotNull($order->fresh()->receipt);
        $this->assertTrue(Storage::disk('local')->exists($order->receipt->file_path));
        $this->assertSame(0, WhatsappMessage::query()->count());
        $this->assertTrue($order->isAccepted());
    }

    public function test_approve_queues_fonnte_when_key_and_send_receipt(): void
    {
        Storage::fake('local');
        Http::fake([
            'https://api.fonnte.com/send' => Http::response(['status' => true, 'id' => 'wa-1'], 200),
        ]);

        $world = $this->createGuestRestaurant();
        $world['restaurant']->update([
            'fonnte_api_key_encrypted' => Crypt::encryptString('test-fonnte-token'),
        ]);

        $order = $this->paidGuestOrder($world, 'receipt-send', sendReceipt: true);

        $this->assertSame(1, WhatsappMessage::query()->count());
        $message = WhatsappMessage::query()->first();
        $this->assertSame('sent', $message->status);
        $this->assertSame(1, $message->attempts);
        $this->assertStringContainsString('Unduh PDF', $message->body);
        $this->assertStringContainsString('/receipts/', $message->body);
        $this->assertStringContainsString('Pesanan:', $message->body);
        $this->assertStringContainsString('Total: Rp', $message->body);

        Http::assertSent(function ($request): bool {
            if ($request->url() !== 'https://api.fonnte.com/send') {
                return false;
            }

            if (! $request->hasHeader('Authorization', 'test-fonnte-token')) {
                return false;
            }

            $body = $request->body();

            return ! str_contains($body, 'Content-Disposition: form-data; name="file"')
                && str_contains($body, 'Unduh PDF');
        });
    }

    public function test_fonnte_failure_keeps_order_paid_and_marks_failed(): void
    {
        Storage::fake('local');
        Http::fake([
            'https://api.fonnte.com/send' => Http::response(['status' => false, 'reason' => 'timeout'], 500),
        ]);

        $world = $this->createGuestRestaurant();
        $world['restaurant']->update([
            'fonnte_api_key_encrypted' => Crypt::encryptString('test-fonnte-token'),
        ]);

        $order = $this->paidGuestOrder($world, 'receipt-fail', sendReceipt: true);

        $this->assertTrue($order->fresh()->isAccepted());
        $message = WhatsappMessage::query()->first();
        $this->assertSame('failed', $message?->status);
        $this->assertStringContainsString('timeout', (string) $message?->last_error);
        $this->assertGreaterThanOrEqual(1, $message?->attempts);
    }

    public function test_token_invalid_fails_immediately_without_extra_retries(): void
    {
        Storage::fake('local');
        Http::fake([
            'https://api.fonnte.com/send' => Http::response(['status' => false, 'reason' => 'token invalid'], 200),
        ]);

        $world = $this->createGuestRestaurant();
        $world['restaurant']->update([
            'fonnte_api_key_encrypted' => Crypt::encryptString('bad-token'),
        ]);

        $this->paidGuestOrder($world, 'receipt-token-invalid', sendReceipt: true);

        $message = WhatsappMessage::query()->first();
        $this->assertSame('failed', $message?->status);
        $this->assertSame(1, $message?->attempts);
        $this->assertSame('Token Fonnte tidak valid. Periksa API key di Profil CMS.', $message?->last_error);
    }

    public function test_send_receipt_without_wa_records_failed_message(): void
    {
        Storage::fake('local');
        Http::fake();

        $world = $this->createGuestRestaurant();
        $world['restaurant']->update([
            'fonnte_api_key_encrypted' => Crypt::encryptString('test-fonnte-token'),
        ]);

        $order = $this->paidGuestOrder($world, 'receipt-no-wa', sendReceipt: false);
        $order->visit?->update(['customer_wa' => '']);
        $order->forceFill([
            'send_receipt' => true,
            'receipt_wa_snapshot' => null,
        ])->save();

        app(OrderReceiptService::class)->afterPaid($order->fresh(['restaurant', 'visit']), null);

        Http::assertNothingSent();
        $message = WhatsappMessage::query()->first();
        $this->assertSame('failed', $message?->status);
        $this->assertSame('Nomor WhatsApp tamu belum ada.', $message?->last_error);
    }

    public function test_invalid_wa_records_failed_message(): void
    {
        Storage::fake('local');
        Http::fake();

        $world = $this->createGuestRestaurant();
        $world['restaurant']->update([
            'fonnte_api_key_encrypted' => Crypt::encryptString('test-fonnte-token'),
        ]);

        $order = $this->paidGuestOrder($world, 'receipt-bad-wa', sendReceipt: false);
        $order->forceFill([
            'send_receipt' => true,
            'receipt_wa_snapshot' => '123',
        ])->save();

        app(OrderReceiptService::class)->afterPaid($order->fresh(['restaurant', 'visit']), null);

        Http::assertNothingSent();
        $message = WhatsappMessage::query()->first();
        $this->assertSame('failed', $message?->status);
        $this->assertStringContainsString('tidak valid', (string) $message?->last_error);
    }

    public function test_resend_creates_new_row_and_rejects_empty_key(): void
    {
        Storage::fake('local');
        Http::fake([
            'https://api.fonnte.com/send' => Http::response(['status' => true, 'id' => 'wa-2'], 200),
        ]);

        $world = $this->createGuestRestaurant();
        $world['restaurant']->update([
            'fonnte_api_key_encrypted' => Crypt::encryptString('test-fonnte-token'),
        ]);

        $order = $this->paidGuestOrder($world, 'receipt-resend', sendReceipt: true);
        $user = User::factory()->create();

        app(OrderReceiptService::class)->resend($order->fresh(), $user);

        $this->assertSame(2, WhatsappMessage::query()->count());

        $world['restaurant']->update(['fonnte_api_key_encrypted' => null]);

        $this->expectException(ValidationException::class);
        app(OrderReceiptService::class)->resend($order->fresh(['restaurant']), $user);
    }

    public function test_receipt_row_is_idempotent(): void
    {
        Storage::fake('local');
        $world = $this->createGuestRestaurant();
        $order = $this->paidGuestOrder($world, 'receipt-once');

        $second = app(OrderReceiptService::class)->generate($order->fresh());

        $this->assertSame(1, OrderReceipt::query()->count());
        $this->assertSame($order->receipt->id, $second->id);
    }

    public function test_pdf_receipt_uses_thermal_layout_without_wifi_or_at(): void
    {
        Storage::fake('local');

        $world = $this->createGuestRestaurant();
        $world['outlet']->update(['phone' => '081234567890']);
        $order = $this->paidGuestOrder($world, 'receipt-layout');
        $order->loadMissing(['items.modifiers', 'visit.diningTable', 'restaurant', 'outlet', 'payments.paidByUser']);

        $html = view('receipts.order', [
            'order' => $order,
            'payment' => $order->payments->first(),
            'logoDataUri' => ReceiptLogo::dataUri($order->restaurant),
        ])->render();

        $this->assertStringContainsString('Terima kasih telah berkunjung.', $html);
        $this->assertStringContainsString('Kode Struk', $html);
        $this->assertStringContainsString('WHATSAPP:', $html);
        $this->assertStringContainsString('Bukan faktur pajak resmi.', $html);
        $this->assertStringNotContainsString('Pass Wifi', $html);
        $this->assertDoesNotMatchRegularExpression('/x\d+\s+@/', $html);
        $this->assertStringNotContainsString('Kembalian', $html);

        $pdf = Storage::disk('local')->get($order->receipt->file_path);
        $this->assertNotFalse($pdf);
        $this->assertStringStartsWith('%PDF', $pdf);
        $this->assertStringContainsString((string) $order->payments->first()?->paidByUser?->name, $html);
    }

    public function test_cash_receipt_includes_tender_and_change(): void
    {
        Storage::fake('local');

        $world = $this->createGuestRestaurant();
        $user = User::factory()->create();
        $order = app(CashierOrderService::class)->create(
            $user,
            $world['table'],
            null,
            'Walk-in',
            'cash',
            false,
            [['menu_item_id' => $world['item']->id, 'qty' => 1]],
            50000,
        );

        app(OrderPaymentService::class)->approve($order, $user);

        $order->refresh()->loadMissing(['items.modifiers', 'visit.diningTable', 'restaurant', 'outlet', 'payments.paidByUser']);
        $payment = $order->payments->first();

        $html = view('receipts.order', [
            'order' => $order,
            'payment' => $payment,
            'logoDataUri' => ReceiptLogo::dataUri($order->restaurant),
        ])->render();

        $this->assertStringContainsString('TUNAI', $html);
        $this->assertStringContainsString('Bayar', $html);
        $this->assertStringContainsString('Kembalian', $html);
        $this->assertStringContainsString(number_format((int) $payment->cash_received, 0, ',', '.'), $html);
        $this->assertStringContainsString(number_format((int) $payment->change_amount, 0, ',', '.'), $html);
    }
}
