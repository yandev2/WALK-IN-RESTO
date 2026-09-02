<?php

namespace Tests\Feature;

use App\Models\CmsBanner;
use App\Models\CmsFaq;
use App\Models\CmsGalleryImage;
use App\Models\CmsProfile;
use App\Models\LandingTemplate;
use App\Models\RestaurantReview;
use App\Models\Visit;
use App\Services\LandingPageDataService;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\LandingTemplateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class LandingMultiTemplateTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_default_landing_uses_classic_template(): void
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->get(route('landing.show', 'resto-demo'));

        $response->assertOk();
        $response->assertSee('Cerita di balik', false);
        $response->assertSee('Suasana', false);
    }

    public function test_landing_switches_to_foodie_template(): void
    {
        $world = $this->createGuestRestaurant();
        $this->seed(LandingTemplateSeeder::class);

        CmsProfile::query()->updateOrCreate(
            ['restaurant_id' => $world['restaurant']->id],
            [
                'headline' => 'Desire Food for Your Taste',
                'about_html' => '<p>Cerita di balik resto kami yang berdiri sejak 2020.</p>',
                'landing_template' => 'foodie',
            ]
        );

        $response = $this->get(route('landing.show', $world['restaurant']));

        $response->assertOk();
        $response->assertSee('Tentang kami', false);
        $response->assertSee('Cerita di balik', false);
        $response->assertSee('Walk-in, scan, bayar', false);
        $response->assertSee('Datang', false);
        $response->assertSee('Desire Food for Your Taste', false);
    }

    public function test_landing_switches_to_glassmorphism_template(): void
    {
        $world = $this->createGuestRestaurant();
        $this->seed(LandingTemplateSeeder::class);

        CmsProfile::query()->updateOrCreate(
            ['restaurant_id' => $world['restaurant']->id],
            [
                'headline' => 'Symphony of Flavours',
                'about_html' => '<p>Restoran berkonsep modern glassmorphism futuristik.</p>',
                'landing_template' => 'glassmorphism',
            ]
        );

        $response = $this->get(route('landing.show', $world['restaurant']));

        $response->assertOk();
        $response->assertSee('Symphony of Flavours', false);
        $response->assertSee('backdrop-blur', false);
        $response->assertSee('Restoran berkonsep modern glassmorphism', false);
    }

    public function test_glassmorphism_template_renders_all_sections_including_banners_and_faqs(): void
    {
        $world = $this->createGuestRestaurant();
        $this->seed(LandingTemplateSeeder::class);

        $world['item']->update(['is_active' => true]);

        CmsProfile::query()->updateOrCreate(
            ['restaurant_id' => $world['restaurant']->id],
            [
                'landing_template' => 'glassmorphism',
                'about_html' => '<p>Tentang kami restoran konsep kaca iOS.</p>',
            ]
        );

        CmsBanner::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'title' => 'Promo Diskon Merdeka',
            'subtitle' => 'Dapatkan potongan harga menarik',
            'image_path' => 'banners/demo.jpg',
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDays(7),
            'is_active' => true,
            'sort_order' => 1,
        ]);

        CmsFaq::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'question' => 'Bagaimana cara memesan makanan?',
            'answer_html' => '<p>Cukup scan QR code di meja Anda.</p>',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        CmsGalleryImage::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'caption' => 'Suasana ruangan VIP',
            'image_path' => 'gallery/demo.jpg',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $visit = Visit::query()->create([
            'public_id' => (string) Str::ulid(),
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'table_id' => $world['table']->id,
            'status' => 'closed',
            'join_pin' => '1234',
            'customer_wa' => '6281234567890',
            'customer_name' => 'Budi Santoso',
            'claimed_at' => now()->subHour(),
            'claim_expires_at' => now()->subMinutes(30),
            'closed_at' => now()->subMinutes(20),
        ]);

        RestaurantReview::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'visit_id' => $visit->id,
            'rating' => 5,
            'customer_name' => 'Budi Santoso',
            'comment' => 'Makanan enak sekali!',
            'submitted_at' => now(),
        ]);

        $response = $this->get(route('landing.show', $world['restaurant']));

        $response->assertOk();
        $response->assertSee('Promo Diskon Merdeka', false);
        $response->assertSee('Bagaimana cara memesan makanan?', false);
        $response->assertSee('Suasana ruangan VIP', false);
        $response->assertSee('Budi Santoso', false);
        $response->assertSee('Es Teh', false);
        $response->assertSee('Rp 8.000', false);
    }

    public function test_foodie_template_renders_all_sections_including_banners_and_faqs(): void
    {
        $world = $this->createGuestRestaurant();
        $this->seed(LandingTemplateSeeder::class);

        $world['item']->update(['is_active' => true]);

        CmsProfile::query()->updateOrCreate(
            ['restaurant_id' => $world['restaurant']->id],
            [
                'landing_template' => 'foodie',
                'about_html' => '<p>Tentang kami restoran terbaik.</p>',
            ]
        );

        CmsBanner::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'title' => 'Promo Diskon Merdeka',
            'subtitle' => 'Dapatkan potongan harga menarik',
            'image_path' => 'banners/demo.jpg',
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDays(7),
            'is_active' => true,
            'sort_order' => 1,
        ]);

        CmsFaq::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'question' => 'Bagaimana cara memesan makanan?',
            'answer_html' => '<p>Cukup scan QR code di meja Anda.</p>',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        CmsGalleryImage::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'caption' => 'Suasana ruangan VIP',
            'image_path' => 'gallery/demo.jpg',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $visit = Visit::query()->create([
            'public_id' => (string) Str::ulid(),
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'table_id' => $world['table']->id,
            'status' => 'closed',
            'join_pin' => '1234',
            'customer_wa' => '6281234567890',
            'customer_name' => 'Budi Santoso',
            'claimed_at' => now()->subHour(),
            'claim_expires_at' => now()->subMinutes(30),
            'closed_at' => now()->subMinutes(20),
        ]);

        RestaurantReview::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'visit_id' => $visit->id,
            'rating' => 5,
            'customer_name' => 'Budi Santoso',
            'comment' => 'Makanan enak sekali!',
            'submitted_at' => now(),
        ]);

        $response = $this->get(route('landing.show', $world['restaurant']));

        $response->assertOk();
        $response->assertSee('Promo Diskon Merdeka', false);
        $response->assertSee('Bagaimana cara memesan makanan?', false);
        $response->assertSee('Suasana ruangan VIP', false);
        $response->assertSee('Budi Santoso', false);
        $response->assertSee('Es Teh', false);
        $response->assertSee('Rp 8.000', false);
    }

    public function test_foodie_template_renders_real_database_menu_items_and_prices(): void
    {
        $world = $this->createGuestRestaurant();
        $this->seed(LandingTemplateSeeder::class);

        $world['item']->update(['is_active' => true]);

        CmsProfile::query()->updateOrCreate(
            ['restaurant_id' => $world['restaurant']->id],
            [
                'landing_template' => 'foodie',
            ]
        );

        $response = $this->get(route('landing.show', $world['restaurant']));

        $response->assertOk();
        $response->assertSee('Es Teh', false);
        $response->assertSee('Rp 8.000', false);
        $response->assertSee('Hidangan populer hari ini', false);
    }

    public function test_landing_page_data_service_resolves_fallback_safely(): void
    {
        $world = $this->createGuestRestaurant();
        $service = app(LandingPageDataService::class);

        $resolved = $service->resolveTemplate('unknown-template-slug');
        $this->assertEquals('classic', $resolved->slug);

        $payload = $service->getPayload($world['restaurant']);
        $this->assertArrayHasKey('restaurant', $payload);
        $this->assertArrayHasKey('menuItems', $payload);
        $this->assertArrayHasKey('template', $payload);
        $this->assertEquals($world['restaurant']->id, $payload['restaurant']->id);
    }

    public function test_landing_templates_exist_in_database(): void
    {
        $this->seed(LandingTemplateSeeder::class);

        $classic = LandingTemplate::query()->where('slug', 'classic')->first();
        $this->assertNotNull($classic);
        $this->assertEquals('Classic Elegant', $classic->name);
        $this->assertTrue($classic->is_active);

        $foodie = LandingTemplate::query()->where('slug', 'foodie')->first();
        $this->assertNotNull($foodie);
        $this->assertEquals('Foodie Delight', $foodie->name);
        $this->assertEquals('Populer', $foodie->badge);
        $this->assertTrue($foodie->is_active);

        $glass = LandingTemplate::query()->where('slug', 'glassmorphism')->first();
        $this->assertNotNull($glass);
        $this->assertEquals('Glassmorphism iOS', $glass->name);
        $this->assertEquals('Eksklusif', $glass->badge);
        $this->assertTrue($glass->is_active);
    }
}
