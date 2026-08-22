<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestaurantReview extends Model
{
    use Concerns\ScopedToRestaurant;

    protected $fillable = [
        'restaurant_id',
        'outlet_id',
        'visit_id',
        'order_id',
        'customer_name',
        'rating',
        'comment',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
        ];
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function displayName(): string
    {
        return filled($this->customer_name) ? $this->customer_name : 'Tamu';
    }
}
