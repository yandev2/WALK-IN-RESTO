<?php

namespace Tests\Feature;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Support\MenuSearch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LandingMenuCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_show_has_view_all_menu_button(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $response = $this->get(route('landing.show', 'resto-demo'));

        $response->assertOk();
        $response->assertSee('Lihat semua daftar menu', false);
        $response->assertSee(route('landing.menu', 'resto-demo'), false);
    }

    public function test_catalog_lists_only_active_items(): void
    {
        $world = $this->createRestaurantWithMenu();

        $response = $this->get(route('landing.menu', $world['restaurant']));

        $response->assertOk();
        $response->assertSee('Soto Betawi', false);
        $response->assertDontSee('Menu Nonaktif', false);
        $response->assertDontSee('<select', false);
        $response->assertSee('Semua kategori', false);
        $response->assertSee('$set(\'categoryId\'', false);
    }

    public function test_search_is_case_and_space_insensitive(): void
    {
        $world = $this->createRestaurantWithMenu();

        Livewire::test(\App\Livewire\Landing\RestaurantMenuCatalog::class, [
            'restaurant' => $world['restaurant'],
        ])
            ->set('search', 'NASI  goreng')
            ->assertSee('Nasi Goreng')
            ->assertDontSee('Soto Betawi');
    }

    public function test_category_filter_limits_results(): void
    {
        $world = $this->createRestaurantWithMenu();

        MenuItem::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'category_id' => $world['minuman']->id,
            'station_id' => $world['station']->id,
            'name' => 'Es Teh',
            'price' => 5000,
            'is_active' => true,
            'sort_order' => 3,
        ]);

        Livewire::test(\App\Livewire\Landing\RestaurantMenuCatalog::class, [
            'restaurant' => $world['restaurant'],
        ])
            ->set('categoryId', $world['minuman']->id)
            ->assertSee('Es Teh')
            ->assertDontSee('Soto Betawi');
    }

    public function test_price_sort_toggle(): void
    {
        $world = $this->createRestaurantWithMenu();

        Livewire::test(\App\Livewire\Landing\RestaurantMenuCatalog::class, [
            'restaurant' => $world['restaurant'],
        ])
            ->set('priceSort', 'asc')
            ->assertSeeInOrder(['Nasi Goreng', 'Soto Betawi']);

        Livewire::test(\App\Livewire\Landing\RestaurantMenuCatalog::class, [
            'restaurant' => $world['restaurant'],
        ])
            ->set('priceSort', 'desc')
            ->assertSeeInOrder(['Soto Betawi', 'Nasi Goreng']);
    }

    public function test_pagination_shows_twelve_items_per_page(): void
    {
        $world = $this->createRestaurantWithMenu();

        foreach (range(1, 12) as $index) {
            MenuItem::query()->create([
                'restaurant_id' => $world['restaurant']->id,
                'outlet_id' => $world['outlet']->id,
                'category_id' => $world['makanan']->id,
                'station_id' => $world['station']->id,
                'name' => 'Item '.$index,
                'price' => 1000 + $index,
                'is_active' => true,
                'sort_order' => 10 + $index,
            ]);
        }

        $pageOne = $this->get(route('landing.menu', $world['restaurant']));
        $pageOne->assertOk();
        $pageOne->assertSee('Item 1', false);
        $pageOne->assertSee('Item 12', false);
        $pageOne->assertDontSee('Soto Betawi', false);

        $pageTwo = $this->get(route('landing.menu', $world['restaurant']).'?page=2');
        $pageTwo->assertOk();
        $pageTwo->assertSee('Soto Betawi', false);
        $pageTwo->assertDontSee('Item 1', false);
    }

    public function test_inactive_restaurant_returns_not_found(): void
    {
        $restaurant = Restaurant::query()->create([
            'name' => 'Tutup',
            'slug' => 'tutup',
            'is_active' => false,
        ]);

        $this->get(route('landing.menu', $restaurant))->assertNotFound();
    }

    public function test_menu_search_normalize_strips_spaces_and_lowercases(): void
    {
        $this->assertSame('nasigoreng', MenuSearch::normalize('NASI  Goreng'));
    }

    public function test_menu_catalog_renders_glassmorphism_background_when_template_is_glassmorphism(): void
    {
        $world = $this->createRestaurantWithMenu();
        \App\Models\CmsProfile::query()->updateOrCreate(
            ['restaurant_id' => $world['restaurant']->id],
            ['landing_template' => 'glassmorphism']
        );

        $response = $this->get(route('landing.menu', $world['restaurant']));

        $response->assertOk();
        // Verifies the floating organic 3D spheres / ambient fluid mesh glow HTML elements
        $response->assertSee('dark:from-[#060913]', false);
        $response->assertSee('blur-[110px]', false);
        $response->assertSee('border-white/70', false);
    }

    public function test_menu_catalog_does_not_render_glassmorphism_background_when_template_is_classic(): void
    {
        $world = $this->createRestaurantWithMenu();
        \App\Models\CmsProfile::query()->updateOrCreate(
            ['restaurant_id' => $world['restaurant']->id],
            ['landing_template' => 'classic']
        );

        $response = $this->get(route('landing.menu', $world['restaurant']));

        $response->assertOk();
        $response->assertDontSee('dark:from-[#060913]', false);
        $response->assertDontSee('blur-[110px]', false);
    }

    /**
     * @return array{
     *     restaurant: Restaurant,
     *     outlet: \App\Models\Outlet,
     *     makanan: MenuCategory,
     *     minuman: MenuCategory,
     *     station: \App\Models\KdsStation
     * }
     */
    private function createRestaurantWithMenu(): array
    {
        $restaurant = Restaurant::query()->create([
            'name' => 'Katalog Test',
            'slug' => 'katalog-test',
            'is_active' => true,
        ]);

        $outlet = \App\Models\Outlet::query()->create([
            'restaurant_id' => $restaurant->id,
            'code' => 'MAIN',
            'name' => 'Utama',
            'is_default' => true,
            'is_open' => true,
            'is_active' => true,
        ]);

        $station = \App\Models\KdsStation::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'slug' => 'kitchen',
            'name' => 'Dapur',
        ]);

        $makanan = MenuCategory::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'name' => 'Makanan',
            'sort_order' => 1,
        ]);

        $minuman = MenuCategory::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'name' => 'Minuman',
            'sort_order' => 2,
        ]);

        MenuItem::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'category_id' => $makanan->id,
            'station_id' => $station->id,
            'name' => 'Soto Betawi',
            'price' => 30000,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        MenuItem::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'category_id' => $makanan->id,
            'station_id' => $station->id,
            'name' => 'Nasi Goreng',
            'price' => 28000,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        MenuItem::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'category_id' => $makanan->id,
            'station_id' => $station->id,
            'name' => 'Menu Nonaktif',
            'price' => 10000,
            'is_active' => false,
            'sort_order' => 3,
        ]);

        return compact('restaurant', 'outlet', 'makanan', 'minuman', 'station');
    }
}
