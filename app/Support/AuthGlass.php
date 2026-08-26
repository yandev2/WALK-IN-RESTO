<?php

namespace App\Support;

use App\Models\PlatformSetting;
use Illuminate\Support\HtmlString;

final class AuthGlass
{
    public static function backgroundUrl(): ?string
    {
        return CmsMedia::url(PlatformSetting::optional()?->auth_background_path);
    }

    public static function cssVariables(): string
    {
        $theme = RestaurantTheme::for(null);
        $primary = $theme['primary'];
        $parts = [
            '--auth-primary: '.$primary.';',
            '--auth-primary-hover: '.$theme['primary_dark'].';',
            '--auth-primary-text: '.self::contrastColor($primary).';',
        ];

        $url = self::backgroundUrl();

        if (filled($url)) {
            $parts[] = '--auth-bg-image: url('.json_encode($url, JSON_UNESCAPED_SLASHES).');';
        }

        return implode(' ', $parts);
    }

    private static function contrastColor(string $hex): string
    {
        $hex = ltrim($hex, '#');
        $r = hexdec(substr($hex, 0, 2)) / 255;
        $g = hexdec(substr($hex, 2, 2)) / 255;
        $b = hexdec(substr($hex, 4, 2)) / 255;
        $luminance = (0.2126 * $r) + (0.7152 * $g) + (0.0722 * $b);

        return $luminance > 0.55 ? '#1c1917' : '#ffffff';
    }

    public static function headHtml(bool $includeVite = false): HtmlString
    {
        return new HtmlString(view('filament.hooks.auth-glass-head', [
            'includeVite' => $includeVite,
            'cssVariables' => self::cssVariables(),
        ])->render());
    }
}
