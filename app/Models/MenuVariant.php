<?php

namespace App\Models;

use App\Models\Concerns\BelongsToRestaurantAndOutlet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuVariant extends Model
{
    use BelongsToRestaurantAndOutlet;
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'restaurant_id',
        'outlet_id',
        'menu_item_id',
        'name',
        'price_delta',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }
}
