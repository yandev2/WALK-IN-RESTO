<?php

namespace App\Models;

use App\Enums\InvoiceSource;
use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionInvoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'restaurant_id',
        'plan_code',
        'invoice_type',
        'requested_plan_code',
        'billing_months',
        'amount',
        'total_omzet',
        'commission_percentage',
        'status',
        'source',
        'period_key',
        'period_month',
        'period_start',
        'period_end',
        'due_at',
        'payment_proof_path',
        'payment_submitted_at',
        'paid_at',
        'created_by',
        'verified_by',
        'payment_notes',
        'admin_notes',
        'rejection_notes',
    ];

    protected function casts(): array
    {
        return [
            'invoice_type' => InvoiceType::class,
            'billing_months' => 'integer',
            'amount' => 'integer',
            'total_omzet' => 'integer',
            'commission_percentage' => 'float',
            'status' => InvoiceStatus::class,
            'source' => InvoiceSource::class,
            'period_start' => 'datetime',
            'period_end' => 'datetime',
            'due_at' => 'datetime',
            'payment_submitted_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_code', 'code');
    }

    public function requestedPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'requested_plan_code', 'code');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function effectivePlanCode(): string
    {
        return $this->requested_plan_code ?: $this->plan_code;
    }

    public function isOpen(): bool
    {
        return $this->status instanceof InvoiceStatus && $this->status->isOpen();
    }

    public function formattedAmount(): string
    {
        return 'Rp '.number_format((int) $this->amount, 0, ',', '.');
    }

    public function isCashierCommission(): bool
    {
        return $this->invoice_type === InvoiceType::CashierCommission;
    }

    public function formattedOmzet(): string
    {
        return 'Rp '.number_format((int) ($this->total_omzet ?? 0), 0, ',', '.');
    }
}
