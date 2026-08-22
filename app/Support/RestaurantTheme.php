<?php

namespace App\Support;

use App\Models\PlatformSetting;
use App\Models\Restaurant;

final class RestaurantTheme
{
    public const DEFAULT_PRIMARY = '#F97316';

    public const DEFAULT_ACCENT = '#FB923C';

    /**
     * @return array{primary: string, primary_dark: string, accent: string}
     */
    public static function for(?Restaurant $restaurant): array
    {
        if (! $restaurant) {
            $primary = self::normalizeHex(PlatformSetting::optional()?->primary_color)
                ?? self::DEFAULT_PRIMARY;

            return [
                'primary' => $primary,
                'primary_dark' => self::darken($primary, 0.14),
                'accent' => self::lighten($primary, 0.18),
            ];
        }

        $profile = $restaurant->relationLoaded('cmsProfile')
            ? $restaurant->cmsProfile
            : $restaurant->cmsProfile;

        $primary = self::normalizeHex($profile?->primary_color) ?? self::DEFAULT_PRIMARY;
        $accent = self::normalizeHex($profile?->accent_color) ?? self::DEFAULT_ACCENT;

        return [
            'primary' => $primary,
            'primary_dark' => self::darken($primary, 0.14),
            'accent' => $accent,
        ];
    }

    public static function normalizeHex(?string $color): ?string
    {
        if (blank($color)) {
            return null;
        }

        $color = strtoupper(trim($color));

        if (! preg_match('/^#([A-F0-9]{6})$/', $color)) {
            return null;
        }

        return $color;
    }

    public static function darken(string $hex, float $amount = 0.14): string
    {
        $hex = ltrim($hex, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $factor = max(0, min(1, 1 - $amount));

        return sprintf(
            '#%02X%02X%02X',
            (int) round($r * $factor),
            (int) round($g * $factor),
            (int) round($b * $factor),
        );
    }

    public static function lighten(string $hex, float $amount = 0.18): string
    {
        $hex = ltrim($hex, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        $amount = max(0, min(1, $amount));

        return sprintf(
            '#%02X%02X%02X',
            (int) round($r + ((255 - $r) * $amount)),
            (int) round($g + ((255 - $g) * $amount)),
            (int) round($b + ((255 - $b) * $amount)),
        );
    }
}
