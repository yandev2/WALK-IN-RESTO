<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Order extends Model
{
    use Concerns\ScopedToRestaurant;

    protected $fillable = [
        'public_id',
        'restaurant_id',
        'outlet_id',
        'visit_id',
        'number',
        'status',
        'source',
        'payment_method',
        'created_by_user_id',
        'idempotency_key',
        'currency',
        'pb1_pct_snapshot',
        'service_pct_snapshot',
        'tax_mode_snapshot',
        'subtotal',
        'discount_amount',
        'service_amount',
        'pb1_amount',
        'grand_before',
        'grand_payable',
        'send_receipt',
        'receipt_wa_snapshot',
        'paid_at',
        'cancelled_at',
        'voided_at',
    ];

    public const ACCEPTED_STATUSES = ['paid', 'in_production', 'completed'];

    protected function casts(): array
    {
        return [
            'send_receipt' => 'boolean',
            'paid_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'voided_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $order): void {
            if (blank($order->public_id)) {
                $order->public_id = (string) Str::ulid();
            }
        });
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

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function receipt(): HasOne
    {
        return $this->hasOne(OrderReceipt::class);
    }

    public function whatsappMessages(): HasMany
    {
        return $this->hasMany(WhatsappMessage::class);
    }

    public function isAccepted(): bool
    {
        return in_array($this->status, self::ACCEPTED_STATUSES, true);
    }
}
