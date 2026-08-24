<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderItem extends Model
{
    use Concerns\ScopedToRestaurant;

    protected $fillable = [
        'restaurant_id',
        'outlet_id',
        'order_id',
        'menu_item_id',
        'station_id',
        'name_snapshot',
        'variant_name_snapshot',
        'unit_price',
        'qty',
        'notes',
        'kds_status',
        'queued_at',
        'preparing_at',
        'ready_at',
        'served_at',
        'served_reverted_at',
        'voided_at',
        'void_omzet_policy',
        'void_reason',
    ];

    protected function casts(): array
    {
        return [
            'queued_at' => 'datetime',
            'preparing_at' => 'datetime',
            'ready_at' => 'datetime',
            'served_at' => 'datetime',
            'served_reverted_at' => 'datetime',
            'voided_at' => 'datetime',
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

    public function station(): BelongsTo
    {
        return $this->belongsTo(KdsStation::class, 'station_id');
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function modifiers(): HasMany
    {
        return $this->hasMany(OrderItemModifier::class);
    }

    public function displayName(): string
    {
        $name = $this->name_snapshot;

        if (filled($this->variant_name_snapshot)) {
            $name .= ' ('.$this->variant_name_snapshot.')';
        }

        $extras = $this->modifiers->pluck('name_snapshot')->filter()->implode(', ');

        if (filled($extras)) {
            $name .= ' + '.$extras;
        }

        return $name;
    }

    public function batchKey(): string
    {
        $extras = $this->modifiers->pluck('name_snapshot')->sort()->implode(',');

        return strtolower(trim($this->name_snapshot.'|'.$this->variant_name_snapshot.'|'.$extras.'|'.$this->notes));
    }

    public function elapsedMinutes(): int
    {
        $start = $this->order?->paid_at ?? $this->queued_at ?? $this->created_at;

        if (! $start) {
            return 0;
        }

        return (int) abs($start->diffInMinutes(now()));
    }

    public function timerBand(): string
    {
        $minutes = $this->elapsedMinutes();

        return match (true) {
            $minutes < 10 => 'green',
            $minutes < 20 => 'yellow',
            default => 'red',
        };
    }

    public function canRevertServed(): bool
    {
        return $this->kds_status === 'served'
            && $this->served_at
            && $this->served_at->gte(now()->subMinutes(2));
    }
}
