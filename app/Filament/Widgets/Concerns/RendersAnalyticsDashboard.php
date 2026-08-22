<?php

namespace App\Filament\Widgets\Concerns;

use App\Models\Restaurant;
use App\Support\CmsMedia;
use App\Support\RestaurantTheme;
use Filament\Facades\Filament;

trait RendersAnalyticsDashboard
{
    use InteractsWithRestaurantAnalytics;

    /**
     * @return array{primary: string, primary_dark: string, accent: string, ink: string}
     */
    protected function analyticsTheme(): array
    {
        $restaurant = Filament::getTenant();
        $theme = RestaurantTheme::for($restaurant instanceof Restaurant ? $restaurant : null);

        return [
            ...$theme,
            'ink' => RestaurantTheme::darken($theme['primary'], 0.55),
        ];
    }

    /**
     * @return array{
     *     day_count: int,
     *     range_label: string,
     *     total_omzet: int,
     *     avg_omzet: int,
     *     total_orders: int,
     *     avg_orders: float,
     *     total_omzet_formatted: string,
     *     avg_omzet_formatted: string,
     * }
     */
    protected function periodSummary(): array
    {
        $snapshot = $this->analyticsSnapshot();
        $trend = $snapshot['trend'] ?? [];
        $dayCount = max(1, (int) ($snapshot['day_count'] ?? count($trend) ?: $this->analyticsDayCount()));

        $totalOmzet = (int) array_sum(array_column($trend, 'omzet'));
        $totalOrders = (int) array_sum(array_column($trend, 'count'));

        return [
            'day_count' => $dayCount,
            'range_label' => $this->analyticsRangeLabel(),
            'total_omzet' => $totalOmzet,
            'avg_omzet' => (int) round($totalOmzet / $dayCount),
            'total_orders' => $totalOrders,
            'avg_orders' => round($totalOrders / $dayCount, 1),
            'total_omzet_formatted' => CmsMedia::formatIdr($totalOmzet),
            'avg_omzet_formatted' => CmsMedia::formatIdr((int) round($totalOmzet / $dayCount)),
        ];
    }

    /**
     * @return array{
     *     qris: int,
     *     cash: int,
     *     total: int,
     *     qris_pct: float,
     *     cash_pct: float,
     *     qris_formatted: string,
     *     cash_formatted: string,
     * }
     */
    protected function paymentMixSummary(): array
    {
        $mix = $this->analyticsSnapshot()['payment_mix'] ?? ['qris' => 0, 'cash' => 0, 'total' => 0];
        $total = max(0, (int) ($mix['total'] ?? 0));
        $qris = (int) ($mix['qris'] ?? 0);
        $cash = (int) ($mix['cash'] ?? 0);

        return [
            'qris' => $qris,
            'cash' => $cash,
            'total' => $total,
            'qris_pct' => $total > 0 ? round(($qris / $total) * 100, 1) : 0.0,
            'cash_pct' => $total > 0 ? round(($cash / $total) * 100, 1) : 0.0,
            'qris_formatted' => CmsMedia::formatIdr($qris),
            'cash_formatted' => CmsMedia::formatIdr($cash),
        ];
    }

    /**
     * @return list<array{
     *     label: string,
     *     value: string,
     *     hint: string|null,
     *     hint_tone: string,
     *     icon: string,
     * }>
     */
    protected function todayKpiCards(): array
    {
        $snapshot = $this->analyticsSnapshot();

        if ($snapshot === []) {
            return [
                $this->makeKpiCard(
                    label: 'Omzet hari ini',
                    value: CmsMedia::formatIdr(0),
                    icon: 'revenue',
                ),
            ];
        }

        $today = $snapshot['today'];
        $comparison = $snapshot['comparison'];
        $omzetDelta = $this->formatKpiDelta($comparison['omzet_delta_pct'], 'vs kemarin');
        $countDelta = $this->formatKpiDelta($comparison['count_delta_pct'], 'order vs kemarin');

        return [
            $this->makeKpiCard(
                label: 'Omzet hari ini',
                value: CmsMedia::formatIdr($today['omzet']),
                hint: $omzetDelta['text'],
                hintTone: $omzetDelta['tone'],
                icon: 'revenue',
            ),
            $this->makeKpiCard(
                label: 'Order lunas',
                value: (string) $today['count'],
                hint: $countDelta['text'],
                hintTone: $countDelta['tone'],
                icon: 'orders',
            ),
            $this->makeKpiCard(
                label: 'Rata-rata order',
                value: CmsMedia::formatIdr($comparison['aov_today']),
                hint: 'Kemarin: '.CmsMedia::formatIdr($comparison['aov_yesterday']),
                hintTone: 'neutral',
                icon: 'average',
            ),
            $this->makeKpiCard(
                label: 'QRIS hari ini',
                value: CmsMedia::formatIdr($today['qris']),
                icon: 'qris',
            ),
            $this->makeKpiCard(
                label: 'Tunai hari ini',
                value: CmsMedia::formatIdr($today['cash']),
                icon: 'cash',
            ),
            $this->makeKpiCard(
                label: 'Void / waste',
                value: (string) $today['voided'].' void',
                hint: CmsMedia::formatIdr($today['waste']).' waste',
                hintTone: 'neutral',
                icon: 'void',
            ),
        ];
    }

    /**
     * @return array{text: string, tone: string}
     */
    private function formatKpiDelta(?float $delta, string $suffix): array
    {
        if ($delta === null) {
            return [
                'text' => 'Baru ada penjualan '.$suffix,
                'tone' => 'new',
            ];
        }

        if ($delta == 0.0) {
            return [
                'text' => '0% '.$suffix,
                'tone' => 'neutral',
            ];
        }

        $prefix = $delta > 0 ? '+' : '';

        return [
            'text' => $prefix.$delta.'% '.$suffix,
            'tone' => $delta > 0 ? 'up' : 'down',
        ];
    }

    /**
     * @return array{
     *     label: string,
     *     value: string,
     *     hint: string|null,
     *     hint_tone: string,
     *     icon: string,
     * }
     */
    private function makeKpiCard(
        string $label,
        string $value,
        string $icon,
        ?string $hint = null,
        string $hintTone = 'neutral',
    ): array {
        return [
            'label' => $label,
            'value' => $value,
            'hint' => $hint,
            'hint_tone' => $hintTone,
            'icon' => $icon,
        ];
    }
}
