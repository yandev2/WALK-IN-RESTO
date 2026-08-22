<?php

namespace App\Services;

use App\Enums\SubscriptionStatus;
use App\Models\Restaurant;
use App\Support\SubscriptionGate;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SubscriptionLifecycleService
{
    public function __construct(
        private readonly SubscriptionGate $gate,
    ) {}

    /**
     * @return array{grace: int, expired: int, activated: int}
     */
    public function process(?\DateTimeInterface $now = null): array
    {
        $now = $now ? Carbon::parse($now) : now();
        $graceDays = (int) config('subscription.grace_days', 7);

        $stats = ['grace' => 0, 'expired' => 0, 'activated' => 0];

        Restaurant::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->each(function (Restaurant $restaurant) use ($now, $graceDays, &$stats): void {
                $result = $this->transition($restaurant, $now, $graceDays);

                if ($result !== null) {
                    $stats[$result]++;
                }
            });

        return $stats;
    }

    public function transition(Restaurant $restaurant, ?Carbon $now = null, ?int $graceDays = null): ?string
    {
        $now ??= now();
        $graceDays ??= (int) config('subscription.grace_days', 7);

        return DB::transaction(function () use ($restaurant, $now, $graceDays): ?string {
            /** @var Restaurant $restaurant */
            $restaurant = Restaurant::query()->whereKey($restaurant->id)->lockForUpdate()->firstOrFail();
            $status = $this->gate->status($restaurant);
            $expiry = $this->gate->effectiveExpiryAt($restaurant);

            if ($restaurant->subscribed_until && $restaurant->subscribed_until->gt($now)) {
                if ($status !== SubscriptionStatus::Active) {
                    $restaurant->forceFill([
                        'subscription_status' => SubscriptionStatus::Active,
                        'grace_ends_at' => null,
                    ])->save();

                    return 'activated';
                }

                return null;
            }

            if ($status === SubscriptionStatus::Expired) {
                return null;
            }

            if ($status === SubscriptionStatus::Trial && $restaurant->trial_ends_at && $restaurant->trial_ends_at->lte($now)) {
                $restaurant->forceFill([
                    'subscription_status' => SubscriptionStatus::Grace,
                    'grace_ends_at' => $now->copy()->addDays($graceDays),
                ])->save();

                return 'grace';
            }

            if ($status === SubscriptionStatus::Active && $expiry && $expiry->lte($now)) {
                $restaurant->forceFill([
                    'subscription_status' => SubscriptionStatus::Grace,
                    'grace_ends_at' => $now->copy()->addDays($graceDays),
                ])->save();

                return 'grace';
            }

            if ($status === SubscriptionStatus::Grace && $restaurant->grace_ends_at && $restaurant->grace_ends_at->lte($now)) {
                $restaurant->forceFill([
                    'subscription_status' => SubscriptionStatus::Expired,
                ])->save();

                return 'expired';
            }

            return null;
        });
    }
}
