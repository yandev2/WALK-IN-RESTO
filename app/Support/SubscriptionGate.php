<?php

namespace App\Support;

use App\Enums\InvoiceStatus;
use App\Enums\SubscriptionStatus;
use App\Models\Restaurant;
use App\Models\SubscriptionInvoice;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Support\Carbon;

class SubscriptionGate
{
    public function canAccessPanel(Restaurant $restaurant): bool
    {
        if (! $restaurant->is_active) {
            return false;
        }

        $status = $this->status($restaurant);

        return $status->isPanelAccessible();
    }

    public function isReadOnly(Restaurant $restaurant): bool
    {
        return $this->status($restaurant)->isReadOnly();
    }

    public function hasFeature(Restaurant $restaurant, string $feature): bool
    {
        // Landing page & CMS profile is 100% free and always accessible
        if ($feature === 'cms') {
            return true;
        }

        if ($this->status($restaurant) === SubscriptionStatus::Expired) {
            return false;
        }

        // If cashier commission or billing invoice is unpaid/overdue, suspend cashier operations, kds, roles, analytics, and crm
        if ($restaurant->hasOverdueCashierInvoice() || $restaurant->hasUnpaidOverdueInvoice()) {
            if (in_array($feature, ['operations', 'roles', 'settings_full', 'kds', 'analytics', 'crm'], true)) {
                return false;
            }
        }

        $plan = $restaurant->relationLoaded('subscriptionPlan')
            ? $restaurant->subscriptionPlan
            : $restaurant->subscriptionPlan()->first();

        if (! $plan instanceof SubscriptionPlan) {
            $plan = SubscriptionPlan::query()->where('code', $restaurant->plan_code)->first();
        }

        if (! $plan instanceof SubscriptionPlan) {
            return in_array($feature, ['cms', 'menu', 'operations', 'analytics', 'settings', 'settings_full', 'kds', 'crm'], true);
        }

        // Bonus: If restaurant is on commission plan (management_kds), cms & landing page is always granted as a free bonus
        if ($feature === 'cms' && $plan->isCommissionBased()) {
            return true;
        }

        if ($feature === 'roles') {
            return $plan->hasFeature('settings_full');
        }

        if ($feature === 'kds') {
            return $plan->hasFeature('kds') || $plan->hasFeature('operations');
        }

        if ($feature === 'crm') {
            return $plan->hasFeature('crm') || $plan->hasFeature('analytics') || $plan->hasFeature('operations');
        }

        return $plan->hasFeature($feature);
    }

    public function effectiveExpiryAt(Restaurant $restaurant): ?Carbon
    {
        return $restaurant->subscribed_until ?? $restaurant->trial_ends_at;
    }

    public function shouldAutoInvoice(Restaurant $restaurant, ?Carbon $now = null): bool
    {
        $now ??= now();
        $status = $this->status($restaurant);

        if (! in_array($status, [SubscriptionStatus::Trial, SubscriptionStatus::Active], true)) {
            return false;
        }

        $expiry = $this->effectiveExpiryAt($restaurant);

        if (! $expiry instanceof Carbon) {
            return false;
        }

        $leadDays = (int) config('subscription.invoice_lead_days', 7);

        if ($expiry->gt($now->copy()->addDays($leadDays))) {
            return false;
        }

        return ! $this->hasOpenInvoice($restaurant);
    }

    public function hasOpenInvoice(Restaurant $restaurant): bool
    {
        return SubscriptionInvoice::query()
            ->where('restaurant_id', $restaurant->id)
            ->whereIn('status', [InvoiceStatus::Sent->value, InvoiceStatus::AwaitingVerification->value])
            ->exists();
    }

    public function bypasses(User $user): bool
    {
        return $user->isPlatformOperator();
    }

    public function status(Restaurant $restaurant): SubscriptionStatus
    {
        $status = $restaurant->subscription_status;

        if ($status instanceof SubscriptionStatus) {
            return $status;
        }

        return SubscriptionStatus::tryFrom((string) $status) ?? SubscriptionStatus::Active;
    }
}
