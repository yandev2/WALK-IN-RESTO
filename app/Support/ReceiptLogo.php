<?php

namespace App\Support;

use App\Models\PlatformSetting;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Throwable;

final class ReceiptLogo
{
    public const FALLBACK_PATH = 'images/receipt-platform-logo.png';

    /**
     * @var list<string>
     */
    private const ALLOWED_MIMES = [
        'image/jpeg',
        'image/png',
        'image/gif',
    ];

    public static function dataUri(?Restaurant $restaurant): ?string
    {
        foreach ([$restaurant?->logo_path, PlatformSetting::optional()?->logo_path] as $path) {
            $uri = self::toDataUri($path);

            if ($uri !== null) {
                return $uri;
            }
        }

        return self::fallbackDataUri();
    }

    private static function toDataUri(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (CmsMedia::isExternalUrl($path)) {
            return self::fromRemote($path);
        }

        $storage = Storage::disk('public');

        if (! $storage->exists($path)) {
            return null;
        }

        return self::encode($storage->get($path));
    }

    private static function fromRemote(string $url): ?string
    {
        try {
            $bytes = Cache::remember('receipt-logo:'.sha1($url), now()->addHours(6), function () use ($url): ?string {
                $response = Http::timeout(5)->get($url);

                if (! $response->successful() || blank($response->body())) {
                    return null;
                }

                return $response->body();
            });
        } catch (Throwable) {
            return null;
        }

        return self::encode(is_string($bytes) ? $bytes : null);
    }

    private static function fallbackDataUri(): ?string
    {
        $path = public_path(self::FALLBACK_PATH);

        if (! is_file($path)) {
            return null;
        }

        $bytes = file_get_contents($path);

        return self::encode(is_string($bytes) ? $bytes : null);
    }

    private static function encode(?string $bytes): ?string
    {
        if ($bytes === null || $bytes === '') {
            return null;
        }

        $info = @getimagesizefromstring($bytes);

        if ($info === false) {
            return null;
        }

        $mime = $info['mime'] ?? null;

        if (! in_array($mime, self::ALLOWED_MIMES, true)) {
            return null;
        }

        return 'data:'.$mime.';base64,'.base64_encode($bytes);
    }
}
