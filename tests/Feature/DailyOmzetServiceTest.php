<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use App\Services\DailyOmzetService;
use App\Services\KdsItemService;
use App\Services\OrderPaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class DailyOmzetServiceTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_counts_in_production_and_completed_orders(): void
    {
        $world = $this->createGuestRestaurant();
        $order = $this->placeAndPay($world, 'omzet-1');

        $this->assertSame('in_production', $order->status);
        $this->assertGreaterThan(0, $order->grand_before);

        $summary = app(DailyOmzetService::class)->forRestaurant($world['restaurant']);

        $this->assertSame((int) $order->grand_before, $summary['omzet']);
        $this->assertSame(1, $summary['count']);
        $this->assertSame((int) $order->grand_before, $summary['qris']);
        $this->assertSame(0, $summary['cash']);

        $item = $order->items()->first();
        $cook = User::factory()->create();
        $kds = app(KdsItemService::class);
        $kds->advance($item->refresh(), $cook);
        $kds->advance($item->refresh(), $cook);
        $kds->advance($item->refresh(), $cook);

        $this->assertSame('completed', $order->fresh()->status);

        $afterComplete = app(DailyOmzetService::class)->forRestaurant($world['restaurant']);

        $this->assertSame((int) $order->grand_before, $afterComplete['omzet']);
        $this->assertSame(1, $afterComplete['count']);
    }

    public function test_csv_includes_daily_totals(): void
    {
        $world = $this->createGuestRestaurant();
        $this->placeAndPay($world, 'omzet-csv-1');

        $csv = app(DailyOmzetService::class)->csv($world['restaurant']);

        $this->assertStringContainsString('tanggal,zona_waktu,omzet,jumlah_order,qris,tunai,void,waste', $csv);
        $this->assertStringContainsString(',1,', $csv);
    }

    public function test_csv_for_range_includes_one_row_per_day(): void
    {
        $world = $this->createGuestRestaurant();
        $this->placeAndPay($world, 'omzet-csv-range');

        $today = now($world['restaurant']->timezone ?: 'Asia/Jakarta')->startOfDay();
        $from = $today->copy()->subDays(2);

        $csv = app(DailyOmzetService::class)->csvForRange(
            $world['restaurant'],
            $from,
            $today,
        );

        $lines = array_values(array_filter(explode("\n", trim($csv))));

        $this->assertCount(4, $lines);
        $this->assertStringContainsString('tanggal,zona_waktu,omzet,jumlah_order,qris,tunai,void,waste', $lines[0]);
    }

    /**
     * @param  array{restaurant: Restaurant, item: MenuItem, token: string}  $world
     */
    private function placeAndPay(array $world, string $idempotencyKey): Order
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
                'idempotency_key' => $idempotencyKey,
            ])
            ->assertCreated()
            ->json('data.public_id');

        $order = Order::query()->where('public_id', $publicId)->firstOrFail();
        app(OrderPaymentService::class)->approve($order, User::factory()->create());

        return $order->fresh(['items']);
    }
}
