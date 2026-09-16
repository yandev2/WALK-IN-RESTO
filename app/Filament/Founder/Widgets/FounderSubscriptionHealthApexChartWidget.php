<?php

namespace App\Filament\Founder\Widgets;

use App\Services\FounderAnalyticsService;
use Filament\Widgets\Widget;

class FounderSubscriptionHealthApexChartWidget extends Widget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 3;

    protected string $view = 'filament.widgets.founder-subscription-health-apex-chart';

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'xl' => 1,
    ];

    public function __lazyLoad(): void
    {
    }

    public function getViewData(): array
    {
        $service = app(FounderAnalyticsService::class);
        $dist = $service->getSubscriptionDistribution();

        $status = $dist['status'];
        $series = [
            $status['active'],
            $status['trial'],
            $status['grace'],
            $status['expired'],
        ];

        $labels = ['Aktif', 'Trial', 'Grace (Tenggang)', 'Expired (Mangkir)'];
        $total = array_sum($series);

        return [
            'series' => $series,
            'labels' => $labels,
            'totalTenants' => $total,
            'statusCounts' => $status,
            'plans' => $dist['plans'],
            'planLabels' => $dist['planLabels'],
        ];
    }
}
