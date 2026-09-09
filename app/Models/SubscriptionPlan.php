<?php

namespace App\Models;

use App\Enums\BillingType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'price_monthly',
        'billing_type',
        'commission_percentage',
        'features',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price_monthly' => 'integer',
            'billing_type' => BillingType::class,
            'commission_percentage' => 'float',
            'features' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function restaurants(): HasMany
    {
        return $this->hasMany(Restaurant::class, 'plan_code', 'code');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(SubscriptionInvoice::class, 'plan_code', 'code');
    }

    public function isCommissionBased(): bool
    {
        return $this->billing_type === BillingType::Commission;
    }

    public function hasFeature(string $feature): bool
    {
        $features = $this->features ?? [];

        if ($feature === 'settings_full') {
            $settings = $features['settings'] ?? false;

            return $settings === true || $settings === 'full';
        }

        if ($feature === 'settings') {
            return (bool) ($features['settings'] ?? false);
        }

        return (bool) ($features[$feature] ?? false);
    }

    public function formattedPrice(): string
    {
        if ($this->isCommissionBased()) {
            $rate = $this->commission_percentage ?? 10.00;

            return rtrim(rtrim(number_format((float) $rate, 2, ',', '.'), '0'), ',').'% omzet';
        }

        return 'Rp '.number_format((int) $this->price_monthly, 0, ',', '.');
    }
}
