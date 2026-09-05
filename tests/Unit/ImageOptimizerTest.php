<?php

namespace Tests\Unit;

use App\Support\ImageOptimizer;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageOptimizerTest extends TestCase
{
    public function test_optimizes_oversized_image(): void
    {
        Storage::fake('public');

        // Buat gambar JPEG 2000x1500 di memory menggunakan GD
        $img = imagecreatetruecolor(2000, 1500);
        $color = imagecolorallocate($img, 100, 150, 200);
        imagefilledrectangle($img, 0, 0, 2000, 1500, $color);

        ob_start();
        imagejpeg($img, null, 95);
        $jpegData = ob_get_clean();
        imagedestroy($img);

        Storage::disk('public')->put('cms/banners/test-banner.jpg', $jpegData);

        // Jalankan optimizer dengan batas lebar 1600, tinggi 900
        $result = ImageOptimizer::optimize('cms/banners/test-banner.jpg', 1600, 900, 500000, 'public');

        $this->assertTrue($result);

        // Periksa dimensi hasil optimasi
        $fullPath = Storage::disk('public')->path('cms/banners/test-banner.jpg');
        $newInfo = getimagesize($fullPath);

        $this->assertNotEmpty($newInfo);
        $this->assertLessThanOrEqual(1600, $newInfo[0]);
        $this->assertLessThanOrEqual(900, $newInfo[1]);
    }

    public function test_skips_already_optimal_image(): void
    {
        Storage::fake('public');

        $img = imagecreatetruecolor(800, 600);
        $color = imagecolorallocate($img, 50, 50, 50);
        imagefilledrectangle($img, 0, 0, 800, 600, $color);

        ob_start();
        imagejpeg($img, null, 80);
        $jpegData = ob_get_clean();
        imagedestroy($img);

        Storage::disk('public')->put('cms/banners/optimal.jpg', $jpegData);

        $result = ImageOptimizer::optimize('cms/banners/optimal.jpg', 1600, 900, 1048576, 'public');

        $this->assertTrue($result);

        $fullPath = Storage::disk('public')->path('cms/banners/optimal.jpg');
        $info = getimagesize($fullPath);
        $this->assertSame(800, $info[0]);
        $this->assertSame(600, $info[1]);
    }
}
