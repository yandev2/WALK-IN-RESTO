<?php

namespace Tests\Concerns;

use App\Models\DiningTable;
use App\Models\KdsStation;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Outlet;
use App\Models\Restaurant;
use App\Models\User;
use App\Services\OrderPaymentService;
use App\Support\TableQrToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait CreatesGuestRestaurant
{
    /**
     * @return array{restaurant: Restaurant, outlet: Outlet, table: DiningTable, otherTable: DiningTable, item: MenuItem, token: string}
     */
    protected function createGuestRestaurant(): array
    {
        $restaurant = Restaurant::query()->create([
            'name' => 'Resto API',
            'slug' => 'resto-api',
            'timezone' => 'Asia/Jakarta',
            'currency' => 'IDR',
            'is_active' => true,
        ]);

        $outlet = Outlet::query()->create([
            'restaurant_id' => $restaurant->id,
            'code' => 'MAIN',
            'name' => 'Utama',
            'address' => 'Jl. Demo',
            'is_default' => true,
            'is_open' => true,
            'is_active' => true,
            'latitude' => -6.2000000,
            'longitude' => 106.8166667,
            'geofence_radius_m' => 30,
            'gps_accuracy_max_m' => 50,
            'pb1_pct' => 10,
            'service_pct' => 5,
            'tax_mode' => 'exclusive',
        ]);

        DB::table('outlet_sequences')->insert([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'seq_key' => 'order',
            'next_value' => 1,
        ]);

        $station = KdsStation::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'slug' => 'kitchen',
            'name' => 'Dapur',
        ]);

        $category = MenuCategory::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'name' => 'Minuman',
            'sort_order' => 1,
        ]);

        $item = MenuItem::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'category_id' => $category->id,
            'station_id' => $station->id,
            'name' => 'Es Teh',
            'price' => 8000,
            'sort_order' => 1,
        ]);

        $table = DiningTable::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'code' => '1',
            'capacity' => 2,
            'qr_version' => 1,
            'qr_secret' => Str::random(64),
        ]);

        $otherTable = DiningTable::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'code' => '2',
            'capacity' => 2,
            'qr_version' => 1,
            'qr_secret' => Str::random(64),
        ]);

        return [
            'restaurant' => $restaurant,
            'outlet' => $outlet,
            'table' => $table,
            'otherTable' => $otherTable,
            'item' => $item,
            'token' => TableQrToken::make($table),
        ];
    }

    protected function deviceHeaders(string $deviceToken): array
    {
        return [
            'Accept' => 'application/json',
            'X-Guest-Device' => $deviceToken,
        ];
    }

    protected function newDeviceToken(): string
    {
        $response = $this->postJson('/api/v1/guest/session');

        $response->assertOk();

        return $response->json('data.device_token');
    }

    protected function extraMenuItem(array $world, string $name = 'Nasi Goreng', int $price = 20000): MenuItem
    {
        return MenuItem::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'category_id' => $world['item']->category_id,
            'station_id' => $world['item']->station_id,
            'name' => $name,
            'price' => $price,
            'sort_order' => 2,
        ]);
    }

    /**
     * @param  array{restaurant: Restaurant, item: MenuItem, token: string}  $world
     * @param  list<int>  $menuItemIds
     */
    protected function paidGuestOrder(array $world, string $idempotencyKey, array $menuItemIds = [], bool $sendReceipt = false): Order
    {
        $device = $this->newDeviceToken();
        $headers = $this->deviceHeaders($device);

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/tables/'.$world['token'].'/claim', [
                'customer_wa' => '081234567890',
            ])
            ->assertCreated();

        foreach ($menuItemIds ?: [$world['item']->id] as $menuItemId) {
            $this->withHeaders($headers)
                ->postJson('/api/v1/guest/cart/items', [
                    'menu_item_id' => $menuItemId,
                ])
                ->assertCreated();
        }

        $publicId = $this->withHeaders($headers)
            ->postJson('/api/v1/guest/checkout', [
                'method' => 'qris',
                'idempotency_key' => $idempotencyKey,
                'send_receipt' => $sendReceipt,
            ])
            ->assertCreated()
            ->json('data.public_id');

        $order = Order::query()->where('public_id', $publicId)->firstOrFail();
        app(OrderPaymentService::class)->approve($order, User::factory()->create());

        return $order->fresh(['items']);
    }
}
