<?php

namespace App\Models;

use App\Models\Concerns\PurgesPublicDiskFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property Carbon|null $awaiting_expires_at
 * @property Carbon|null $created_at
 * @property Carbon|null $paid_at
 * @property Carbon|null $rejected_at
 * @property Carbon|null $gps_overridden_at
 * @property Carbon|null $cancelled_at
 * @property Carbon|null $expired_at
 */
class Payment extends Model
{
    use Concerns\ScopedToRestaurant;
    use PurgesPublicDiskFiles;

    protected $fillable = [
        'public_id',
        'restaurant_id',
        'outlet_id',
        'order_id',
        'method',
        'provider',
        'status',
        'amount',
        'cash_received',
        'change_amount',
        'unique_add',
        'qris_hold_amount',
        'qris_image_path_snapshot',
        'proof_image_path',
        'gps_status',
        'gps_latitude',
        'gps_longitude',
        'gps_accuracy_m',
        'gps_distance_m',
        'gps_override_by_user_id',
        'gps_override_reason',
        'gps_overridden_at',
        'awaiting_expires_at',
        'paid_at',
        'paid_by_user_id',
        'rejected_at',
        'reject_reason',
        'cancelled_at',
        'expired_at',
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
            'rejected_at' => 'datetime',
            'awaiting_expires_at' => 'datetime',
            'gps_overridden_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'expired_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $payment): void {
            if (blank($payment->public_id)) {
                $payment->public_id = (string) Str::ulid();
            }
        });
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function paidByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by_user_id');
    }

    /**
     * @return list<string>
     */
    protected function storedFileAttributes(): array
    {
        return ['qris_image_path_snapshot', 'proof_image_path'];
    }
}
