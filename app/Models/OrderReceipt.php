<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderReceipt extends Model
{
    use Concerns\ScopedToRestaurant;

    public $timestamps = false;

    protected $fillable = [
        'restaurant_id',
        'outlet_id',
        'order_id',
        'file_path',
        'generated_at',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'generated_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
