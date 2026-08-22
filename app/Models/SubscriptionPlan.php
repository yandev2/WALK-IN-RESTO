<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'price_monthly',
        'features',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price_monthly' => 'integer',
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
        return 'Rp '.number_format((int) $this->price_monthly, 0, ',', '.');
    }
}
