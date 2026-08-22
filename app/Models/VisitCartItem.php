<?php

namespace App\Models;

use App\Models\Concerns\BelongsToRestaurantAndOutlet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class VisitCartItem extends Model
{
    use BelongsToRestaurantAndOutlet;

    protected $fillable = [
        'restaurant_id',
        'outlet_id',
        'visit_id',
        'menu_item_id',
        'menu_variant_id',
        'qty',
        'notes',
    ];

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(MenuVariant::class, 'menu_variant_id');
    }

    public function modifiers(): BelongsToMany
    {
        return $this->belongsToMany(Modifier::class, 'visit_cart_item_modifiers')
            ->withPivot(['restaurant_id', 'outlet_id']);
    }

    public function lineTotal(): int
    {
        $price = (int) ($this->menuItem?->effectivePrice() ?? 0);
        $delta = (int) ($this->variant?->price_delta ?? 0);
        $extras = (int) $this->modifiers->sum('price');

        return ($price + $delta + $extras) * (int) $this->qty;
    }
}
