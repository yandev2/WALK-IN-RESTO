<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Services\CashierOrderService;
use App\Services\OrderPaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
