<?php

namespace App\Filament\Blogger\Widgets;

use App\Models\User;
use App\Services\BlogAnalyticsService;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget as BaseTableWidget;

class BlogReferrersWidget extends BaseTableWidget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;

    protected static bool $isLazy = false;

    protected static ?int $sort = 7;

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
            ->heading('Sumber Rujukan Trafik (Referrers)')
            ->description("Asal tautan dan kunjungan pembaca dalam {$period} hari terakhir")
            ->records(function () use ($period, $authorId): array {
                $analytics = app(BlogAnalyticsService::class);

                return $analytics
                    ->topReferrers(10, $period, $authorId)
                    ->values()
                    ->mapWithKeys(function (array $row, int $index) use ($analytics): array {
                        $formatted = $analytics->formatReferrer($row['referer']);

                        return [
                            $index + 1 => [
                                'label' => $formatted['label'],
                                'url' => $formatted['url'],
                                'views' => $row['views'],
                            ],
                        ];
                    })
                    ->all();
            })
            ->columns([
                TextColumn::make('index')
                    ->label('No. ')
                    ->width('sm')
                    ->badge()
                    ->color('primary')
                    ->rowIndex(),

                TextColumn::make('label')
                    ->label('Sumber Rujukan (Source)')
                    ->description(function ($record): ?string {
                        $url = is_array($record) ? ($record['url'] ?? '') : ($record->url ?? '');
                        $label = is_array($record) ? ($record['label'] ?? '') : ($record->label ?? '');

                        return filled($url) && $url !== $label ? $url : null;
                    })
                    ->wrap()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('views')
                    ->label('Views')
                    ->numeric()
                    ->sortable()
                    ->alignEnd(),
            ])
            ->defaultSort('views', 'desc')
            ->paginated(false)
            ->emptyStateHeading('Belum ada data rujukan trafik')
            ->emptyStateDescription('Data rujukan akan tercatat saat pengunjung membuka blog dari tautan luar.');
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
