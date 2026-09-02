<?php

namespace App\Models;

use App\Support\CmsMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class LandingTemplate extends Model
{
    use LogsActivity;

    public const DEFAULT_TEMPLATE = 'classic';

    protected $fillable = [
        'slug',
        'name',
        'description',
        'badge',
        'thumbnail_path',
        'view_path',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'description',
                'badge',
                'thumbnail_path',
                'view_path',
                'is_active',
                'sort_order',
            ])
            ->logOnlyDirty()
            ->useLogName('platform')
            ->dontLogEmptyChanges();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getThumbnailUrlAttribute(): string
    {
        if (filled($this->thumbnail_path)) {
            if (str_starts_with($this->thumbnail_path, 'images/') || str_starts_with($this->thumbnail_path, 'http://') || str_starts_with($this->thumbnail_path, 'https://')) {
                return asset($this->thumbnail_path);
            }

            return CmsMedia::url($this->thumbnail_path);
        }

        return asset('images/landing/templates/' . $this->slug . '.webp');
    }
}
