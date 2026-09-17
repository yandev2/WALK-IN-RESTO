<?php

namespace Tests\Feature;

use App\Enums\BlogPostStatus;
use App\Models\BlogCategory;
use App\Models\BlogHeroSetting;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BlogSeoAndMediaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_sitemap_xml_includes_all_blog_pages_images_and_multilingual_alternates(): void
    {
        Storage::fake('public');

        $author = User::factory()->create(['is_active' => true]);
        $author->assignRole('blogger');

        $category = BlogCategory::create(['is_active' => true, 'sort_order' => 1]);
        $category->translations()->create([
            'locale' => 'id',
            'name' => 'Kuliner Nusantara',
            'slug' => 'kuliner-nusantara',
        ]);
        $category->translations()->create([
            'locale' => 'en',
            'name' => 'Indonesian Culinary',
            'slug' => 'indonesian-culinary',
        ]);

        $tag = BlogTag::create(['is_active' => true]);
        $tag->translations()->create([
            'locale' => 'id',
            'name' => 'Resep',
            'slug' => 'resep',
        ]);
        $tag->translations()->create([
            'locale' => 'en',
            'name' => 'Recipe',
            'slug' => 'recipe',
        ]);

        $post = BlogPost::create([
            'author_id' => $author->id,
            'blog_category_id' => $category->id,
            'status' => BlogPostStatus::Published,
            'is_active' => true,
            'featured_image' => 'blog/featured/sate-ayam.jpg',
            'published_at' => now()->subDay(),
            'reading_time_minutes' => 4,
            'views_count' => 10,
        ]);
        $post->tags()->attach($tag->id);

        $post->translations()->create([
            'locale' => 'id',
            'title' => 'Resep Sate Ayam Madura Asli',
            'slug' => 'resep-sate-ayam-madura-asli',
            'excerpt' => 'Panduan lengkap membuat sate ayam bumbu kacang.',
            'content' => '<p>Langkah pembuatan sate ayam madura asli.</p>',
            'featured_image_alt' => 'Foto Sate Ayam Madura',
        ]);
        $post->translations()->create([
            'locale' => 'en',
            'title' => 'Authentic Madura Chicken Satay Recipe',
            'slug' => 'authentic-madura-chicken-satay-recipe',
            'excerpt' => 'Complete guide to making Indonesian chicken satay.',
            'content' => '<p>Steps to make authentic Madura chicken satay.</p>',
            'featured_image_alt' => 'Madura Chicken Satay Photo',
        ]);

        // Create an unpublished draft post that should NOT be in the sitemap
        $draftPost = BlogPost::create([
            'author_id' => $author->id,
            'status' => BlogPostStatus::Draft,
            'is_active' => true,
        ]);
        $draftPost->translations()->create([
            'locale' => 'id',
            'title' => 'Artikel Rahasia Draft',
            'slug' => 'artikel-rahasia-draft',
            'content' => '<p>Draft konten rahasia.</p>',
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=utf-8')
            ->assertSee('xmlns:xhtml="http://www.w3.org/1999/xhtml"', false)
            ->assertSee('xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"', false)
            // Blog main routes
            ->assertSee(route('blog.index'), false)
            ->assertSee(route('blog.archive'), false)
            // Category routes & alternates
            ->assertSee(route('blog.category', ['slug' => 'kuliner-nusantara']), false)
            ->assertSee(route('blog.category', ['slug' => 'indonesian-culinary']), false)
            ->assertSee('hreflang="id"', false)
            ->assertSee('hreflang="en"', false)
            // Tag routes
            ->assertSee(route('blog.tag', ['slug' => 'resep']), false)
            ->assertSee(route('blog.tag', ['slug' => 'recipe']), false)
            // Post routes & Google Images
            ->assertSee(route('blog.show', ['slug' => 'resep-sate-ayam-madura-asli']), false)
            ->assertSee(route('blog.show', ['slug' => 'authentic-madura-chicken-satay-recipe']), false)
            ->assertSee('<image:loc>', false)
            ->assertSee('blog/featured/sate-ayam.jpg', false)
            ->assertSee('Foto Sate Ayam Madura', false)
            // Draft should not appear
            ->assertDontSee('artikel-rahasia-draft');
    }

    public function test_robots_txt_allows_blog_and_disallows_blogger_panel(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertSee('Allow: /blog', false)
            ->assertSee('Disallow: /blogger', false)
            ->assertSee('Disallow: /admin', false)
            ->assertSee('Disallow: /founder', false)
            ->assertSee('Sitemap: '.route('sitemap'), false);
    }

    public function test_article_page_renders_json_ld_schema_breadcrumbs_and_open_graph(): void
    {
        $author = User::factory()->create(['name' => 'Budi Santoso', 'is_active' => true]);
        $author->assignRole('blogger');

        $category = BlogCategory::create(['is_active' => true]);
        $category->translations()->create([
            'locale' => 'id',
            'name' => 'Resep Pilihan',
            'slug' => 'resep-pilihan',
        ]);
        $category->translations()->create([
            'locale' => 'en',
            'name' => 'Selected Recipes',
            'slug' => 'selected-recipes',
        ]);

        $post = BlogPost::create([
            'author_id' => $author->id,
            'blog_category_id' => $category->id,
            'status' => BlogPostStatus::Published,
            'is_active' => true,
            'featured_image' => 'blog/featured/rendang.jpg',
            'published_at' => now()->subDays(2),
        ]);

        $post->translations()->create([
            'locale' => 'id',
            'title' => 'Cara Memasak Rendang Daging Empuk',
            'slug' => 'cara-memasak-rendang-daging-empuk',
            'meta_title' => 'Resep Rendang Daging Empuk Gurih Asli Padang',
            'meta_description' => 'Tips dan resep membuat rendang daging khas Minang.',
            'meta_keywords' => 'rendang, masakan padang, daging sapi',
            'excerpt' => 'Rendang daging sapi dengan bumbu rempah melimpah.',
            'content' => '<p>Langkah demi langkah memasak rendang.</p>',
        ]);
        $post->translations()->create([
            'locale' => 'en',
            'title' => 'How to Cook Tender Beef Rendang',
            'slug' => 'how-to-cook-tender-beef-rendang',
            'meta_description' => 'Tips and recipe for authentic Minang beef rendang.',
            'excerpt' => 'Tender beef rendang with aromatic spices.',
            'content' => '<p>Step by step beef rendang recipe.</p>',
        ]);

        $response = $this->get('/blog/cara-memasak-rendang-daging-empuk');

        $response->assertOk()
            // Custom Meta Title & Description
            ->assertSee('Resep Rendang Daging Empuk Gurih Asli Padang', false)
            ->assertSee('Tips dan resep membuat rendang daging khas Minang.', false)
            ->assertSee('name="keywords" content="rendang, masakan padang, daging sapi"', false)
            // JSON-LD Schema
            ->assertSee('"@type": "BlogPosting"', false)
            ->assertSee('"@type": "BreadcrumbList"', false)
            ->assertSee('Budi Santoso', false)
            ->assertSee('Resep Pilihan', false)
            // OpenGraph article meta
            ->assertSee('property="article:published_time"', false)
            ->assertSee('property="article:author" content="Budi Santoso"', false)
            ->assertSee('property="article:section" content="Resep Pilihan"', false)
            // Hreflang alternates
            ->assertSee('hreflang="id"', false)
            ->assertSee('hreflang="en"', false)
            ->assertSee('hreflang="x-default"', false);
    }

    public function test_physical_image_is_deleted_when_article_featured_image_is_updated_or_removed(): void
    {
        Storage::fake('public');

        $author = User::factory()->create(['is_active' => true]);
        $author->assignRole('blogger');

        $oldImage = 'blog/featured/old-food.jpg';
        $newImage = 'blog/featured/new-food.jpg';

        Storage::disk('public')->put($oldImage, 'fake old image content');
        Storage::disk('public')->put($newImage, 'fake new image content');

        $this->assertTrue(Storage::disk('public')->exists($oldImage));
        $this->assertTrue(Storage::disk('public')->exists($newImage));

        $post = BlogPost::create([
            'author_id' => $author->id,
            'status' => BlogPostStatus::Published,
            'is_active' => true,
            'featured_image' => $oldImage,
            'published_at' => now(),
        ]);

        // 1. Update featured image with a new one
        $post->update(['featured_image' => $newImage]);

        // Old file must be physically deleted, new file must exist
        $this->assertFalse(Storage::disk('public')->exists($oldImage));
        $this->assertTrue(Storage::disk('public')->exists($newImage));

        // 2. Remove/delete featured image (set to null)
        $post->update(['featured_image' => null]);

        // The image file must be physically deleted
        $this->assertFalse(Storage::disk('public')->exists($newImage));
    }

    public function test_physical_files_are_deleted_when_post_is_force_deleted(): void
    {
        Storage::fake('public');

        $author = User::factory()->create(['is_active' => true]);
        $author->assignRole('blogger');

        $featuredPath = 'blog/featured/sample-post-pic.jpg';
        $ogPath = 'blog/og/sample-og-pic.jpg';
        $inlinePath = 'blog/attachments/editor-upload-1.png';

        Storage::disk('public')->put($featuredPath, 'featured content');
        Storage::disk('public')->put($ogPath, 'og content');
        Storage::disk('public')->put($inlinePath, 'inline attachment content');

        $post = BlogPost::create([
            'author_id' => $author->id,
            'status' => BlogPostStatus::Published,
            'is_active' => true,
            'featured_image' => $featuredPath,
            'og_image' => $ogPath,
            'published_at' => now(),
        ]);

        $post->translations()->create([
            'locale' => 'id',
            'title' => 'Judul Artikel dengan Gambar',
            'slug' => 'judul-artikel-dengan-gambar',
            'content' => '<p>Konten artikel dengan foto <img src="/storage/'.$inlinePath.'" alt="foto"></p>',
        ]);

        $this->assertTrue(Storage::disk('public')->exists($featuredPath));
        $this->assertTrue(Storage::disk('public')->exists($ogPath));
        $this->assertTrue(Storage::disk('public')->exists($inlinePath));

        // Force delete post
        $post->forceDelete();

        // All physical files must be removed
        $this->assertFalse(Storage::disk('public')->exists($featuredPath));
        $this->assertFalse(Storage::disk('public')->exists($ogPath));
        $this->assertFalse(Storage::disk('public')->exists($inlinePath));
    }

    public function test_removed_inline_attachment_in_content_is_deleted_when_content_is_updated(): void
    {
        Storage::fake('public');

        $author = User::factory()->create(['is_active' => true]);
        $author->assignRole('blogger');

        $attachment1 = 'blog/attachments/kept-photo.jpg';
        $attachment2 = 'blog/attachments/deleted-photo.jpg';

        Storage::disk('public')->put($attachment1, 'kept image');
        Storage::disk('public')->put($attachment2, 'deleted image');

        $post = BlogPost::create([
            'author_id' => $author->id,
            'status' => BlogPostStatus::Published,
            'is_active' => true,
            'published_at' => now(),
        ]);

        $translation = $post->translations()->create([
            'locale' => 'id',
            'title' => 'Artikel Dua Foto',
            'slug' => 'artikel-dua-foto',
            'content' => '<p><img src="/storage/'.$attachment1.'"> dan <img src="/storage/'.$attachment2.'"></p>',
        ]);

        $this->assertTrue(Storage::disk('public')->exists($attachment1));
        $this->assertTrue(Storage::disk('public')->exists($attachment2));

        // Update content: user deleted attachment2 from the text editor
        $translation->update([
            'content' => '<p><img src="/storage/'.$attachment1.'"> saja sekarang.</p>',
        ]);

        // attachment2 should be physically deleted, attachment1 should still exist
        $this->assertTrue(Storage::disk('public')->exists($attachment1));
        $this->assertFalse(Storage::disk('public')->exists($attachment2));
    }

    public function test_blog_hero_setting_purges_old_banner_image_on_update(): void
    {
        Storage::fake('public');

        $oldBanner = 'blog/hero/old-hero-banner.jpg';
        $newBanner = 'blog/hero/new-hero-banner.jpg';

        Storage::disk('public')->put($oldBanner, 'old hero banner');
        Storage::disk('public')->put($newBanner, 'new hero banner');

        $setting = BlogHeroSetting::getSingleton();
        $setting->update(['banner_image' => $oldBanner]);

        $this->assertTrue(Storage::disk('public')->exists($oldBanner));

        // Update with new banner
        $setting->update(['banner_image' => $newBanner]);

        // Old banner must be deleted
        $this->assertFalse(Storage::disk('public')->exists($oldBanner));
        $this->assertTrue(Storage::disk('public')->exists($newBanner));
    }

    public function test_category_and_tag_slug_resolve_across_locales_without_404(): void
    {
        // Category only has 'id' translation
        $category = BlogCategory::create(['is_active' => true]);
        $category->translations()->create([
            'locale' => 'id',
            'name' => 'Kuliner Nusantara',
            'slug' => 'kuliner-nusantara',
        ]);

        // Tag only has 'id' translation
        $tag = BlogTag::create(['is_active' => true]);
        $tag->translations()->create([
            'locale' => 'id',
            'name' => 'Kopi Enak',
            'slug' => 'kopi-enak',
        ]);

        // Set application locale to English ('en')
        app()->setLocale('en');

        // Request without locale query param
        $catResponse = $this->get('/blog/category/kuliner-nusantara');
        $catResponse->assertOk()
            ->assertSee('Kuliner Nusantara', false);

        $tagResponse = $this->get('/blog/tag/kopi-enak');
        $tagResponse->assertOk()
            ->assertSee('Kopi Enak', false);
    }
}

