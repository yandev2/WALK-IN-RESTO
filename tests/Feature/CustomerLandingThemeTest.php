<?php

namespace Tests\Feature;

use App\Models\CmsProfile;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Support\RestaurantTheme;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerLandingThemeTest extends TestCase
{
    use RefreshDatabase;

    public function test_restaurant_theme_uses_cms_colors_with_fallback(): void
    {
        $restaurant = Restaurant::query()->create([
            'name' => 'Theme Test',
            'slug' => 'theme-test',
            'is_active' => true,
        ]);

        $default = RestaurantTheme::for($restaurant);
        $this->assertSame('#F97316', $default['primary']);
        $this->assertSame('#FB923C', $default['accent']);

        CmsProfile::query()->create([
            'restaurant_id' => $restaurant->id,
            'primary_color' => '#112233',
            'accent_color' => '#AABBCC',
        ]);

        $restaurant->load('cmsProfile');
        $theme = RestaurantTheme::for($restaurant);

        $this->assertSame('#112233', $theme['primary']);
        $this->assertSame('#AABBCC', $theme['accent']);
        $this->assertSame(RestaurantTheme::darken('#112233', 0.14), $theme['primary_dark']);
    }

    public function test_home_directory_uses_semantic_text_and_shows_logo(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('RestoTerdekat', false);
        $response->assertSee('Temukan restoran', false);
        $response->assertSee('Kopi Nusantara', false);
        $response->assertSee('directory-shell', false);
        $response->assertSee('text-muted', false);
        $response->assertSee('text-body', false);
        $response->assertSee('bg-surface-raised', false);
        $response->assertSee('rounded-full', false);
        $response->assertSee('data-theme-toggle', false);
        $response->assertDontSee('text-ink/60', false);
        $response->assertDontSee('text-ink/45', false);
    }

    public function test_landing_show_injects_brand_css_and_menu_from_database(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $response = $this->get(route('landing.show', 'resto-demo'));

        $response->assertOk();
        $response->assertSee('--brand-primary: #F97316', false);
        $response->assertSee('--surface-base:', false);
        $response->assertSee('Nasi Goreng', false);
        $response->assertSee('id="menu"', false);
        $response->assertSee('id="promo"', false);
        $response->assertSee('menanti Anda', false);
        $response->assertSee('landing-hero-clip', false);
        $response->assertSee('landing-media-overlay', false);
        $response->assertSee('landing-tab', false);
        $response->assertSee('Hidangan populer', false);
        $response->assertDontSee('color-mix(in srgb, var(--brand-primary) 88%', false);
        $response->assertDontSee('Chicken noodles here', false);
    }

    public function test_landing_includes_theme_toggle_and_anti_flash_script(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $response = $this->get(route('landing.show', 'resto-demo'));

        $response->assertOk();
        $response->assertSee('data-theme-toggle', false);
        $response->assertSee('localStorage.getItem(\'theme\')', false);
        $response->assertSee('prefers-color-scheme', false);
        $response->assertSee('classList.add(\'dark\')', false);
    }

    public function test_landing_uses_semantic_surface_classes_and_dark_variant_hooks(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $response = $this->get(route('landing.show', 'resto-demo'));

        $response->assertOk();
        $response->assertSee('bg-surface-base', false);
        $response->assertSee('bg-surface-section', false);
        $response->assertSee('landing-reveal', false);
        $response->assertSee('landing-card-hover', false);
        $response->assertSee('bg-zinc-950', false);
    }

    public function test_landing_menu_catalog_uses_equal_height_grid(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $response = $this->get(route('landing.menu', 'resto-demo'));

        $response->assertOk();
        $response->assertSee('items-stretch', false);
        $response->assertSee('flex h-full flex-col', false);
        $response->assertSee('mt-auto pt-3', false);
    }

    public function test_landing_uses_dedicated_how_to_and_about_images_not_hero(): void
    {
        $restaurant = Restaurant::query()->create([
            'name' => 'Gambar Section',
            'slug' => 'gambar-section',
            'is_active' => true,
        ]);

        CmsProfile::query()->create([
            'restaurant_id' => $restaurant->id,
            'headline' => 'Headline section',
            'about_html' => '<p>Tentang khusus.</p>',
            'hero_image_path' => 'https://example.com/hero-only.jpg',
            'how_to_image_path' => 'https://example.com/how-to-only.jpg',
            'about_image_path' => 'https://example.com/about-only.jpg',
        ]);

        $response = $this->get(route('landing.show', $restaurant));

        $response->assertOk();
        $response->assertSee('https://example.com/how-to-only.jpg', false);
        $response->assertSee('https://example.com/about-only.jpg', false);
        $response->assertSee('https://example.com/hero-only.jpg', false);
        $html = $response->getContent();
        $caraPos = strpos($html, 'id="cara-pesan"');
        $tentangPos = strpos($html, 'id="tentang"');
        $howToImgPos = strpos($html, 'https://example.com/how-to-only.jpg');
        $aboutImgPos = strpos($html, 'https://example.com/about-only.jpg');
        $heroImgPos = strpos($html, 'https://example.com/hero-only.jpg');

        $this->assertNotFalse($caraPos);
        $this->assertNotFalse($tentangPos);
        $this->assertNotFalse($howToImgPos);
        $this->assertNotFalse($aboutImgPos);
        $this->assertNotFalse($heroImgPos);
        $this->assertGreaterThan($caraPos, $howToImgPos);
        $this->assertLessThan($tentangPos, $howToImgPos);
        $this->assertGreaterThan($tentangPos, $aboutImgPos);
        $this->assertLessThan($caraPos, $heroImgPos);
    }

    public function test_landing_hides_menu_section_when_no_active_items(): void
    {
        $restaurant = Restaurant::query()->create([
            'name' => 'Kosong',
            'slug' => 'kosong',
            'is_active' => true,
        ]);

        CmsProfile::query()->create([
            'restaurant_id' => $restaurant->id,
            'headline' => 'Headline kosong',
        ]);

        $response = $this->get(route('landing.show', $restaurant));

        $response->assertOk();
        $response->assertSee('Headline kosong', false);
        $response->assertDontSee('id="menu"', false);
        $response->assertDontSee('Hidangan populer', false);
    }

    public function test_landing_menu_section_lists_only_seeded_items(): void
    {
        $restaurant = Restaurant::query()->create([
            'name' => 'Satu Menu',
            'slug' => 'satu-menu',
            'is_active' => true,
        ]);

        $world = $this->createMinimalOutlet($restaurant);

        MenuItem::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $world['outlet']->id,
            'category_id' => $world['category']->id,
            'station_id' => $world['station']->id,
            'name' => 'Soto Betawi',
            'price' => 30000,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        MenuItem::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $world['outlet']->id,
            'category_id' => $world['category']->id,
            'station_id' => $world['station']->id,
            'name' => 'Menu Nonaktif',
            'price' => 10000,
            'is_active' => false,
            'sort_order' => 2,
        ]);

        $response = $this->get(route('landing.show', $restaurant));

        $response->assertOk();
        $response->assertSee('Soto Betawi', false);
        $response->assertDontSee('Menu Nonaktif', false);
    }

    public function test_guest_menu_layout_uses_semantic_surfaces_and_theme_init(): void
    {
        [$visit, $token] = $this->openGuestForMenuTest();

        $this->withCookie('guest_device', $token)
            ->get(route('guest.menu'))
            ->assertOk()
            ->assertSee('guest-menu-shell', false)
            ->assertSee('bg-surface-base', false)
            ->assertSee('localStorage.getItem(\'theme\')', false)
            ->assertSee('data-theme-toggle', false);
    }

    public function test_tenant_landing_renders_restaurant_logo_as_favicon(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $restaurant = Restaurant::query()->where('slug', 'resto-demo')->firstOrFail();
        $restaurant->update(['logo_path' => 'restaurants/logos/custom-demo-logo.png']);

        $response = $this->get(route('landing.show', 'resto-demo'));

        $response->assertOk()
            ->assertSee('storage/restaurants/logos/custom-demo-logo.png', false)
            ->assertSee('rel="icon"', false)
            ->assertSee('rel="shortcut icon"', false)
            ->assertSee('rel="apple-touch-icon"', false);
    }

    public function test_tenant_landing_falls_back_to_platform_favicon_when_no_logo(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        \App\Models\PlatformSetting::current()->update([
            'favicon_path' => 'platform/favicon/platform-fav.png',
        ]);

        $restaurant = Restaurant::query()->where('slug', 'resto-demo')->firstOrFail();
        $restaurant->update(['logo_path' => null]);

        $response = $this->get(route('landing.show', 'resto-demo'));

        $response->assertOk()
            ->assertSee('storage/platform/favicon/platform-fav.png', false);
    }

    public function test_guest_order_renders_restaurant_logo_as_favicon(): void
    {
        [$visit, $token] = $this->openGuestForMenuTest();

        $visit->outlet->restaurant->update(['logo_path' => 'restaurants/logos/custom-guest-logo.png']);

        $response = $this->withCookie('guest_device', $token)->get(route('guest.menu'));

        $response->assertOk()
            ->assertSee('storage/restaurants/logos/custom-guest-logo.png', false);
    }

    /**
     * @return array{0: \App\Models\Visit, 1: string}
     */
    private function openGuestForMenuTest(): array
    {
        $restaurant = Restaurant::query()->create([
            'name' => 'Guest Theme',
            'slug' => 'guest-theme',
            'is_active' => true,
        ]);

        $world = $this->createMinimalOutlet($restaurant);

        $table = \App\Models\DiningTable::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $world['outlet']->id,
            'code' => 'A1',
            'qr_token' => 'test-qr-token',
            'is_active' => true,
        ]);

        $token = \Illuminate\Support\Str::random(64);

        $visit = \App\Models\Visit::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $world['outlet']->id,
            'table_id' => $table->id,
            'status' => 'open',
            'join_pin' => '1234',
            'customer_name' => 'Tamu',
            'customer_wa' => '6281234567890',
            'claimed_at' => now(),
            'claim_expires_at' => now()->addHour(),
        ]);

        $table->update(['open_visit_id' => $visit->id]);

        \App\Models\VisitDevice::query()->create([
            'restaurant_id' => $visit->restaurant_id,
            'outlet_id' => $visit->outlet_id,
            'visit_id' => $visit->id,
            'device_token' => $token,
            'is_host' => true,
            'user_agent' => 'test',
            'joined_at' => now(),
            'last_seen_at' => now(),
        ]);

        return [$visit, $token];
    }

    /**
     * @return array{outlet: \App\Models\Outlet, category: \App\Models\MenuCategory, station: \App\Models\KdsStation}
     */
    private function createMinimalOutlet(Restaurant $restaurant): array
    {
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

        $category = \App\Models\MenuCategory::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'name' => 'Makanan',
            'sort_order' => 1,
        ]);

        return compact('outlet', 'category', 'station');
    }
}
