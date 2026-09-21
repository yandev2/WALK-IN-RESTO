<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

final class CmsMedia
{
    public static function url(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }

    public static function safeUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $url = Storage::disk('public')->url($path);
        $pathOnly = parse_url($url, PHP_URL_PATH);

        return filled($pathOnly) ? $pathOnly : $url;
    }

    public static function extractMapEmbedUrl(?string $input): ?string
    {
        if (blank($input)) {
            return null;
        }

        $trimmed = trim($input);

        // If user pasted an entire <iframe> HTML snippet from Google Maps
        if (preg_match('/<iframe[^>]+src=["\']([^"\']+)["\']/i', $trimmed, $matches)) {
            return $matches[1];
        }

        return $trimmed;
    }

    public static function mapsEmbedUrl(?string $embedUrl, mixed $latitude, mixed $longitude): ?string
    {
        $cleaned = self::extractMapEmbedUrl($embedUrl);

        if (filled($cleaned) && self::isAllowedMapEmbed($cleaned)) {
            return $cleaned;
        }

        if (filled($latitude) && filled($longitude)) {
            return 'https://maps.google.com/maps?q='.urlencode($latitude.','.$longitude).'&z=16&output=embed';
        }

        return null;
    }

    public static function mapsSearchUrl(?string $address, mixed $latitude, mixed $longitude): ?string
    {
        if (filled($latitude) && filled($longitude)) {
            return 'https://www.google.com/maps/search/?api=1&query='.urlencode($latitude.','.$longitude);
        }

        if (filled($address)) {
            return 'https://www.google.com/maps/search/?api=1&query='.urlencode($address);
        }

        return null;
    }

    public static function whatsappUrl(?string $phone): ?string
    {
        if (blank($phone)) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        }

        return 'https://wa.me/'.$digits;
    }

    public static function instagramUrl(?string $handleOrUrl): ?string
    {
        if (blank($handleOrUrl)) {
            return null;
        }

        $value = trim($handleOrUrl);

        if (self::isExternalUrl($value)) {
            return $value;
        }

        $handle = ltrim($value, '@/');

        if ($handle === '') {
            return null;
        }

        return 'https://www.instagram.com/'.$handle;
    }

    public static function formatIdr(int|string|null $amount): string
    {
        return 'Rp '.number_format((int) $amount, 0, ',', '.');
    }

    public static function formatShortIdr(int|string|null $amount): string
    {
        $val = (int) $amount;

        if ($val >= 1_000_000_000) {
            $m = $val / 1_000_000_000;
            if ($m == (int) $m) {
                return 'Rp '.((int) $m).' M';
            }
            $formatted = number_format($m, 2, ',', '.');
            $parts = explode(',', $formatted);
            $dec = rtrim($parts[1] ?? '', '0');

            return 'Rp '.$parts[0].($dec !== '' ? ','.$dec : '').' M';
        }

        if ($val >= 1_000_000) {
            $jt = $val / 1_000_000;
            if ($jt == (int) $jt) {
                return 'Rp '.((int) $jt).' jt';
            }
            $formatted = number_format($jt, 2, ',', '.');
            $parts = explode(',', $formatted);
            $dec = rtrim($parts[1] ?? '', '0');

            return 'Rp '.$parts[0].($dec !== '' ? ','.$dec : '').' jt';
        }

        return self::formatIdr($val);
    }

    public static function delete(?string $path, string $disk = 'public'): void
    {
        if (blank($path) || self::isExternalUrl($path)) {
            return;
        }

        Storage::disk($disk)->delete($path);
    }

    public static function copy(?string $from, string $to, string $disk = 'public'): ?string
    {
        if (blank($from) || self::isExternalUrl($from)) {
            return null;
        }

        $storage = Storage::disk($disk);

        if (! $storage->exists($from)) {
            return null;
        }

        $storage->copy($from, $to);

        return $to;
    }

    public static function isExternalUrl(?string $path): bool
    {
        return filled($path)
            && (str_starts_with($path, 'http://') || str_starts_with($path, 'https://'));
    }

    public static function isAllowedMapEmbed(string $url): bool
    {
        $host = parse_url($url, PHP_URL_HOST);

        return in_array($host, [
            'www.google.com',
            'google.com',
            'maps.google.com',
            'www.google.co.id',
            'google.co.id',
        ], true);
    }
}
