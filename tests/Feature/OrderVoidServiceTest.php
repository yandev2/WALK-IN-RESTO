<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\DailyOmzetService;
use App\Services\KdsItemService;
use App\Services\OrderVoidService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class OrderVoidServiceTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_queued_void_cuts_omzet_and_leaves_kds(): void
    {
        $world = $this->createGuestRestaurant();
        $food = $this->extraMenuItem($world);
        $order = $this->paidGuestOrder($world, 'void-cut-1', [$world['item']->id, $food->id]);
        $grandBefore = (int) $order->grand_before;

        $queued = $order->items->firstWhere('menu_item_id', $world['item']->id);
        $kept = $order->items->firstWhere('menu_item_id', $food->id);

        app(OrderVoidService::class)->voidItem($queued, User::factory()->create(), 'salah pesan');

        $queued->refresh();
        $order->refresh();

        $this->assertSame('voided', $queued->kds_status);
        $this->assertSame('cut', $queued->void_omzet_policy);
        $this->assertSame($grandBefore, (int) $order->grand_before);
        $this->assertSame('in_production', $order->status);
        $this->assertSame('queued', $kept->fresh()->kds_status);

        $omzet = app(DailyOmzetService::class)->forRestaurant($world['restaurant']);
        $this->assertSame($grandBefore - ((int) $queued->unit_price * (int) $queued->qty), $omzet['omzet']);

        $this->assertSame(0, OrderItem::query()
            ->where('order_id', $order->id)
            ->whereIn('kds_status', ['queued', 'preparing', 'ready'])
            ->whereKey($queued->id)
            ->count());

        $this->assertTrue(Activity::query()->where('event', 'order.void_item')->exists());
    }

    public function test_preparing_void_is_waste_and_keeps_omzet(): void
    {
        $world = $this->createGuestRestaurant();
        $order = $this->paidGuestOrder($world, 'void-waste-1');
        $item = $order->items()->first();
        $cook = User::factory()->create();

        app(KdsItemService::class)->advance($item->refresh(), $cook);

        $this->assertSame('preparing', $item->fresh()->kds_status);

        $grandBefore = (int) $order->grand_before;
        app(OrderVoidService::class)->voidItem($item->refresh(), $cook, 'komplain');

        $this->assertSame('waste', $item->fresh()->void_omzet_policy);
        $this->assertSame('voided', $order->fresh()->status);
        $this->assertNotNull($order->fresh()->voided_at);
        $this->assertSame($grandBefore, (int) $order->fresh()->grand_before);

        $omzet = app(DailyOmzetService::class)->forRestaurant($world['restaurant']);
        $this->assertSame($grandBefore, $omzet['omzet']);
        $this->assertSame(1, $omzet['voided']);
        $this->assertSame(0, $omzet['count']);
        $this->assertSame((int) $item->unit_price * (int) $item->qty, $omzet['waste']);
    }

    public function test_void_all_queued_items_voids_order_and_nets_zero_omzet(): void
    {
        $world = $this->createGuestRestaurant();
        $order = $this->paidGuestOrder($world, 'void-all-1');
        $user = User::factory()->create();

        app(OrderVoidService::class)->voidOrder($order, $user, 'batal semua');

        $order->refresh();
        $item = $order->items()->first();

        $this->assertSame('voided', $order->status);
        $this->assertNotNull($order->voided_at);
        $this->assertSame('cut', $item->void_omzet_policy);
        $this->assertSame(0, OrderItem::query()
            ->where('order_id', $order->id)
            ->whereIn('kds_status', ['queued', 'preparing', 'ready'])
            ->count());

        $omzet = app(DailyOmzetService::class)->forRestaurant($world['restaurant']);
        $this->assertSame(0, $omzet['omzet']);
        $this->assertTrue(Activity::query()->where('event', 'order.void')->exists());
    }

    public function test_unpaid_order_cannot_be_voided(): void
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

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/checkout', [
                'method' => 'qris',
                'idempotency_key' => 'void-unpaid',
            ])
            ->assertCreated();

        $order = Order::query()->where('idempotency_key', 'void-unpaid')->firstOrFail();

        $this->expectException(ValidationException::class);
        app(OrderVoidService::class)->voidOrder($order, User::factory()->create(), 'terlalu cepat');
    }
}
