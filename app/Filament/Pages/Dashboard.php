<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AnalyticsKpiWidget;
use App\Filament\Widgets\AnalyticsPeriodSummaryWidget;
use App\Filament\Widgets\AnalyticsRevenueBarWidget;
use App\Filament\Widgets\AnalyticsSidebarWidget;
use App\Filament\Widgets\AnalyticsTopMenuWidget;
use App\Filament\Widgets\PendingPaymentsWidget;
use App\Filament\Widgets\RestaurantReadinessWidget;
use App\Filament\Widgets\WelcomeBannerWidget;
use App\Models\ExportFile;
use App\Models\Restaurant;
use App\Models\User;
use App\Services\Export\ReportExportDispatcher;
use App\Support\PermissionTeam;
use App\Support\RestaurantAnalyticsPeriod;
use App\Support\SubscriptionAccess;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Size;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        PermissionTeam::syncFromTenant(user: $user);

        return $user->can('analytics.view')
            || $user->can('order.verify_payment')
            || $user->can('order.reject_payment')
            || $user->can('cms.manage')
            || $user->can('settings.manage')
            || $user->can('audit.view')
            || $user->can('menu.manage');
    }

    public function getHeaderWidgets(): array
    {
        return [
            WelcomeBannerWidget::class,
        ];
    }

    public function getWidgets(): array
    {
        return [
            AnalyticsKpiWidget::class,
            RestaurantReadinessWidget::class,
            PendingPaymentsWidget::class,
            AnalyticsRevenueBarWidget::class,
            AnalyticsTopMenuWidget::class,
            AnalyticsPeriodSummaryWidget::class,
        ];
    }

    public function getColumns(): int|array
    {
        return [
            'default' => 1,
            'md' => 6,
            'xl' => 12,
        ];
    }

    public function getFiltersForm(): Schema
    {
        if ((! $this->isCachingSchemas) && $this->hasCachedSchema('filtersForm')) {
            return $this->getSchema('filtersForm');
        }

        $schema = $this->makeSchema()
            ->columns(1)
            ->extraAttributes([
                'wire:partial' => 'table-filters-form',
                'class' => 'fi-dashboard-analytics-toolbar',
            ])
            ->live()
            ->statePath('filters');

        return $this->filtersForm($schema);
    }

    public function filtersForm(Schema $schema): Schema
    {
        if (! $this->canViewAnalyticsFilters()) {
            return $schema->components([]);
        }

        return $schema
            ->components([
                Flex::make([
                    DatePicker::make('date_from')
                        ->label('Dari')
                        ->native(false)
                        ->format('Y-m-d')
                        ->displayFormat('d M Y')
                        ->live()
                        ->maxWidth(Width::Medium)
                        ->default(fn (): string => $this->defaultAnalyticsDateRange()['from']->toDateString())
                        ->maxDate(fn () => RestaurantAnalyticsPeriod::localToday($this->restaurantOrAbort())->endOfDay()),
                    DatePicker::make('date_to')
                        ->label('Sampai')
                        ->native(false)
                        ->format('Y-m-d')
                        ->displayFormat('d M Y')
                        ->live()
                        ->maxWidth(Width::Medium)
                        ->default(fn (): string => $this->defaultAnalyticsDateRange()['to']->toDateString())
                        ->maxDate(fn () => RestaurantAnalyticsPeriod::localToday($this->restaurantOrAbort())->endOfDay())
                        ->afterOrEqual('date_from'),
                    Actions::make([
                        Action::make('downloadOmzetCsv')
                            ->label('Ekspor omzet')
                            ->button()
                            ->size(Size::Small)
                            ->icon(Heroicon::OutlinedArrowDownTray)
                            ->extraAttributes(['class' => 'whitespace-nowrap'])
                            ->action(function (): void {
                                $restaurant = $this->restaurantOrAbort();
                                $user = auth()->user();
                                abort_unless($user instanceof User, 403);

                                try {
                                    app(ReportExportDispatcher::class)->dispatch(
                                        $restaurant,
                                        $user,
                                        ExportFile::MODULE_OMZET_HARIAN,
                                        ExportFile::FORMAT_EXCEL,
                                        [
                                            'date_from' => $this->filters['date_from'] ?? null,
                                            'date_to' => $this->filters['date_to'] ?? null,
                                        ],
                                    );
                                } catch (ValidationException $exception) {
                                    Notification::make()
                                        ->title('Tidak bisa mengekspor')
                                        ->body(collect($exception->errors())->flatten()->first() ?: 'Validasi gagal.')
                                        ->danger()
                                        ->send();

                                    return;
                                }

                                Notification::make()
                                    ->title('Ekspor omzet diantrikan')
                                    ->body('Unduh dari menu Laporan → Riwayat ekspor setelah selesai.')
                                    ->success()
                                    ->actions([
                                        Action::make('openReports')
                                            ->label('Buat / riwayat')
                                            ->url(GenerateReport::getUrl()),
                                    ])
                                    ->send();
                            }),
                    ])
                        ->grow()
                        ->alignEnd()
                        ->verticallyAlignEnd(),
                ])
                    ->from('md')
                    ->dense()
                    ->verticallyAlignEnd()
                    ->columnSpanFull(),
            ]);
    }

    protected function canViewAnalyticsFilters(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        return ($user->isSuperAdmin() || $user->can('analytics.view'))
            && SubscriptionAccess::allows('analytics');
    }

    /**
     * @return array{from: Carbon, to: Carbon}
     */
    protected function defaultAnalyticsDateRange(): array
    {
        $restaurant = Filament::getTenant();

        if (! $restaurant instanceof Restaurant) {
            return RestaurantAnalyticsPeriod::defaultLocalDateRange(
                new Restaurant(['timezone' => 'Asia/Jakarta']),
            );
        }

        return RestaurantAnalyticsPeriod::defaultLocalDateRange($restaurant);
    }

    protected function restaurantOrAbort(): Restaurant
    {
        $restaurant = Filament::getTenant();

        abort_unless($restaurant instanceof Restaurant, 404);

        return $restaurant;
    }
}
