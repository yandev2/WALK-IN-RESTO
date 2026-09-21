<?php

namespace App\Filament\Founder\Pages;

use App\Filament\Founder\Widgets\FounderOverdueRestaurantsWidget;
use App\Filament\Founder\Widgets\FounderPendingInvoicesWidget;
use App\Filament\Founder\Widgets\FounderRecentTenantsWidget;
use App\Filament\Founder\Widgets\FounderRevenueGrowthApexChartWidget;
use App\Filament\Founder\Widgets\FounderStatsWidget;
use App\Filament\Founder\Widgets\FounderSubscriptionHealthApexChartWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Ringkasan Platform';

    public function getColumns(): int|array
    {
        return [
            'default' => 1,
            'md' => 2,
            'xl' => 3,
        ];
    }

    public function getWidgets(): array
    {
        return [
            FounderStatsWidget::class,
            FounderRevenueGrowthApexChartWidget::class,
            FounderSubscriptionHealthApexChartWidget::class,
            FounderPendingInvoicesWidget::class,
            FounderOverdueRestaurantsWidget::class,
            FounderRecentTenantsWidget::class,
        ];
    }
}
