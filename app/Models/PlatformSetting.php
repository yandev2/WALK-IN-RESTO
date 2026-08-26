<?php

namespace App\Models;

use App\Models\Concerns\PurgesPublicDiskFiles;
use App\Support\CmsMedia;
use App\Support\RestaurantTheme;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Throwable;

class PlatformSetting extends Model
{
    use PurgesPublicDiskFiles;

    public const CACHE_KEY = 'platform_setting';

    public const PLACEHOLDER_QR_URL = 'https://picsum.photos/seed/founder-billing-qr/200/200';

    protected $fillable = [
        'primary_color',
        'site_name',
        'logo_path',
        'auth_background_path',
        'hero_eyebrow',
        'hero_title',
        'hero_highlight',
        'hero_subtitle',
        'hero_image_path',
        'cta_register_label',
        'search_placeholder',
        'search_button_label',
        'location_cta_label',
        'footer_about',
        'footer_email',
        'footer_phone',
        'footer_address',
        'footer_instagram',
        'footer_copyright',
        'footer_privacy_url',
        'footer_terms_url',
        'bank_name',
        'bank_holder',
        'bank_account',
        'qr_image_path',
        'trial_days',
    ];

    protected function casts(): array
    {
        return [
            'trial_days' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $setting): void {
            if (filled($setting->primary_color)) {
                $setting->primary_color = RestaurantTheme::normalizeHex($setting->primary_color)
                    ?? RestaurantTheme::DEFAULT_PRIMARY;
            }
        });

        static::saved(fn () => self::forgetCache());
        static::deleted(fn () => self::forgetCache());
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaults(): array
    {
        return [
            'primary_color' => RestaurantTheme::DEFAULT_PRIMARY,
            'site_name' => 'RestoTerdekat',
            'logo_path' => null,
            'auth_background_path' => null,
            'hero_eyebrow' => 'Walk-in',
            'hero_title' => 'Temukan restoran terdekat & terbaik',
            'hero_highlight' => 'terdekat',
            'hero_subtitle' => 'Jelajahi restoran di sekitar Anda, lihat menu, jam buka, dan langsung datang ke meja pilihan.',
            'hero_image_path' => null,
            'cta_register_label' => 'Daftarkan restoran',
            'search_placeholder' => 'Cari restoran, masakan, atau menu...',
            'search_button_label' => 'Cari',
            'location_cta_label' => 'Izinkan lokasi',
            'footer_about' => '{site} membantu tamu menemukan restoran terdekat dan membantu resto menerima tamu walk-in.',
            'footer_email' => (string) config('subscription.contact_email', 'founder@restoterdekat.id'),
            'footer_phone' => (string) config('subscription.contact_whatsapp', ''),
            'footer_address' => null,
            'footer_instagram' => null,
            'footer_copyright' => '© {year} {site}. Semua hak dilindungi.',
            'footer_privacy_url' => null,
            'footer_terms_url' => null,
            'bank_name' => (string) config('subscription.bank_name', 'BCA'),
            'bank_holder' => (string) config('subscription.bank_holder', 'RestoTerdekat'),
            'bank_account' => (string) config('subscription.bank_account', '0000000000'),
            'qr_image_path' => null,
            'trial_days' => (int) config('subscription.trial_days', 30),
        ];
    }

    public static function optional(): ?self
    {
        try {
            if (! Schema::hasTable('platform_settings')) {
                return null;
            }

            if (! app()->environment('testing') && Cache::has(self::CACHE_KEY)) {
                $cached = Cache::get(self::CACHE_KEY);

                return $cached instanceof self ? $cached : null;
            }

            $row = static::query()->first();

            if ($row instanceof self && ! app()->environment('testing')) {
                Cache::forever(self::CACHE_KEY, $row);
            }

            return $row;
        } catch (Throwable) {
            return null;
        }
    }

    public static function current(): self
    {
        $existing = static::optional();

        if ($existing instanceof self) {
            return $existing;
        }

        $created = static::query()->create(static::defaults());
        self::forgetCache();

        return $created;
    }

    public static function forgetCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    public static function trialDays(): int
    {
        $fallback = max(1, (int) config('subscription.trial_days', 30));
        $value = static::optional()?->trial_days;

        if ($value === null || (int) $value < 1) {
            return $fallback;
        }

        return min(365, (int) $value);
    }

    /**
     * @return array<string, mixed>
     */
    public static function homeViewData(): array
    {
        $defaults = static::defaults();
        $row = static::optional();
        $data = [];

        foreach ($defaults as $key => $fallback) {
            $value = $row?->getAttribute($key);
            $data[$key] = filled($value) ? $value : $fallback;
        }

        $data['hero_image_url'] = CmsMedia::url($row?->hero_image_path);
        $data['logo_url'] = CmsMedia::url($row?->logo_path);
        $data['auth_background_url'] = CmsMedia::url($row?->auth_background_path);

        $siteName = (string) ($data['site_name'] ?? '');
        foreach (['footer_about', 'footer_copyright'] as $tokenField) {
            $data[$tokenField] = self::applyFooterTokens((string) ($data[$tokenField] ?? ''), $siteName);
        }

        $data['footer_whatsapp_url'] = CmsMedia::whatsappUrl($data['footer_phone'] ?? null);
        $data['footer_instagram_url'] = CmsMedia::instagramUrl($data['footer_instagram'] ?? null);

        return $data;
    }

    public static function applyFooterTokens(string $text, string $siteName): string
    {
        return str_replace(
            ['{year}', '{site}'],
            [(string) now()->year, $siteName],
            $text,
        );
    }

    /**
     * @return array{bankName: string, bankAccount: string, bankHolder: string, contactEmail: string|null, qrUrl: string}
     */
    public static function billingViewData(): array
    {
        $row = static::optional();
        $defaults = static::defaults();

        return [
            'bankName' => filled($row?->bank_name) ? (string) $row->bank_name : $defaults['bank_name'],
            'bankAccount' => filled($row?->bank_account) ? (string) $row->bank_account : $defaults['bank_account'],
            'bankHolder' => filled($row?->bank_holder) ? (string) $row->bank_holder : $defaults['bank_holder'],
            'contactEmail' => filled($row?->footer_email)
                ? (string) $row->footer_email
                : config('subscription.contact_email'),
            'qrUrl' => CmsMedia::url($row?->qr_image_path) ?: self::PLACEHOLDER_QR_URL,
        ];
    }

    public static function heroTitleHtml(array $home): string
    {
        $title = (string) ($home['hero_title'] ?? '');
        $highlight = (string) ($home['hero_highlight'] ?? '');

        if ($title === '') {
            return '';
        }

        if ($highlight === '' || ! str_contains($title, $highlight)) {
            return e($title);
        }

        $escapedTitle = e($title);
        $escapedHighlight = e($highlight);
        $pos = strpos($escapedTitle, $escapedHighlight);

        if ($pos === false) {
            return $escapedTitle;
        }

        return substr($escapedTitle, 0, $pos)
            .'<span class="text-primary">'.$escapedHighlight.'</span>'
            .substr($escapedTitle, $pos + strlen($escapedHighlight));
    }

    /**
     * @return list<string>
     */
    protected function storedFileAttributes(): array
    {
        return ['hero_image_path', 'logo_path', 'qr_image_path', 'auth_background_path'];
    }
}
