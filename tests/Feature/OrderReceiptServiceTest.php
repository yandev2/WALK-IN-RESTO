<?php

namespace Tests\Feature;

use App\Models\OrderReceipt;
use App\Models\User;
use App\Models\WhatsappMessage;
use App\Services\OrderReceiptService;
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

        Http::assertSent(function ($request): bool {
            return $request->url() === 'https://api.fonnte.com/send'
                && $request->hasHeader('Authorization', 'test-fonnte-token');
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
        $this->assertSame('failed', WhatsappMessage::query()->first()?->status);
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
}
