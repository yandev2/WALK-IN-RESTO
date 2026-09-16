<?php

namespace App\Filament\Blogger\Pages;

use App\Filament\Blogger\Widgets\BlogAudienceWidget;
use App\Filament\Blogger\Widgets\BlogCategoryDistributionWidget;
use App\Filament\Blogger\Widgets\BlogContentProgressWidget;
use App\Filament\Blogger\Widgets\BlogReferrersWidget;
use App\Filament\Blogger\Widgets\BlogTrafficApexChartWidget;
use App\Filament\Blogger\Widgets\BlogVisitStatsWidget;
use App\Filament\Blogger\Widgets\TopBlogPostsWidget;
use App\Models\User;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Pages\Page;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class BlogAnalytics extends Page
{
    use HasFiltersForm;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static string|UnitEnum|null $navigationGroup = 'Blog';

    protected static ?string $navigationLabel = 'Analytics';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Blog Analytics & Performa';

    protected ?string $subheading = 'Pantau performa, tren pembaca, dan target publikasi blog Anda';

    protected static ?string $slug = 'blog-analytics';

    public static function canAccess(): bool
    {
        $user = auth()->user();
        if (! $user instanceof User) {
            return false;
        }

        return $user->isPlatformOperator() || $user->hasRole('blogger');
    }

    public function mount(): void
    {
        $this->mountHasFilters();
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->extraAttributes(['class' => 'vision-filter-card'])
            ->components([
                Select::make('period')
                    ->label('Filter Rentang Waktu Laporan')
                    ->prefixIcon('heroicon-o-calendar')
                    ->options([
                        7 => '7 Hari Terakhir (Mingguan)',
                        30 => '30 Hari Terakhir (Bulanan)',
                        90 => '90 Hari Terakhir (Triwulan)',
                    ])
                    ->default(30)
                    ->native(false)
                    ->columnSpanFull()
                    ->live(),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                EmbeddedSchema::make('filtersForm'),
            ]);
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return [
            'default' => 1,
            'xl' => 3,
        ];
    }

    public function getFooterWidgetsColumns(): int|array
    {
        return [
            'default' => 1,
            'lg' => 2,
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            BlogVisitStatsWidget::class,
            BlogTrafficApexChartWidget::class,
            BlogContentProgressWidget::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        $widgets = [
            BlogCategoryDistributionWidget::class,
            BlogAudienceWidget::class,
            TopBlogPostsWidget::class,
            BlogReferrersWidget::class,
        ];

        if (auth()->user()?->isPlatformOperator()) {
            $widgets[] = \App\Filament\Blogger\Widgets\TopBloggersWidget::class;
        }

        return $widgets;
    }
}
