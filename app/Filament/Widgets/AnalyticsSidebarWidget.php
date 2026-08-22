<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\RefreshesAnalyticsChart;
use App\Filament\Widgets\Concerns\RendersAnalyticsDashboard;
use Filament\Widgets\Concerns\CanPoll;
use Filament\Widgets\Widget;

class AnalyticsSidebarWidget extends Widget
{
    use CanPoll;
    use RefreshesAnalyticsChart;
    use RendersAnalyticsDashboard;

    protected static bool $isLazy = false;

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'xl' => 4,
    ];

    protected string $view = 'filament.widgets.analytics.sidebar';

    public static function canView(): bool
    {
        return self::canViewAnalytics();
    }

    protected function getPollingInterval(): ?string
    {
        return '30s';
    }

    /**
     * @return array{datasets: list<array<string, mixed>>, labels: list<string>}
     */
    public function getChartData(): array
    {
        $mix = $this->paymentMixSummary();
        $theme = $this->analyticsTheme();

        if ($mix['total'] === 0) {
            return [
                'datasets' => [
                    [
                        'data' => [1, 1],
                        'backgroundColor' => ['#e2e8f0', '#f1f5f9'],
                        'borderWidth' => 0,
                        'hoverOffset' => 0,
                    ],
                ],
                'labels' => ['QRIS', 'Tunai'],
            ];
        }

        return [
            'datasets' => [
                [
                    'data' => [$mix['qris'], $mix['cash']],
                    'backgroundColor' => [$theme['primary'], $theme['ink']],
                    'borderWidth' => 0,
                    'hoverOffset' => 4,
                ],
            ],
            'labels' => ['QRIS', 'Tunai'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getChartOptions(): array
    {
        $mix = $this->paymentMixSummary();

        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
                'tooltip' => [
                    'enabled' => $mix['total'] > 0,
                ],
            ],
            'cutout' => '70%',
            'maintainAspectRatio' => false,
        ];
    }
}
