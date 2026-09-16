<?php

namespace App\Filament\Blogger\Widgets;

use App\Models\User;
use App\Services\BlogAnalyticsService;
use Filament\Widgets\Widget;

class BlogContentProgressWidget extends Widget
{
    protected static bool $isDiscovered = false;

    protected static bool $isLazy = false;

    protected static ?int $sort = 3;

    protected string $view = 'filament.widgets.blog-content-progress';

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'xl' => 1,
    ];

    public function __lazyLoad(): void
    {
    }

    public function getViewData(): array
    {
        $authorId = $this->getAuthorId();
        $progress = app(BlogAnalyticsService::class)->contentProgress($authorId);

        return [
            'progress' => $progress,
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
