<?php

namespace App\Models;

use App\Models\Concerns\BelongsToRestaurantAndOutlet;
use App\Models\Concerns\PurgesPublicDiskFiles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class CmsBanner extends Model
{
    use BelongsToRestaurantAndOutlet;
    use LogsActivity;
    use PurgesPublicDiskFiles;
    use SoftDeletes;

    protected $fillable = [
        'restaurant_id',
        'title',
        'subtitle',
        'badge_text',
        'price_label',
        'cta_label',
        'image_path',
        'link_url',
        'starts_at',
        'ends_at',
        'sort_order',
        'is_active',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'title',
                'subtitle',
                'badge_text',
                'price_label',
                'cta_label',
                'link_url',
                'is_active',
                'starts_at',
                'ends_at',
            ])
            ->logOnlyDirty()
            ->useLogName('cms')
            ->dontLogEmptyChanges();
    }

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * @param  Builder<CmsBanner>  $query
     * @return Builder<CmsBanner>
     */
    public function scopeCurrentlyLive($query)
    {
        $now = now();

        return $query
            ->where('is_active', true)
            ->where('starts_at', '<=', $now)
            ->where('ends_at', '>=', $now);
    }

    /**
     * @return list<string>
     */
    protected function storedFileAttributes(): array
    {
        return ['image_path'];
    }
}
