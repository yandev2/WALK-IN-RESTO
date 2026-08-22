<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\RendersAnalyticsDashboard;
use Filament\Widgets\Concerns\CanPoll;
use Filament\Widgets\Widget;

class AnalyticsPeriodSummaryWidget extends Widget
{
    use CanPoll;
    use RendersAnalyticsDashboard;

    protected static bool $isLazy = false;

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'xl' => 4,
    ];

    protected string $view = 'filament.widgets.analytics.period-summary';

    public static function canView(): bool
    {
        return self::canViewAnalytics();
    }

    protected function getPollingInterval(): ?string
    {
        return '30s';
    }
}
