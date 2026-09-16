<?php

namespace App\Models;

use App\Models\Concerns\PurgesPublicDiskFiles;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class BlogHeroSetting extends Model implements TranslatableContract
{
    use PurgesPublicDiskFiles, Translatable;

    protected $table = 'blog_hero_settings';

    protected function storedFileAttributes(): array
    {
        return ['banner_image'];
    }

    public array $translatedAttributes = [
        'badge_text',
        'title',
        'subtitle',
        'search_placeholder',
    ];

    protected $fillable = [
        'banner_image',
        'overlay_opacity',
        'show_quick_categories',
    ];

    protected function casts(): array
    {
        return [
            'overlay_opacity' => 'integer',
            'show_quick_categories' => 'boolean',
        ];
    }

    public static function getSingleton(): self
    {
        return static::query()->first() ?? static::query()->create([
            'overlay_opacity' => 60,
            'show_quick_categories' => true,
        ]);
    }
}
