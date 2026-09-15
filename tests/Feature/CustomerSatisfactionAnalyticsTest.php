<?php

namespace Tests\Feature;

use App\Filament\Pages\CustomerSatisfactionAnalytics;
use App\Filament\Resources\CustomerReviews\Pages\ListCustomerReviews;
use App\Models\Restaurant;
use App\Models\RestaurantReview;
use App\Models\User;
use App\Models\Visit;
use App\Services\CustomerCrmService;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class CustomerSatisfactionAnalyticsTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_get_satisfaction_analytics_calculates_csat_and_star_distribution(): void
    {
        Storage::fake('local');
        $world = $this->createGuestRestaurant();

        /** @var Restaurant $restaurant */
        $restaurant = $world['restaurant'];
        $outlet = $world['outlet'];
        $table = $world['table'];

        // Buat 3 ulasan: dua bintang 5, satu bintang 2
        for ($i = 1; $i <= 3; $i++) {
            $visit = Visit::withoutRestaurantScope()->create([
                'restaurant_id' => $restaurant->id,
                'outlet_id' => $outlet->id,
                'table_id' => $table->id,
                'status' => 'closed',
                'join_pin' => '1234',
                'claimed_at' => now()->subHour(),
                'claim_expires_at' => now()->addHour(),
                'customer_name' => "Tamu {$i}",
                'customer_wa' => "628120000000{$i}",
            ]);

            RestaurantReview::withoutRestaurantScope()->create([
                'restaurant_id' => $restaurant->id,
                'outlet_id' => $outlet->id,
                'visit_id' => $visit->id,
                'customer_name' => "Tamu {$i}",
                'rating' => $i <= 2 ? 5 : 2,
                'comment' => "Komentar ulasan {$i}",
                'is_published' => true,
                'submitted_at' => now()->subDays($i),
            ]);
        }

        $crm = app(CustomerCrmService::class);
        $analytics = $crm->getSatisfactionAnalytics($restaurant);

        $this->assertSame(3, $analytics['total_reviews']);
        // Average: (5 + 5 + 2) / 3 = 4.0
        $this->assertEquals(4.0, $analytics['avg_rating']);
        // Positive count: 2 (stars 5 & 4)
        $this->assertSame(2, $analytics['positive_count']);
        // Satisfaction rate: 2 / 3 * 100 = 66.7%
        $this->assertEquals(66.7, $analytics['satisfaction_rate']);
        // Critical count: 1 (star 2)
        $this->assertSame(1, $analytics['critical_count']);
        $this->assertEquals(33.3, $analytics['critical_rate']);
        // Star counts
        $this->assertSame(2, $analytics['star_counts'][5]);
        $this->assertSame(1, $analytics['star_counts'][2]);
        $this->assertSame(0, $analytics['star_counts'][1]);
    }

    public function test_customer_review_resource_table_renders_for_owner(): void
    {
        Storage::fake('local');
        $this->seed(RolePermissionSeeder::class);
        $world = $this->createGuestRestaurant();

        /** @var Restaurant $restaurant */
        $restaurant = $world['restaurant'];
        $outlet = $world['outlet'];
        $table = $world['table'];

        $owner = User::factory()->create();
        $ownerRole = \App\Models\Role::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('name', 'owner')
            ->first();

        if ($ownerRole) {
            $owner->assignRole($ownerRole);
        } else {
            $owner->assignRole('super_admin');
        }

        $visit = Visit::withoutRestaurantScope()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'table_id' => $table->id,
            'status' => 'closed',
            'join_pin' => '1234',
            'claimed_at' => now()->subHour(),
            'claim_expires_at' => now()->addHour(),
            'customer_name' => 'Budi Santoso',
            'customer_wa' => '6281234567890',
        ]);

        $review = RestaurantReview::withoutRestaurantScope()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'visit_id' => $visit->id,
            'customer_name' => 'Budi Santoso',
            'rating' => 5,
            'comment' => 'Makanannya lezat sekali!',
            'is_published' => true,
            'submitted_at' => now(),
        ]);

        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        Livewire::test(ListCustomerReviews::class)
            ->assertSuccessful()
            ->assertSee('Budi Santoso')
            ->assertSee('Makanannya lezat sekali!')
            ->callTableAction('chatWa', $review, [
                'message' => 'Halo Budi, terima kasih banyak atas kunjungannya!',
            ])
            ->assertHasNoTableActionErrors();
    }

    public function test_customer_satisfaction_analytics_page_renders_for_owner(): void
    {
        Storage::fake('local');
        $this->seed(RolePermissionSeeder::class);
        $world = $this->createGuestRestaurant();

        /** @var Restaurant $restaurant */
        $restaurant = $world['restaurant'];
        $outlet = $world['outlet'];
        $table = $world['table'];

        $owner = User::factory()->create();
        $ownerRole = \App\Models\Role::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('name', 'owner')
            ->first();

        if ($ownerRole) {
            $owner->assignRole($ownerRole);
        } else {
            $owner->assignRole('super_admin');
        }

        $visit = Visit::withoutRestaurantScope()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'table_id' => $table->id,
            'status' => 'closed',
            'join_pin' => '1234',
            'claimed_at' => now()->subHour(),
            'claim_expires_at' => now()->addHour(),
            'customer_name' => 'Dewi Lestari',
            'customer_wa' => '6281234567899',
        ]);

        RestaurantReview::withoutRestaurantScope()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'visit_id' => $visit->id,
            'customer_name' => 'Dewi Lestari',
            'rating' => 5,
            'comment' => 'Pelayanan ramah dan tempat bersih.',
            'is_published' => true,
            'submitted_at' => now(),
        ]);

        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        Livewire::test(CustomerSatisfactionAnalytics::class)
            ->assertSuccessful()
            ->assertSee('Indeks Kepuasan Pelanggan')
            ->assertSee('Skor Rata-Rata Rating');
    }

    public function test_review_wa_link_supports_custom_message(): void
    {
        Storage::fake('local');
        $world = $this->createGuestRestaurant();

        /** @var Restaurant $restaurant */
        $restaurant = $world['restaurant'];
        $outlet = $world['outlet'];
        $table = $world['table'];

        $visit = Visit::withoutRestaurantScope()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'table_id' => $table->id,
            'status' => 'closed',
            'join_pin' => '1234',
            'claimed_at' => now()->subHour(),
            'claim_expires_at' => now()->addHour(),
            'customer_name' => 'Ahmad',
            'customer_wa' => '081298765432',
        ]);

        $review = RestaurantReview::withoutRestaurantScope()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'visit_id' => $visit->id,
            'customer_name' => 'Ahmad',
            'rating' => 2,
            'comment' => 'Pesanan agak lama.',
            'is_published' => true,
            'submitted_at' => now(),
        ]);

        $customMsg = 'Halo Kak Ahmad, mohon maaf atas kendala kemarin. Kami beri diskon 20% untuk kunjungan berikutnya.';
        $link = $review->waLink($customMsg);

        $this->assertNotNull($link);
        $this->assertStringContainsString('https://wa.me/6281298765432', $link);
        $this->assertStringContainsString(urlencode($customMsg), $link);
    }
}
