<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Restaurant;
use App\Models\User;
use App\Services\OrderPaymentService;
use App\Services\RestaurantAnalyticsService;
use App\Support\RestaurantAnalyticsPeriod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class RestaurantAnalyticsServiceTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_snapshot_includes_today_vs_yesterday_comparison(): void
    {
        $world = $this->createGuestRestaurant();
        $this->placeAndPay($world, 'analytics-today');

        $range = RestaurantAnalyticsPeriod::defaultLocalDateRange($world['restaurant']);
        $snapshot = app(RestaurantAnalyticsService::class)->buildSnapshot(
            $world['restaurant'],
            $range['from'],
            $range['to'],
        );

        $this->assertSame(7, $snapshot['day_count']);
        $this->assertSame($range['from']->toDateString(), $snapshot['date_from']);
        $this->assertSame($range['to']->toDateString(), $snapshot['date_to']);
        $this->assertGreaterThan(0, $snapshot['today']['omzet']);
        $this->assertSame(0, $snapshot['yesterday']['omzet']);
        $this->assertNull($snapshot['comparison']['omzet_delta_pct']);
        $this->assertGreaterThan(0, $snapshot['comparison']['aov_today']);
    }

    public function test_snapshot_supports_custom_date_range(): void
    {
        $world = $this->createGuestRestaurant();
        $today = RestaurantAnalyticsPeriod::localToday($world['restaurant']);
        $from = $today->copy()->subDays(13);

        $snapshot = app(RestaurantAnalyticsService::class)->buildSnapshot(
            $world['restaurant'],
            $from,
            $today,
        );

        $this->assertSame(14, $snapshot['day_count']);
        $this->assertSame($from->toDateString(), $snapshot['date_from']);
        $this->assertSame($today->toDateString(), $snapshot['date_to']);
        $this->assertCount(14, $snapshot['trend']);
    }

    public function test_daily_trend_buckets_by_restaurant_timezone(): void
    {
        $world = $this->createGuestRestaurant();
        $restaurant = $world['restaurant'];
        $restaurant->update(['timezone' => 'Asia/Jakarta']);

        $order = $this->placeAndPay($world, 'analytics-tz');
        $paidAt = Carbon::parse('2026-08-19 17:30:00', 'Asia/Jakarta')->utc();
        $order->update(['paid_at' => $paidAt]);

        Carbon::setTestNow(Carbon::parse('2026-08-19 18:00:00', 'Asia/Jakarta'));

        $range = RestaurantAnalyticsPeriod::defaultLocalDateRange($restaurant);
        $snapshot = app(RestaurantAnalyticsService::class)->buildSnapshot(
            $restaurant,
            $range['from'],
            $range['to'],
        );
        $todayPoint = collect($snapshot['trend'])->firstWhere('date', '2026-08-19');

        $this->assertNotNull($todayPoint);
        $this->assertSame(1, $todayPoint['count']);
        $this->assertGreaterThan(0, $todayPoint['omzet']);

        Carbon::setTestNow();
    }

    public function test_top_menu_items_exclude_void_cut(): void
    {
        $world = $this->createGuestRestaurant();
        $order = $this->placeAndPay($world, 'analytics-top');

        OrderItem::query()->where('order_id', $order->id)->update([
            'voided_at' => now(),
            'void_omzet_policy' => 'cut',
        ]);

        $range = RestaurantAnalyticsPeriod::defaultLocalDateRange($world['restaurant']);
        $items = app(RestaurantAnalyticsService::class)->topMenuItems(
            $world['restaurant'],
            $range['from'],
            $range['to'],
        );

        $this->assertSame([], $items);
    }

    public function test_payment_mix_splits_qris_and_cash(): void
    {
        $world = $this->createGuestRestaurant();
        $this->placeAndPay($world, 'analytics-qris', 'qris');

        $range = RestaurantAnalyticsPeriod::defaultLocalDateRange($world['restaurant']);
        $snapshot = app(RestaurantAnalyticsService::class)->buildSnapshot(
            $world['restaurant'],
            $range['from'],
            $range['to'],
        );

        $this->assertGreaterThan(0, $snapshot['payment_mix']['qris']);
        $this->assertSame(0, $snapshot['payment_mix']['cash']);
        $this->assertSame(
            $snapshot['payment_mix']['qris'],
            $snapshot['payment_mix']['total'],
        );
    }

    /**
     * @param  array{restaurant: Restaurant, item: MenuItem, token: string}  $world
     */
    private function placeAndPay(array $world, string $idempotencyKey, string $method = 'qris'): Order
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
                'method' => $method,
                'idempotency_key' => $idempotencyKey,
            ])
            ->assertCreated()
            ->json('data.public_id');

        $order = Order::query()->where('public_id', $publicId)->firstOrFail();
        app(OrderPaymentService::class)->approve($order, User::factory()->create());

        return $order->fresh(['items']);
    }
}
