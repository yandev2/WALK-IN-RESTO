<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\OrderPaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class SimpleModeGuestCheckoutTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_guest_qris_checkout_in_simple_mode_bypasses_kds_and_completes_on_approval(): void
    {
        $world = $this->createGuestRestaurant();
        $world['outlet']->update(['simple_mode' => true]);

        $order = $this->checkoutGuestOrder($world, 'qris', 'simple-guest-qris-1');

        // Verify initial state: awaiting cashier payment, but items are marked served (KDS bypassed)
        $this->assertSame('awaiting_cashier', $order->status);
        $this->assertCount(1, $order->items);
        $this->assertSame('served', $order->items->first()->kds_status);
        $this->assertNotNull($order->items->first()->served_at);

        // Verify it does NOT appear in active KDS queue
        $kdsCount = OrderItem::query()
            ->whereIn('kds_status', ['queued', 'preparing', 'ready'])
            ->where('order_id', $order->id)
            ->count();
        $this->assertSame(0, $kdsCount);

        // Approve payment
        $cashier = User::factory()->create();
        app(OrderPaymentService::class)->approve($order, $cashier);

        // Verify order is immediately completed without kitchen action
        $freshOrder = $order->fresh(['items', 'payments']);
        $this->assertSame(Order::STATUS_COMPLETED, $freshOrder->status);
        $this->assertSame('paid', $freshOrder->payments->first()->status);
        $this->assertSame('served', $freshOrder->items->first()->kds_status);
    }

    public function test_guest_cash_checkout_in_simple_mode_bypasses_kds_and_completes_on_approval(): void
    {
        $world = $this->createGuestRestaurant();
        $world['outlet']->update(['simple_mode' => true]);

        $order = $this->checkoutGuestOrder($world, 'cash', 'simple-guest-cash-1');

        $this->assertSame('awaiting_cashier', $order->status);
        $this->assertSame('served', $order->items->first()->kds_status);
        $this->assertNotNull($order->items->first()->served_at);

        // Approve cash payment with cash received
        $cashier = User::factory()->create();
        app(OrderPaymentService::class)->approve($order, $cashier, gpsOverrideReason: 'Tamu di kasir', cashReceived: 50000);

        $freshOrder = $order->fresh(['items', 'payments']);
        $this->assertSame(Order::STATUS_COMPLETED, $freshOrder->status);
        $this->assertSame('paid', $freshOrder->payments->first()->status);
    }

    public function test_guest_checkout_in_standard_mode_still_requires_kitchen(): void
    {
        $world = $this->createGuestRestaurant();
        $world['outlet']->update(['simple_mode' => false]);

        $order = $this->checkoutGuestOrder($world, 'qris', 'standard-guest-qris-1');

        // Standard mode items must be queued for the kitchen
        $this->assertSame('awaiting_cashier', $order->status);
        $this->assertSame('queued', $order->items->first()->kds_status);
        $this->assertNull($order->items->first()->served_at);

        // Approve payment
        $cashier = User::factory()->create();
        app(OrderPaymentService::class)->approve($order, $cashier);

        // In standard mode, approving payment leaves order in_production until kitchen finishes
        $freshOrder = $order->fresh(['items']);
        $this->assertSame('in_production', $freshOrder->status);
        $this->assertSame('queued', $freshOrder->items->first()->kds_status);
    }

    private function checkoutGuestOrder(array $world, string $method, string $key): Order
    {
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

        $payload = [
            'method' => $method,
            'idempotency_key' => $key,
        ];

        if ($method === 'cash') {
            // Include restaurant GPS coords to satisfy GPS check
            $payload['lat'] = -6.200000;
            $payload['lng'] = 106.816666;
            $payload['accuracy'] = 10;
        }

        $publicId = $this->withHeaders($headers)
            ->postJson('/api/v1/guest/checkout', $payload)
            ->assertCreated()
            ->json('data.public_id');

        return Order::query()->where('public_id', $publicId)->firstOrFail();
    }
}
