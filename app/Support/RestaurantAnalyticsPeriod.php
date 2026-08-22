<?php

namespace App\Support;

use App\Models\Restaurant;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

final class RestaurantAnalyticsPeriod
{
    public const MAX_SPAN_DAYS = 90;

    public const DEFAULT_SPAN_DAYS = 7;

    public static function timezone(Restaurant $restaurant): string
    {
        return $restaurant->timezone ?: 'Asia/Jakarta';
    }

    public static function localToday(Restaurant $restaurant, ?CarbonInterface $at = null): Carbon
    {
        return Carbon::parse($at ?? now())->timezone(self::timezone($restaurant));
    }

    /**
     * @return array{from: Carbon, to: Carbon}
     */
    public static function defaultLocalDateRange(Restaurant $restaurant): array
    {
        $to = self::localToday($restaurant)->startOfDay();

        return [
            'from' => $to->copy()->subDays(self::DEFAULT_SPAN_DAYS - 1),
            'to' => $to,
        ];
    }

    /**
     * @return array{from: Carbon, to: Carbon}
     */
    public static function normalizeLocalDateRange(
        Restaurant $restaurant,
        ?string $dateFrom,
        ?string $dateTo,
    ): array {
        $timezone = self::timezone($restaurant);
        $today = self::localToday($restaurant)->startOfDay();
        $defaults = self::defaultLocalDateRange($restaurant);

        $to = filled($dateTo)
            ? Carbon::parse($dateTo, $timezone)->startOfDay()
            : $defaults['to']->copy();

        $from = filled($dateFrom)
            ? Carbon::parse($dateFrom, $timezone)->startOfDay()
            : $defaults['from']->copy();

        if ($to->gt($today)) {
            $to = $today->copy();
        }

        if ($from->gt($to)) {
            [$from, $to] = [$to->copy(), $from->copy()];
        }

        if ($from->diffInDays($to) + 1 > self::MAX_SPAN_DAYS) {
            $from = $to->copy()->subDays(self::MAX_SPAN_DAYS - 1);
        }

        return [
            'from' => $from,
            'to' => $to,
        ];
    }

    public static function dayCount(CarbonInterface $from, CarbonInterface $to): int
    {
        return (int) Carbon::parse($from)->startOfDay()->diffInDays(Carbon::parse($to)->startOfDay()) + 1;
    }

    public static function formatRangeLabel(CarbonInterface $from, CarbonInterface $to): string
    {
        $from = Carbon::parse($from)->startOfDay();
        $to = Carbon::parse($to)->startOfDay();

        if ($from->isSameDay($to)) {
            return $from->format('j M Y');
        }

        if ($from->year === $to->year) {
            return $from->format('j M').' – '.$to->format('j M Y');
        }

        return $from->format('j M Y').' – '.$to->format('j M Y');
    }

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    public static function utcRangeForLocalDay(Restaurant $restaurant, CarbonInterface $day): array
    {
        $local = Carbon::parse($day)->timezone(self::timezone($restaurant));

        return [
            $local->copy()->startOfDay()->utc(),
            $local->copy()->endOfDay()->utc(),
        ];
    }

    /**
     * @return array{start: Carbon, end: Carbon, labels: list<string>}
     */
    public static function utcRangeForLocalDays(Restaurant $restaurant, int $days, ?CarbonInterface $at = null): array
    {
        $endDay = self::localToday($restaurant, $at);
        $startDay = $endDay->copy()->subDays(max(0, $days - 1));

        return self::utcRangeForLocalDates($restaurant, $startDay, $endDay);
    }

    /**
     * @return array{start: Carbon, end: Carbon, labels: list<string>, from: Carbon, to: Carbon}
     */
    public static function utcRangeForLocalDates(
        Restaurant $restaurant,
        CarbonInterface $startDay,
        CarbonInterface $endDay,
    ): array {
        $normalized = self::normalizeLocalDateRange(
            $restaurant,
            Carbon::parse($startDay)->toDateString(),
            Carbon::parse($endDay)->toDateString(),
        );

        $from = $normalized['from'];
        $to = $normalized['to'];
        $labels = [];

        for ($cursor = $from->copy(); $cursor->lte($to); $cursor->addDay()) {
            $labels[] = $cursor->toDateString();
        }

        return [
            'start' => $from->copy()->startOfDay()->utc(),
            'end' => $to->copy()->endOfDay()->utc(),
            'labels' => $labels,
            'from' => $from,
            'to' => $to,
        ];
    }
}
