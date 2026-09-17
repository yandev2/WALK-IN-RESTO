<?php

namespace App\Livewire\Guest\Concerns;

use App\Models\CustomerLoyaltyPoint;
use App\Models\Visit;

trait HasLoyaltyEarnedAlert
{
    public function getUnclaimedLoyaltyPoint(?Visit $visit): ?CustomerLoyaltyPoint
    {
        if (! $visit) {
            return null;
        }

        $orderIds = $visit->orders()->whereIn('status', ['paid', 'in_production', 'completed'])->pluck('id');
        if ($orderIds->isEmpty()) {
            return null;
        }

        $dismissed = session()->get('dismissed_loyalty_order_ids', []);

        return CustomerLoyaltyPoint::withoutRestaurantScope()
            ->whereIn('order_id', $orderIds)
            ->where('type', 'earn')
            ->where('points', '>', 0)
            ->whereNotIn('order_id', $dismissed)
            ->with(['order', 'customer'])
            ->latest('id')
            ->first();
    }

    public function dismissLoyaltyAlert(int $orderId): void
    {
        $dismissed = session()->get('dismissed_loyalty_order_ids', []);
        $dismissed[] = $orderId;
        session()->put('dismissed_loyalty_order_ids', array_values(array_unique($dismissed)));
    }
}
