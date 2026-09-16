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

class BloggerPanelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_guest_is_redirected_to_blogger_login(): void
    {
        $response = $this->get('/blogger');
        $response->assertRedirect('/blogger/login');
    }

    public function test_founder_can_access_blogger_panel(): void
    {
        $founder = User::factory()->create(['is_active' => true]);
        $founder->assignRole('founder');

        $response = $this->actingAs($founder)->get('/blogger');
        $response->assertSuccessful();
    }

    public function test_blogger_can_access_blogger_panel(): void
    {
        $blogger = User::factory()->create(['is_active' => true]);
        $blogger->assignRole('blogger');

        $response = $this->actingAs($blogger)->get('/blogger');
        $response->assertSuccessful();
    }

    public function test_unauthorized_user_cannot_access_blogger_panel(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $response = $this->actingAs($user)->get('/blogger');
        $response->assertForbidden();
    }

    public function test_bilingual_blog_category_creation_and_translation(): void
    {
        $category = BlogCategory::create([
            'sort_order' => 1,
            'is_active' => true,
        ]);

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

        $this->assertSame('Kuliner Nusantara', $category->translate('id')->name);
        $this->assertSame('Indonesian Culinary', $category->translate('en')->name);
        $this->assertSame('kuliner-nusantara', $category->translate('id')->slug);
    }

    public function test_bilingual_blog_tag_creation(): void
    {
        $tag = BlogTag::create([
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $tag->translations()->create([
            'locale' => 'id',
            'name' => 'Resep Pedas',
            'slug' => 'resep-pedas',
        ]);

        $tag->translations()->create([
            'locale' => 'en',
            'name' => 'Spicy Recipe',
            'slug' => 'spicy-recipe',
        ]);

        $this->assertSame('Resep Pedas', $tag->translate('id')->name);
        $this->assertSame('Spicy Recipe', $tag->translate('en')->name);
    }

    public function test_bilingual_blog_post_and_author_attribution(): void
    {
        $author = User::factory()->create(['name' => 'Chef Ragil', 'is_active' => true]);
        $author->assignRole('blogger');

        $category = BlogCategory::create(['sort_order' => 0, 'is_active' => true]);
        $category->translations()->create([
            'locale' => 'id',
            'name' => 'Makanan Utama',
            'slug' => 'makanan-utama',
        ]);

        $post = BlogPost::create([
            'blog_category_id' => $category->id,
            'author_id' => $author->id,
            'status' => BlogPostStatus::Published,
            'published_at' => now(),
            'reading_time_minutes' => 5,
            'is_active' => true,
        ]);

        $post->translations()->create([
            'locale' => 'id',
            'title' => 'Rahasia Rendang Daging Sapi Empuk',
            'slug' => 'rahasia-rendang-daging-sapi-empuk',
            'excerpt' => 'Tips memasak rendang tradisional Minang.',
            'content' => '<p>Gunakan santan kelapa tua murni dan api kecil.</p>',
        ]);

        $post->translations()->create([
            'locale' => 'en',
            'title' => 'Secret to Tender Beef Rendang',
            'slug' => 'secret-to-tender-beef-rendang',
            'excerpt' => 'Traditional Minang cooking tips for authentic rendang.',
            'content' => '<p>Use fresh coconut milk and slow simmering method.</p>',
        ]);

        $this->assertSame('Chef Ragil', $post->author->name);
        $this->assertSame('Rahasia Rendang Daging Sapi Empuk', $post->translate('id')->title);
        $this->assertSame('Secret to Tender Beef Rendang', $post->translate('en')->title);
        $this->assertSame(BlogPostStatus::Published, $post->status);
    }

    public function test_blog_comment_observer_syncs_comments_count(): void
    {
        $post = BlogPost::create([
            'status' => BlogPostStatus::Published,
            'published_at' => now(),
            'comments_count' => 0,
            'is_active' => true,
        ]);

        $comment = BlogComment::create([
            'blog_post_id' => $post->id,
            'author_name' => 'FoodLover',
            'content' => 'Resepnya sangat enak dan mudah diikuti!',
            'status' => BlogCommentStatus::Approved,
        ]);

        $post->refresh();
        $this->assertEquals(1, $post->comments_count);

        $comment->update(['status' => BlogCommentStatus::Rejected]);
        $post->refresh();
        $this->assertEquals(0, $post->comments_count);

        $comment->update(['status' => BlogCommentStatus::Approved]);
        $post->refresh();
        $this->assertEquals(1, $post->comments_count);

        $comment->delete();
        $post->refresh();
        $this->assertEquals(0, $post->comments_count);
    }

    public function test_scheduled_posts_transition_to_published(): void
    {
        $post = BlogPost::create([
            'status' => BlogPostStatus::Scheduled,
            'published_at' => now()->subMinute(),
            'is_active' => true,
        ]);

        $this->assertSame(BlogPostStatus::Scheduled, $post->status);

        BlogPost::publishDueScheduled();
        $post->refresh();

        $this->assertSame(BlogPostStatus::Published, $post->status);
    }

    public function test_founder_and_blogger_can_access_blogger_profile(): void
    {
        $founder = User::factory()->create(['is_active' => true]);
        $founder->assignRole('founder');

        $responseFounder = $this->actingAs($founder)->get('/blogger/profile');
        $responseFounder->assertSuccessful();

        $blogger = User::factory()->create(['is_active' => true]);
        $blogger->assignRole('blogger');

        $responseBlogger = $this->actingAs($blogger)->get('/blogger/profile');
        $responseBlogger->assertSuccessful();
    }

    public function test_founder_can_access_manage_blog_hero_page(): void
    {
        $founder = User::factory()->create(['is_active' => true]);
        $founder->assignRole('founder');

        $response = $this->actingAs($founder)->get('/blogger/blog-hero');
        $response->assertSuccessful();
    }

    public function test_manage_blog_hero_can_save_banner_without_custom_translations(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');
        $founder = User::factory()->create(['is_active' => true]);
        $founder->assignRole('founder');

        $this->actingAs($founder);

        $file = \Illuminate\Http\UploadedFile::fake()->image('hero.jpg', 1920, 1080);

        \Livewire\Livewire::test(\App\Filament\Blogger\Pages\ManageBlogHero::class)
            ->assertSuccessful()
            ->set('data.banner_image', [$file])
            ->set('data.overlay_opacity', 80)
            ->call('save')
            ->assertHasNoErrors()
            ->assertNotified('Pengaturan berhasil disimpan.');

        $hero = \App\Models\BlogHeroSetting::getSingleton();
        $this->assertNotNull($hero->banner_image);
        $this->assertSame(80, $hero->overlay_opacity);
    }

    public function test_custom_blog_hero_setting_is_rendered_on_public_landing(): void
    {
        $hero = \App\Models\BlogHeroSetting::getSingleton();
        $hero->update([
            'overlay_opacity' => 75,
            'show_quick_categories' => true,
        ]);
        $hero->translations()->create([
            'locale' => 'id',
            'badge_text' => '✨ Eksplorasi Kuliner Nusantara Terlengkap',
            'title' => 'Dunia Kuliner Spesial Cita Rasa Kita',
            'subtitle' => 'Panduan terlengkap mencicipi kuliner nusantara terbaik dari sabang sampai merauke.',
            'search_placeholder' => 'Cari resep atau resto favorit...',
        ]);

        $response = $this->get('/blog');
        $response->assertSuccessful();
        $response->assertSee('✨ Eksplorasi Kuliner Nusantara Terlengkap');
        $response->assertSee('Dunia Kuliner Spesial Cita Rasa Kita');
        $response->assertSee('Panduan terlengkap mencicipi kuliner nusantara terbaik');
        $response->assertSee('Cari resep atau resto favorit...');
    }

    public function test_bloggers_table_renders_total_articles_and_total_views_columns(): void
    {
        $founder = User::factory()->create(['is_active' => true]);
        $founder->assignRole('founder');

        $blogger = User::factory()->create([
            'name' => 'Penulis Teladan',
            'is_active' => true,
        ]);
        $blogger->assignRole('blogger');

        $category = BlogCategory::create(['is_active' => true]);
        $category->translations()->create([
            'locale' => 'id',
            'name' => 'Kategori Uji',
            'slug' => 'kategori-uji',
        ]);

        BlogPost::create([
            'author_id' => $blogger->id,
            'blog_category_id' => $category->id,
            'status' => BlogPostStatus::Published,
            'is_active' => true,
            'published_at' => now()->subDay(),
            'reading_time_minutes' => 5,
            'views_count' => 150,
            'likes_count' => 10,
        ]);

        $response = $this->actingAs($founder)->get('/blogger/bloggers');
        $response->assertSuccessful();
        $response->assertSee('Total Artikel');
        $response->assertSee('Total Views');
        $response->assertSee('Penulis Teladan');
        $response->assertSee('150');
    }

    public function test_founder_dashboard_renders_top_bloggers_widget(): void
    {
        $founder = User::factory()->create(['is_active' => true]);
        $founder->assignRole('founder');

        $response = $this->actingAs($founder)->get('/blogger');
        $response->assertSuccessful();
        $response->assertSee('Top 5 Penulis Terpopuler');
    }

    public function test_blogger_dashboard_does_not_render_top_bloggers_widget(): void
    {
        $blogger = User::factory()->create(['is_active' => true]);
        $blogger->assignRole('blogger');

        $response = $this->actingAs($blogger)->get('/blogger');
        $response->assertSuccessful();
        $response->assertDontSee('Top 5 Penulis Terpopuler');
    }

    public function test_founder_sees_panel_switcher_to_founder_in_blogger_panel(): void
    {
        $founder = User::factory()->create(['is_active' => true]);
        $founder->assignRole('founder');

        $response = $this->actingAs($founder)->get('/blogger');
        $response->assertSuccessful();
        $response->assertSee('fi-panel-switcher');
        $response->assertSee('Panel Founder');
        $response->assertSee('/founder');
    }

    public function test_founder_sees_panel_switcher_to_blogger_in_founder_panel(): void
    {
        $founder = User::factory()->create(['is_active' => true]);
        $founder->assignRole('founder');

        $response = $this->actingAs($founder)->get('/founder');
        $response->assertSuccessful();
        $response->assertSee('fi-panel-switcher');
        $response->assertSee('Panel Blog');
        $response->assertSee('/blogger');
    }

    public function test_regular_blogger_does_not_see_panel_switcher(): void
    {
        $blogger = User::factory()->create(['is_active' => true]);
        $blogger->assignRole('blogger');

        $response = $this->actingAs($blogger)->get('/blogger');
        $response->assertSuccessful();
        $response->assertDontSee('fi-panel-switcher');
        $response->assertDontSee('Panel Founder');
    }
}


