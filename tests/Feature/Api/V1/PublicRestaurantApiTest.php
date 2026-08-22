<?php

namespace Tests\Feature\Api\V1;

use App\Models\CmsProfile;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\RestaurantReview;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class PublicRestaurantApiTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_restaurant_show_includes_theme_and_rating_summary(): void
    {
        $world = $this->createGuestRestaurant();

        CmsProfile::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'primary_color' => '#112233',
            'accent_color' => '#AABBCC',
        ]);

        RestaurantReview::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'visit_id' => $this->createOpenVisit($world),
            'rating' => 5,
            'comment' => 'Sangat memuaskan sekali.',
            'submitted_at' => now(),
        ]);

        $this->getJson('/api/v1/restaurants/'.$world['restaurant']->slug)
            ->assertOk()
            ->assertJsonPath('data.theme.primary', '#112233')
            ->assertJsonPath('data.theme.accent', '#AABBCC')
            ->assertJsonPath('data.rating_summary.count', 1)
            ->assertJsonPath('data.rating_summary.average', 5)
            ->assertJsonPath('data.recent_reviews.0.rating', 5)
            ->assertJsonPath('data.outlet_name', 'Utama')
            ->assertJsonPath('data.is_open', true)
            ->assertJsonStructure([
                'data' => [
                    'is_open',
                    'is_open_now',
                    'today_hours',
                    'closed_dates',
                    'operating_hours',
                ],
            ]);
    }

    public function test_restaurant_show_includes_closed_dates_and_six_recent_reviews(): void
    {
        $world = $this->createGuestRestaurant();

        \App\Models\OutletClosedDate::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'closed_on' => '2026-08-17',
            'reason' => 'Libur kemerdekaan',
        ]);

        \App\Models\OutletOperatingHour::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'day_of_week' => now('Asia/Jakarta')->dayOfWeek,
            'opens_at' => '10:00:00',
            'closes_at' => '22:00:00',
            'is_closed' => false,
        ]);

        foreach (range(1, 7) as $index) {
            RestaurantReview::query()->create([
                'restaurant_id' => $world['restaurant']->id,
                'outlet_id' => $world['outlet']->id,
                'visit_id' => $this->createOpenVisit($world),
                'customer_name' => 'Tamu '.$index,
                'rating' => 4,
                'comment' => 'Ulasan lengkap nomor '.$index.'.',
                'submitted_at' => now()->subMinutes($index),
            ]);
        }

        $response = $this->getJson('/api/v1/restaurants/'.$world['restaurant']->slug)
            ->assertOk()
            ->assertJsonPath('data.closed_dates.0.date', '2026-08-17')
            ->assertJsonPath('data.closed_dates.0.reason', 'Libur kemerdekaan')
            ->assertJsonPath('data.today_hours.opens_at', '10:00:00')
            ->assertJsonPath('data.today_hours.closes_at', '22:00:00')
            ->assertJsonCount(6, 'data.recent_reviews');

        $this->assertNotNull($response->json('data.today_hours.day_name'));
    }

    public function test_restaurant_index_includes_theme_and_rating(): void
    {
        $world = $this->createGuestRestaurant();

        $this->getJson('/api/v1/restaurants')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'resto-api')
            ->assertJsonStructure([
                'data' => [
                    [
                        'theme' => ['primary', 'primary_dark', 'accent'],
                        'rating_average',
                        'rating_count',
                    ],
                ],
            ]);
    }

    public function test_public_menu_catalog_supports_search_filter_sort_and_pagination(): void
    {
        $world = $this->createGuestRestaurant();

        $makanan = MenuCategory::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'name' => 'Makanan',
            'sort_order' => 1,
        ]);

        MenuItem::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'category_id' => $makanan->id,
            'station_id' => $world['item']->station_id,
            'name' => 'Nasi Goreng',
            'price' => 28000,
            'discount_percent' => 20,
            'sort_order' => 2,
        ]);

        MenuItem::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'category_id' => $makanan->id,
            'station_id' => $world['item']->station_id,
            'name' => 'Ayam Bakar',
            'price' => 35000,
            'sort_order' => 3,
        ]);

        $this->getJson('/api/v1/restaurants/'.$world['restaurant']->slug.'/menu?search=nasigoreng')
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.items.0.name', 'Nasi Goreng')
            ->assertJsonPath('data.items.0.price', 22400);

        $this->getJson('/api/v1/restaurants/'.$world['restaurant']->slug.'/menu?category_id='.$makanan->id)
            ->assertOk()
            ->assertJsonPath('meta.total', 2);

        $this->getJson('/api/v1/restaurants/'.$world['restaurant']->slug.'/menu?sort=desc&per_page=1')
            ->assertOk()
            ->assertJsonPath('meta.per_page', 1)
            ->assertJsonPath('meta.total', 3)
            ->assertJsonPath('data.items.0.name', 'Ayam Bakar');
    }

    public function test_public_reviews_are_paginated(): void
    {
        $world = $this->createGuestRestaurant();

        foreach (range(1, 3) as $index) {
            RestaurantReview::query()->create([
                'restaurant_id' => $world['restaurant']->id,
                'outlet_id' => $world['outlet']->id,
                'visit_id' => $this->createOpenVisit($world),
                'customer_name' => 'Tamu '.$index,
                'rating' => 4,
                'comment' => 'Ulasan tamu nomor '.$index.'.',
                'submitted_at' => now()->subMinutes($index),
            ]);
        }

        $this->getJson('/api/v1/restaurants/'.$world['restaurant']->slug.'/reviews?per_page=2')
            ->assertOk()
            ->assertJsonPath('meta.per_page', 2)
            ->assertJsonPath('meta.total', 3)
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    ['id', 'customer_name', 'rating', 'comment', 'submitted_at'],
                ],
            ]);
    }

    /**
     * @param  array<string, mixed>  $world
     */
    private function createOpenVisit(array $world, ?string $publicId = null): int
    {
        return \App\Models\Visit::query()->create([
            'public_id' => $publicId ?? (string) Str::ulid(),
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'table_id' => $world['table']->id,
            'status' => 'closed',
            'join_pin' => '1234',
            'customer_wa' => '6281234567890',
            'customer_name' => 'Tamu',
            'claimed_at' => now()->subHour(),
            'claim_expires_at' => now()->subMinutes(30),
            'closed_at' => now()->subMinutes(20),
        ])->id;
    }
}
