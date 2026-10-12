<?php

namespace Tests\Feature;

use App\Enums\BlogPostStatus;
use App\Livewire\Landing\RestaurantDirectory;
use App\Models\BlogPost;
use App\Models\DiningTable;
use App\Models\Restaurant;
use App\Models\RestaurantCategory;
use App\Models\RestaurantReview;
use App\Models\Visit;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
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

    public function test_recommended_restaurants_appear_in_slider(): void
    {
        $restaurant = Restaurant::query()->firstOrFail();
        $restaurant->update(['is_recommended' => true]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('recommended-slider-container', false)
            ->assertSee('Rekomendasi Spesial', false)
            ->assertSee('Populer', false)
            ->assertSee($restaurant->name, false)
            ->assertSee('Lihat Detail Restoran', false);
    }

    public function test_non_recommended_or_inactive_restaurants_do_not_appear_in_slider(): void
    {
        Restaurant::query()->update(['is_recommended' => false]);

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('recommended-slider-container', false);

        $restaurant = Restaurant::query()->firstOrFail();
        $restaurant->update([
            'is_recommended' => true,
            'is_active' => false,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('recommended-slider-container', false);
    }

    public function test_location_defaults_to_all_distances_sorted_by_nearest(): void
    {
        $totalActive = Restaurant::query()->listedInDirectory()->count();

        $component = Livewire::test(RestaurantDirectory::class)
            ->call('setUserLocation', -6.2615, 106.8108, 20)
            ->assertSet('sort', 'distance')
            ->assertSet('locationStatus', 'granted')
            ->assertSet('maxDistanceKm', null);

        $this->assertSame($totalActive, $component->viewData('totalCount'));

        $cards = $component->viewData('cards');
        $this->assertNotEmpty($cards);

        // Check that cards with distance are ordered ascending (nearest first)
        $distances = collect($cards)
            ->pluck('distance_km')
            ->filter(fn ($d) => $d !== null)
            ->values();

        $sortedDistances = $distances->sort()->values();
        $this->assertEquals($sortedDistances->all(), $distances->all());
    }

    public function test_custom_max_distance_filters_restaurants_and_can_be_reset_to_all(): void
    {
        $totalActive = Restaurant::query()->listedInDirectory()->count();

        $component = Livewire::test(RestaurantDirectory::class)
            ->call('setUserLocation', -6.2615, 106.8108, 20)
            ->assertSet('maxDistanceKm', null)
            ->set('maxDistanceKm', 1);

        $countWith1Km = $component->viewData('totalCount');
        $this->assertLessThan($totalActive, $countWith1Km);

        // Reset to all distances by setting null
        $component->set('maxDistanceKm', null);
        $this->assertSame($totalActive, $component->viewData('totalCount'));
    }

    public function test_reset_filters_preserves_null_max_distance_and_distance_sort_when_granted(): void
    {
        Livewire::test(RestaurantDirectory::class)
            ->call('setUserLocation', -6.2615, 106.8108, 20)
            ->set('maxDistanceKm', 5)
            ->call('resetFilters')
            ->assertSet('maxDistanceKm', null)
            ->assertSet('sort', 'distance');
    }

    public function test_popular_menu_items_filter_by_category(): void
    {
        $kopiCategory = RestaurantCategory::query()->where('slug', 'kopi-kafe')->first();
        $this->assertNotNull($kopiCategory);

        $component = Livewire::test(RestaurantDirectory::class)
            ->set('categoryIds', [$kopiCategory->id]);

        $items = $component->viewData('popularMenuItems');
        $this->assertNotEmpty($items);

        foreach ($items as $item) {
            $menuModel = \App\Models\MenuItem::find($item['id']);
            $this->assertTrue(
                $menuModel->restaurant->categories->contains('id', $kopiCategory->id)
            );
        }
    }

    public function test_popular_menu_items_filter_by_distance(): void
    {
        $component = Livewire::test(RestaurantDirectory::class)
            ->call('setUserLocation', -6.2615, 106.8108, 20)
            ->set('maxDistanceKm', 10);

        $items = $component->viewData('popularMenuItems');
        $this->assertNotEmpty($items);
        foreach ($items as $item) {
            $this->assertLessThanOrEqual(10.0, (float) $item['distance_km']);
        }
    }

    public function test_popular_menu_items_link_to_restaurant_landing(): void
    {
        $component = Livewire::test(RestaurantDirectory::class);
        $items = $component->viewData('popularMenuItems');
        $this->assertNotEmpty($items);

        foreach ($items as $item) {
            $this->assertNotNull($item['landing_url']);
            $this->assertStringStartsWith('http', $item['landing_url']);
        }
    }

    public function test_popular_menu_items_respects_hidden_price_and_includes_modal_payload(): void
    {
        $baseComponent = Livewire::test(RestaurantDirectory::class);
        $initialItems = $baseComponent->viewData('popularMenuItems');
        $this->assertNotEmpty($initialItems);

        $targetItemData = $initialItems[0];
        $menuItem = \App\Models\MenuItem::findOrFail($targetItemData['id']);
        $outlet = $menuItem->restaurant->defaultOutlet;
        $this->assertNotNull($outlet);

        $outlet->update(['hide_landing_menu_prices' => true]);

        $component = Livewire::test(RestaurantDirectory::class);
        $items = $component->viewData('popularMenuItems');
        $matched = collect($items)->firstWhere('id', $menuItem->id);

        $this->assertNotNull($matched);
        $this->assertTrue($matched['hide_price']);
        $this->assertNull($matched['price']);
        $this->assertNull($matched['price_formatted']);
        $this->assertIsArray($matched['photos']);

        // Kembalikan ke false
        $outlet->update(['hide_landing_menu_prices' => false]);

        $componentAfter = Livewire::test(RestaurantDirectory::class);
        $itemsAfter = $componentAfter->viewData('popularMenuItems');
        $matchedAfter = collect($itemsAfter)->firstWhere('id', $menuItem->id);

        $this->assertNotNull($matchedAfter);
        $this->assertFalse($matchedAfter['hide_price']);
        $this->assertNotNull($matchedAfter['price']);
        $this->assertNotNull($matchedAfter['price_formatted']);
    }

    public function test_directory_ratings_only_count_and_average_published_reviews(): void
    {
        $restaurant = Restaurant::query()->where('slug', 'sushi-haru')->firstOrFail();
        $outlet = $restaurant->defaultOutlet;
        $table = DiningTable::withoutRestaurantScope()->where('restaurant_id', $restaurant->id)->firstOrFail();

        // Clear existing reviews for Sushi Haru to have an exact baseline
        RestaurantReview::withoutRestaurantScope()->where('restaurant_id', $restaurant->id)->delete();

        // 1. Create 1 published review with 5 stars
        $visit1 = Visit::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'table_id' => $table->id,
            'status' => 'closed',
            'public_id' => (string) Str::ulid(),
            'join_pin' => '1111',
            'claimed_at' => now()->subHours(2),
            'claim_expires_at' => now()->subHour(),
        ]);
        RestaurantReview::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'visit_id' => $visit1->id,
            'customer_name' => 'Tamu Terbit',
            'rating' => 5,
            'comment' => 'Enak sekali.',
            'is_published' => true,
            'submitted_at' => now()->subHour(),
        ]);

        // 2. Create 1 unpublished review with 1 star (tayang di web = off)
        $visit2 = Visit::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'table_id' => $table->id,
            'status' => 'closed',
            'public_id' => (string) Str::ulid(),
            'join_pin' => '2222',
            'claimed_at' => now()->subHour(),
            'claim_expires_at' => now()->subMinutes(30),
        ]);
        $unapprovedReview = RestaurantReview::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'visit_id' => $visit2->id,
            'customer_name' => 'Tamu Belum Disetujui',
            'rating' => 1,
            'comment' => 'Kurang suka.',
            'is_published' => false,
            'submitted_at' => now()->subMinutes(30),
        ]);

        // Verify in Livewire directory component
        $component = Livewire::test(RestaurantDirectory::class)
            ->set('search', 'Sushi Haru');

        $cards = $component->viewData('cards');
        $this->assertNotEmpty($cards);
        $card = collect($cards)->firstWhere('slug', 'sushi-haru');
        $this->assertNotNull($card);

        // Only the 1 published review should be counted, average should be 5.0 (NOT (5+1)/2 = 3.0)
        $this->assertSame(1, $card['rating_count']);
        $this->assertEquals(5.0, $card['rating_average']);

        // Now activate/publish the second review (tayang di web = on)
        $unapprovedReview->update(['is_published' => true]);

        $componentAfterPublish = Livewire::test(RestaurantDirectory::class)
            ->set('search', 'Sushi Haru');
        $cardAfterPublish = collect($componentAfterPublish->viewData('cards'))->firstWhere('slug', 'sushi-haru');

        // Now both reviews are counted: average (5 + 1) / 2 = 3.0, count = 2
        $this->assertSame(2, $cardAfterPublish['rating_count']);
        $this->assertEquals(3.0, $cardAfterPublish['rating_average']);
    }

    public function test_directory_sorting_by_rating_uses_only_published_reviews(): void
    {
        // Resto A has 1 review: 4 stars (published)
        $restoA = Restaurant::query()->where('slug', 'sushi-haru')->firstOrFail();
        // Resto B has 1 review: 5 stars (published)
        $restoB = Restaurant::query()->where('slug', 'burger-street')->firstOrFail();

        RestaurantReview::withoutRestaurantScope()->whereIn('restaurant_id', [$restoA->id, $restoB->id])->delete();

        $tableA = DiningTable::withoutRestaurantScope()->where('restaurant_id', $restoA->id)->firstOrFail();
        $visitA = Visit::query()->create([
            'restaurant_id' => $restoA->id,
            'outlet_id' => $restoA->defaultOutlet->id,
            'table_id' => $tableA->id,
            'status' => 'closed',
            'public_id' => (string) Str::ulid(),
            'join_pin' => '3333',
            'claimed_at' => now()->subHour(),
            'claim_expires_at' => now()->subMinutes(30),
        ]);
        RestaurantReview::query()->create([
            'restaurant_id' => $restoA->id,
            'outlet_id' => $restoA->defaultOutlet->id,
            'visit_id' => $visitA->id,
            'rating' => 4,
            'comment' => 'Bagus',
            'is_published' => true,
            'submitted_at' => now(),
        ]);

        // Resto A also gets five 5-star reviews that are UNPUBLISHED (is_published = false)
        foreach (range(1, 5) as $i) {
            $unpubVisit = Visit::query()->create([
                'restaurant_id' => $restoA->id,
                'outlet_id' => $restoA->defaultOutlet->id,
                'table_id' => $tableA->id,
                'status' => 'closed',
                'public_id' => (string) Str::ulid(),
                'join_pin' => '444'.$i,
                'claimed_at' => now()->subHour(),
                'claim_expires_at' => now()->subMinutes(30),
            ]);
            RestaurantReview::query()->create([
                'restaurant_id' => $restoA->id,
                'outlet_id' => $restoA->defaultOutlet->id,
                'visit_id' => $unpubVisit->id,
                'rating' => 5,
                'comment' => 'Rahasia '.$i,
                'is_published' => false,
                'submitted_at' => now(),
            ]);
        }

        $tableB = DiningTable::withoutRestaurantScope()->where('restaurant_id', $restoB->id)->firstOrFail();
        $visitB = Visit::query()->create([
            'restaurant_id' => $restoB->id,
            'outlet_id' => $restoB->defaultOutlet->id,
            'table_id' => $tableB->id,
            'status' => 'closed',
            'public_id' => (string) Str::ulid(),
            'join_pin' => '5555',
            'claimed_at' => now()->subHour(),
            'claim_expires_at' => now()->subMinutes(30),
        ]);
        RestaurantReview::query()->create([
            'restaurant_id' => $restoB->id,
            'outlet_id' => $restoB->defaultOutlet->id,
            'visit_id' => $visitB->id,
            'rating' => 5,
            'comment' => 'Sempurna',
            'is_published' => true,
            'submitted_at' => now(),
        ]);

        // If unpublished reviews were counted, Resto A would have avg ~4.83 and 6 reviews.
        // But only published reviews count: Resto B has avg 5.0, Resto A has avg 4.0.
        // When sorting by 'rating', Resto B must be ranked higher than Resto A!
        $component = Livewire::test(RestaurantDirectory::class)
            ->set('sort', 'rating');

        $cards = collect($component->viewData('cards'))->pluck('slug')->all();
        $posB = array_search('burger-street', $cards, true);
        $posA = array_search('sushi-haru', $cards, true);

        $this->assertNotFalse($posB);
        $this->assertNotFalse($posA);
        $this->assertLessThan($posA, $posB, 'Resto B (5.0 stars published) should rank ahead of Resto A (4.0 stars published, despite having unpublished 5-star reviews)');
    }

    public function test_latest_articles_returns_posts_sorted_by_latest_update_without_time_limit(): void
    {
        // Bersihkan artikel seeder untuk pengujian terisolasi
        BlogPost::query()->forceDelete();

        $oldPostUpdatedRecently = BlogPost::query()->create([
            'status' => BlogPostStatus::Published,
            'is_active' => true,
            'published_at' => now()->subDays(60),
            'created_at' => now()->subDays(60),
            'updated_at' => now(),
        ]);
        $oldPostUpdatedRecently->translations()->create([
            'locale' => 'id',
            'title' => 'Artikel Lawas Baru Saja Diupdate',
            'slug' => 'artikel-lawas-update',
            'content' => '<p>Konten artikel lawas</p>',
        ]);

        $olderPost = BlogPost::query()->create([
            'status' => BlogPostStatus::Published,
            'is_active' => true,
            'published_at' => now()->subDays(90),
            'created_at' => now()->subDays(90),
            'updated_at' => now()->subDays(10),
        ]);
        $olderPost->translations()->create([
            'locale' => 'id',
            'title' => 'Artikel 90 Hari Lalu',
            'slug' => 'artikel-90-hari-lalu',
            'content' => '<p>Konten artikel 90 hari</p>',
        ]);

        $draftPost = BlogPost::query()->create([
            'status' => BlogPostStatus::Draft,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $draftPost->translations()->create([
            'locale' => 'id',
            'title' => 'Artikel Draf Rahasia',
            'slug' => 'artikel-draf',
            'content' => '<p>Konten draf</p>',
        ]);

        $component = Livewire::test(RestaurantDirectory::class);
        $articles = $component->get('latestArticles');

        $this->assertCount(2, $articles);
        $this->assertSame('Artikel Lawas Baru Saja Diupdate', $articles[0]['title']);
        $this->assertSame('Artikel 90 Hari Lalu', $articles[1]['title']);
    }

    public function test_latest_articles_falls_back_when_current_locale_has_no_translations(): void
    {
        BlogPost::query()->forceDelete();

        $post = BlogPost::query()->create([
            'status' => BlogPostStatus::Published,
            'is_active' => true,
            'published_at' => now()->subDays(20),
        ]);
        $post->translations()->create([
            'locale' => 'id',
            'title' => 'Hanya Bahasa Indonesia',
            'slug' => 'hanya-bahasa-indonesia',
            'content' => '<p>Konten bahasa Indonesia</p>',
        ]);

        app()->setLocale('en');

        $component = Livewire::test(RestaurantDirectory::class);
        $articles = $component->get('latestArticles');

        $this->assertNotEmpty($articles);
        $this->assertSame('Hanya Bahasa Indonesia', $articles[0]['title']);

        // Kembalikan locale ke id
        app()->setLocale('id');
    }
}

