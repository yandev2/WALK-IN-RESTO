<?php

namespace Tests\Feature;

use App\Models\CmsProfile;
use App\Models\KdsStation;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Outlet;
use App\Models\Restaurant;
use App\Services\LandingPageDataService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MenuItemBestSellerTest extends TestCase
{
    use RefreshDatabase;

    public function test_menu_item_model_persists_and_casts_is_best_seller(): void
    {
        $world = $this->createRestaurantWithCategories();

        $item = MenuItem::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'category_id' => $world['category']->id,
            'station_id' => $world['station']->id,
            'name' => 'Nasi Goreng Spesial',
            'price' => 35000,
            'is_active' => true,
            'is_best_seller' => true,
            'sort_order' => 1,
        ]);

        $this->assertTrue($item->is_best_seller);
        $this->assertDatabaseHas('menu_items', [
            'id' => $item->id,
            'name' => 'Nasi Goreng Spesial',
            'is_best_seller' => 1,
        ]);
    }

    public function test_order_by_landing_priority_sorts_best_seller_then_discount_then_regular(): void
    {
        $world = $this->createRestaurantWithCategories();

        // 1. Regular item created first with low sort_order
        $regular = MenuItem::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'category_id' => $world['category']->id,
            'station_id' => $world['station']->id,
            'name' => 'Menu Biasa',
            'price' => 20000,
            'is_active' => true,
            'is_best_seller' => false,
            'discount_percent' => null,
            'sort_order' => 1,
        ]);

        // 2. Discounted item (not best seller)
        $discounted = MenuItem::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'category_id' => $world['category']->id,
            'station_id' => $world['station']->id,
            'name' => 'Menu Diskon Promo',
            'price' => 25000,
            'is_active' => true,
            'is_best_seller' => false,
            'discount_percent' => 20,
            'sort_order' => 5,
        ]);

        // 3. Best seller item (without discount, higher sort_order)
        $bestSeller = MenuItem::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'category_id' => $world['category']->id,
            'station_id' => $world['station']->id,
            'name' => 'Menu Best Seller Juara',
            'price' => 30000,
            'is_active' => true,
            'is_best_seller' => true,
            'discount_percent' => null,
            'sort_order' => 10,
        ]);

        $ordered = MenuItem::query()
            ->where('restaurant_id', $world['restaurant']->id)
            ->orderByLandingPriority()
            ->orderBy('sort_order')
            ->pluck('name')
            ->toArray();

        $this->assertSame([
            'Menu Best Seller Juara',
            'Menu Diskon Promo',
            'Menu Biasa',
        ], $ordered);
    }

    public function test_landing_page_renders_best_seller_badge_and_prioritizes_order(): void
    {
        $world = $this->createRestaurantWithCategories();

        $regular = MenuItem::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'category_id' => $world['category']->id,
            'station_id' => $world['station']->id,
            'name' => 'Kopi Tubruk',
            'price' => 12000,
            'is_active' => true,
            'is_best_seller' => false,
            'sort_order' => 1,
        ]);

        $discounted = MenuItem::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'category_id' => $world['category']->id,
            'station_id' => $world['station']->id,
            'name' => 'Kopi Susu Diskon',
            'price' => 18000,
            'discount_percent' => 15,
            'is_active' => true,
            'is_best_seller' => false,
            'sort_order' => 2,
        ]);

        $bestSeller = MenuItem::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'category_id' => $world['category']->id,
            'station_id' => $world['station']->id,
            'name' => 'Kopi Signature Viral',
            'price' => 25000,
            'is_active' => true,
            'is_best_seller' => true,
            'sort_order' => 3,
        ]);

        // Landing page service payload ordering
        $service = app(LandingPageDataService::class);
        $payload = $service->getPayload($world['restaurant']);
        $itemNames = $payload['menuItems']->pluck('name')->toArray();

        $this->assertSame('Kopi Signature Viral', $itemNames[0]);
        $this->assertSame('Kopi Susu Diskon', $itemNames[1]);
        $this->assertSame('Kopi Tubruk', $itemNames[2]);

        // Check HTML output
        $response = $this->get(route('landing.show', $world['restaurant']));
        $response->assertOk();
        $response->assertSee('Best Seller', false);
        $response->assertSeeInOrder([
            'Kopi Signature Viral',
            'Kopi Susu Diskon',
            'Kopi Tubruk',
        ]);
    }

    public function test_catalog_livewire_renders_best_seller_badge(): void
    {
        $world = $this->createRestaurantWithCategories();

        MenuItem::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'category_id' => $world['category']->id,
            'station_id' => $world['station']->id,
            'name' => 'Ayam Bakar Madu',
            'price' => 32000,
            'is_active' => true,
            'is_best_seller' => true,
            'sort_order' => 1,
        ]);

        Livewire::test(\App\Livewire\Landing\RestaurantMenuCatalog::class, [
            'restaurant' => $world['restaurant'],
        ])
            ->assertOk()
            ->assertSee('Best Seller', false)
            ->assertSee('Ayam Bakar Madu');
    }

    public function test_landing_page_foodie_and_glassmorphism_templates_render_best_seller_badge(): void
    {
        $world = $this->createRestaurantWithCategories();

        MenuItem::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'category_id' => $world['category']->id,
            'station_id' => $world['station']->id,
            'name' => 'Bebek Goreng Crispy',
            'price' => 45000,
            'is_active' => true,
            'is_best_seller' => true,
            'sort_order' => 1,
        ]);

        // Foodie template
        CmsProfile::query()->updateOrCreate(
            ['restaurant_id' => $world['restaurant']->id],
            ['landing_template' => 'foodie']
        );
        $foodieRes = $this->get(route('landing.show', $world['restaurant']));
        $foodieRes->assertOk();
        $foodieRes->assertSee('Best Seller', false);

        // Glassmorphism template
        CmsProfile::query()->updateOrCreate(
            ['restaurant_id' => $world['restaurant']->id],
            ['landing_template' => 'glassmorphism']
        );
        $glassRes = $this->get(route('landing.show', $world['restaurant']));
        $glassRes->assertOk();
        $glassRes->assertSee('Best Seller', false);
    }

    /**
     * @return array{
     *     restaurant: Restaurant,
     *     outlet: Outlet,
     *     category: MenuCategory,
     *     station: KdsStation
     * }
     */
    private function createRestaurantWithCategories(): array
    {
        $restaurant = Restaurant::query()->create([
            'name' => 'Resto Uji Best Seller',
            'slug' => 'resto-uji-best-seller',
            'is_active' => true,
        ]);

        $outlet = Outlet::query()->create([
            'restaurant_id' => $restaurant->id,
            'code' => 'MAIN',
            'name' => 'Outlet Utama',
            'is_default' => true,
            'is_open' => true,
            'is_active' => true,
        ]);

        $station = KdsStation::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'slug' => 'dapur',
            'name' => 'Dapur Utama',
        ]);

        $category = MenuCategory::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'name' => 'Favorit',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        return compact('restaurant', 'outlet', 'station', 'category');
    }
}
