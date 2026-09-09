<?php

namespace Tests\Feature;

use App\Models\CmsProfile;
use App\Models\KdsStation;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Outlet;
use App\Models\Restaurant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class LandingMenuHiddenPriceTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    private function setupRestaurantWithMenu(bool $hideLandingPrices = false): array
    {
        $restaurant = Restaurant::query()->create([
            'name' => 'Resto Hidden Price',
            'slug' => 'resto-hidden-price',
            'is_active' => true,
            'listed_in_directory' => true,
            'landing_enabled' => true,
        ]);

        $outlet = Outlet::query()->create([
            'restaurant_id' => $restaurant->id,
            'name' => 'Outlet Utama',
            'code' => 'OUT-01',
            'is_default' => true,
            'is_open' => true,
            'is_active' => true,
            'hide_landing_menu_prices' => $hideLandingPrices,
        ]);

        $station = KdsStation::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'slug' => 'kitchen',
            'name' => 'Dapur',
        ]);

        CmsProfile::query()->create([
            'restaurant_id' => $restaurant->id,
            'headline' => 'Resto Nikmat',
            'landing_template' => 'classic',
        ]);

        $category = MenuCategory::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'name' => 'Makanan Utama',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $item = MenuItem::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'category_id' => $category->id,
            'station_id' => $station->id,
            'name' => 'Sop Buntut Spesial',
            'description' => 'Sop buntut sapi gurih rempah pilihan',
            'price' => 75000,
            'discount_percent' => 10,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        return compact('restaurant', 'outlet', 'station', 'category', 'item');
    }

    public function test_menu_prices_are_displayed_by_default_when_switch_is_off(): void
    {
        $data = $this->setupRestaurantWithMenu(hideLandingPrices: false);

        // 1. Landing show page
        $response = $this->get(route('landing.show', $data['restaurant']));
        $response->assertOk();
        $response->assertSee('Sop Buntut Spesial');
        $response->assertSee('Rp 67.500'); // 75.000 with 10% discount

        // 2. Catalog Livewire page
        $catalog = $this->get(route('landing.menu', $data['restaurant']));
        $catalog->assertOk();
        $catalog->assertSee('Sop Buntut Spesial');
        $catalog->assertSee('Rp 67.500');
        $catalog->assertSee('Harga terendah');
        $catalog->assertSee('Harga tertinggi');

        // 3. Public Landing API (show)
        $apiShow = $this->getJson("/api/v1/restaurants/{$data['restaurant']->slug}");
        $apiShow->assertOk();
        $featured = $apiShow->json('data.featured_menu.0');
        $this->assertSame(67500, $featured['price']);
        $this->assertSame(75000, $featured['original_price']);
        $this->assertSame(10, $featured['discount_percent']);

        // 4. Public Menu API
        $apiMenu = $this->getJson("/api/v1/restaurants/{$data['restaurant']->slug}/menu");
        $apiMenu->assertOk();
        $menuItem = $apiMenu->json('data.items.0');
        $this->assertSame(67500, $menuItem['price']);
        $this->assertSame(75000, $menuItem['original_price']);
        $this->assertSame(10, $menuItem['discount_percent']);
    }

    public function test_menu_prices_are_hidden_on_landing_and_catalog_when_switch_is_on(): void
    {
        $data = $this->setupRestaurantWithMenu(hideLandingPrices: true);

        // 1. Landing show page (Classic template)
        $response = $this->get(route('landing.show', $data['restaurant']));
        $response->assertOk();
        $response->assertSee('Sop Buntut Spesial');
        $response->assertSee('Sop buntut sapi gurih rempah pilihan');
        // Prices and original prices should NOT be displayed, but discount badge (-10%) should still be displayed
        $response->assertDontSee('Rp 67.500');
        $response->assertDontSee('Rp 75.000');
        $response->assertSee('-10%');

        // Test Foodie template
        $data['restaurant']->cmsProfile->update(['landing_template' => 'foodie']);
        $responseFoodie = $this->get(route('landing.show', $data['restaurant']));
        $responseFoodie->assertOk();
        $responseFoodie->assertSee('Sop Buntut Spesial');
        $responseFoodie->assertDontSee('Rp 67.500');
        $responseFoodie->assertDontSee('Rp 75.000');
        $responseFoodie->assertSee('-10%');

        // Test Glassmorphism template
        $data['restaurant']->cmsProfile->update(['landing_template' => 'glassmorphism']);
        $responseGlass = $this->get(route('landing.show', $data['restaurant']));
        $responseGlass->assertOk();
        $responseGlass->assertSee('Sop Buntut Spesial');
        $responseGlass->assertDontSee('Rp 67.500');
        $responseGlass->assertDontSee('Rp 75.000');
        $responseGlass->assertSee('-10%');

        // 2. Catalog Livewire page
        $catalog = $this->get(route('landing.menu', $data['restaurant']));
        $catalog->assertOk();
        $catalog->assertSee('Sop Buntut Spesial');
        $catalog->assertDontSee('Rp 67.500');
        $catalog->assertDontSee('Rp 75.000');
        $catalog->assertSee('-10%');
        // Price sort filter dropdown should be hidden when prices are hidden
        $catalog->assertDontSee('Harga terendah');
        $catalog->assertDontSee('Harga tertinggi');
        // Category filter is still functional
        $catalog->assertSee('Semua kategori');

        // 3. Public Landing API (show)
        $apiShow = $this->getJson("/api/v1/restaurants/{$data['restaurant']->slug}");
        $apiShow->assertOk();
        $featured = $apiShow->json('data.featured_menu.0');
        $this->assertNull($featured['price']);
        $this->assertNull($featured['original_price']);
        $this->assertSame(10, $featured['discount_percent']);

        // 4. Public Menu API
        $apiMenu = $this->getJson("/api/v1/restaurants/{$data['restaurant']->slug}/menu");
        $apiMenu->assertOk();
        $menuItem = $apiMenu->json('data.items.0');
        $this->assertNull($menuItem['price']);
        $this->assertNull($menuItem['original_price']);
        $this->assertSame(10, $menuItem['discount_percent']);
    }

    public function test_guest_qr_scan_menu_still_displays_full_prices_when_landing_prices_are_hidden(): void
    {
        $world = $this->createGuestRestaurant();

        // Turn on hidden landing price for this outlet
        $world['outlet']->update(['hide_landing_menu_prices' => true]);
        $this->assertTrue((bool) $world['outlet']->fresh()->hide_landing_menu_prices);

        $device = $this->newDeviceToken();
        $headers = $this->deviceHeaders($device);

        $this->withHeaders($headers)
            ->postJson('/api/v1/guest/tables/'.$world['token'].'/claim', [
                'customer_wa' => '081234567890',
            ])
            ->assertCreated();

        // Guest accessing guest menu API
        $apiGuest = $this->withHeaders($headers)
            ->getJson('/api/v1/guest/menu');
        $apiGuest->assertOk();

        $category = $apiGuest->json('data.0');
        $firstItem = $category['items'][0];
        $this->assertSame(8000, $firstItem['price']);
        $this->assertSame(8000, $firstItem['original_price']);
    }

    public function test_outlet_setting_persistence(): void
    {
        $data = $this->setupRestaurantWithMenu(hideLandingPrices: false);
        $outlet = $data['outlet'];

        $this->assertFalse($outlet->hide_landing_menu_prices);

        $outlet->update(['hide_landing_menu_prices' => true]);
        $this->assertTrue($outlet->fresh()->hide_landing_menu_prices);

        $outlet->update(['hide_landing_menu_prices' => false]);
        $this->assertFalse($outlet->fresh()->hide_landing_menu_prices);
    }
}
