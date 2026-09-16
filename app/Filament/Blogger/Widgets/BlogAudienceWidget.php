<?php

namespace App\Filament\Blogger\Widgets;

use App\Models\User;
use App\Services\BlogAnalyticsService;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;

class BlogAudienceWidget extends Widget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;

    protected static bool $isLazy = false;

    protected static ?int $sort = 5;

    protected string $view = 'filament.widgets.blog-audience';

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

        $analytics = app(BlogAnalyticsService::class);
        $devices = $analytics->audienceDevices($period, $authorId);
        $locales = $analytics->viewsByLocale($period, $authorId);

        return [
            'period' => $period,
            'devices' => $devices,
            'locales' => $locales,
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
