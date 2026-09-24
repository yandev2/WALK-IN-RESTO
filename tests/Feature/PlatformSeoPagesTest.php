<?php

namespace Tests\Feature;

use App\Models\PlatformSetting;
use App\Models\Restaurant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlatformSeoPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_static_pages_have_unique_canonical_and_do_not_bleed_home_canonical(): void
    {
        PlatformSetting::current()->update([
            'site_name' => 'CitarasaKita',
            'canonical_url' => 'https://citarasakita.com',
            'about_title' => 'Tentang Kami',
            'about_content' => '<p>Platform direktori kuliner terbaik untuk pecinta makanan lokal.</p>',
            'terms_title' => 'Syarat dan Ketentuan',
            'terms_content' => '<p>Harap membaca syarat penggunaan platform sebelum mendaftar.</p>',
        ]);

        // 1. Check About Page
        $aboutResponse = $this->get(route('page.about'));
        $aboutResponse->assertOk()
            ->assertSee('rel="canonical" href="'.route('page.about').'"', false)
            ->assertDontSee('rel="canonical" href="https://citarasakita.com"', false)
            ->assertSee('"@type": "BreadcrumbList"', false)
            ->assertSee('Platform direktori kuliner terbaik', false);

        // 2. Check Terms Page
        $termsResponse = $this->get(route('page.terms'));
        $termsResponse->assertOk()
            ->assertSee('rel="canonical" href="'.route('page.terms').'"', false)
            ->assertDontSee('rel="canonical" href="https://citarasakita.com"', false)
            ->assertSee('"@type": "BreadcrumbList"', false)
            ->assertSee('Harap membaca syarat penggunaan platform', false);

        // 3. Check Register Page
        $registerResponse = $this->get(route('register.restaurant'));
        $registerResponse->assertOk()
            ->assertSee('rel="canonical" href="'.route('register.restaurant').'"', false)
            ->assertDontSee('rel="canonical" href="https://citarasakita.com"', false)
            ->assertSee('Daftarkan Restoran Anda - Coba Gratis', false);
    }

    public function test_restaurant_landing_and_menu_pages_have_rich_seo_tags_and_schema(): void
    {
        $restaurant = Restaurant::query()->create([
            'name' => 'Kopi Nusantara',
            'slug' => 'kopi-nusantara',
            'is_active' => true,
            'listed_in_directory' => true,
            'landing_enabled' => true,
        ]);

        // 1. Restaurant Landing Page
        $landingResponse = $this->get(route('landing.show', $restaurant));
        $landingResponse->assertOk()
            ->assertSee('rel="canonical" href="'.route('landing.show', $restaurant).'"', false)
            ->assertSee('name="robots" content="index, follow', false)
            ->assertSee('property="og:type" content="restaurant.restaurant"', false)
            ->assertSee('property="og:title" content="', false)
            ->assertSee('"@type": "Restaurant"', false)
            ->assertSee('"name": "Kopi Nusantara"', false);

        // 2. Restaurant Menu Page
        $menuResponse = $this->get(route('landing.menu', $restaurant));
        $menuResponse->assertOk()
            ->assertSee('rel="canonical" href="'.route('landing.menu', $restaurant).'"', false)
            ->assertSee('name="robots" content="index, follow', false)
            ->assertSee('property="og:type" content="restaurant.restaurant"', false)
            ->assertSee('Menu Lengkap · Kopi Nusantara', false)
            ->assertSee('"@type": "Restaurant"', false);
    }

    public function test_home_canonical_normalizes_and_removes_sitelinks_searchbox_template(): void
    {
        config(['app.url' => 'https://citarasakita.com']);

        $response = $this->get('https://www.citarasakita.com');
        $response->assertOk()
            ->assertSee('rel="canonical" href="https://citarasakita.com"', false)
            ->assertDontSee('rel="canonical" href="https://www.citarasakita.com"', false)
            ->assertDontSee('{search_term_string}', false);
    }

    public function test_robots_txt_disallows_en_blog_search_and_admin(): void
    {
        $response = $this->get('/robots.txt');
        $response->assertOk()
            ->assertSee('Disallow: /en/', false)
            ->assertSee('Disallow: /en/blog', false)
            ->assertSee('Disallow: /admin', false)
            ->assertSee('Disallow: /*?*search=', false)
            ->assertSee('Allow: /id/blog', false);

        // Also check static public/robots.txt file
        $staticRobots = file_get_contents(public_path('robots.txt'));
        $this->assertStringContainsString('Disallow: /en/', $staticRobots);
        $this->assertStringContainsString('Disallow: /en/blog', $staticRobots);
        $this->assertStringContainsString('Disallow: /admin', $staticRobots);
        $this->assertStringContainsString('Disallow: /*?*search=', $staticRobots);
    }

    public function test_admin_login_link_in_register_page_has_rel_nofollow(): void
    {
        $response = $this->get(route('register.restaurant'));
        $response->assertOk()
            ->assertSee('href="'.url('/admin/login').'" rel="nofollow"', false);
    }

    public function test_english_blog_has_noindex_nofollow_meta(): void
    {
        $response = $this->get('/en/blog');
        $response->assertOk()
            ->assertSee('name="robots" content="noindex, nofollow"', false);
    }

    public function test_legacy_blog_redirect_defaults_strictly_to_id(): void
    {
        $response = $this->get('/blog');
        $response->assertStatus(301)
            ->assertRedirect('/id/blog');

        $homeResponse = $this->get('/');
        $homeResponse->assertOk()
            ->assertSee(route('blog.index', ['locale' => 'id']), false);
    }
}

