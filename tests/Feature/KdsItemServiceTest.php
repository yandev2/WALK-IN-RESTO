<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Services\KdsItemService;
use App\Services\OrderPaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class KdsItemServiceTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_paid_order_enters_kitchen_then_completes(): void
    {
        $order = $this->paidOrder();
        $item = $order->items()->first();
        $user = User::factory()->create();

        $this->assertSame('in_production', $order->status);
        $this->assertSame('queued', $item->kds_status);

        $kds = app(KdsItemService::class);
        $kds->advance($item->refresh(), $user);
        $this->assertSame('preparing', $item->fresh()->kds_status);

        $kds->advance($item->refresh(), $user);
        $this->assertSame('ready', $item->fresh()->kds_status);

        $kds->advance($item->refresh(), $user);
        $this->assertSame('served', $item->fresh()->kds_status);
        $this->assertSame('completed', $order->fresh()->status);
    }

    public function test_served_can_revert_only_within_two_minutes(): void
    {
        $order = $this->paidOrder();
        $item = $order->items()->first();
        $user = User::factory()->create();
        $kds = app(KdsItemService::class);

        $kds->advance($item->refresh(), $user);
        $kds->advance($item->refresh(), $user);
        $kds->advance($item->refresh(), $user);

        $kds->revertServed($item->refresh(), $user);
        $this->assertSame('ready', $item->fresh()->kds_status);

        $kds->advance($item->refresh(), $user);
        $this->travel(3)->minutes();

        $this->expectException(ValidationException::class);
        $kds->revertServed($item->refresh(), $user);
    }

    private function paidOrder(): Order
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
                'method' => 'qris',
                'idempotency_key' => 'kds-order-1',
            ])
            ->assertCreated()
            ->json('data.public_id');

        $order = Order::query()->where('public_id', $publicId)->firstOrFail();
        app(OrderPaymentService::class)->approve($order, User::factory()->create());

        return $order->fresh(['items']);
    }
}
