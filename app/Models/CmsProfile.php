<?php

namespace App\Models;

use App\Models\Concerns\BelongsToRestaurantAndOutlet;
use App\Models\Concerns\PurgesPublicDiskFiles;
use App\Support\RestaurantTheme;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class CmsProfile extends Model
{
    use BelongsToRestaurantAndOutlet;
    use LogsActivity;
    use PurgesPublicDiskFiles;

    protected $fillable = [
        'restaurant_id',
        'headline',
        'about_html',
        'hero_image_path',
        'how_to_image_path',
        'about_image_path',
        'map_embed_url',
        'cta_label',
        'cta_url',
        'primary_color',
        'accent_color',
        'landing_sections',
        'landing_copy',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'headline',
                'cta_label',
                'cta_url',
                'primary_color',
                'accent_color',
                'hero_image_path',
                'how_to_image_path',
                'about_image_path',
                'map_embed_url',
            ])
            ->logOnlyDirty()
            ->useLogName('cms')
            ->dontLogEmptyChanges();
    }

    protected function casts(): array
    {
        return [
            'landing_sections' => 'array',
            'landing_copy' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $profile): void {
            if (filled($profile->primary_color)) {
                $profile->primary_color = RestaurantTheme::normalizeHex($profile->primary_color);
            }

            if (filled($profile->accent_color)) {
                $profile->accent_color = RestaurantTheme::normalizeHex($profile->accent_color);
            }
        });
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * @return list<string>
     */
    protected function storedFileAttributes(): array
    {
        return ['hero_image_path', 'how_to_image_path', 'about_image_path'];
    }
}
