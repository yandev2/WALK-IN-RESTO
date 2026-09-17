<?php

namespace Tests\Feature;

use App\Filament\Blogger\Resources\BlogCategories\Pages\CreateBlogCategory;
use App\Filament\Blogger\Resources\BlogCategories\Pages\ListBlogCategories;
use App\Filament\Blogger\Resources\BlogComments\Pages\ListBlogComments;
use App\Filament\Blogger\Resources\Bloggers\Pages\CreateBlogger;
use App\Filament\Blogger\Resources\Bloggers\Pages\EditBlogger;
use App\Filament\Blogger\Resources\Bloggers\Pages\ListBloggers;
use App\Filament\Blogger\Resources\Bloggers\Pages\ViewBlogger;
use App\Filament\Blogger\Resources\BlogPosts\Pages\CreateBlogPost;
use App\Filament\Blogger\Resources\BlogPosts\Pages\ListBlogPosts;
use App\Filament\Blogger\Resources\BlogTags\Pages\CreateBlogTag;
use App\Filament\Blogger\Resources\BlogTags\Pages\ListBlogTags;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BloggerResourceLivewireTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        \Filament\Facades\Filament::setCurrentPanel('blogger');
    }

    public function test_founder_can_render_all_blog_pages(): void
    {
        $founder = User::factory()->create(['is_active' => true]);
        $founder->assignRole('founder');
        $this->actingAs($founder);

        Livewire::test(ListBlogPosts::class)->assertSuccessful();
        Livewire::test(CreateBlogPost::class)->assertSuccessful();
        Livewire::test(ListBlogCategories::class)->assertSuccessful();
        Livewire::test(CreateBlogCategory::class)->assertSuccessful();
        Livewire::test(ListBlogTags::class)->assertSuccessful();
        Livewire::test(CreateBlogTag::class)->assertSuccessful();
        Livewire::test(ListBlogComments::class)->assertSuccessful();
        Livewire::test(ListBloggers::class)->assertSuccessful();
    }

    public function test_blogger_can_render_blog_writing_pages_but_blogger_resource_is_hidden(): void
    {
        $blogger = User::factory()->create(['is_active' => true]);
        $blogger->assignRole('blogger');
        $this->actingAs($blogger);

        Livewire::test(ListBlogPosts::class)->assertSuccessful();
        Livewire::test(CreateBlogPost::class)->assertSuccessful();
        Livewire::test(ListBlogCategories::class)->assertSuccessful();
        Livewire::test(ListBlogTags::class)->assertSuccessful();
        Livewire::test(ListBlogComments::class)->assertSuccessful();

        $response = $this->get('/blogger/bloggers');
        $response->assertForbidden();
    }

    public function test_blogger_can_create_blog_post_with_bilingual_data(): void
    {
        $blogger = User::factory()->create(['is_active' => true]);
        $blogger->assignRole('blogger');
        $this->actingAs($blogger);

        Livewire::test(CreateBlogPost::class)
            ->set('data.id.title', 'Nasi Goreng Spesial')
            ->set('data.id.slug', 'nasi-goreng-spesial')
            ->set('data.id.content', '<p>Resep nasi goreng khas Indonesia.</p>')
            ->set('data.en.title', 'Special Fried Rice')
            ->set('data.en.slug', 'special-fried-rice')
            ->set('data.en.content', '<p>Special Indonesian fried rice recipe.</p>')
            ->set('data.status', 'draft')
            ->call('create')
            ->assertHasNoFormErrors();

        $post = \App\Models\BlogPost::first();
        $this->assertNotNull($post);
        $this->assertEquals($blogger->id, $post->author_id);
        $this->assertEquals('Nasi Goreng Spesial', $post->translate('id')->title);
        $this->assertEquals('Special Fried Rice', $post->translate('en')->title);
    }

    public function test_founder_can_create_new_blogger_user(): void
    {
        $founder = User::factory()->create(['is_active' => true]);
        $founder->assignRole('founder');
        $this->actingAs($founder);

        Livewire::test(\App\Filament\Blogger\Resources\Bloggers\Pages\CreateBlogger::class)
            ->set('data.name', 'Chef Arnold')
            ->set('data.username', 'chefarnold')
            ->set('data.email', 'arnold@citaraskita.com')
            ->set('data.password', 'password123')
            ->set('data.is_active', true)
            ->call('create')
            ->assertHasNoFormErrors();

        $newBlogger = User::where('email', 'arnold@citaraskita.com')->first();
        $this->assertNotNull($newBlogger);
        $this->assertTrue($newBlogger->hasRole('blogger'));
        $this->assertTrue($newBlogger->isBlogger());
    }

    public function test_founder_can_render_edit_and_view_blogger_pages(): void
    {
        $founder = User::factory()->create(['is_active' => true]);
        $founder->assignRole('founder');
        $this->actingAs($founder);

        $blogger = User::factory()->create(['is_active' => true]);
        $blogger->assignRole('blogger');

        Livewire::test(EditBlogger::class, ['record' => $blogger->getKey()])
            ->assertSuccessful()
            ->assertFormSet([
                'name' => $blogger->name,
                'email' => $blogger->email,
            ]);

        Livewire::test(ViewBlogger::class, ['record' => $blogger->getKey()])
            ->assertSuccessful();
    }

    public function test_founder_can_create_new_blogger_user_when_permissions_team_id_is_null(): void
    {
        $founder = User::factory()->create(['is_active' => true]);
        $founder->assignGlobalRole('founder');
        $this->actingAs($founder);

        // Explicitly set permissionsTeamId to null to simulate fresh HTTP request lifecycle
        app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId(null);

        Livewire::test(\App\Filament\Blogger\Resources\Bloggers\Pages\CreateBlogger::class)
            ->set('data.name', 'Chef Renatta')
            ->set('data.username', 'chefrenatta')
            ->set('data.email', 'renatta@citaraskita.com')
            ->set('data.password', 'password123')
            ->set('data.is_active', true)
            ->call('create')
            ->assertHasNoFormErrors();

        $newBlogger = User::where('email', 'renatta@citaraskita.com')->first();
        $this->assertNotNull($newBlogger);
        $this->assertTrue($newBlogger->isBlogger());
    }

    public function test_founder_can_create_blogger_even_if_blogger_role_missing_initially(): void
    {
        $founder = User::factory()->create(['is_active' => true]);
        $founder->assignGlobalRole('founder');
        $this->actingAs($founder);

        // Delete role blogger if exists to simulate unseeded environment
        \App\Models\Role::where('name', 'blogger')->delete();
        app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId(null);

        Livewire::test(\App\Filament\Blogger\Resources\Bloggers\Pages\CreateBlogger::class)
            ->set('data.name', 'Chef Juna')
            ->set('data.username', 'chefjuna')
            ->set('data.email', 'juna@citaraskita.com')
            ->set('data.password', 'password123')
            ->set('data.is_active', true)
            ->call('create')
            ->assertHasNoFormErrors();

        $newBlogger = User::where('email', 'juna@citaraskita.com')->first();
        $this->assertNotNull($newBlogger);
        $this->assertTrue($newBlogger->isBlogger());
    }

    public function test_blogger_can_only_see_their_own_articles_in_list_blog_posts(): void
    {
        $bloggerA = User::factory()->create(['is_active' => true]);
        $bloggerA->assignGlobalRole('blogger');

        $bloggerB = User::factory()->create(['is_active' => true]);
        $bloggerB->assignGlobalRole('blogger');

        $category = BlogCategory::create(['is_active' => true]);
        $category->translations()->create(['locale' => 'id', 'name' => 'Kategori A', 'slug' => 'kategori-a']);

        $postA = BlogPost::create([
            'author_id' => $bloggerA->id,
            'blog_category_id' => $category->id,
            'is_active' => true,
        ]);
        $postA->translations()->create(['locale' => 'id', 'title' => 'Artikel Blogger A', 'slug' => 'artikel-a', 'content' => '<p>Konten A</p>']);

        $postB = BlogPost::create([
            'author_id' => $bloggerB->id,
            'blog_category_id' => $category->id,
            'is_active' => true,
        ]);
        $postB->translations()->create(['locale' => 'id', 'title' => 'Artikel Blogger B', 'slug' => 'artikel-b', 'content' => '<p>Konten B</p>']);

        // Blogger A testing
        $this->actingAs($bloggerA);

        Livewire::test(ListBlogPosts::class)
            ->assertCanSeeTableRecords([$postA])
            ->assertCanNotSeeTableRecords([$postB]);

        // Blogger A cannot edit Post B via direct route
        $response = $this->get("/blogger/blog-posts/{$postB->id}/edit");
        $this->assertTrue(in_array($response->status(), [403, 404], true));

        // Blogger A can edit Post A
        $response = $this->get("/blogger/blog-posts/{$postA->id}/edit");
        $response->assertSuccessful();
    }

    public function test_blogger_can_only_see_comments_on_their_own_posts(): void
    {
        $bloggerA = User::factory()->create(['is_active' => true]);
        $bloggerA->assignGlobalRole('blogger');

        $bloggerB = User::factory()->create(['is_active' => true]);
        $bloggerB->assignGlobalRole('blogger');

        $category = BlogCategory::create(['is_active' => true]);
        $category->translations()->create(['locale' => 'id', 'name' => 'Kategori B', 'slug' => 'kategori-b']);

        $postA = BlogPost::create([
            'author_id' => $bloggerA->id,
            'blog_category_id' => $category->id,
            'is_active' => true,
        ]);
        $postA->translations()->create(['locale' => 'id', 'title' => 'Artikel Milik A', 'slug' => 'artikel-milik-a', 'content' => '<p>Konten A</p>']);

        $postB = BlogPost::create([
            'author_id' => $bloggerB->id,
            'blog_category_id' => $category->id,
            'is_active' => true,
        ]);
        $postB->translations()->create(['locale' => 'id', 'title' => 'Artikel Milik B', 'slug' => 'artikel-milik-b', 'content' => '<p>Konten B</p>']);

        $commentA = BlogComment::create([
            'blog_post_id' => $postA->id,
            'author_name' => 'User One',
            'content' => 'Komentar untuk artikel A',
            'status' => \App\Enums\BlogCommentStatus::Pending,
        ]);

        $commentB = BlogComment::create([
            'blog_post_id' => $postB->id,
            'author_name' => 'User Two',
            'content' => 'Komentar untuk artikel B',
            'status' => \App\Enums\BlogCommentStatus::Pending,
        ]);

        $this->actingAs($bloggerA);

        Livewire::test(ListBlogComments::class)
            ->assertCanSeeTableRecords([$commentA])
            ->assertCanNotSeeTableRecords([$commentB]);

        // Direct edit attempt on comment B
        $response = $this->get("/blogger/blog-comments/{$commentB->id}/edit");
        $this->assertTrue(in_array($response->status(), [403, 404], true));

        // Direct edit attempt on comment A succeeds
        $response = $this->get("/blogger/blog-comments/{$commentA->id}/edit");
        $response->assertSuccessful();
    }

    public function test_blogger_cannot_access_manage_blog_hero(): void
    {
        $blogger = User::factory()->create(['is_active' => true]);
        $blogger->assignGlobalRole('blogger');

        $response = $this->actingAs($blogger)->get('/blogger/blog-hero');
        $response->assertForbidden();

        $founder = User::factory()->create(['is_active' => true]);
        $founder->assignGlobalRole('founder');

        $responseFounder = $this->actingAs($founder)->get('/blogger/blog-hero');
        $responseFounder->assertSuccessful();
    }

    public function test_blogger_cannot_edit_or_delete_categories_or_tags(): void
    {
        $blogger = User::factory()->create(['is_active' => true]);
        $blogger->assignGlobalRole('blogger');

        $category = BlogCategory::create(['is_active' => true]);
        $category->translations()->create(['locale' => 'id', 'name' => 'Kategori Umum', 'slug' => 'kategori-umum']);

        $tag = BlogTag::create(['is_active' => true]);
        $tag->translations()->create(['locale' => 'id', 'name' => 'Tag Umum', 'slug' => 'tag-umum']);

        $this->actingAs($blogger);

        // View is permitted
        $this->get("/blogger/blog-categories/{$category->id}")->assertSuccessful();
        $this->get("/blogger/blog-tags/{$tag->id}")->assertSuccessful();

        // Edit route is forbidden
        $this->get("/blogger/blog-categories/{$category->id}/edit")->assertForbidden();
        $this->get("/blogger/blog-tags/{$tag->id}/edit")->assertForbidden();

        // Table action buttons visibility
        Livewire::test(ListBlogCategories::class)
            ->assertTableActionHidden('edit', $category);

        Livewire::test(ListBlogTags::class)
            ->assertTableActionHidden('edit', $tag);
    }

    public function test_founder_has_full_access_to_all_authors_posts_and_comments(): void
    {
        $founder = User::factory()->create(['is_active' => true]);
        $founder->assignGlobalRole('founder');

        $blogger = User::factory()->create(['is_active' => true]);
        $blogger->assignGlobalRole('blogger');

        $category = BlogCategory::create(['is_active' => true]);
        $category->translations()->create(['locale' => 'id', 'name' => 'Kategori Founder', 'slug' => 'kategori-founder']);

        $post = BlogPost::create([
            'author_id' => $blogger->id,
            'blog_category_id' => $category->id,
            'is_active' => true,
        ]);
        $post->translations()->create(['locale' => 'id', 'title' => 'Artikel Penulis Luar', 'slug' => 'artikel-luar', 'content' => '<p>Konten Luar</p>']);

        $comment = BlogComment::create([
            'blog_post_id' => $post->id,
            'author_name' => 'Tamu',
            'content' => 'Komentar publik',
            'status' => \App\Enums\BlogCommentStatus::Approved,
        ]);

        $this->actingAs($founder);

        Livewire::test(ListBlogPosts::class)
            ->assertCanSeeTableRecords([$post])
            ->assertTableActionVisible('edit', $post);

        Livewire::test(ListBlogComments::class)
            ->assertCanSeeTableRecords([$comment])
            ->assertTableActionVisible('edit', $comment);

        $this->get("/blogger/blog-posts/{$post->id}/edit")->assertSuccessful();
        $this->get("/blogger/blog-comments/{$comment->id}/edit")->assertSuccessful();
        $this->get("/blogger/blog-categories/{$category->id}/edit")->assertSuccessful();
    }
}
