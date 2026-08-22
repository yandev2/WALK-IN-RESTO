<?php

namespace App\Models;

use App\Models\Concerns\BelongsToRestaurantAndOutlet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModifierGroup extends Model
{
    use BelongsToRestaurantAndOutlet;
    use SoftDeletes;

    protected $fillable = [
        'restaurant_id',
        'outlet_id',
        'name',
        'min_select',
        'max_select',
        'is_required',
    ];

    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
        ];
    }

    public function modifiers(): HasMany
    {
        return $this->hasMany(Modifier::class)->orderBy('sort_order');
    }

    public function menuItems(): BelongsToMany
    {
        return $this->belongsToMany(MenuItem::class, 'menu_item_modifier_groups')
            ->withPivot(['restaurant_id', 'outlet_id']);
    }

    protected static function booted(): void
    {
        static::deleting(function (self $group): void {
            if ($group->isForceDeleting()) {
                $group->modifiers()->withTrashed()->each(fn (Modifier $modifier) => $modifier->forceDelete());

                return;
            }

            $group->modifiers()->each(fn (Modifier $modifier) => $modifier->delete());
        });

        static::restoring(function (self $group): void {
            $group->modifiers()->onlyTrashed()->each(fn (Modifier $modifier) => $modifier->restore());
        });
    }

    public function minRequired(): int
    {
        $min = (int) $this->min_select;

        if ($this->is_required) {
            return max($min, 1);
        }

        return max(0, $min);
    }

    public function maxAllowed(): int
    {
        $max = (int) $this->max_select;
        $min = $this->minRequired();

        if ($max < 1) {
            return max($min, 1);
        }

        return max($max, $min);
    }
}
