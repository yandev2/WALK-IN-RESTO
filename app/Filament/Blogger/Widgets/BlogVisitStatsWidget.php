<?php

namespace App\Filament\Blogger\Widgets;

use App\Models\User;
use App\Services\BlogAnalyticsService;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;

class BlogVisitStatsWidget extends Widget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;

    protected static bool $isLazy = false;

    protected static ?int $sort = 1;

    protected string $view = 'filament.widgets.blog-visit-stats';

    protected int|string|array $columnSpan = 'full';

    public function __lazyLoad(): void
    {
    }

    public function getViewData(): array
    {
        $analytics = app(BlogAnalyticsService::class);
        $period = $this->period();
        $authorId = $this->getAuthorId();

        return [
            'period' => $period,
            'viewsGrowth' => $analytics->viewsGrowthRate($period, $authorId),
            'visitorsGrowth' => $analytics->visitorsGrowthRate($period, $authorId),
            'progress' => $analytics->contentProgress($authorId),
            'totalViews' => $analytics->totalViews(authorId: $authorId),
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

    private function period(): int
    {
        $period = (int) ($this->pageFilters['period'] ?? 30);

        return in_array($period, [7, 30, 90], true) ? $period : 30;
    }
}
