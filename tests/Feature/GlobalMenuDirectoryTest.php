<?php

namespace Tests\Feature;

use App\Livewire\Landing\GlobalMenuDirectory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\RestaurantCategory;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class GlobalMenuDirectoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    public function test_global_menu_directory_page_is_accessible(): void
    {
        $this->get(route('directory.menus'))
            ->assertOk()
            ->assertSee('Daftar Menu', false)
            ->assertSee('Jelajahi Menu Makanan & Minuman', false)
            ->assertSee('Jelajahi Berdasarkan Kategori', false)
            ->assertSee('Urutkan:', false);
    }

    public function test_search_filters_menu_items_by_name(): void
    {
        $item = MenuItem::query()->where('is_active', true)->whereHas('restaurant', fn ($r) => $r->listedInDirectory())->first();
        $this->assertNotNull($item);

        Livewire::test(GlobalMenuDirectory::class)
            ->set('search', $item->name)
            ->assertSee($item->name, false);
    }

    public function test_category_filter_limits_menu_items(): void
    {
        $kopiCategory = RestaurantCategory::query()->where('slug', 'kopi-kafe')->first();
        $this->assertNotNull($kopiCategory);

        $component = Livewire::test(GlobalMenuDirectory::class)
            ->set('categoryIds', [$kopiCategory->id]);

        $items = $component->viewData('items');
        $this->assertNotEmpty($items);

        foreach ($items as $item) {
            $menuModel = MenuItem::find($item['id']);
            $this->assertTrue(
                $menuModel->restaurant->categories->contains('id', $kopiCategory->id)
            );
        }
    }

    public function test_location_sorting_calculates_distance(): void
    {
        // Lokasi Monas Jakarta Pusat (-6.1754, 106.8272)
        $component = Livewire::test(GlobalMenuDirectory::class)
            ->call('setUserLocation', -6.1754, 106.8272, 15);

        $this->assertEquals('granted', $component->get('locationStatus'));
        $this->assertEquals('distance', $component->get('sort'));

        $items = $component->viewData('items');
        $this->assertNotEmpty($items);
        $this->assertNotNull($items[0]['distance_km']);
        $this->assertNotNull($items[0]['distance_label']);
    }

    public function test_max_distance_filters_out_far_dishes(): void
    {
        // Set lokasi user di Kemang (-6.2615, 106.8108), lalu batasi radius 5 km
        $component = Livewire::test(GlobalMenuDirectory::class)
            ->call('setUserLocation', -6.2615, 106.8108, 10)
            ->set('maxDistanceKm', 5);

        $items = $component->viewData('items');
        $this->assertNotEmpty($items);
        foreach ($items as $item) {
            $this->assertLessThanOrEqual(5.0, (float) $item['distance_km']);
        }
    }

    public function test_menu_cards_link_to_restaurant_landing_page(): void
    {
        $item = MenuItem::query()->where('is_active', true)->whereHas('restaurant', fn ($r) => $r->listedInDirectory())->first();
        $this->assertNotNull($item);

        $expectedUrl = route('landing.show', $item->restaurant);

        $component = Livewire::test(GlobalMenuDirectory::class)
            ->set('search', $item->name);

        $items = $component->viewData('items');
        $matched = collect($items)->firstWhere('id', $item->id);

        $this->assertNotNull($matched);
        $this->assertEquals($expectedUrl, $matched['landing_url']);
    }

    public function test_price_sort_orders_menu_items_correctly(): void
    {
        $ascComponent = Livewire::test(GlobalMenuDirectory::class)
            ->set('sort', 'price_asc');

        $ascItems = $ascComponent->viewData('items');
        if (count($ascItems) >= 2) {
            $this->assertLessThanOrEqual($ascItems[1]['effective_price'], $ascItems[0]['effective_price']);
        }

        $descComponent = Livewire::test(GlobalMenuDirectory::class)
            ->set('sort', 'price_desc');

        $descItems = $descComponent->viewData('items');
        if (count($descItems) >= 2) {
            $this->assertGreaterThanOrEqual($descItems[1]['effective_price'], $descItems[0]['effective_price']);
        }
    }

    public function test_header_has_login_link_for_guests(): void
    {
        $this->get(route('directory.menus'))
            ->assertOk()
            ->assertSee('href="'.url('/admin/login').'"', false)
            ->assertSee('Login', false);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('href="'.url('/admin/login').'"', false)
            ->assertSee('Login', false);
    }

    public function test_menu_directory_respects_hidden_price_and_renders_modal(): void
    {
        $item = MenuItem::query()->where('is_active', true)->whereHas('restaurant', fn ($r) => $r->listedInDirectory())->first();
        $this->assertNotNull($item);

        $outlet = $item->restaurant->defaultOutlet;
        $this->assertNotNull($outlet);

        $outlet->update(['hide_landing_menu_prices' => true]);

        $component = Livewire::test(GlobalMenuDirectory::class)
            ->set('search', $item->name);

        $items = $component->viewData('items');
        $matched = collect($items)->firstWhere('id', $item->id);

        $this->assertNotNull($matched);
        $this->assertTrue($matched['hide_price']);
        $this->assertNull($matched['price']);
        $this->assertNull($matched['price_formatted']);
        $this->assertIsArray($matched['photos']);

        // Pastikan halaman HTML memuat modal dan trigger dispatch
        $this->get(route('directory.menus'))
            ->assertOk()
            ->assertSee('open-menu-modal', false)
            ->assertSee('Kunjungi Restoran', false);

        // Kembalikan ke false
        $outlet->update(['hide_landing_menu_prices' => false]);
    }
}
