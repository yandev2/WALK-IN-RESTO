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
        'cashier_shift_id',
        'idempotency_key',
        'currency',
        'pb1_pct_snapshot',
        'service_pct_snapshot',
        'tax_mode_snapshot',
        'subtotal',
        'discount_amount',
        'points_redeemed',
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

    public const STATUS_PENDING_PAYMENT = 'pending_payment';
    public const STATUS_AWAITING_CASHIER = 'awaiting_cashier';
    public const STATUS_PAID = 'paid';
    public const STATUS_IN_PRODUCTION = 'in_production';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_VOIDED = 'voided';

    public const ACCEPTED_STATUSES = ['paid', 'in_production', 'completed'];

    public const STATUSES = [
        self::STATUS_AWAITING_CASHIER => 'Menunggu kasir',
        self::STATUS_PAID => 'Lunas (Antrian dapur)',
        self::STATUS_IN_PRODUCTION => 'Sedang dimasak',
        self::STATUS_COMPLETED => 'Selesai',
        self::STATUS_PENDING_PAYMENT => 'Pending bayar',
        self::STATUS_REJECTED => 'Ditolak',
        self::STATUS_CANCELLED => 'Batal',
        self::STATUS_VOIDED => 'Void',
    ];

    protected function casts(): array
    {
        return [
            'points_redeemed' => 'integer',
            'subtotal' => 'integer',
            'discount_amount' => 'integer',
            'service_amount' => 'integer',
            'pb1_amount' => 'integer',
            'grand_before' => 'integer',
            'grand_payable' => 'integer',
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

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function cashierShift(): BelongsTo
    {
        return $this->belongsTo(CashierShift::class, 'cashier_shift_id');
    }

    public function isAccepted(): bool
    {
        return in_array($this->status, self::ACCEPTED_STATUSES, true);
    }

    public function hasPointsRedeemed(): bool
    {
        return ((int) $this->points_redeemed) > 0;
    }

    public function pointsDiscountAmount(): int
    {
        return (int) $this->discount_amount;
    }
}
