<?php

namespace App\Models;

use App\Models\Concerns\BelongsToRestaurantAndOutlet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CashierShift extends Model
{
    use BelongsToRestaurantAndOutlet;

    public const STATUS_OPEN = 'open';
    public const STATUS_CLOSED = 'closed';

    protected $fillable = [
        'public_id',
        'restaurant_id',
        'outlet_id',
        'user_id',
        'status',
        'opened_at',
        'closed_at',
        'starting_cash',
        'cash_sales',
        'non_cash_sales',
        'cash_in',
        'cash_out',
        'expected_ending_cash',
        'actual_ending_cash',
        'cash_difference',
        'difference_reason',
        'notes',
        'closed_by_user_id',
    ];

    protected function casts(): array
    {
        return [
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
            'starting_cash' => 'integer',
            'cash_sales' => 'integer',
            'non_cash_sales' => 'integer',
            'cash_in' => 'integer',
            'cash_out' => 'integer',
            'expected_ending_cash' => 'integer',
            'actual_ending_cash' => 'integer',
            'cash_difference' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $shift): void {
            if (blank($shift->public_id)) {
                $shift->public_id = (string) Str::ulid();
            }
        });
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function closedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by_user_id');
    }

    public function movements(): HasMany
    {
        return $this->hasMany(CashierShiftMovement::class, 'cashier_shift_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'cashier_shift_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'cashier_shift_id');
    }

    public function isOpen(): bool
    {
        return $this->status === self::STATUS_OPEN;
    }

    public function currentExpectedCash(): int
    {
        return (int) ($this->starting_cash + $this->cash_sales + $this->cash_in - $this->cash_out);
    }
}
