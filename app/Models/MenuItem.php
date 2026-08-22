<?php

namespace App\Models;

use App\Models\Concerns\BelongsToRestaurantAndOutlet;
use App\Models\Concerns\PurgesPublicDiskFiles;
use App\Support\CmsMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class MenuItem extends Model
{
    use BelongsToRestaurantAndOutlet;
    use PurgesPublicDiskFiles;
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'restaurant_id',
        'outlet_id',
        'category_id',
        'station_id',
        'name',
        'description',
        'price',
        'discount_percent',
        'photo_path',
        'is_active',
        'is_out_of_stock',
        'sort_order',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'price',
                'discount_percent',
                'is_active',
                'is_out_of_stock',
                'category.name',
                'station.name',
            ])
            ->logOnlyDirty()
            ->useLogName('menu')
            ->dontLogEmptyChanges();
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_out_of_stock' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::forceDeleting(function (self $item): void {
            $item->photos()->each(fn (MenuItemPhoto $photo) => $photo->delete());
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'category_id');
    }

    public function station(): BelongsTo
    {
        return $this->belongsTo(KdsStation::class, 'station_id');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(MenuVariant::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(MenuItemPhoto::class)->orderBy('sort_order');
    }

    /**
     * @return list<string>
     */
    public function photoUrls(): array
    {
        $urls = [];

        if ($url = CmsMedia::url($this->photo_path)) {
            $urls[] = $url;
        }

        if ($this->relationLoaded('photos')) {
            foreach ($this->photos as $photo) {
                if ($url = CmsMedia::url($photo->photo_path)) {
                    $urls[] = $url;
                }
            }
        }

        return $urls;
    }

    public function modifierGroups(): BelongsToMany
    {
        return $this->belongsToMany(ModifierGroup::class, 'menu_item_modifier_groups')
            ->withPivot(['restaurant_id', 'outlet_id']);
    }

    public function hasDiscount(): bool
    {
        return filled($this->discount_percent) && (int) $this->discount_percent > 0;
    }

    public function effectivePrice(): int
    {
        if (! $this->hasDiscount()) {
            return (int) $this->price;
        }

        $percent = min(100, max(0, (int) $this->discount_percent));

        return (int) round((int) $this->price * (100 - $percent) / 100);
    }

    /**
     * @param  Builder<MenuItem>  $query
     * @return Builder<MenuItem>
     */
    public function scopeOrderByEffectivePrice(Builder $query, string $direction = 'asc'): Builder
    {
        $dir = strtolower($direction) === 'desc' ? 'desc' : 'asc';

        return $query->orderByRaw(
            '(CASE WHEN discount_percent IS NOT NULL AND discount_percent > 0 THEN ROUND(price * (100 - discount_percent) / 100.0) ELSE price END) '.$dir
        );
    }

    /**
     * @return list<string>
     */
    protected function storedFileAttributes(): array
    {
        return ['photo_path'];
    }
}
