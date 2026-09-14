<?php

namespace App\Models;

use App\Models\Concerns\ScopedToRestaurant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerLoyaltyPoint extends Model
{
    use ScopedToRestaurant;

    protected $fillable = [
        'restaurant_id',
        'customer_id',
        'order_id',
        'type',
        'points',
        'balance_after',
        'description',
        'created_by_user_id',
    ];

    protected function casts(): array
    {
        return [
            'points' => 'integer',
            'balance_after' => 'integer',
        ];
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
