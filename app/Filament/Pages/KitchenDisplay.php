<?php

namespace App\Filament\Pages;

use App\Filament\Support\TableRightClick;
use App\Models\DiningTable;
use App\Models\KdsStation;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\KdsItemService;
use App\Support\SubscriptionAccess;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Resources\Concerns\HasTabs;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\View as SchemaView;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Url;

class KitchenDisplay extends Page implements HasTable
{
    use HasTabs;
    use InteractsWithTable;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedFire;

    protected static string|\UnitEnum|null $navigationGroup = 'Operasional';

    protected static ?string $navigationLabel = 'Dapur';

    protected static ?string $title = 'Layar dapur';

    protected static ?string $slug = 'kds';

    protected static ?int $navigationSort = 0;

    #[Url(as: 'tab')]
    public ?string $activeTab = null;

    public array $announcedOrderIds = [];

    private bool $alertsPolled = false;

    public function mount(): void
    {
        $this->loadDefaultActiveTab();
        $this->announcedOrderIds = $this->alertOrderIds();
    }

    public function rendering(): void
    {
        if ($this->alertsPolled) {
            return;
        }

        $this->alertsPolled = true;
        $this->pollAlerts();
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        return ($user->isSuperAdmin()
            || $user->can('kds.view')
            || $user->can('kds.update_status')
            || $user->can('order.verify_payment'))
            && SubscriptionAccess::allows('operations');
    }

    public static function getNavigationBadge(): ?string
    {
        $count = OrderItem::query()
            ->where('restaurant_id', Filament::getTenant()?->getKey())
            ->whereIn('kds_status', ['queued', 'preparing', 'ready'])
            ->whereHas('order', fn ($query) => $query->whereIn('status', Order::ACCEPTED_STATUSES))
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }

