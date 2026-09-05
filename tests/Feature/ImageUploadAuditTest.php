<?php

namespace Tests\Feature;

use App\Support\ImageOptimizer;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageUploadAuditTest extends TestCase
{
    /**
     * Daftar seluruh file yang memuat definisi FileUpload di Filament
     */
    private array $filesWithFileUpload = [
        'app/Filament/Profile/ProfileInformationForm.php' => [
            'field' => 'avatar_path',
            'expected_ratio' => '1:1',
            'expected_width' => '500',
            'expected_height' => '500',
            'crop' => true,
        ],
        'app/Filament/Resources/Users/UserResource.php' => [
            'field' => 'avatar_path',
            'expected_ratio' => '1:1',
            'expected_width' => '500',
            'expected_height' => '500',
            'crop' => true,
        ],
        'app/Filament/Resources/Restaurants/RestaurantResource.php' => [
            'field' => 'logo_path',
            'expected_ratio' => '1:1',
            'expected_width' => '800',
            'expected_height' => '800',
            'crop' => true,
        ],
        'app/Filament/Resources/Outlets/OutletResource.php' => [
            'field' => 'qris_image_path',
            'expected_ratio' => '1:1',
            'expected_width' => '1000',
            'expected_height' => '1000',
            'crop' => true,
        ],
        'app/Filament/Resources/MenuItems/MenuItemResource.php' => [
            'field' => 'photo_path',
            'expected_ratio' => '4:3',
            'expected_width' => '1200',
            'expected_height' => '900',
            'crop' => true,
            'is_repeater' => true,
        ],
        'app/Filament/Resources/CmsGalleryImages/CmsGalleryImageResource.php' => [
            'field' => 'image_path',
            'expected_ratio' => '4:3',
            'expected_width' => '1200',
            'expected_height' => '900',
            'crop' => true,
        ],
        'app/Filament/Resources/CmsBanners/CmsBannerResource.php' => [
            'field' => 'image_path',
            'expected_ratio' => '16:9',
            'expected_width' => '1600',
            'expected_height' => '900',
            'crop' => true,
        ],
        'app/Filament/Pages/ManageCmsProfile.php' => [
            'multiple' => [
                'logo_path' => ['ratio' => '1:1', 'width' => '800', 'height' => '800', 'crop' => true],
                'hero_image_path' => ['ratio' => '16:9', 'width' => '1600', 'height' => '900', 'crop' => true],
                'how_to_image_path' => ['ratio' => '1:1', 'width' => '800', 'height' => '800', 'crop' => true],
                'about_image_path' => ['ratio' => '4:3', 'width' => '1200', 'height' => '900', 'crop' => true],
            ],
        ],
        'app/Filament/Pages/SubscriptionStatus.php' => [
            'field' => 'payment_proof_path',
            'expected_width' => '1600',
            'expected_height' => '1600',
            'mode' => 'contain',
            'crop' => false,
        ],
        'app/Filament/Founder/Pages/ManageBillingAccount.php' => [
            'field' => 'qr_image_path',
            'expected_ratio' => '1:1',
            'expected_width' => '1000',
            'expected_height' => '1000',
            'crop' => true,
        ],
        'app/Filament/Founder/Pages/ManageHomeLanding.php' => [
            'multiple' => [
                'logo_path' => ['ratio' => '1:1', 'width' => '800', 'height' => '800', 'crop' => true],
                'favicon_path' => ['ratio' => '1:1', 'width' => '512', 'height' => '512', 'crop' => true],
                'auth_background_path' => ['ratio' => '16:9', 'width' => '1600', 'height' => '900', 'crop' => true],
                'hero_image_path' => ['ratio' => '16:9', 'width' => '1600', 'height' => '900', 'crop' => true],
                'og_image_path' => ['ratio' => '1.91:1', 'width' => '1200', 'height' => '630', 'crop' => true],
            ],
        ],
        'app/Filament/Founder/Resources/LandingTemplates/LandingTemplateResource.php' => [
            'field' => 'thumbnail_path',
            'expected_ratio' => '16:10',
            'expected_width' => '1280',
            'expected_height' => '800',
            'crop' => true,
        ],
    ];

    /**
     * Audit bahwa setiap FileUpload memiliki batas 15MB, auto-resize, upscale false, dan helperText
     */
    public function test_all_fileupload_instances_are_properly_configured(): void
    {
        $totalInstancesFound = 0;

        foreach ($this->filesWithFileUpload as $filePath => $config) {
            $this->assertFileExists(base_path($filePath), "Berkas {$filePath} harus ada");

            $content = File::get(base_path($filePath));

            // Periksa kemunculan FileUpload::make
            $matchCount = preg_match_all("/FileUpload::make\(['\"]([^'\"]+)['\"]\)/", $content, $matches);
            $this->assertGreaterThan(0, $matchCount, "Berkas {$filePath} harus memuat FileUpload::make");

            $totalInstancesFound += $matchCount;

            // Pastikan setiap file memuat konfigurasi standar
            $this->assertStringContainsString('maxSize(15360)', $content, "Berkas {$filePath} harus memuat maxSize(15360)");
            $this->assertStringContainsString('automaticallyResizeImagesToWidth', $content, "Berkas {$filePath} harus memuat automaticallyResizeImagesToWidth");
            $this->assertStringContainsString('automaticallyResizeImagesToHeight', $content, "Berkas {$filePath} harus memuat automaticallyResizeImagesToHeight");
            $this->assertStringContainsString('automaticallyResizeImagesMode', $content, "Berkas {$filePath} harus memuat automaticallyResizeImagesMode");
            $this->assertStringContainsString('automaticallyUpscaleImagesWhenResizing(false)', $content, "Berkas {$filePath} harus memuat automaticallyUpscaleImagesWhenResizing(false)");
            $this->assertStringContainsString('helperText', $content, "Berkas {$filePath} harus memuat helperText");

            // Jika bukan bukti pembayaran, pastikan auto-crop dan imageAspectRatio ada
            if ($config['crop'] ?? true) {
                $this->assertStringContainsString('automaticallyCropImagesToAspectRatio()', $content, "Berkas {$filePath} harus memuat automaticallyCropImagesToAspectRatio()");
                $this->assertStringContainsString('imageAspectRatio', $content, "Berkas {$filePath} harus memuat imageAspectRatio");
            }
        }

        // Tepat 20 instansi FileUpload di seluruh sistem
        $this->assertSame(20, $totalInstancesFound, "Harus ada tepat 20 instansi FileUpload di aplikasi");
    }

    /**
     * Audit konfigurasi global di AppServiceProvider
     */
    public function test_app_service_provider_configures_indonesian_validation_messages(): void
    {
        $content = File::get(base_path('app/Providers/AppServiceProvider.php'));

        $this->assertStringContainsString('FileUpload::configureUsing', $content);
        $this->assertStringContainsString('Ukuran berkas terlalu besar', $content);
        $this->assertStringContainsString('Format berkas tidak didukung', $content);
        $this->assertStringContainsString('ImageOptimizer::optimize', $content);
    }

    /**
     * Audit bahwa kamus validasi bahasa Indonesia ada dan lengkap
     */
    public function test_indonesian_validation_dictionary_exists(): void
    {
        $this->assertFileExists(base_path('lang/id/validation.php'));

        $messages = require base_path('lang/id/validation.php');

        $this->assertIsArray($messages);
        $this->assertArrayHasKey('uploaded', $messages);
        $this->assertArrayHasKey('image', $messages);
        $this->assertArrayHasKey('dimensions', $messages);
        $this->assertArrayHasKey('max', $messages);
        $this->assertArrayHasKey('attributes', $messages);
        $this->assertArrayHasKey('photo_path', $messages['attributes']);
        $this->assertArrayHasKey('logo_path', $messages['attributes']);
    }

    /**
     * Audit ImageOptimizer bekerja untuk berbagai format gambar
     */
    public function test_image_optimizer_handles_png_transparency(): void
    {
        Storage::fake('public');

        // Buat PNG transparan 1800x1800
        $img = imagecreatetruecolor(1800, 1800);
        imagealphablending($img, false);
        imagesavealpha($img, true);
        $transparent = imagecolorallocatealpha($img, 255, 255, 255, 127);
        imagefilledrectangle($img, 0, 0, 1800, 1800, $transparent);

        // Tambah lingkaran berwarna di tengah
        $red = imagecolorallocatealpha($img, 255, 0, 0, 0);
        imagefilledellipse($img, 900, 900, 800, 800, $red);

        ob_start();
        imagepng($img);
        $pngData = ob_get_clean();
        imagedestroy($img);

        Storage::disk('public')->put('restaurants/logos/test-logo.png', $pngData);

        $result = ImageOptimizer::optimize('restaurants/logos/test-logo.png', 800, 800, 1048576, 'public');

        $this->assertTrue($result);

        $fullPath = Storage::disk('public')->path('restaurants/logos/test-logo.png');
        $info = getimagesize($fullPath);
        $this->assertLessThanOrEqual(800, $info[0]);
        $this->assertLessThanOrEqual(800, $info[1]);
        $this->assertSame(IMAGETYPE_PNG, $info[2]);
    }
}
