<?php

namespace App\Support;

use App\Models\Restaurant;
use App\Models\User;
use Filament\Facades\Filament;

final class SubscriptionAccess
{
    public static function allows(?string $feature = null, bool $mutate = false): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        if ($user->isPlatformOperator()) {
            return true;
        }

        $tenant = Filament::getTenant();

        if (! $tenant instanceof Restaurant) {
            return true;
        }

        $gate = app(SubscriptionGate::class);

        if (! $gate->canAccessPanel($tenant)) {
            return false;
        }

        if (filled($feature) && ! $gate->hasFeature($tenant, $feature)) {
            return false;
        }

        if ($mutate && $gate->isReadOnly($tenant)) {
            return false;
        }

        return true;
    }

    public static function isReadOnly(): bool
    {
        $user = auth()->user();

        if ($user instanceof User && $user->isPlatformOperator()) {
            return false;
        }

        $tenant = Filament::getTenant();

        if (! $tenant instanceof Restaurant) {
            return false;
        }

        return app(SubscriptionGate::class)->isReadOnly($tenant);
    }

    public static function isLocked(?Restaurant $tenant = null): bool
    {
        $tenant ??= Filament::getTenant() instanceof Restaurant ? Filament::getTenant() : null;

        if (! $tenant instanceof Restaurant) {
            return false;
        }

        $gate = app(SubscriptionGate::class);
        $expiry = $gate->effectiveExpiryAt($tenant);
        $isPastExpiry = $expiry instanceof \Illuminate\Support\Carbon && $expiry->isPast();

        return $tenant->hasOverdueCashierInvoice()
            || $tenant->hasUnpaidOverdueInvoice()
            || $gate->status($tenant) === \App\Enums\SubscriptionStatus::Expired
            || $isPastExpiry;
    }

    /**
     * @return array{
     *     is_locked: bool,
     *     period_month: string|null,
     *     amount: int,
     *     formatted_amount: string|null,
     *     billing_url: string,
     * }
     */
    public static function getLockDetails(?Restaurant $tenant = null): array
    {
        $tenant ??= Filament::getTenant() instanceof Restaurant ? Filament::getTenant() : null;

        if (! $tenant instanceof Restaurant) {
            return [
                'is_locked' => false,
                'period_month' => null,
                'amount' => 0,
                'formatted_amount' => null,
                'billing_url' => '#',
            ];
        }

        $isLocked = self::isLocked($tenant);
        $overdueInvoice = $tenant->overdueInvoice();

        $billingUrl = '#';
        try {
            $billingUrl = \App\Filament\Pages\SubscriptionStatus::getUrl(tenant: $tenant);
        } catch (\Throwable) {
            $billingUrl = url('/admin/' . $tenant->slug . '/subscription-status');
        }

        return [
            'is_locked' => $isLocked,
            'period_month' => $overdueInvoice?->period_month,
            'amount' => (int) ($overdueInvoice?->amount ?? 0),
            'formatted_amount' => $overdueInvoice ? 'Rp ' . number_format($overdueInvoice->amount, 0, ',', '.') : null,
            'billing_url' => $billingUrl,
        ];
    }
}
