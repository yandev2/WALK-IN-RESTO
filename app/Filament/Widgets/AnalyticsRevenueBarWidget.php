<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\RefreshesAnalyticsChart;
use App\Filament\Widgets\Concerns\RendersAnalyticsDashboard;
use Filament\Widgets\Concerns\CanPoll;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class AnalyticsRevenueBarWidget extends Widget
{
    use CanPoll;
    use RefreshesAnalyticsChart;
    use RendersAnalyticsDashboard;

    protected static bool $isLazy = false;

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = [
        'default' => 'full',
    ];

    protected string $view = 'filament.widgets.analytics.revenue-bar';

    public static function canView(): bool
    {
        return self::canViewAnalytics();
    }

    protected function getPollingInterval(): ?string
    {
        return '30s';
    }

    /**
     * @return array{
     *     series: list<int>,
     *     categories: list<string>,
     *     today_omzet: string,
     *     seven_days_omzet: string,
     *     total_omzet: string,
     *     range_label: string,
     * }
     */
    public function getVisionAreaData(): array
    {
        $snapshot = $this->analyticsSnapshot();
        $trend = $snapshot['trend'] ?? [];
        $values = array_column($trend, 'omzet');
        $dates = array_column($trend, 'date');

        $categories = [];
        foreach ($dates as $date) {
            $categories[] = Carbon::parse($date)->translatedFormat('d M');
        }

        $totalOmzet = (int) array_sum($values);
        $sevenDays = (int) array_sum(array_slice($values, -7));
        $todayOmzet = (int) ($snapshot['today']['omzet'] ?? 0);

        return [
            'series' => array_map('intval', $values),
            'categories' => $categories,
            'today_omzet' => \App\Support\CmsMedia::formatIdr($todayOmzet),
            'seven_days_omzet' => \App\Support\CmsMedia::formatIdr($sevenDays),
            'total_omzet' => \App\Support\CmsMedia::formatIdr($totalOmzet),
            'range_label' => $this->analyticsRangeLabel(),
        ];
    }

    /**
     * @return array{datasets: list<array<string, mixed>>, labels: list<string>}
     */
    public function getChartData(): array
    {
        $snapshot = $this->analyticsSnapshot();
        $trend = $snapshot['trend'] ?? [];
        $theme = $this->analyticsTheme();
        $values = array_column($trend, 'omzet');
        $dates = array_column($trend, 'date');
        $dayCount = max(1, count($dates));

        $background = [];
        foreach (array_keys($values) as $index) {
            $background[] = $index % 2 === 0 ? $theme['primary'] : $theme['ink'];
        }

        $maxTicks = match (true) {
            $dayCount <= 7 => $dayCount,
            $dayCount <= 14 => 6,
            $dayCount <= 21 => 7,
            $dayCount <= 40 => 8,
            default => 9,
        };

        $visible = array_fill(0, $dayCount, false);

        if ($dayCount === 1) {
            $visible[0] = true;
        } else {
            for ($tick = 0; $tick < $maxTicks; $tick++) {
                $index = (int) round($tick * ($dayCount - 1) / max(1, $maxTicks - 1));
                $visible[$index] = true;
            }
        }

        $labels = [];
        foreach ($dates as $index => $date) {
            if (! $visible[$index]) {
                $labels[] = '';

                continue;
            }

            $carbon = Carbon::parse($date);
            $labels[] = $dayCount <= 10
                ? $carbon->translatedFormat('j M')
                : $carbon->format('j/n');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Omzet',
                    'data' => $values,
                    'backgroundColor' => $background,
                    'borderRadius' => 8,
                    'borderSkipped' => false,
                    'maxBarThickness' => 36,
                    'categoryPercentage' => 0.72,
                    'barPercentage' => 0.86,
                ],
            ],
            'labels' => $labels,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getChartOptions(): array
    {
        $values = array_column($this->analyticsSnapshot()['trend'] ?? [], 'omzet');
        $peak = max($values === [] ? [0] : $values);

        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'layout' => [
                'autoPadding' => true,
                'padding' => [
                    'top' => 8,
                    'right' => 12,
                    'bottom' => 4,
                    'left' => 4,
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'suggestedMax' => $peak > 0 ? null : 100000,
                    'ticks' => [
                        'display' => true,
                        'maxTicksLimit' => 5,
                        'padding' => 8,
                        'color' => 'rgb(100, 116, 139)',
                        'font' => [
                            'size' => 11,
                        ],
                    ],
                    'grid' => [
                        'drawBorder' => false,
                        'drawTicks' => false,
                        'color' => 'rgba(148, 163, 184, 0.18)',
                    ],
                    'border' => [
                        'display' => false,
                    ],
                ],
                'x' => [
                    'type' => 'category',
                    'offset' => true,
                    'ticks' => [
                        'display' => true,
                        'autoSkip' => false,
                        'maxRotation' => 0,
                        'minRotation' => 0,
                        'padding' => 8,
                        'align' => 'center',
                        'color' => 'rgb(100, 116, 139)',
                        'font' => [
                            'size' => 10,
                            'weight' => '500',
                        ],
                    ],
                    'grid' => [
                        'display' => false,
                        'drawBorder' => false,
                        'drawTicks' => false,
                    ],
                    'border' => [
                        'display' => false,
                    ],
                ],
            ],
        ];
    }
}
