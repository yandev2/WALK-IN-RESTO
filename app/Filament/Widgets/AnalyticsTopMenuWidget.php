<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\RendersAnalyticsDashboard;
use App\Support\CmsMedia;
use Filament\Widgets\Concerns\CanPoll;
use Filament\Widgets\Widget;

class AnalyticsTopMenuWidget extends Widget
{
    use CanPoll;
    use RendersAnalyticsDashboard;

    protected static bool $isLazy = false;

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.widgets.analytics.top-menu';

    public static function canView(): bool
    {
        return self::canViewAnalytics();
    }

    protected function getPollingInterval(): ?string
    {
        return '30s';
    }

    /**
     * @return list<array{rank: int, name: string, total_qty: int, revenue: int, revenue_formatted: string}>
     */
    public function getMenuRows(): array
    {
        $items = $this->analyticsSnapshot()['top_menu_items'] ?? [];

        return collect($items)
            ->values()
            ->map(fn (array $item, int $index): array => [
                'rank' => $index + 1,
                'name' => (string) ($item['name'] ?? '-'),
                'total_qty' => (int) ($item['total_qty'] ?? 0),
                'revenue' => (int) ($item['revenue'] ?? 0),
                'revenue_formatted' => CmsMedia::formatIdr((int) ($item['revenue'] ?? 0)),
            ])
            ->all();
    }
}
