<?php

namespace App\Models;

use App\Models\Concerns\BelongsToRestaurantAndOutlet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitDevice extends Model
{
    use BelongsToRestaurantAndOutlet;

    public $timestamps = false;

    protected $fillable = [
        'restaurant_id',
        'outlet_id',
        'visit_id',
        'device_token',
        'is_host',
        'user_agent',
        'joined_at',
        'last_seen_at',
    ];

    protected function casts(): array
    {
        return [
            'is_host' => 'boolean',
            'joined_at' => 'datetime',
            'last_seen_at' => 'datetime',
        ];
    }

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }
}
