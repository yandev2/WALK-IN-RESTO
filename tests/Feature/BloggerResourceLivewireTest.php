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
}
