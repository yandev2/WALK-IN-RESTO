<?php

namespace Tests\Feature;

use App\Livewire\Landing\RestaurantDirectory;
use App\Models\Restaurant;
use App\Models\RestaurantCategory;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RestaurantDirectoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    public function test_home_renders_directory_layout(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('RestoTerdekat', false)
            ->assertSee('Temukan restoran', false)
            ->assertSee('directory-shell', false)
            ->assertSee('directory-map-desktop', false)
            ->assertSee('Kopi Nusantara', false)
            ->assertSee('Semua Kategori', false)
            ->assertSee('Kopi & Kafe')
            ->assertSee('setCategoryFilter', false)
            ->assertDontSee('<select', false)
            ->assertSee('sm:grid-cols-2', false)
            ->assertDontSee('lg:grid-cols-2', false)
            ->assertDontSee('Masuk / Daftar', false)
            ->assertDontSee('Muat lebih banyak', false)
            ->assertSee('aria-label="Pagination"', false);
    }

    public function test_search_filters_restaurants_by_name(): void
    {
        Livewire::test(RestaurantDirectory::class)
            ->set('search', 'Sushi Haru')
            ->assertSee('Sushi Haru', false)
            ->assertDontSee('Burger Street', false);
    }

    public function test_category_filter_limits_results(): void
    {
        $jepangId = RestaurantCategory::query()->where('slug', 'jepang')->value('id');

        Livewire::test(RestaurantDirectory::class)
            ->set('categoryIds', [(int) $jepangId])
            ->assertSee('Sushi Haru', false)
            ->assertDontSee('Burger Street', false);
    }

    public function test_inactive_restaurants_are_hidden(): void
    {
        Restaurant::query()->where('slug', 'burger-street')->update(['is_active' => false]);

        Livewire::test(RestaurantDirectory::class)
            ->assertDontSee('Burger Street', false);
    }

    public function test_pagination_shows_six_cards_per_page(): void
    {
        $component = Livewire::test(RestaurantDirectory::class);

        $this->assertCount(6, $component->viewData('cards'));
        $this->assertGreaterThan(6, $component->viewData('totalCount'));

        $firstPageSlugs = collect($component->viewData('cards'))->pluck('slug')->all();

        $component
            ->call('gotoPage', 2)
            ->assertSee('aria-label="Pagination"', false);

        $secondPageSlugs = collect($component->viewData('cards'))->pluck('slug')->all();

        $this->assertLessThanOrEqual(6, count($secondPageSlugs));
        $this->assertNotEmpty($secondPageSlugs);
        $this->assertNotEquals($firstPageSlugs, $secondPageSlugs);
    }

    public function test_view_mode_switches_between_list_and_grid(): void
    {
        Livewire::test(RestaurantDirectory::class)
            ->assertSet('viewMode', 'grid')
            ->assertSee('sm:grid-cols-2', false)
            ->assertSee('directory-card-grid', false)
            ->call('setViewMode', 'list')
            ->assertSet('viewMode', 'list')
            ->assertDontSee('directory-card-grid', false)
            ->call('setViewMode', 'grid')
            ->assertSet('viewMode', 'grid');
    }

    public function test_category_dropdown_filters_by_selected_category(): void
    {
        $jepangId = RestaurantCategory::query()->where('slug', 'jepang')->value('id');

        Livewire::test(RestaurantDirectory::class)
            ->call('setCategoryFilter', $jepangId)
            ->assertSet('categoryIds', [(int) $jepangId])
            ->assertSee('Sushi Haru', false)
            ->assertDontSee('Burger Street', false)
            ->call('setCategoryFilter', null)
            ->assertSet('categoryIds', []);
    }

    public function test_category_dropdown_includes_active_category_names(): void
    {
        Livewire::test(RestaurantDirectory::class)
            ->assertSee('Semua Kategori', false)
            ->assertSee('Indonesia', false)
            ->assertSee('Kopi & Kafe')
            ->assertSee('Jepang', false)
            ->assertSee('setCategoryFilter', false);
    }

    public function test_location_sorts_restaurants_by_nearest(): void
    {
        $component = Livewire::test(RestaurantDirectory::class)
            ->call('setUserLocation', -6.2615, 106.8108, 20)
            ->assertSet('sort', 'distance')
            ->assertSet('locationStatus', 'granted');

        $firstSlug = collect($component->viewData('cards'))->value('slug');

        $this->assertSame('kopi-nusantara', $firstSlug);
    }

    public function test_radius_filter_limits_nearby_restaurants(): void
    {
        Livewire::test(RestaurantDirectory::class)
            ->call('setUserLocation', -6.2615, 106.8108, 20)
            ->set('maxDistanceKm', 1)
            ->assertSee('Kopi Nusantara', false)
            ->assertDontSee('Warung Nusantara', false);
    }

    public function test_cards_have_no_distance_without_user_location(): void
    {
        $cards = Livewire::test(RestaurantDirectory::class)
            ->viewData('cards');

        foreach ($cards as $card) {
            $this->assertNull($card['distance_label']);
        }
    }

    public function test_denied_location_falls_back_to_newest_sort(): void
    {
        Livewire::test(RestaurantDirectory::class)
            ->call('setUserLocation', -6.2615, 106.8108, 20)
            ->call('clearUserLocation', 'denied')
            ->assertSet('sort', 'newest')
            ->assertSet('locationStatus', 'denied');
    }

    public function test_disable_location_search_returns_to_default_listing(): void
    {
        $component = Livewire::test(RestaurantDirectory::class)
            ->call('setUserLocation', -6.2615, 106.8108, 20)
            ->assertSee('Matikan lokasi', false)
            ->call('disableLocationSearch')
            ->assertSet('locationStatus', 'idle')
            ->assertSet('sort', 'newest')
            ->assertSet('userLat', null)
            ->assertSet('userLng', null)
            ->assertDontSee('Matikan lokasi', false);

        foreach ($component->viewData('cards') as $card) {
            $this->assertNull($card['distance_label']);
        }
    }
}
