<?php

namespace App\Models;

use App\Models\Concerns\BelongsToRestaurantAndOutlet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class CashierShiftMovement extends Model
{
    use BelongsToRestaurantAndOutlet;

    public const TYPE_CASH_IN = 'cash_in';
    public const TYPE_CASH_OUT = 'cash_out';

    protected $fillable = [
        'public_id',
        'cashier_shift_id',
        'restaurant_id',
        'outlet_id',
        'user_id',
        'type',
        'amount',
        'category',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $movement): void {
            if (blank($movement->public_id)) {
                $movement->public_id = (string) Str::ulid();
            }
        });
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(CashierShift::class, 'cashier_shift_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }
}
