<?php

namespace App\Filament\Blogger\Widgets;

use App\Models\User;
use App\Services\BlogAnalyticsService;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;

class BlogCategoryDistributionWidget extends Widget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;

    protected static bool $isLazy = false;

    protected static ?int $sort = 4;

    protected string $view = 'filament.widgets.blog-category-distribution';

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'lg' => 1,
    ];

    public function __lazyLoad(): void
    {
    }

    public function getViewData(): array
    {
        $period = (int) ($this->pageFilters['period'] ?? 30);
        $period = in_array($period, [7, 30, 90], true) ? $period : 30;
        $authorId = $this->getAuthorId();

        $categories = app(BlogAnalyticsService::class)->topCategories(5, $period, $authorId);

        return [
            'period' => $period,
            'categories' => $categories,
            'names' => $categories->pluck('name')->all(),
            'views' => $categories->pluck('views')->all(),
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
