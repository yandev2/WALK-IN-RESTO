<?php

namespace App\Models;

use App\Models\Concerns\BelongsToRestaurantAndOutlet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\ValidationException;

class KdsStation extends Model
{
    use BelongsToRestaurantAndOutlet;
    use SoftDeletes;

    protected $fillable = [
        'restaurant_id',
        'outlet_id',
        'slug',
        'name',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'station_id');
    }

    protected static function booted(): void
    {
        static::deleting(function (self $station): void {
            if ($station->menuItems()->exists()) {
                throw ValidationException::withMessages([
                    'station' => 'Stasiun masih dipakai item menu. Pindahkan itemnya dulu.',
                ]);
            }
        });
    }
}
