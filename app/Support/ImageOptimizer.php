<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Throwable;

class ImageOptimizer
{
    /**
     * Pastikan gambar yang disimpan tidak melebihi dimensi target dan ukuran target byte (default 1 MB).
     * Menggunakan PHP GD bawaan tanpa ketergantungan library eksternal.
     *
     * @param string $path Path relatif di disk public
     * @param int $maxWidth Lebar maksimal dalam pixel
     * @param int $maxHeight Tinggi maksimal dalam pixel
     * @param int $maxBytes Ukuran maksimal berkas dalam bytes (default 1048576 = 1 MB)
     * @param string $disk Nama disk storage (default 'public')
     * @return bool True jika berhasil dioptimasi atau sudah sesuai
     */
    public static function optimize(
        string $path,
        int $maxWidth = 1600,
        int $maxHeight = 900,
        int $maxBytes = 1048576,
        string $disk = 'public'
    ): bool {
        if (! extension_loaded('gd')) {
            return false;
        }

        $storage = Storage::disk($disk);

        if (! $storage->exists($path)) {
            return false;
        }

        $fullPath = $storage->path($path);

        if (! file_exists($fullPath) || ! is_readable($fullPath)) {
            return false;
        }

        try {
            $imageInfo = @getimagesize($fullPath);
            if (! $imageInfo) {
                return false;
            }

            [$origWidth, $origHeight, $imageType] = $imageInfo;
            $fileSize = filesize($fullPath);

            // Jika dimensi dan ukuran sudah di bawah batas, tidak perlu diproses ulang
            if ($origWidth <= $maxWidth && $origHeight <= $maxHeight && $fileSize <= $maxBytes) {
                return true;
            }

            // Hitung skala aspect ratio baru
            $ratio = min($maxWidth / max(1, $origWidth), $maxHeight / max(1, $origHeight), 1.0);
            $newWidth = (int) round($origWidth * $ratio);
            $newHeight = (int) round($origHeight * $ratio);

            $sourceImage = match ($imageType) {
                IMAGETYPE_JPEG => @imagecreatefromjpeg($fullPath),
                IMAGETYPE_PNG => @imagecreatefrompng($fullPath),
                IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($fullPath) : null,
                default => null,
            };

            if (! $sourceImage) {
                return false;
            }

            $targetImage = imagecreatetruecolor($newWidth, $newHeight);

            // Pertahankan transparansi PNG / WEBP
            if (in_array($imageType, [IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
                imagealphablending($targetImage, false);
                imagesavealpha($targetImage, true);
                $transparent = imagecolorallocatealpha($targetImage, 255, 255, 255, 127);
                imagefilledrectangle($targetImage, 0, 0, $newWidth, $newHeight, $transparent);
            }

            imagecopyresampled(
                $targetImage,
                $sourceImage,
                0, 0, 0, 0,
                $newWidth,
                $newHeight,
                $origWidth,
                $origHeight
            );

            // Simpan kembali dengan kompresi optimal
            match ($imageType) {
                IMAGETYPE_JPEG => imagejpeg($targetImage, $fullPath, 82),
                IMAGETYPE_PNG => imagepng($targetImage, $fullPath, 6),
                IMAGETYPE_WEBP => function_exists('imagewebp') ? imagewebp($targetImage, $fullPath, 82) : null,
                default => null,
            };

            imagedestroy($sourceImage);
            imagedestroy($targetImage);

            clearstatcache(true, $fullPath);

            return true;
        } catch (Throwable) {
            return false;
        }
    }
}
