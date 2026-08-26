<?php

namespace Tests\Feature;

use App\Models\CmsGalleryImage;
use App\Models\CmsProfile;
use App\Models\RestaurantReview;
use App\Models\Visit;
use App\Support\LandingLayout;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class LandingLayoutTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_demo_landing_keeps_default_section_copy(): void
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->get(route('landing.show', 'resto-demo'));

        $response->assertOk();
        $response->assertSee('Cerita di balik', false);
        $response->assertSee('text-primary">dapur</span>', false);
        $response->assertSee('Suasana', false);
        $response->assertSee('text-primary">resto</span>', false);
        $response->assertSee('id="tentang"', false);
        $response->assertSee('id="galeri"', false);
    }

    public function test_custom_about_title_appears_on_landing(): void
    {
        $world = $this->createGuestRestaurant();

        CmsProfile::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'about_html' => '<p>Isi tentang restoran.</p>',
            'landing_copy' => [
                'about' => [
                    'title' => 'Kisah kami sendiri',
                    'highlight' => 'kami',
                ],
            ],
        ]);

        $this->get(route('landing.show', $world['restaurant']))
            ->assertOk()
            ->assertSee('Kisah', false)
            ->assertSee('text-primary">kami</span>', false)
            ->assertDontSee('Cerita di balik', false);
    }

    public function test_disabled_gallery_section_is_hidden(): void
    {
        $world = $this->createGuestRestaurant();

        CmsProfile::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'about_html' => '<p>Ada cerita.</p>',
            'landing_sections' => [
                ['id' => 'gallery', 'enabled' => false],
            ],
        ]);

        CmsGalleryImage::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'image_path' => 'https://example.com/suasana.jpg',
            'caption' => 'Suasana',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->get(route('landing.show', $world['restaurant']))
            ->assertOk()
            ->assertDontSee('id="galeri"', false);
    }

    public function test_about_can_render_before_menu(): void
    {
        $world = $this->createGuestRestaurant();

        CmsProfile::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'about_html' => '<p>Cerita dulu.</p>',
            'landing_sections' => [
                ['id' => 'about', 'enabled' => true],
                ['id' => 'menu', 'enabled' => true],
            ],
        ]);

        $html = $this->get(route('landing.show', $world['restaurant']))
            ->assertOk()
            ->getContent();

        $aboutPos = strpos($html, 'id="tentang"');
        $menuPos = strpos($html, 'id="menu"');

        $this->assertNotFalse($aboutPos);
        $this->assertNotFalse($menuPos);
        $this->assertLessThan($menuPos, $aboutPos);
    }

    public function test_public_api_includes_merged_landing_layout(): void
    {
        $world = $this->createGuestRestaurant();

        $this->getJson('/api/v1/restaurants/'.$world['restaurant']->slug)
            ->assertOk()
            ->assertJsonPath('data.landing.sections.0.id', 'hero')
            ->assertJsonPath('data.landing.copy.about.title', 'Cerita di balik dapur')
            ->assertJsonPath('data.landing.copy.gallery.title', 'Suasana resto');
    }

    public function test_layout_normalizes_unknown_and_missing_sections(): void
    {
        $layout = LandingLayout::for(new CmsProfile([
            'landing_sections' => [
                ['id' => 'unknown', 'enabled' => true],
                ['id' => 'about', 'enabled' => false],
            ],
            'landing_copy' => [
                'about' => ['title' => '  '],
            ],
        ]));

        $this->assertSame('about', $layout->sections[0]['id']);
        $this->assertFalse($layout->sections[0]['enabled']);
        $this->assertSame('hero', $layout->sections[1]['id']);
        $this->assertSame('Cerita di balik dapur', $layout->copyFor('about')['title']);
        $this->assertCount(count(LandingLayout::SECTION_IDS), $layout->sections);
    }

    public function test_landing_reviews_use_testimonial_carousel(): void
    {
        $world = $this->createGuestRestaurant();

        foreach (range(1, 5) as $index) {
            RestaurantReview::query()->create([
                'restaurant_id' => $world['restaurant']->id,
                'outlet_id' => $world['outlet']->id,
                'visit_id' => Visit::query()->create([
                    'public_id' => (string) Str::ulid(),
                    'restaurant_id' => $world['restaurant']->id,
                    'outlet_id' => $world['outlet']->id,
                    'table_id' => $world['table']->id,
                    'status' => 'closed',
                    'join_pin' => '1234',
                    'customer_wa' => '6281234567890',
                    'customer_name' => 'Tamu '.$index,
                    'claimed_at' => now()->subHour(),
                    'claim_expires_at' => now()->subMinutes(30),
                    'closed_at' => now()->subMinutes(20),
                ])->id,
                'customer_name' => 'Tamu '.$index,
                'rating' => 4,
                'comment' => 'Ulasan carousel nomor '.$index.'.',
                'submitted_at' => now()->subMinutes($index),
            ]);
        }

        $this->get(route('landing.show', $world['restaurant']))
            ->assertOk()
            ->assertSee('id="ulasan"', false)
            ->assertSee('landing-testimonial', false)
            ->assertSee('testimonialCarousel', false)
            ->assertSee('/api/v1/restaurants/'.$world['restaurant']->slug.'/reviews', false)
            ->assertSee('Tamu 1', false)
            ->assertSee('landing-testimonial-slide', false)
            ->assertSee('landing-testimonial-track', false);
    }

    public function test_gallery_images_open_shared_image_preview_modal(): void
    {
        $world = $this->createGuestRestaurant();

        CmsGalleryImage::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'image_path' => 'https://example.com/galeri-1.jpg',
            'caption' => 'Meja depan',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        CmsGalleryImage::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'image_path' => 'https://example.com/galeri-2.jpg',
            'caption' => 'Dapur terbuka',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $this->get(route('landing.show', $world['restaurant']))
            ->assertOk()
            ->assertSee('id="galeri"', false)
            ->assertSee('imagePreview', false)
            ->assertSee('window.imagePreview', false)
            ->assertSee('openPreview(0)', false)
            ->assertSee('openPreview(1)', false)
            ->assertSee('image-preview-modal', false)
            ->assertSee('aria-modal="true"', false)
            ->assertSee('Meja depan', false)
            ->assertSee('https://example.com/galeri-1.jpg', false);
    }
}
