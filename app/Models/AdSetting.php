<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Throwable;

class AdSetting extends Model
{
    public const CACHE_KEY = 'ad_setting';

    protected $fillable = [
        'is_enabled',
        'ads_txt_content',
        'adsense_enabled',
        'adsense_client_id',
        'adsense_auto_ads',
        'adsterra_enabled',
        'adsterra_social_bar_enabled',
        'adsterra_social_bar_code',
        'adsterra_native_enabled',
        'adsterra_native_code',
        'slot_blog_article_top',
        'slot_blog_article_middle',
        'slot_blog_article_bottom',
        'slot_blog_sidebar',
        'slot_blog_feed',
        'slot_directory_native',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'adsense_enabled' => 'boolean',
            'adsense_auto_ads' => 'boolean',
            'adsterra_enabled' => 'boolean',
            'adsterra_social_bar_enabled' => 'boolean',
            'adsterra_native_enabled' => 'boolean',
            'slot_blog_article_top' => 'array',
            'slot_blog_article_middle' => 'array',
            'slot_blog_article_bottom' => 'array',
            'slot_blog_sidebar' => 'array',
            'slot_blog_feed' => 'array',
            'slot_directory_native' => 'array',
        ];
    }

    /**
     * Get the current (singleton) ad setting row, cached for 24 hours.
     */
    public static function current(): self
    {
        try {
            return Cache::remember(self::CACHE_KEY, now()->addDay(), function () {
                return self::query()->firstOrCreate([]);
            });
        } catch (Throwable) {
            return new self;
        }
    }

    /**
     * Return null-safe optional accessor.
     */
    public static function optional(): ?self
    {
        try {
            return self::current();
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Clear the cached setting.
     */
    public static function forgetCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Check if master ads are enabled.
     */
    public function isMasterEnabled(): bool
    {
        return (bool) $this->is_enabled;
    }

    /**
     * Get a slot configuration.
     *
     * @return array{provider: string|null, code: string|null, is_active: bool}
     */
    public function getSlot(string $slotName): array
    {
        $attr = "slot_{$slotName}";
        $slot = $this->getAttribute($attr);

        return [
            'provider' => $slot['provider'] ?? null,
            'code' => $slot['code'] ?? null,
            'is_active' => (bool) ($slot['is_active'] ?? false),
        ];
    }

    /**
     * Check if a specific slot is renderable.
     */
    public function isSlotActive(string $slotName): bool
    {
        if (! $this->isMasterEnabled()) {
            return false;
        }

        $slot = $this->getSlot($slotName);

        if (! $slot['is_active']) {
            return false;
        }

        if (filled($slot['code'])) {
            return true;
        }

        // Fallback: if provider is adsterra and global native banner is active with code
        if (($slot['provider'] ?? '') === 'adsterra'
            && (bool) $this->adsterra_enabled
            && (bool) $this->adsterra_native_enabled
            && filled($this->adsterra_native_code)
        ) {
            return true;
        }

        return false;
    }

    protected static function booted(): void
    {
        static::saved(function (): void {
            self::forgetCache();
        });
    }
}
