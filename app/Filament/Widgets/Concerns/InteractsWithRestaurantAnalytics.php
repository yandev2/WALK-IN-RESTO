<?php

namespace App\Filament\Widgets\Concerns;

use App\Models\Restaurant;
use App\Models\User;
use App\Services\RestaurantAnalyticsService;
use App\Support\RestaurantAnalyticsPeriod;
use App\Support\SubscriptionAccess;
use Filament\Facades\Filament;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;

trait InteractsWithRestaurantAnalytics
{
    use InteractsWithPageFilters;

    public static function canViewAnalytics(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        return ($user->isSuperAdmin() || $user->can('analytics.view'))
            && SubscriptionAccess::allows('analytics');
    }

    protected function analyticsDateFrom(): Carbon
    {
        return $this->normalizedAnalyticsDateRange()['from'];
    }

    protected function analyticsDateTo(): Carbon
    {
        return $this->normalizedAnalyticsDateRange()['to'];
    }

    protected function analyticsDayCount(): int
    {
        $range = $this->normalizedAnalyticsDateRange();

        return RestaurantAnalyticsPeriod::dayCount($range['from'], $range['to']);
    }

    protected function analyticsRangeLabel(): string
    {
        $range = $this->normalizedAnalyticsDateRange();

        return RestaurantAnalyticsPeriod::formatRangeLabel($range['from'], $range['to']);
    }

    /**
     * @return array{from: Carbon, to: Carbon}
     */
    protected function normalizedAnalyticsDateRange(): array
    {
        $restaurant = Filament::getTenant();

        if (! $restaurant instanceof Restaurant) {
            $today = Carbon::today();

            return [
                'from' => $today->copy()->subDays(RestaurantAnalyticsPeriod::DEFAULT_SPAN_DAYS - 1),
                'to' => $today->copy(),
            ];
        }

        return RestaurantAnalyticsPeriod::normalizeLocalDateRange(
            $restaurant,
            $this->pageFilters['date_from'] ?? null,
            $this->pageFilters['date_to'] ?? null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    protected function analyticsSnapshot(): array
    {
        $restaurant = Filament::getTenant();

        if (! $restaurant instanceof Restaurant) {
            return [];
        }

        $range = $this->normalizedAnalyticsDateRange();

        return app(RestaurantAnalyticsService::class)->buildSnapshot(
            $restaurant,
            $range['from'],
            $range['to'],
        );
    }
}
