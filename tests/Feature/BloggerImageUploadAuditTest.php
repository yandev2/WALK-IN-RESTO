<?php

namespace Tests\Feature;

use App\Filament\Blogger\Resources\BlogPosts\Pages\CreateBlogPost;
use App\Filament\Blogger\Resources\BlogPosts\Schemas\BlogPostForm;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use Tests\TestCase;

class BloggerImageUploadAuditTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        Storage::fake('public');
        Storage::fake('local');
        config()->set('filesystems.disks.tmp-for-tests', [
            'driver' => 'local',
            'root' => storage_path('framework/testing/disks/tmp-for-tests'),
        ]);
        config()->set('livewire.temporary_file_upload.disk', 'tmp-for-tests');
        \Filament\Facades\Filament::setCurrentPanel(\Filament\Facades\Filament::getPanel('blogger'));
    }

    public function test_livewire_temporary_file_upload_accepts_image_over_2mb_and_up_to_15mb(): void
    {
        $blogger = User::factory()->create(['is_active' => true]);
        $blogger->assignRole('blogger');

        $signedUrl = URL::temporarySignedRoute('livewire.upload-file', now()->addMinutes(5));

        // Simulate 2.5 MB image upload (which previously failed on default 2MB PHP limit)
        $file = UploadedFile::fake()->image('chatgpt_image.png', 1200, 675)->size(2500);

        $response = $this->actingAs($blogger)
            ->post($signedUrl, [
                'files' => [$file],
            ], [
                'Accept' => 'application/json',
            ]);

        $response->assertSuccessful();
        $this->assertArrayHasKey('paths', $response->json());
        $this->assertNotEmpty($response->json('paths'));
    }

    public function test_handles_translatable_form_humanizes_upload_errors_for_featured_image(): void
    {
        $component = new CreateBlogPost();

        $uuid = '26eab4dd-8ac0-44b8-a03a-d32d3d85d213';
        $fieldName = "data.featured_image.{$uuid}";

        try {
            $component->_uploadErrored(
                $fieldName,
                json_encode(['errors' => ['files.0' => ['Ukuran berkas terlalu besar.']]]),
                false
            );
            $this->fail('Expected ValidationException was not thrown.');
        } catch (ValidationException $e) {
            $errors = $e->errors();
            $this->assertArrayHasKey($fieldName, $errors);
            $message = $errors[$fieldName][0];
            $this->assertStringNotContainsString('data.featured_image', $message);
            $this->assertStringNotContainsString($uuid, $message);
            $this->assertStringContainsString('gambar utama artikel', $message);
            $this->assertStringContainsString('15 MB', $message);
        }
    }

    public function test_handles_translatable_form_humanizes_upload_errors_for_og_image(): void
    {
        $component = new CreateBlogPost();

        $uuid = '84ac1234-9bc0-44a1-b02c-e45f6a78b901';
        $fieldName = "data.og_image.{$uuid}";

        try {
            $component->_uploadErrored($fieldName, null, false);
            $this->fail('Expected ValidationException was not thrown.');
        } catch (ValidationException $e) {
            $errors = $e->errors();
            $this->assertArrayHasKey($fieldName, $errors);
            $message = $errors[$fieldName][0];
            $this->assertStringNotContainsString('data.og_image', $message);
            $this->assertStringNotContainsString($uuid, $message);
            $this->assertStringContainsString('gambar open graph', $message);
            $this->assertStringContainsString('15 MB', $message);
        }
    }

    public function test_blog_post_form_media_components_are_standardized(): void
    {
        $blogger = User::factory()->create(['is_active' => true]);
        $blogger->assignRole('blogger');

        $testable = Livewire::actingAs($blogger)->test(CreateBlogPost::class);

        $form = $testable->instance()->form;
        $featuredImage = $form->getComponent('featured_image') ?? ($form->getFlatFields()['featured_image'] ?? null);
        $ogImage = $form->getComponent('og_image') ?? ($form->getFlatFields()['og_image'] ?? null);

        $this->assertNotNull($featuredImage, 'featured_image component should exist');
        $this->assertNotNull($ogImage, 'og_image component should exist');

        // Max size must be 15360 KB (15 MB)
        $this->assertEquals(15360, $featuredImage->getMaxSize());
        $this->assertEquals(15360, $ogImage->getMaxSize());

        // Aspect ratios must be valid
        $this->assertEquals('16:9', $featuredImage->getImageAspectRatio());
        $this->assertEquals('1.91:1', $ogImage->getImageAspectRatio());
    }
}
