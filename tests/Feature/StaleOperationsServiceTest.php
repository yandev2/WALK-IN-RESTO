<?php

namespace Tests\Feature;

use App\Models\DiningTable;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\User;
use App\Models\Visit;
use App\Services\OrderPaymentService;
use App\Services\StaleOperationsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class StaleOperationsServiceTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_expired_claim_without_checkout_frees_the_table(): void
    {
        $world = $this->createGuestRestaurant();
        $this->claimTable($world);

        $this->travel(11)->minutes();
        app(StaleOperationsService::class)->sweep();

        $visit = Visit::query()->first();
        $table = DiningTable::query()->findOrFail($world['table']->id);

        $this->assertSame('closed', $visit->status);
        $this->assertNull($table->open_visit_id);
        $this->assertFalse($table->needs_cleaning);
        $this->assertSame('available', $table->fresh()->floorStatus());
    }

    public function test_awaiting_cashier_expires_and_releases_qris_hold(): void
    {
        $world = $this->createGuestRestaurant();
        $order = $this->checkoutQris($world, 'ttl-qris-1');
        $hold = $order->payments()->first()->qris_hold_amount;

        $this->assertNotNull($hold);
        $this->assertSame('awaiting_cashier', $order->status);

        $this->travel(21)->minutes();
        $this->artisan('ops:expire-stale')->assertSuccessful();

        $order->refresh();
        $payment = $order->payments()->first();

        $this->assertSame('cancelled', $order->status);
        $this->assertSame('cancelled', $payment->status);
        $this->assertNull($payment->qris_hold_amount);
    }

    public function test_paid_visit_is_not_closed_after_claim_ttl(): void
    {
        $world = $this->createGuestRestaurant();
        $order = $this->checkoutQris($world, 'ttl-paid-1');
        app(OrderPaymentService::class)->approve($order, User::factory()->create());

        $this->travel(30)->minutes();
        app(StaleOperationsService::class)->sweep();

        $this->assertSame('open', Visit::query()->first()->status);
        $this->assertSame(Visit::query()->first()->id, DiningTable::query()->findOrFail($world['table']->id)->open_visit_id);
        $this->assertTrue($order->fresh()->isAccepted());
    }

    public function test_awaiting_order_keeps_visit_open_until_payment_ttl_then_claim_can_close(): void
    {
        $world = $this->createGuestRestaurant();
        $this->checkoutQris($world, 'ttl-wait-1');

        $this->travel(11)->minutes();
        app(StaleOperationsService::class)->sweep();

        $this->assertSame('open', Visit::query()->first()->status);
        $this->assertSame('awaiting_cashier', Order::query()->first()->status);

        $this->travel(10)->minutes();
        app(StaleOperationsService::class)->sweep();

        $this->assertSame('cancelled', Order::query()->first()->status);
        $this->assertSame('closed', Visit::query()->first()->status);
        $this->assertNull(DiningTable::query()->findOrFail($world['table']->id)->open_visit_id);
    }

    public function test_expire_awaiting_ignores_orders_still_within_ttl(): void
    {
        $world = $this->createGuestRestaurant();
        $order = $this->checkoutQris($world, 'ttl-fresh-1');

        $expired = app(OrderPaymentService::class)->expireAwaiting($order);

        $this->assertFalse($expired);
        $this->assertSame('awaiting_cashier', $order->fresh()->status);
        $this->assertSame('awaiting_cashier', $order->payments()->first()->status);
    }

    public function test_future_awaiting_expires_at_is_not_cancelled_by_old_created_at(): void
    {
        $world = $this->createGuestRestaurant();
        $order = $this->checkoutQris($world, 'ttl-future-1');
        $payment = $order->payments()->first();

        $payment->forceFill([
            'created_at' => now()->subHour(),
            'awaiting_expires_at' => now()->addHour(),
        ])->save();

        $this->assertSame(0, app(OrderPaymentService::class)->expireStaleAwaiting());
        $this->assertSame('awaiting_cashier', $order->fresh()->status);
    }

    public function test_simple_mode_guest_visit_auto_closes_5_minutes_after_last_completed_order(): void
    {
        $world = $this->createGuestRestaurant();
        $world['outlet']->update(['simple_mode' => true]);

        $order = $this->checkoutQris($world, 'ttl-simple-1');
        app(OrderPaymentService::class)->approve($order, User::factory()->create());

        $this->assertSame(Order::STATUS_COMPLETED, $order->fresh()->status);
        $this->assertTrue($order->fresh()->items->every(fn ($i) => $i->kds_status === 'served'));
        $visit = $order->visit->fresh();
        $this->assertSame('open', $visit->status);

        // At 2 minutes: sweep() does not close visit yet
        $this->travel(2)->minutes();
        app(StaleOperationsService::class)->sweep();
        $this->assertSame('open', $visit->fresh()->status);

        // At 6 minutes (past 5 minutes): sweep() auto closes visit and frees table
        $this->travel(4)->minutes();
        app(StaleOperationsService::class)->sweep();

        $this->assertSame('closed', $visit->fresh()->status);
        $table = DiningTable::query()->findOrFail($world['table']->id);
        $this->assertNull($table->open_visit_id);
        $this->assertFalse($table->needs_cleaning);
        $this->assertSame('available', $table->floorStatus());
    }

    /**
     * @param  array{token: string}  $world
     */
    private function claimTable(array $world): void
    {
        $device = $this->newDeviceToken();

        $this->withHeaders($this->deviceHeaders($device))
            ->postJson('/api/v1/guest/tables/'.$world['token'].'/claim', [
                'customer_wa' => '081234567890',
            ])
            ->assertCreated();
    }

    /**
     * @param  array{item: MenuItem, token: string}  $world
     */
    private function checkoutQris(array $world, string $key): Order
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

        $publicId = $this->withHeaders($headers)
            ->postJson('/api/v1/guest/checkout', [
                'method' => 'qris',
                'idempotency_key' => $key,
            ])
            ->assertCreated()
            ->json('data.public_id');

        return Order::query()->where('public_id', $publicId)->firstOrFail();
    }
}
