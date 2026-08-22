<?php

namespace App\Models;

use App\Models\Concerns\BelongsToRestaurantAndOutlet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItemModifier extends Model
{
    use BelongsToRestaurantAndOutlet;

    public $timestamps = false;

    protected $fillable = [
        'restaurant_id',
        'outlet_id',
        'order_item_id',
        'modifier_id',
        'name_snapshot',
        'price_snapshot',
    ];

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function modifier(): BelongsTo
    {
        return $this->belongsTo(Modifier::class);
    }
}
