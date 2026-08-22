<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\RendersAnalyticsDashboard;
use Filament\Widgets\Concerns\CanPoll;
use Filament\Widgets\Widget;

class AnalyticsKpiWidget extends Widget
{
    use CanPoll;
    use RendersAnalyticsDashboard;

    protected static bool $isLazy = false;

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = [
        'default' => 'full',
    ];

    protected string $view = 'filament.widgets.analytics.kpi';

    public static function canView(): bool
    {
        return self::canViewAnalytics();
    }

    protected function getPollingInterval(): ?string
    {
        return '30s';
    }

    /**
     * @return list<array{label: string, value: string, hint: string|null, hint_tone: string, icon: string}>
     */
    public function getStatCards(): array
    {
        return $this->todayKpiCards();
    }
}
