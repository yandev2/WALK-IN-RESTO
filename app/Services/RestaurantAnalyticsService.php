<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Restaurant;
use App\Support\RestaurantAnalyticsPeriod;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RestaurantAnalyticsService
{
    public function __construct(
        private readonly DailyOmzetService $dailyOmzet,
    ) {}

    /**
     * @return array{
     *     date_from: string,
     *     date_to: string,
     *     day_count: int,
     *     timezone: string,
     *     today: array{omzet: int, count: int, qris: int, cash: int, voided: int, waste: int, date: string},
     *     yesterday: array{omzet: int, count: int, qris: int, cash: int, voided: int, waste: int, date: string},
     *     comparison: array{omzet_delta_pct: float|null, count_delta_pct: float|null, aov_today: int, aov_yesterday: int},
     *     trend: list<array{date: string, omzet: int, count: int}>,
     *     payment_mix: array{qris: int, cash: int, total: int},
     *     top_menu_items: list<array{menu_item_id: int|null, name: string, total_qty: int, revenue: int}>
     * }
     */
    public function buildSnapshot(
        Restaurant $restaurant,
        CarbonInterface $dateFrom,
        CarbonInterface $dateTo,
    ): array {
        $range = RestaurantAnalyticsPeriod::utcRangeForLocalDates($restaurant, $dateFrom, $dateTo);
        $from = $range['from'];
        $to = $range['to'];
        $localToday = RestaurantAnalyticsPeriod::localToday($restaurant);
        $cacheKey = sprintf(
            'analytics:%d:%s:%s:%s',
            $restaurant->id,
            $from->toDateString(),
            $to->toDateString(),
            $localToday->toDateString(),
        );

        return Cache::remember(
            $cacheKey,
            60,
            fn (): array => $this->computeSnapshot($restaurant, $range),
        );
    }

    /**
     * @param  array{start: Carbon, end: Carbon, labels: list<string>, from: Carbon, to: Carbon}  $range
     * @return array{
     *     date_from: string,
     *     date_to: string,
     *     day_count: int,
     *     timezone: string,
     *     today: array{omzet: int, count: int, qris: int, cash: int, voided: int, waste: int, date: string},
     *     yesterday: array{omzet: int, count: int, qris: int, cash: int, voided: int, waste: int, date: string},
     *     comparison: array{omzet_delta_pct: float|null, count_delta_pct: float|null, aov_today: int, aov_yesterday: int},
     *     trend: list<array{date: string, omzet: int, count: int}>,
     *     payment_mix: array{qris: int, cash: int, total: int},
     *     top_menu_items: list<array{menu_item_id: int|null, name: string, total_qty: int, revenue: int}>
     * }
     */
    private function computeSnapshot(Restaurant $restaurant, array $range): array
    {
        $timezone = RestaurantAnalyticsPeriod::timezone($restaurant);
        $today = RestaurantAnalyticsPeriod::localToday($restaurant);
        $yesterday = $today->copy()->subDay();

        $todaySummary = $this->dailyOmzet->forRestaurant($restaurant, $today);
        $yesterdaySummary = $this->dailyOmzet->forRestaurant($restaurant, $yesterday);

        $rangeStart = $range['start'];
        $rangeEnd = $range['end'];
        $labels = $range['labels'];

        $orders = $this->dailyOmzet
            ->paidOrdersQuery($restaurant, $rangeStart, $rangeEnd)
            ->select(['id', 'grand_before', 'paid_at', 'payment_method', 'status'])
            ->with(['items:id,order_id,unit_price,qty,void_omzet_policy,voided_at'])
            ->get();

        $trend = $this->buildDailyTrend($orders, $labels, $timezone);
        $paymentMix = $this->buildPaymentMix($orders);

        return [
            'date_from' => $range['from']->toDateString(),
            'date_to' => $range['to']->toDateString(),
            'day_count' => count($labels),
            'timezone' => $timezone,
            'today' => $todaySummary,
            'yesterday' => $yesterdaySummary,
            'comparison' => $this->buildComparison($todaySummary, $yesterdaySummary),
            'trend' => $trend,
            'payment_mix' => $paymentMix,
            'top_menu_items' => $this->topMenuItems($restaurant, $range['from'], $range['to']),
        ];
    }

    /**
     * @param  array{omzet: int, count: int}  $today
     * @param  array{omzet: int, count: int}  $yesterday
     * @return array{omzet_delta_pct: float|null, count_delta_pct: float|null, aov_today: int, aov_yesterday: int}
     */
    private function buildComparison(array $today, array $yesterday): array
    {
        return [
            'omzet_delta_pct' => $this->deltaPercent($today['omzet'], $yesterday['omzet']),
            'count_delta_pct' => $this->deltaPercent($today['count'], $yesterday['count']),
            'aov_today' => $today['count'] > 0 ? (int) round($today['omzet'] / $today['count']) : 0,
            'aov_yesterday' => $yesterday['count'] > 0 ? (int) round($yesterday['omzet'] / $yesterday['count']) : 0,
        ];
    }

    /**
     * @param  Collection<int, Order>  $orders
     * @param  list<string>  $labels
     * @return list<array{date: string, omzet: int, count: int}>
     */
    private function buildDailyTrend(Collection $orders, array $labels, string $timezone): array
    {
        $buckets = [];

        foreach ($labels as $label) {
            $buckets[$label] = ['date' => $label, 'omzet' => 0, 'count' => 0];
        }

        foreach ($orders as $order) {
            if (! $order->paid_at instanceof CarbonInterface) {
                continue;
            }

            if (! in_array($order->status, Order::ACCEPTED_STATUSES, true)) {
                continue;
            }

            $dateKey = $order->paid_at->copy()->timezone($timezone)->toDateString();

            if (! isset($buckets[$dateKey])) {
                continue;
            }

            $buckets[$dateKey]['omzet'] += $this->dailyOmzet->netOmzet($order);
            $buckets[$dateKey]['count']++;
        }

        return array_values($buckets);
    }

    /**
     * @param  Collection<int, Order>  $orders
     * @return array{qris: int, cash: int, total: int}
     */
    private function buildPaymentMix(Collection $orders): array
    {
        $qris = 0;
        $cash = 0;

        foreach ($orders as $order) {
            if (! in_array($order->status, Order::ACCEPTED_STATUSES, true)) {
                continue;
            }

            $net = $this->dailyOmzet->netOmzet($order);

            if ($order->payment_method === 'qris') {
                $qris += $net;
            }

            if ($order->payment_method === 'cash') {
                $cash += $net;
            }
        }

        return [
            'qris' => $qris,
            'cash' => $cash,
            'total' => $qris + $cash,
        ];
    }

    /**
     * @return list<array{menu_item_id: int|null, name: string, total_qty: int, revenue: int}>
     */
    public function topMenuItems(
        Restaurant $restaurant,
        CarbonInterface $dateFrom,
        CarbonInterface $dateTo,
        int $limit = 10,
    ): array {
        $range = RestaurantAnalyticsPeriod::utcRangeForLocalDates($restaurant, $dateFrom, $dateTo);

        $rows = DB::table('order_items as oi')
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->where('o.restaurant_id', $restaurant->id)
            ->whereNotNull('o.paid_at')
            ->whereIn('o.status', Order::ACCEPTED_STATUSES)
            ->whereBetween('o.paid_at', [$range['start'], $range['end']])
            ->where(function ($query): void {
                $query->whereNull('oi.voided_at')
                    ->orWhere('oi.void_omzet_policy', '!=', 'cut');
            })
            ->groupBy('oi.menu_item_id', 'oi.name_snapshot')
            ->orderByDesc('total_qty')
            ->limit($limit)
            ->selectRaw('oi.menu_item_id as menu_item_id')
            ->selectRaw('oi.name_snapshot as name')
            ->selectRaw('SUM(oi.qty) as total_qty')
            ->selectRaw('SUM(oi.unit_price * oi.qty) as revenue')
            ->get();

        return $rows->map(fn (object $row): array => [
            'menu_item_id' => $row->menu_item_id !== null ? (int) $row->menu_item_id : null,
            'name' => (string) $row->name,
            'total_qty' => (int) $row->total_qty,
            'revenue' => (int) $row->revenue,
        ])->all();
    }

    private function deltaPercent(int $current, int $previous): ?float
    {
        if ($previous === 0) {
            return $current === 0 ? 0.0 : null;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }
}
