<?php

namespace Tests\Feature;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogLocaleRoutingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $author = User::factory()->create([
            'is_active' => true,
        ]);

        $category = BlogCategory::create([
            'sort_order' => 1,
            'is_active' => true,
        ]);
        $category->translations()->createMany([
            ['locale' => 'id', 'name' => 'Kuliner Nusantara', 'slug' => 'kuliner-nusantara'],
            ['locale' => 'en', 'name' => 'Indonesian Culinary', 'slug' => 'indonesian-culinary'],
        ]);

        $tag = BlogTag::create([
            'is_active' => true,
        ]);
        $tag->translations()->createMany([
            ['locale' => 'id', 'name' => 'Tips Resto', 'slug' => 'tips-resto'],
            ['locale' => 'en', 'name' => 'Restaurant Tips', 'slug' => 'restaurant-tips'],
        ]);

        $post = BlogPost::create([
            'author_id' => $author->id,
            'blog_category_id' => $category->id,
            'status' => 'published',
            'published_at' => now()->subDay(),
            'is_featured' => true,
            'is_active' => true,
        ]);
        $post->translations()->createMany([
            [
                'locale' => 'id',
                'title' => 'Panduan Menjalankan Resto',
                'slug' => 'panduan-menjalankan-resto',
                'excerpt' => 'Ringkasan artikel panduan resto.',
                'content' => '<p>Konten artikel lengkap bahasa indonesia.</p>',
            ],
            [
                'locale' => 'en',
                'title' => 'Guide to Running a Restaurant',
                'slug' => 'guide-to-running-a-restaurant',
                'excerpt' => 'Summary of restaurant guide.',
                'content' => '<p>Full restaurant guide in english.</p>',
            ],
        ]);
        $post->tags()->attach($tag->id);
    }

    public function test_localized_blog_index_returns_200_for_id_and_en(): void
    {
        $responseId = $this->get('/id/blog');
        $responseId->assertOk();
        $responseId->assertSee('Kuliner Nusantara');
        $responseId->assertSee('aria-label="Bahasa Indonesia"', false);
        $responseId->assertSee('aria-label="English"', false);
        $responseId->assertSee('ID');
        $responseId->assertSee('EN');

        $responseEn = $this->get('/en/blog');
        $responseEn->assertOk();
        $responseEn->assertSee('Indonesian Culinary');
    }

    public function test_legacy_blog_url_redirects_301_to_localized_path(): void
    {
        $responseDefault = $this->get('/blog');
        $responseDefault->assertStatus(301);
        $responseDefault->assertRedirect('/id/blog');

        $responseEn = $this->get('/blog?lang=en');
        $responseEn->assertStatus(301);
        $responseEn->assertRedirect('/en/blog');

        $responseArticles = $this->get('/blog/articles?q=resto');
        $responseArticles->assertStatus(301);
        $responseArticles->assertRedirect('/id/blog/articles?q=resto');

        $responseCategory = $this->get('/blog/category/kuliner-nusantara');
        $responseCategory->assertStatus(301);
        $responseCategory->assertRedirect('/id/blog/category/kuliner-nusantara');

        $responseTag = $this->get('/blog/tag/tips-resto');
        $responseTag->assertStatus(301);
        $responseTag->assertRedirect('/id/blog/tag/tips-resto');

        $responseShow = $this->get('/blog/panduan-menjalankan-resto');
        $responseShow->assertStatus(301);
        $responseShow->assertRedirect('/id/blog/panduan-menjalankan-resto');
    }

    public function test_localized_blog_post_detail_page_loads_correctly(): void
    {
        $responseId = $this->get('/id/blog/panduan-menjalankan-resto');
        $responseId->assertOk();
        $responseId->assertSee('Panduan Menjalankan Resto');
        $responseId->assertSee('/en/blog/guide-to-running-a-restaurant');

        $responseEn = $this->get('/en/blog/guide-to-running-a-restaurant');
        $responseEn->assertOk();
        $responseEn->assertSee('Guide to Running a Restaurant');
        $responseEn->assertSee('/id/blog/panduan-menjalankan-resto');
    }

    public function test_sitemap_outputs_clean_localized_blog_paths(): void
    {
        $response = $this->get('/sitemap.xml');
        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');

        $content = $response->getContent();
        $this->assertStringContainsString('/id/blog', $content);
        $this->assertStringContainsString('/en/blog', $content);
        $this->assertStringNotContainsString('?lang=', $content);
    }
}
