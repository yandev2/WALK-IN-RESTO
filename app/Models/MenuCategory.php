<?php

namespace App\Models;

use App\Models\Concerns\BelongsToRestaurantAndOutlet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\ValidationException;

class MenuCategory extends Model
{
    use BelongsToRestaurantAndOutlet;
    use SoftDeletes;

    protected $fillable = [
        'restaurant_id',
        'outlet_id',
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

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'category_id');
    }

    protected static function booted(): void
    {
        static::deleting(function (self $category): void {
            if ($category->items()->exists()) {
                throw ValidationException::withMessages([
                    'category' => 'Kategori masih dipakai item menu. Pindahkan atau hapus itemnya dulu.',
                ]);
            }
        });
    }
}
