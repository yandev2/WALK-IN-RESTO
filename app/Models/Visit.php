<?php

namespace App\Models;

use App\Models\Concerns\BelongsToRestaurantAndOutlet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Visit extends Model
{
    use BelongsToRestaurantAndOutlet;

    protected $fillable = [
        'public_id',
        'restaurant_id',
        'outlet_id',
        'table_id',
        'status',
        'join_pin',
        'pin_fail_count',
        'join_locked_until',
        'opened_by_user_id',
        'customer_name',
        'customer_wa',
        'claimed_at',
        'claim_expires_at',
        'closed_at',
        'closed_by_user_id',
    ];

    protected function casts(): array
    {
        return [
            'join_locked_until' => 'datetime',
            'claimed_at' => 'datetime',
            'claim_expires_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $visit): void {
            if (blank($visit->public_id)) {
                $visit->public_id = (string) Str::ulid();
            }
        });
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function diningTable(): BelongsTo
    {
        return $this->belongsTo(DiningTable::class, 'table_id');
    }

    public function devices(): HasMany
    {
        return $this->hasMany(VisitDevice::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(VisitCartItem::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(RestaurantReview::class);
    }

    public function hasBlockingOrders(): bool
    {
        return $this->orders()
            ->whereIn('status', ['awaiting_cashier', 'pending_payment', ...Order::ACCEPTED_STATUSES])
            ->exists();
    }

    public function isJoinLocked(): bool
    {
        return filled($this->join_locked_until) && $this->join_locked_until->isFuture();
    }

    public function hasUnservedKitchenItems(): bool
    {
        return $this->orders()
            ->whereIn('status', Order::ACCEPTED_STATUSES)
            ->whereHas('items', fn ($query) => $query->whereIn('kds_status', ['queued', 'preparing', 'ready']))
            ->exists();
    }
}
