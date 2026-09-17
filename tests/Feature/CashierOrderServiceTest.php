<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\WhatsappMessage;
use App\Services\CashierOrderService;
use App\Services\OrderPaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class CashierOrderServiceTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_cashier_cash_order_skips_gps_and_awaits_approve(): void
    {
        $world = $this->createGuestRestaurant();
        $user = User::factory()->create();

        $order = app(CashierOrderService::class)->create(
            $user,
            $world['table'],
            '081234567890',
            'Walk-in',
            'cash',
            false,
            [['menu_item_id' => $world['item']->id, 'qty' => 2, 'notes' => 'tanpa es']],
        );

        $this->assertSame('cashier', $order->source);
        $this->assertSame('awaiting_cashier', $order->status);
        $this->assertSame($user->id, $order->created_by_user_id);
        $this->assertSame($user->id, $order->visit->opened_by_user_id);
        $this->assertNotNull($order->visit->join_pin);
        $this->assertSame(4, strlen($order->visit->join_pin));
        $this->assertSame('not_required', $order->payments()->first()->gps_status);
        $this->assertSame(2, $order->items()->first()->qty);
        $this->assertNull($order->payments()->first()->cash_received);

        app(OrderPaymentService::class)->approve($order, $user);

        $this->assertTrue($order->fresh()->isAccepted());
        $this->assertSame('queued', $order->items()->first()->kds_status);
    }

    public function test_second_approve_is_rejected(): void
    {
        $world = $this->createGuestRestaurant();
        $user = User::factory()->create();

        $order = app(CashierOrderService::class)->create(
            $user,
            $world['table'],
            '081234567890',
            null,
            'cash',
            false,
            [['menu_item_id' => $world['item']->id, 'qty' => 1]],
        );

        app(OrderPaymentService::class)->approve($order, $user);

        $this->expectException(ValidationException::class);
        app(OrderPaymentService::class)->approve($order->fresh(), $user);
    }

    public function test_cashier_qris_still_gets_unique_amount(): void
    {
        $world = $this->createGuestRestaurant();
        $user = User::factory()->create();
        $service = app(CashierOrderService::class);

        $first = $service->create(
            $user,
            $world['table'],
            '081234567890',
            null,
            'qris',
            false,
            [['menu_item_id' => $world['item']->id, 'qty' => 1]],
        );

        $second = $service->create(
            $user,
            $world['otherTable'],
            '081298765432',
            null,
            'qris',
            false,
            [['menu_item_id' => $world['item']->id, 'qty' => 1]],
        );

        $holdA = Payment::query()->where('order_id', $first->id)->value('qris_hold_amount');
        $holdB = Payment::query()->where('order_id', $second->id)->value('qris_hold_amount');

        $this->assertNotNull($holdA);
        $this->assertNotNull($holdB);
        $this->assertNotSame($holdA, $holdB);
        $this->assertSame((int) $first->grand_before + (int) $first->payments()->first()->unique_add, (int) $holdA);
    }

    public function test_cashier_order_allows_missing_whatsapp(): void
    {
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
        );

        $this->assertNull($order->visit->customer_wa);
        $this->assertFalse($order->send_receipt);
        $this->assertNull($order->receipt_wa_snapshot);
    }

    public function test_cashier_send_receipt_without_whatsapp_fails(): void
    {
        $world = $this->createGuestRestaurant();
        $user = User::factory()->create();

        $this->expectException(ValidationException::class);

        app(CashierOrderService::class)->create(
            $user,
            $world['table'],
            null,
            null,
            'cash',
            true,
            [['menu_item_id' => $world['item']->id, 'qty' => 1]],
        );
    }

    public function test_cashier_cash_tender_stores_change_and_rejects_short_amount(): void
    {
        $world = $this->createGuestRestaurant();
        $user = User::factory()->create();
        $service = app(CashierOrderService::class);

        $order = $service->create(
            $user,
            $world['table'],
            '081234567890',
            null,
            'cash',
            false,
            [['menu_item_id' => $world['item']->id, 'qty' => 1]],
            '20.000',
        );

        $payment = $order->payments()->first();
        $this->assertSame((int) $order->grand_payable, 9240);
        $this->assertSame(20000, (int) $payment->cash_received);
        $this->assertSame(20000 - 9240, (int) $payment->change_amount);

        $this->expectException(ValidationException::class);
        $service->create(
            $user,
            $world['otherTable'],
            null,
            null,
            'cash',
            false,
            [['menu_item_id' => $world['item']->id, 'qty' => 1]],
            5000,
        );
    }

    public function test_cashier_qris_does_not_store_cash_tender(): void
    {
        $world = $this->createGuestRestaurant();
        $user = User::factory()->create();

        $order = app(CashierOrderService::class)->create(
            $user,
            $world['table'],
            '081234567890',
            null,
            'qris',
            false,
            [['menu_item_id' => $world['item']->id, 'qty' => 1]],
            50000,
        );

        $payment = $order->payments()->first();
        $this->assertNull($payment->cash_received);
        $this->assertNull($payment->change_amount);
    }

    public function test_approve_guest_cash_can_record_tender(): void
    {
        $world = $this->createGuestRestaurant();
        $device = $this->newDeviceToken();
        $headers = $this->deviceHeaders($device);

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/tables/'.$world['token'].'/claim', [
                'customer_wa' => '081234567890',
            ])
            ->assertCreated();

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/cart/items', [
                'menu_item_id' => $world['item']->id,
            ])
            ->assertCreated();

        $publicId = $this->withHeaders($headers)
            ->postJson('/api/v1/guest/checkout', [
                'method' => 'cash',
                'idempotency_key' => 'guest-cash-tender-1',
                'gps' => [
                    'lat' => -6.2000000,
                    'lng' => 106.8166667,
                    'accuracy' => 10,
                ],
            ])
            ->assertCreated()
            ->json('data.public_id');

        $order = Order::query()->where('public_id', $publicId)->firstOrFail();
        $cashier = User::factory()->create();

        app(OrderPaymentService::class)->approve($order, $cashier, null, '50.000');

        $payment = $order->fresh()->payments()->first();
        $this->assertSame(50000, (int) $payment->cash_received);
        $this->assertSame(50000 - (int) $order->grand_payable, (int) $payment->change_amount);
    }

    public function test_cashier_order_in_simple_mode_completes_instantly_and_frees_table(): void
    {
        $world = $this->createGuestRestaurant();
        $world['outlet']->update(['simple_mode' => true]);
        $user = User::factory()->create();

        $order = app(CashierOrderService::class)->create(
            $user,
            $world['table'],
            '081234567890',
            'Tamu Prasmanan',
            'cash',
            false,
            [['menu_item_id' => $world['item']->id, 'qty' => 1]],
            50000,
        );

        $this->assertSame('cashier', $order->source);
        $this->assertSame(Order::STATUS_COMPLETED, $order->status);
        $this->assertNotNull($order->paid_at);

        $payment = $order->payments()->first();
        $this->assertSame('paid', $payment->status);
        $this->assertNotNull($payment->paid_at);
        $this->assertSame($user->id, $payment->paid_by_user_id);
        $this->assertSame(50000, (int) $payment->cash_received);
        $this->assertSame(50000 - (int) $order->grand_payable, (int) $payment->change_amount);

        $item = $order->items()->first();
        $this->assertSame('served', $item->kds_status);
        $this->assertNotNull($item->served_at);

        // Verify visit is closed and table is released immediately without needing cleaning
        $this->assertSame('closed', $order->visit->fresh()->status);
        $this->assertNull($world['table']->fresh()->open_visit_id);
        $this->assertFalse($world['table']->fresh()->needs_cleaning);
        $this->assertSame('available', $world['table']->fresh()->floorStatus());

        // Verify a second cashier order can be made on the same table immediately
        $secondOrder = app(CashierOrderService::class)->create(
            $user,
            $world['table'],
            '081298765432',
            'Tamu Prasmanan 2',
            'cash',
            false,
            [['menu_item_id' => $world['item']->id, 'qty' => 2]],
            100000,
        );

        $this->assertSame(Order::STATUS_COMPLETED, $secondOrder->status);
        $this->assertNull($world['table']->fresh()->open_visit_id);
        $this->assertFalse($world['table']->fresh()->needs_cleaning);
        $this->assertSame('available', $world['table']->fresh()->floorStatus());
    }

    public function test_closing_visit_in_simple_mode_leaves_table_ready_without_cleaning(): void
    {
        $world = $this->createGuestRestaurant();
        $world['outlet']->update(['simple_mode' => true]);
        $user = User::factory()->create();

        $visit = app(\App\Services\VisitClaimService::class)->openByCashier(
            $world['table'],
            $user,
            null,
            'Walk-in Simple',
        );

        $this->assertSame('open', $visit->fresh()->status);
        $this->assertSame($visit->id, $world['table']->fresh()->open_visit_id);

        app(\App\Services\VisitLifecycleService::class)->closeByCashier($visit, $user);

        $this->assertSame('closed', $visit->fresh()->status);
        $table = $world['table']->fresh();
        $this->assertNull($table->open_visit_id);
        $this->assertFalse($table->needs_cleaning);
        $this->assertSame('available', $table->floorStatus());
    }

    public function test_cashier_order_in_simple_mode_auto_sends_whatsapp_receipt_post_commit_when_send_receipt_true(): void
    {
        Storage::fake('local');
        Http::fake([
            'https://api.fonnte.com/send' => Http::response(['status' => true, 'id' => 'wa-simple-1'], 200),
        ]);

        $world = $this->createGuestRestaurant();
        $world['outlet']->update(['simple_mode' => true]);
        $world['restaurant']->update([
            'fonnte_api_key_encrypted' => Crypt::encryptString('test-fonnte-token'),
        ]);

        $user = User::factory()->create();

        $order = app(CashierOrderService::class)->create(
            $user,
            $world['table'],
            '081234567890',
            'Tamu Simple WA',
            'cash',
            true,
            [['menu_item_id' => $world['item']->id, 'qty' => 1]],
            50000,
        );

        $this->assertSame(Order::STATUS_COMPLETED, $order->status);
        $this->assertTrue((bool) $order->send_receipt);

        $this->assertSame(1, WhatsappMessage::query()->count());
        $message = WhatsappMessage::query()->first();
        $this->assertSame('sent', $message->status);
        $this->assertSame('6281234567890', $message->to_wa);
        $this->assertStringContainsString('Pesanan:', $message->body);
        $this->assertStringContainsString('Total: Rp', $message->body);

        Http::assertSent(function ($request): bool {
            return $request->url() === 'https://api.fonnte.com/send'
                && $request->hasHeader('Authorization', 'test-fonnte-token')
                && str_contains($request->body(), 'Unduh PDF');
        });
    }

    public function test_cashier_order_in_simple_mode_skips_whatsapp_receipt_when_send_receipt_false(): void
    {
        Storage::fake('local');
        Http::fake();

        $world = $this->createGuestRestaurant();
        $world['outlet']->update(['simple_mode' => true]);
        $world['restaurant']->update([
            'fonnte_api_key_encrypted' => Crypt::encryptString('test-fonnte-token'),
        ]);

        $user = User::factory()->create();

        $order = app(CashierOrderService::class)->create(
            $user,
            $world['table'],
            '081234567890',
            'Tamu Tanpa WA',
            'cash',
            false,
            [['menu_item_id' => $world['item']->id, 'qty' => 1]],
            50000,
        );

        $this->assertSame(Order::STATUS_COMPLETED, $order->status);
        $this->assertFalse((bool) $order->send_receipt);
        $this->assertSame(0, WhatsappMessage::query()->count());
        Http::assertNothingSent();
    }
}

