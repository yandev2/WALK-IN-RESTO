<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Restaurant;
use App\Support\RestaurantAnalyticsPeriod;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class DailyOmzetService
{
    /**
     * @return array{omzet: int, count: int, qris: int, cash: int, voided: int, waste: int, timezone: string, date: string}
     */
    public function forRestaurant(Restaurant $restaurant, ?CarbonInterface $at = null): array
    {
        $timezone = $restaurant->timezone ?: 'Asia/Jakarta';
        $day = Carbon::parse($at ?? now())->timezone($timezone);
        [$start, $end] = [$day->copy()->startOfDay()->utc(), $day->copy()->endOfDay()->utc()];

        $orders = $this->paidOrdersQuery($restaurant, $start, $end)
            ->with('items')
            ->get();

        $omzet = 0;
        $qris = 0;
        $cash = 0;
        $waste = 0;

        foreach ($orders as $order) {
            $net = $this->netOmzet($order);
            $omzet += $net;
            $waste += $this->wasteAmount($order);

            if ($order->payment_method === 'qris') {
                $qris += $net;
            }

            if ($order->payment_method === 'cash') {
                $cash += $net;
            }
        }

        return [
            'omzet' => $omzet,
            'count' => $orders->whereIn('status', Order::ACCEPTED_STATUSES)->count(),
            'qris' => $qris,
            'cash' => $cash,
            'voided' => (int) Order::query()
                ->where('restaurant_id', $restaurant->id)
                ->where('status', 'voided')
                ->whereBetween('voided_at', [$start, $end])
                ->count(),
            'waste' => $waste,
            'timezone' => $timezone,
            'date' => $day->toDateString(),
        ];
    }

    /**
     * @return Builder<Order>
     */
    public function paidOrdersQuery(Restaurant $restaurant, CarbonInterface $start, CarbonInterface $end): Builder
    {
        return Order::query()
            ->where('restaurant_id', $restaurant->id)
            ->whereNotNull('paid_at')
            ->whereIn('status', [...Order::ACCEPTED_STATUSES, 'voided'])
            ->whereBetween('paid_at', [$start, $end]);
    }

    public function csv(Restaurant $restaurant, ?CarbonInterface $at = null): string
    {
        $summary = $this->forRestaurant($restaurant, $at);
        $handle = fopen('php://temp', 'r+');

        fputcsv($handle, ['tanggal', 'zona_waktu', 'omzet', 'jumlah_order', 'qris', 'tunai', 'void', 'waste']);
        fputcsv($handle, [
            $summary['date'],
            $summary['timezone'],
            $summary['omzet'],
            $summary['count'],
            $summary['qris'],
            $summary['cash'],
            $summary['voided'],
            $summary['waste'],
        ]);

        rewind($handle);
        $csv = stream_get_contents($handle) ?: '';
        fclose($handle);

        return $csv;
    }

    public function csvForRange(Restaurant $restaurant, CarbonInterface $from, CarbonInterface $to): string
    {
        $normalized = RestaurantAnalyticsPeriod::normalizeLocalDateRange(
            $restaurant,
            Carbon::parse($from)->toDateString(),
            Carbon::parse($to)->toDateString(),
        );

        $handle = fopen('php://temp', 'r+');

        fputcsv($handle, ['tanggal', 'zona_waktu', 'omzet', 'jumlah_order', 'qris', 'tunai', 'void', 'waste']);

        for ($cursor = $normalized['from']->copy(); $cursor->lte($normalized['to']); $cursor->addDay()) {
            $summary = $this->forRestaurant($restaurant, $cursor);

            fputcsv($handle, [
                $summary['date'],
                $summary['timezone'],
                $summary['omzet'],
                $summary['count'],
                $summary['qris'],
                $summary['cash'],
                $summary['voided'],
                $summary['waste'],
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle) ?: '';
        fclose($handle);

        return $csv;
    }

    public function netOmzet(Order $order): int
    {
        $items = $order->items;
        $cut = $items
            ->filter(fn (OrderItem $item): bool => $item->void_omzet_policy === 'cut')
            ->sum(fn (OrderItem $item): int => (int) $item->unit_price * (int) $item->qty);

        if ($order->status === 'voided' && $items->isNotEmpty() && $items->every(
            fn (OrderItem $item): bool => $item->void_omzet_policy === 'cut',
        )) {
            return 0;
        }

        return max(0, (int) $order->grand_before - (int) $cut);
    }

    public function netMenuOmzet(Order $order): int
    {
        $items = $order->items;
        $cut = $items
            ->filter(fn (OrderItem $item): bool => $item->void_omzet_policy === 'cut')
            ->sum(fn (OrderItem $item): int => (int) $item->unit_price * (int) $item->qty);

        if ($order->status === 'voided' && $items->isNotEmpty() && $items->every(
            fn (OrderItem $item): bool => $item->void_omzet_policy === 'cut',
        )) {
            return 0;
        }

        $subtotalNet = max(0, (int) $order->subtotal - (int) $order->discount_amount);

        return max(0, $subtotalNet - (int) $cut);
    }

    public function wasteAmount(Order $order): int
    {
        return (int) $order->items
            ->filter(fn (OrderItem $item): bool => $item->void_omzet_policy === 'waste')
            ->sum(fn (OrderItem $item): int => (int) $item->unit_price * (int) $item->qty);
    }
}
