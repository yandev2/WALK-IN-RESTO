<?php

namespace App\Filament\Founder\Widgets;

use App\Services\FounderAnalyticsService;
use Filament\Widgets\Widget;

class FounderRevenueGrowthApexChartWidget extends Widget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 2;

    protected string $view = 'filament.widgets.founder-revenue-growth-apex-chart';

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'xl' => 2,
    ];

    public function __lazyLoad(): void
    {
    }

    public function getViewData(): array
    {
        $service = app(FounderAnalyticsService::class);
        $trends = $service->getRevenueTrend(6);

        $totalRev = array_sum($trends['totalRevenue']);
        $totalFlat = array_sum($trends['flatRevenue']);
        $totalComm = array_sum($trends['commissionRevenue']);

        return [
            'labels' => $trends['labels'],
            'flatRevenue' => $trends['flatRevenue'],
            'commissionRevenue' => $trends['commissionRevenue'],
            'totalRevenue' => $trends['totalRevenue'],
            'registrations' => $trends['registrations'],
            'summaryTotalRev' => $totalRev,
            'summaryTotalFlat' => $totalFlat,
            'summaryTotalComm' => $totalComm,
        ];
    }
}