    public function getFooter(): ?View
    {
        return view('filament.pages.partials.kds-beep');
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getTabsContentComponent(),
                SchemaView::make('filament.pages.partials.kds-legend'),
                EmbeddedTable::make(),
            ]);
    }

    public function updatedActiveTab(): void
    {
        $this->resetPage();
    }

    /**
     * @return array<string, Tab>
     */
    public function getTabs(): array
    {
        $tabs = [];

        foreach ($this->stations() as $station) {
            $stationId = $station->id;

            $tabs['station-'.$stationId] = Tab::make($station->name)
                ->badge(fn (): ?string => $this->badgeCount(
                    $this->applyStationScope($this->kdsBaseQuery(), $stationId),
                ))
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query): Builder => $this->applyStationScope($query, $stationId));
        }

        $tabs['ready'] = Tab::make('Siap antar')
            ->badge(fn (): ?string => $this->badgeCount(
                $this->kdsBaseQuery()->where('kds_status', 'ready'),
            ))
            ->badgeColor('success')
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('kds_status', 'ready'));

        return $tabs;
    }

    public function table(Table $table): Table
    {
        $table = $table
            ->modifyQueryUsing($this->modifyQueryWithActiveTab(...))
            ->columns([
                TextColumn::make('order.visit.diningTable.code')
                    ->label('Meja')
                    ->badge()
                    ->placeholder('—'),
                TextColumn::make('order.number')
                    ->label('Pesanan')
                    ->prefix('#')
                    ->searchable(),
                TextColumn::make('name_snapshot')
                    ->label('Item')
                    ->formatStateUsing(fn (OrderItem $record): string => $record->qty.'× '.$record->displayName())
                    ->description(fn (OrderItem $record): ?string => filled($record->notes) ? 'Catatan: '.$record->notes : null)
                    ->wrap()
                    ->searchable(),
                TextColumn::make('kds_status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'queued' => 'Antri',
                        'preparing' => 'Dimasak',
                        'ready' => 'Siap antar',
                        'served' => 'Sudah diantar',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'queued' => 'gray',
                        'preparing' => 'warning',
                        'ready' => 'success',
                        'served' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('timer')
                    ->label('Timer')
                    ->state(fn (OrderItem $record): string => $record->elapsedMinutes().' m')
                    ->badge()
                    ->color(fn (OrderItem $record): string => match ($record->timerBand()) {
                        'green' => 'success',
                        'yellow' => 'warning',
                        default => 'danger',
                    }),
                TextColumn::make('station.name')
                    ->label('Stasiun')
                    ->visible(fn (): bool => ($this->activeTab ?? null) === 'ready'),
            ])
            ->filters([
                SelectFilter::make('table_id')
                    ->label('Meja')
                    ->native(false)
                    ->searchable()
                    ->options(fn (): array => $this->diningTableOptions())
                    ->query(function (Builder $query, array $data): Builder {
                        if (blank($data['value'] ?? null)) {
                            return $query;
                        }

                        return $query->whereHas(
                            'order.visit',
                            fn (Builder $visit): Builder => $visit->where('table_id', $data['value']),
                        );
                    }),
                SelectFilter::make('kds_status')
                    ->label('Status')
                    ->native(false)
                    ->options([
                        'queued' => 'Antri',
                        'preparing' => 'Dimasak',
                        'ready' => 'Siap antar',
                        'served' => 'Sudah diantar',
                    ])
                    ->visible(fn (): bool => ($this->activeTab ?? null) !== 'ready'),
            ], FiltersLayout::AboveContent)
            ->filtersFormColumns(2)
            ->deferFilters(false)
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->searchPlaceholder('Cari nomor pesanan atau nama menu')
            ->recordClasses(fn (OrderItem $record): array => [
                'kds-row',
                'kds-row--'.$record->timerBand(),
            ])
            ->poll('5s')
            ->paginated([25, 50, 100])
            ->defaultPaginationPageOption(25)
            ->columnManager(false)
            ->emptyStateIcon(Heroicon::OutlinedFire)
            ->emptyStateHeading('Antrian kosong')
            ->emptyStateDescription('Item masuk setelah kasir menerima pembayaran.')
            ->recordTitle(fn (OrderItem $record): string => $record->displayName());

        return TableRightClick::apply($table, fn (): array => [
            Action::make('advance')
                ->label(fn (OrderItem $record): string => match ($record->kds_status) {
                    'queued' => 'Mulai masak',
                    'preparing' => 'Tandai siap',
                    'ready' => 'Tandai diantar',
                    default => 'Lanjut',
                })
                ->button()
                ->color(fn (OrderItem $record): string => $record->kds_status === 'ready' ? 'success' : 'primary')
                ->visible(fn (OrderItem $record): bool => in_array($record->kds_status, ['queued', 'preparing', 'ready'], true)
                    && ($record->kds_status === 'ready' ? $this->canMarkServed() : $this->canAdvance()))
                ->action(function (OrderItem $record): void {
                    $this->runKdsAction($record, fn (KdsItemService $kds, OrderItem $item, User $user) => $kds->advance($item, $user));
                }),
            Action::make('revertServed')
                ->label('Kembali ke siap')
                ->button()
                ->color('gray')
                ->outlined()
                ->visible(fn (OrderItem $record): bool => $record->canRevertServed() && $this->canAdvance())
                ->action(function (OrderItem $record): void {
                    $this->runKdsAction($record, fn (KdsItemService $kds, OrderItem $item, User $user) => $kds->revertServed($item, $user));
                }),
        ]);
    }

    public function pollAlerts(): void
    {
        $ids = $this->alertOrderIds();
        $fresh = array_values(array_diff($ids, $this->announcedOrderIds));

        if ($fresh !== []) {
            $this->dispatch('kds-beep');
        }

        $this->announcedOrderIds = array_values(array_unique(array_merge($this->announcedOrderIds, $ids)));
    }

    public function canAdvance(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        return ($user->isSuperAdmin() || $user->can('kds.update_status'))
            && SubscriptionAccess::allows('operations', mutate: true);
    }

    public function canMarkServed(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        return $this->canAdvance() || $user->can('order.verify_payment');
    }

    /**
     * @return Collection<int, KdsStation>
     */
    public function stations(): Collection
    {
        return KdsStation::query()
            ->where('restaurant_id', Filament::getTenant()?->getKey())
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * @return list<int>
     */
    public function alertOrderIds(): array
    {
        return $this->kdsBaseQuery()
            ->where('kds_status', 'queued')
            ->pluck('order_id')
            ->unique()
            ->values()
            ->all();
    }

    protected function getTableQuery(): Builder
    {
        return $this->kdsBaseQuery()
            ->with(['order.visit.diningTable', 'station', 'modifiers'])
            ->orderByRaw("case kds_status when 'queued' then 1 when 'preparing' then 2 when 'ready' then 3 else 4 end")
            ->orderBy('id');
    }

    private function kdsBaseQuery(): Builder
    {
        return OrderItem::query()
            ->where('restaurant_id', Filament::getTenant()?->getKey())
            ->whereHas('order', fn (Builder $order) => $order->whereIn('status', Order::ACCEPTED_STATUSES));
    }

    /**
     * @return array<int, string>
     */
    private function diningTableOptions(): array
    {
        return DiningTable::query()
            ->where('restaurant_id', Filament::getTenant()?->getKey())
            ->orderBy('code')
            ->pluck('code', 'id')
            ->all();
    }

    private function applyStationScope(Builder $query, int $stationId): Builder
    {
        return $query
            ->where('station_id', $stationId)
            ->where(function (Builder $status): void {
                $status->whereIn('kds_status', ['queued', 'preparing', 'ready'])
                    ->orWhere(function (Builder $served): void {
                        $served->where('kds_status', 'served')
                            ->where('served_at', '>=', now()->subMinutes(15));
                    });
            });
    }

    private function badgeCount(Builder $query): ?string
    {
        $count = $query->count();

        return $count > 0 ? (string) $count : null;
    }

    /**
     * @param  callable(KdsItemService, OrderItem, User): void  $callback
     */
    private function runKdsAction(OrderItem $record, callable $callback): void
    {
        abort_unless($record->restaurant_id === Filament::getTenant()?->getKey(), 403);

        if ($record->kds_status === 'ready') {
            abort_unless($this->canMarkServed(), 403);
        } else {
            abort_unless($this->canAdvance(), 403);
        }

        $user = auth()->user();

        if (! $user instanceof User) {
            abort(403);
        }

        try {
            $callback(app(KdsItemService::class), $record, $user);
        } catch (ValidationException $e) {
            Notification::make()
                ->title(collect($e->errors())->flatten()->first() ?: 'Tidak bisa mengubah status')
                ->danger()
                ->send();
        }
    }
}
