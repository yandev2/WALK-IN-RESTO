<?php

namespace App\Filament\Blogger\Widgets;

use App\Models\User;
use App\Services\BlogAnalyticsService;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;

class BlogTrafficApexChartWidget extends Widget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;

    protected static bool $isLazy = false;

    protected static ?int $sort = 2;

    protected string $view = 'filament.widgets.blog-traffic-apex-chart';

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'xl' => 2,
    ];

    public function __lazyLoad(): void
    {
    }

    public function getViewData(): array
    {
        $period = (int) ($this->pageFilters['period'] ?? 30);
        $period = in_array($period, [7, 30, 90], true) ? $period : 30;
        $authorId = $this->getAuthorId();

        $series = app(BlogAnalyticsService::class)->viewsByDayDetailed($period, $authorId);

        return [
            'period' => $period,
            'labels' => $series['labels'],
            'views' => $series['views'],
            'visitors' => $series['visitors'],
            'articles' => $series['articles'],
            'totalViews' => array_sum($series['views']),
            'totalVisitors' => array_sum($series['visitors']),
        ];
    }

    protected function getAuthorId(): ?int
    {
        $user = auth()->user();
        if (! $user instanceof User) {
            return null;
        }

        return $user->isPlatformOperator() ? null : (int) $user->id;
    }
}
