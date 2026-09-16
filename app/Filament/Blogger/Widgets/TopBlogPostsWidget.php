<?php

namespace App\Filament\Blogger\Widgets;

use App\Models\User;
use App\Services\BlogAnalyticsService;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget as BaseTableWidget;

class TopBlogPostsWidget extends BaseTableWidget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;

    protected static bool $isLazy = false;

    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 'full';

    public function __lazyLoad(): void
    {
    }

    public function table(Table $table): Table
    {
        $period = $this->period();
        $authorId = $this->getAuthorId();

        return $table
            ->extraAttributes(['class' => 'vision-table-card'])
            ->heading('Peringkat Artikel Terpopuler')
            ->description("Artikel dengan pembaca terbanyak dalam {$period} hari terakhir")
            ->records(fn (): array => app(BlogAnalyticsService::class)
                ->topPosts(10, $period, $authorId)
                ->mapWithKeys(fn (array $row): array => [
                    $row['post_id'] => $row,
                ])
                ->all())
            ->columns([
                TextColumn::make('index')
                    ->label('No. ')
                    ->width('sm')
                    ->badge()
                    ->color('primary')
                    ->rowIndex(),

                TextColumn::make('title')
                    ->label('Judul Artikel')
                    ->wrap()
                    ->weight('medium')
                    ->searchable(),

                TextColumn::make('period_views')
                    ->label("Views ({$period} Hari)")
                    ->numeric()
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->alignEnd(),

                TextColumn::make('views_count')
                    ->label('Total Views')
                    ->numeric()
                    ->sortable()
                    ->alignEnd(),

                TextColumn::make('likes_count')
                    ->label('Likes')
                    ->numeric()
                    ->badge()
                    ->color('warning')
                    ->sortable()
                    ->alignEnd(),

                TextColumn::make('engagement')
                    ->label('Tingkat Interaksi')
                    ->getStateUsing(function ($record): string {
                        $views = is_array($record) ? (int) ($record['views_count'] ?? 0) : (int) ($record->views_count ?? 0);
                        $likes = is_array($record) ? (int) ($record['likes_count'] ?? 0) : (int) ($record->likes_count ?? 0);

                        if ($views <= 0) {
                            return '0%';
                        }

                        $rate = round(($likes / $views) * 100, 1);

                        return "{$rate}%";
                    })
                    ->badge()
                    ->color('success')
                    ->alignEnd(),
            ])
            ->defaultSort('period_views', 'desc')
            ->paginated(false)
            ->emptyStateHeading('Belum ada data artikel terbaca')
            ->emptyStateDescription('Data akan otomatis terkumpul saat pembaca mengunjungi artikel blog.');
    }

    protected function getAuthorId(): ?int
    {
        $user = auth()->user();
        if (! $user instanceof User) {
            return null;
        }

        return $user->isPlatformOperator() ? null : (int) $user->id;
    }

    public function period(): int
    {
        $period = (int) ($this->pageFilters['period'] ?? 30);

        return in_array($period, [7, 30, 90], true) ? $period : 30;
    }
}
