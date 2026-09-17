<?php

namespace Tests\Feature;

use App\Enums\BlogCommentStatus;
use App\Enums\BlogPostStatus;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\BlogPostTranslation;
use App\Models\User;
use App\Models\VisitLog;
use App\Services\BlogAnalyticsService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    protected User $founder;
    protected User $blogger1;
    protected User $blogger2;
    protected BlogCategory $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);

        $this->founder = User::factory()->create([
            'email' => 'founder@test.com',
            'is_active' => true,
        ]);
        $this->founder->assignRole('founder');

        $this->blogger1 = User::factory()->create([
            'name' => 'Blogger Satu',
            'email' => 'blogger1@test.com',
            'is_active' => true,
        ]);
        $this->blogger1->assignRole('blogger');

        $this->blogger2 = User::factory()->create([
            'name' => 'Blogger Dua',
            'email' => 'blogger2@test.com',
            'is_active' => true,
        ]);
        $this->blogger2->assignRole('blogger');

        $this->category = BlogCategory::create(['is_active' => true]);
        $this->category->translations()->create([
            'locale' => 'id',
            'name' => 'Kuliner Nusantara',
            'slug' => 'kuliner-nusantara',
        ]);
    }

    public function test_founder_can_access_blog_analytics_page(): void
    {
        $response = $this->actingAs($this->founder)->get('/blogger/blog-analytics');

        $response->assertOk();
        $response->assertSee('Blog Analytics & Performa');
    }

    public function test_blogger_can_access_blog_analytics_page(): void
    {
        $response = $this->actingAs($this->blogger1)->get('/blogger/blog-analytics');

        $response->assertOk();
        $response->assertSee('Blog Analytics & Performa');
    }

    public function test_unauthenticated_user_cannot_access_blog_analytics(): void
    {
        $response = $this->get('/blogger/blog-analytics');

        $response->assertRedirect('/blogger/login');
    }

    public function test_public_blog_visit_is_recorded_and_views_count_increments(): void
    {
        $post = BlogPost::create([
            'author_id' => $this->blogger1->id,
            'blog_category_id' => $this->category->id,
            'status' => BlogPostStatus::Published,
            'is_active' => true,
            'published_at' => now()->subDay(),
            'reading_time_minutes' => 5,
            'views_count' => 0,
            'likes_count' => 0,
        ]);

        $post->translations()->create([
            'locale' => 'id',
            'title' => 'Resep Rahasia Rendang',
            'slug' => 'resep-rahasia-rendang',
            'content' => 'Konten rendang mantap...',
        ]);

        $this->get('/id/blog/resep-rahasia-rendang')->assertOk();

        $this->assertDatabaseHas('visit_logs', [
            'page_key' => 'blog_post',
            'visitable_id' => $post->id,
        ]);

        $this->assertEquals(1, $post->fresh()->views_count);
    }

    public function test_visit_debounce_prevents_duplicate_logs_within_window(): void
    {
        $post = BlogPost::create([
            'author_id' => $this->blogger1->id,
            'blog_category_id' => $this->category->id,
            'status' => BlogPostStatus::Published,
            'is_active' => true,
            'published_at' => now()->subDay(),
            'reading_time_minutes' => 4,
            'views_count' => 0,
            'likes_count' => 0,
        ]);

        $post->translations()->create([
            'locale' => 'id',
            'title' => 'Resep Rahasia Rendang 2',
            'slug' => 'resep-rahasia-rendang-2',
            'content' => 'Konten rendang mantap...',
        ]);

        // First visit
        $response = $this->get('/id/blog/resep-rahasia-rendang-2');
        $response->assertOk();
        $this->assertEquals(1, VisitLog::where('visitable_id', $post->id)->count());

        // Extract session cookie to simulate the same browser session on reload
        $sessionCookieName = config('session.cookie');
        $cookie = collect($response->headers->getCookies())->first(fn ($c) => $c->getName() === $sessionCookieName);

        $reload = $cookie
            ? $this->withUnencryptedCookie($sessionCookieName, $cookie->getValue())->get('/id/blog/resep-rahasia-rendang-2')
            : $this->get('/id/blog/resep-rahasia-rendang-2');

        $reload->assertOk();
        $this->assertEquals(1, VisitLog::where('visitable_id', $post->id)->count());
        $this->assertEquals(1, $post->fresh()->views_count);
    }

    public function test_founder_analytics_includes_all_authors_posts(): void
    {
        $post1 = BlogPost::create([
            'author_id' => $this->blogger1->id,
            'blog_category_id' => $this->category->id,
            'status' => BlogPostStatus::Published,
            'is_active' => true,
            'published_at' => now()->subDays(2),
            'reading_time_minutes' => 5,
            'views_count' => 10,
            'likes_count' => 5,
        ]);
        $post1->translations()->create([
            'locale' => 'id',
            'title' => 'Post Satu',
            'slug' => 'post-satu',
            'content' => 'Konten 1',
        ]);

        $post2 = BlogPost::create([
            'author_id' => $this->blogger2->id,
            'blog_category_id' => $this->category->id,
            'status' => BlogPostStatus::Published,
            'is_active' => true,
            'published_at' => now()->subDays(3),
            'reading_time_minutes' => 6,
            'views_count' => 20,
            'likes_count' => 12,
        ]);
        $post2->translations()->create([
            'locale' => 'id',
            'title' => 'Post Dua',
            'slug' => 'post-dua',
            'content' => 'Konten 2',
        ]);

        // Create visit logs for post 1, post 2, and general landing
        VisitLog::create([
            'page_key' => 'blog_index',
            'path' => '/blog',
            'ip_address' => '127.0.0.1',
            'visitor_hash' => 'hash_landing',
            'created_at' => now()->subHours(5),
        ]);

        VisitLog::create([
            'page_key' => 'blog_post',
            'visitable_type' => BlogPost::class,
            'visitable_id' => $post1->id,
            'path' => '/blog/post-1',
            'ip_address' => '127.0.0.1',
            'visitor_hash' => 'hash_user_1',
            'created_at' => now()->subHours(4),
        ]);

        VisitLog::create([
            'page_key' => 'blog_post',
            'visitable_type' => BlogPost::class,
            'visitable_id' => $post2->id,
            'path' => '/blog/post-2',
            'ip_address' => '127.0.0.2',
            'visitor_hash' => 'hash_user_2',
            'created_at' => now()->subHours(2),
        ]);

        $analytics = app(BlogAnalyticsService::class);

        // For founder (authorId = null)
        $founderTotalViews = $analytics->totalViews(authorId: null);
        $founderProgress = $analytics->contentProgress(authorId: null);

        // Total views for founder should count all 3 logs (landing + post1 + post2)
        $this->assertEquals(3, $founderTotalViews);
        // Total published posts across all authors
        $this->assertEquals(2, $founderProgress['total_published']);
        $this->assertEquals(17, $founderProgress['total_likes']); // 5 + 12
    }

    public function test_blogger_analytics_is_strictly_scoped_to_own_authored_posts(): void
    {
        $post1 = BlogPost::create([
            'author_id' => $this->blogger1->id,
            'blog_category_id' => $this->category->id,
            'status' => BlogPostStatus::Published,
            'is_active' => true,
            'published_at' => now()->subDays(2),
            'reading_time_minutes' => 5,
            'views_count' => 10,
            'likes_count' => 5,
        ]);
        $post1->translations()->create([
            'locale' => 'id',
            'title' => 'Post Blogger Satu',
            'slug' => 'post-blogger-satu',
            'content' => 'Konten 1',
        ]);

        $post2 = BlogPost::create([
            'author_id' => $this->blogger2->id,
            'blog_category_id' => $this->category->id,
            'status' => BlogPostStatus::Published,
            'is_active' => true,
            'published_at' => now()->subDays(3),
            'reading_time_minutes' => 6,
            'views_count' => 20,
            'likes_count' => 12,
        ]);
        $post2->translations()->create([
            'locale' => 'id',
            'title' => 'Post Blogger Dua',
            'slug' => 'post-blogger-dua',
            'content' => 'Konten 2',
        ]);

        // Landing log (should not belong to blogger 1)
        VisitLog::create([
            'page_key' => 'blog_index',
            'path' => '/blog',
            'ip_address' => '127.0.0.1',
            'visitor_hash' => 'hash_landing',
            'created_at' => now()->subHours(5),
        ]);

        // Post 1 log (belongs to blogger 1)
        VisitLog::create([
            'page_key' => 'blog_post',
            'visitable_type' => BlogPost::class,
            'visitable_id' => $post1->id,
            'path' => '/blog/post-1',
            'ip_address' => '127.0.0.1',
            'visitor_hash' => 'hash_user_1',
            'created_at' => now()->subHours(4),
        ]);

        // Post 2 log (belongs to blogger 2)
        VisitLog::create([
            'page_key' => 'blog_post',
            'visitable_type' => BlogPost::class,
            'visitable_id' => $post2->id,
            'path' => '/blog/post-2',
            'ip_address' => '127.0.0.2',
            'visitor_hash' => 'hash_user_2',
            'created_at' => now()->subHours(2),
        ]);

        $analytics = app(BlogAnalyticsService::class);

        // Blogger 1 scope
        $blogger1Views = $analytics->totalViews(authorId: $this->blogger1->id);
        $blogger1Progress = $analytics->contentProgress(authorId: $this->blogger1->id);
        $blogger1TopPosts = $analytics->topPosts(10, 30, authorId: $this->blogger1->id);

        $this->assertEquals(1, $blogger1Views);
        $this->assertEquals(1, $blogger1Progress['total_published']);
        $this->assertEquals(5, $blogger1Progress['total_likes']);
        $this->assertEquals(1, $blogger1TopPosts->count());
        $this->assertEquals($post1->id, $blogger1TopPosts->first()['post_id']);

        // Blogger 2 scope
        $blogger2Views = $analytics->totalViews(authorId: $this->blogger2->id);
        $blogger2Progress = $analytics->contentProgress(authorId: $this->blogger2->id);
        $blogger2TopPosts = $analytics->topPosts(10, 30, authorId: $this->blogger2->id);

        $this->assertEquals(1, $blogger2Views);
        $this->assertEquals(1, $blogger2Progress['total_published']);
        $this->assertEquals(12, $blogger2Progress['total_likes']);
        $this->assertEquals(1, $blogger2TopPosts->count());
        $this->assertEquals($post2->id, $blogger2TopPosts->first()['post_id']);
    }

    public function test_period_filter_and_growth_rate_calculation(): void
    {
        $post = BlogPost::create([
            'author_id' => $this->blogger1->id,
            'blog_category_id' => $this->category->id,
            'status' => BlogPostStatus::Published,
            'is_active' => true,
            'published_at' => now()->subDays(2),
            'reading_time_minutes' => 5,
            'views_count' => 0,
            'likes_count' => 0,
        ]);
        $post->translations()->create([
            'locale' => 'id',
            'title' => 'Post Growth',
            'slug' => 'post-growth',
            'content' => 'Konten growth',
        ]);

        // Current period visit (yesterday)
        VisitLog::create([
            'page_key' => 'blog_post',
            'visitable_type' => BlogPost::class,
            'visitable_id' => $post->id,
            'path' => '/blog/post',
            'ip_address' => '127.0.0.1',
            'visitor_hash' => 'hash_current',
            'created_at' => now()->subDay(),
        ]);

        // Previous period visit (15 days ago)
        VisitLog::create([
            'page_key' => 'blog_post',
            'visitable_type' => BlogPost::class,
            'visitable_id' => $post->id,
            'path' => '/blog/post',
            'ip_address' => '127.0.0.2',
            'visitor_hash' => 'hash_prev',
            'created_at' => now()->subDays(15),
        ]);

        $analytics = app(BlogAnalyticsService::class);

        // 7 days period: only 1 in current, 0 in prev (7*2 = 14)
        $growth7 = $analytics->viewsGrowthRate(7, $this->blogger1->id);
        $this->assertEquals(1, $growth7['current']);
        $this->assertEquals(0, $growth7['previous']);
        $this->assertTrue($growth7['is_positive']);

        // 30 days period: 2 in current, 0 in prev
        $growth30 = $analytics->viewsGrowthRate(30, $this->blogger1->id);
        $this->assertEquals(2, $growth30['current']);
    }

    public function test_all_blog_analytics_widgets_disable_lazy_loading_and_support_lazy_load_fallback(): void
    {
        $widgets = [
            \App\Filament\Blogger\Widgets\BlogVisitStatsWidget::class,
            \App\Filament\Blogger\Widgets\BlogTrafficApexChartWidget::class,
            \App\Filament\Blogger\Widgets\BlogContentProgressWidget::class,
            \App\Filament\Blogger\Widgets\BlogCategoryDistributionWidget::class,
            \App\Filament\Blogger\Widgets\BlogAudienceWidget::class,
            \App\Filament\Blogger\Widgets\TopBlogPostsWidget::class,
            \App\Filament\Blogger\Widgets\BlogReferrersWidget::class,
            \App\Filament\Blogger\Widgets\TopBloggersWidget::class,
        ];

        $this->actingAs($this->founder);
        \Filament\Facades\Filament::setCurrentPanel('blogger');

        foreach ($widgets as $widgetClass) {
            $this->assertFalse($widgetClass::isLazy(), "Widget {$widgetClass} must have isLazy = false to prevent lazy load method errors.");

            \Livewire\Livewire::test($widgetClass)
                ->call('__lazyLoad')
                ->assertSuccessful();
        }
    }
}
