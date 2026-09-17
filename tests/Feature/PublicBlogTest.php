<?php

namespace Tests\Feature;

use App\Enums\BlogCommentStatus;
use App\Enums\BlogPostStatus;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicBlogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_blog_landing_page_can_be_rendered(): void
    {
        $author = User::factory()->create(['name' => 'Chef Budi']);
        $category = BlogCategory::create(['is_active' => true, 'sort_order' => 1]);
        $category->translations()->create([
            'locale' => 'id',
            'name' => 'Kuliner Tradisional',
            'slug' => 'kuliner-tradisional',
        ]);
        $category->translations()->create([
            'locale' => 'en',
            'name' => 'Traditional Culinary',
            'slug' => 'traditional-culinary',
        ]);

        $post = BlogPost::create([
            'blog_category_id' => $category->id,
            'author_id' => $author->id,
            'status' => BlogPostStatus::Published,
            'is_active' => true,
            'published_at' => now()->subDay(),
            'reading_time_minutes' => 5,
        ]);
        $post->translations()->create([
            'locale' => 'id',
            'title' => 'Rahasia Rendang Daging Empuk Padang',
            'slug' => 'rahasia-rendang-daging-empuk-padang',
            'excerpt' => 'Pelajari resep rahasia dan teknik memasak rendang autentik.',
            'content' => '<p>Rendang adalah salah satu hidangan terlezat di dunia asal Minangkabau.</p>',
        ]);
        $post->translations()->create([
            'locale' => 'en',
            'title' => 'The Secret to Tender Padang Beef Rendang',
            'slug' => 'secret-to-tender-padang-beef-rendang',
            'excerpt' => 'Learn authentic cooking techniques for beef rendang.',
            'content' => '<p>Rendang is widely recognized as one of the best foods in the world.</p>',
        ]);

        $response = $this->get('/id/blog');
        $response->assertSuccessful();
        $response->assertSee('Rahasia Rendang Daging Empuk Padang');
        $response->assertSee('Chef Budi');
        $response->assertSee('Kuliner Tradisional');
    }

    public function test_blog_landing_page_bilingual_switcher(): void
    {
        $author = User::factory()->create();
        $category = BlogCategory::create(['is_active' => true]);
        $category->translations()->createMany([
            ['locale' => 'id', 'name' => 'Kategori ID', 'slug' => 'kategori-id'],
            ['locale' => 'en', 'name' => 'Category EN', 'slug' => 'category-en'],
        ]);

        $post = BlogPost::create([
            'blog_category_id' => $category->id,
            'author_id' => $author->id,
            'status' => BlogPostStatus::Published,
            'is_active' => true,
            'published_at' => now()->subDay(),
        ]);
        $post->translations()->createMany([
            ['locale' => 'id', 'title' => 'Judul Bahasa Indonesia', 'slug' => 'judul-id', 'content' => '<p>Konten ID</p>'],
            ['locale' => 'en', 'title' => 'English Article Title', 'slug' => 'english-title', 'content' => '<p>English Content</p>'],
        ]);

        $response = $this->get('/en/blog');
        $response->assertSuccessful();
        $response->assertSee('English Article Title');
        $response->assertSee('Category EN');
    }

    public function test_blog_post_detail_page_can_be_rendered(): void
    {
        $author = User::factory()->create(['name' => 'Chef Ragil']);
        $category = BlogCategory::create(['is_active' => true]);
        $category->translations()->create([
            'locale' => 'id',
            'name' => 'Resep Nusantara',
            'slug' => 'resep-nusantara',
        ]);

        $tag = BlogTag::create(['is_active' => true]);
        $tag->translations()->create([
            'locale' => 'id',
            'name' => 'Pedas Mantap',
            'slug' => 'pedas-mantap',
        ]);

        $post = BlogPost::create([
            'blog_category_id' => $category->id,
            'author_id' => $author->id,
            'status' => BlogPostStatus::Published,
            'is_active' => true,
            'published_at' => now()->subHours(5),
            'reading_time_minutes' => 7,
        ]);
        $post->tags()->attach($tag->id);

        $post->translations()->create([
            'locale' => 'id',
            'title' => 'Sensasi Sambal Matah Khas Bali',
            'slug' => 'sensasi-sambal-matah-khas-bali',
            'excerpt' => 'Perpaduan segar bawang merah, serai, dan cabai rawit.',
            'content' => '<p>Sambal matah memberikan kesegaran yang pas untuk hidangan laut bakar.</p>',
        ]);

        $response = $this->get('/id/blog/sensasi-sambal-matah-khas-bali');
        $response->assertSuccessful();
        $response->assertSee('Sensasi Sambal Matah Khas Bali');
        $response->assertSee('Chef Ragil');
        $response->assertSee('Resep Nusantara');
        $response->assertSee('#Pedas Mantap');
        $response->assertSee('7 menit baca');
    }

    public function test_draft_post_returns_404(): void
    {
        $author = User::factory()->create();
        $post = BlogPost::create([
            'author_id' => $author->id,
            'status' => BlogPostStatus::Draft,
            'is_active' => true,
        ]);
        $post->translations()->create([
            'locale' => 'id',
            'title' => 'Draft Artikel yang Belum Siap',
            'slug' => 'draft-artikel-belum-siap',
            'content' => '<p>Masih draft</p>',
        ]);

        $response = $this->get('/id/blog/draft-artikel-belum-siap');
        $response->assertNotFound();
    }

    public function test_scheduled_post_not_yet_due_returns_404(): void
    {
        $author = User::factory()->create();
        $post = BlogPost::create([
            'author_id' => $author->id,
            'status' => BlogPostStatus::Scheduled,
            'is_active' => true,
            'published_at' => now()->addDays(2),
        ]);
        $post->translations()->create([
            'locale' => 'id',
            'title' => 'Artikel Masa Depan',
            'slug' => 'artikel-masa-depan',
            'content' => '<p>Masa depan</p>',
        ]);

        $response = $this->get('/id/blog/artikel-masa-depan');
        $response->assertNotFound();
    }

    public function test_blog_archive_and_search(): void
    {
        $author = User::factory()->create();
        $post1 = BlogPost::create([
            'author_id' => $author->id,
            'status' => BlogPostStatus::Published,
            'is_active' => true,
            'published_at' => now()->subDay(),
        ]);
        $post1->translations()->create([
            'locale' => 'id',
            'title' => 'Resep Soto Ayam Lamongan Koya Gurih',
            'slug' => 'resep-soto-ayam-lamongan',
            'content' => '<p>Soto ayam khas Lamongan dengan bubuk koya renyah.</p>',
        ]);

        $post2 = BlogPost::create([
            'author_id' => $author->id,
            'status' => BlogPostStatus::Published,
            'is_active' => true,
            'published_at' => now()->subDay(),
        ]);
        $post2->translations()->create([
            'locale' => 'id',
            'title' => 'Tips Memilih Ikan Segar di Pasar',
            'slug' => 'tips-memilih-ikan-segar',
            'content' => '<p>Perhatikan insang dan mata ikan saat berbelanja.</p>',
        ]);

        $response = $this->get('/id/blog/articles?q=Soto');
        $response->assertSuccessful();
        $response->assertSee('Resep Soto Ayam Lamongan');
        $response->assertDontSee('Tips Memilih Ikan Segar');
    }

    public function test_blog_category_filter_page(): void
    {
        $author = User::factory()->create();
        $category = BlogCategory::create(['is_active' => true]);
        $category->translations()->create([
            'locale' => 'id',
            'name' => 'Minuman Tradisional',
            'slug' => 'minuman-tradisional',
        ]);

        $post = BlogPost::create([
            'blog_category_id' => $category->id,
            'author_id' => $author->id,
            'status' => BlogPostStatus::Published,
            'is_active' => true,
            'published_at' => now()->subDay(),
        ]);
        $post->translations()->create([
            'locale' => 'id',
            'title' => 'Khasiat Wedang Uwuh untuk Imunitas Tubuh',
            'slug' => 'khasiat-wedang-uwuh',
            'content' => '<p>Rempah kayu secang, jahe, dan cengkeh khas Imogiri.</p>',
        ]);

        $response = $this->get('/id/blog/category/minuman-tradisional');
        $response->assertSuccessful();
        $response->assertSee('Minuman Tradisional');
        $response->assertSee('Khasiat Wedang Uwuh');
    }

    public function test_blog_tag_filter_page(): void
    {
        $author = User::factory()->create();
        $tag = BlogTag::create(['is_active' => true]);
        $tag->translations()->create([
            'locale' => 'id',
            'name' => 'Kuliner Malam',
            'slug' => 'kuliner-malam',
        ]);

        $post = BlogPost::create([
            'author_id' => $author->id,
            'status' => BlogPostStatus::Published,
            'is_active' => true,
            'published_at' => now()->subDay(),
        ]);
        $post->tags()->attach($tag->id);
        $post->translations()->create([
            'locale' => 'id',
            'title' => 'Rekomendasi Nasi Goreng Kambing Kebon Sirih',
            'slug' => 'rekomendasi-nasi-goreng-kambing',
            'content' => '<p>Pilihan kuliner malam legendaris Jakarta Pusat.</p>',
        ]);

        $response = $this->get('/id/blog/tag/kuliner-malam');
        $response->assertSuccessful();
        $response->assertSee('#Kuliner Malam');
        $response->assertSee('Rekomendasi Nasi Goreng Kambing Kebon Sirih');
    }

    public function test_user_can_submit_comment(): void
    {
        $author = User::factory()->create();
        $post = BlogPost::create([
            'author_id' => $author->id,
            'status' => BlogPostStatus::Published,
            'is_active' => true,
            'published_at' => now()->subDay(),
        ]);
        $post->translations()->create([
            'locale' => 'id',
            'title' => 'Mengenal Kopi Gayo Aceh',
            'slug' => 'mengenal-kopi-gayo-aceh',
            'content' => '<p>Kopi arabika dengan aroma khas dan acidity seimbang.</p>',
        ]);

        $response = $this->post('/id/blog/mengenal-kopi-gayo-aceh/comments', [
            'author_name' => 'Siti Aminah',
            'author_email' => 'siti@example.com',
            'content' => 'Artikel yang sangat informatif! Saya suka sekali rasa fruity kopi Gayo.',
        ]);

        $response->assertRedirect('/id/blog/mengenal-kopi-gayo-aceh');
        $response->assertSessionHas('comment_success');

        $this->assertDatabaseHas('blog_comments', [
            'blog_post_id' => $post->id,
            'author_name' => 'Siti Aminah',
            'author_email' => 'siti@example.com',
            'status' => BlogCommentStatus::Pending->value,
            'is_author_reply' => false,
        ]);
    }

    public function test_user_can_toggle_like(): void
    {
        $author = User::factory()->create();
        $post = BlogPost::create([
            'author_id' => $author->id,
            'status' => BlogPostStatus::Published,
            'is_active' => true,
            'published_at' => now()->subDay(),
            'likes_count' => 0,
        ]);
        $post->translations()->create([
            'locale' => 'id',
            'title' => 'Gudeg Yu Djum Legenda Yogyakarta',
            'slug' => 'gudeg-yu-djum-legenda-yogyakarta',
            'content' => '<p>Manis gurih nangka muda bersanding krecek pedas.</p>',
        ]);

        // First like
        $session = ['blog_visitor_id' => 'test-visitor-token-123'];
        $response1 = $this->withSession($session)->postJson('/id/blog/gudeg-yu-djum-legenda-yogyakarta/like');
        $response1->assertSuccessful();
        $response1->assertJson([
            'liked' => true,
            'likes_count' => 1,
        ]);
        $this->assertEquals(1, $post->fresh()->likes_count);

        // Second click toggles off
        $response2 = $this->withSession($session)->postJson('/id/blog/gudeg-yu-djum-legenda-yogyakarta/like');
        $response2->assertSuccessful();
        $response2->assertJson([
            'liked' => false,
            'likes_count' => 0,
        ]);
        $this->assertEquals(0, $post->fresh()->likes_count);
    }
}
