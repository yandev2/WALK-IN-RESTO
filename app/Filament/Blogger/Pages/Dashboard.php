<?php

namespace App\Filament\Blogger\Pages;

use App\Filament\Blogger\Widgets\BlogContentProgressWidget;
use App\Filament\Blogger\Widgets\BlogTrafficApexChartWidget;
use App\Filament\Blogger\Widgets\BlogVisitStatsWidget;
use App\Filament\Blogger\Widgets\TopBloggersWidget;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    protected static ?string $title = 'Dashboard Blog';

    public function getColumns(): int|array
    {
        return [
            'default' => 1,
            'xl' => 3,
        ];
    }

    public function getWidgets(): array
    {
        $widgets = [
            BlogVisitStatsWidget::class,
            BlogTrafficApexChartWidget::class,
            BlogContentProgressWidget::class,
        ];

        if (auth()->user()?->isPlatformOperator()) {
            $widgets[] = TopBloggersWidget::class;
        }

        return $widgets;
    }
}
