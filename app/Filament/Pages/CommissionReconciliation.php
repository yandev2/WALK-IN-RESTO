<?php

namespace App\Filament\Pages;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Restaurant;
use App\Models\User;
use App\Services\CommissionReconciliationService;
use App\Services\DailyOmzetService;
use App\Services\Export\CommissionReconciliationExport;
use App\Support\SubscriptionAccess;
use App\Support\SubscriptionGate;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Components\View as SchemaView;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Url;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CommissionReconciliation extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedScale;

    protected static string|\UnitEnum|null $navigationGroup = 'Laporan';

    protected static ?string $navigationLabel = 'Rekonsiliasi komisi';

    protected static ?string $title = 'Rekonsiliasi Penjualan & Komisi';

    protected static ?string $slug = 'rekonsiliasi-komisi';

    protected static ?int $navigationSort = 2;

    #[Url(as: 'month')]
    public ?string $month = null;

    #[Url(as: 'from')]
    public ?string $dateFrom = null;

    #[Url(as: 'to')]
    public ?string $dateTo = null;

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        $tenant = Filament::getTenant();
        if (! $tenant instanceof Restaurant) {
            return false;
        }

        return ($user->isSuperAdmin() || $user->can('analytics.view') || $user->isRestaurantOwner())
            && app(SubscriptionGate::class)->canAccessPanel($tenant);
    }

    public function mount(): void
    {
        $restaurant = $this->restaurant();
        $timezone = $restaurant?->timezone ?: 'Asia/Jakarta';
        $now = now()->timezone($timezone);

        if (filled($this->month) && preg_match('/^\d{4}-\d{2}$/', (string) $this->month)) {
            $parsed = Carbon::createFromFormat('Y-m', (string) $this->month, $timezone);
            $this->dateFrom = $parsed->copy()->startOfMonth()->toDateString();
            $this->dateTo = $parsed->copy()->endOfMonth()->toDateString();
        } elseif (blank($this->dateFrom) || blank($this->dateTo)) {
            $this->dateFrom = $now->copy()->startOfMonth()->toDateString();
            $this->dateTo = $now->copy()->toDateString();
        }
    }

    public function restaurant(): ?Restaurant
    {
        $tenant = Filament::getTenant();

        return $tenant instanceof Restaurant ? $tenant : null;
    }

    /**
     * @return array<string, mixed>
     */
    public function getSummaryData(): array
    {
        $restaurant = $this->restaurant();

        if (! $restaurant) {
            return [];
        }

        $service = app(CommissionReconciliationService::class);
        $from = Carbon::parse($this->dateFrom ?: now());
        $to = Carbon::parse($this->dateTo ?: now());

        return $service->summaryForPeriod($restaurant, $from, $to);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                SchemaView::make('filament.pages.partials.commission-reconciliation-header')
                    ->viewData(fn (): array => ['summary' => $this->getSummaryData()]),
                EmbeddedTable::make(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        $restaurant = $this->restaurant();
        $timezone = $restaurant?->timezone ?: 'Asia/Jakarta';

        return [
            ActionGroup::make([
                Action::make('thisMonth')
                    ->label('Bulan Ini')
                    ->icon(Heroicon::OutlinedCalendar)
                    ->action(function () use ($timezone) {
                        $now = now()->timezone($timezone);
                        $this->month = null;
                        $this->dateFrom = $now->copy()->startOfMonth()->toDateString();
                        $this->dateTo = $now->copy()->toDateString();
                        $this->resetTable();
                    }),

                Action::make('lastMonth')
                    ->label('Bulan Lalu')
                    ->icon(Heroicon::OutlinedClock)
                    ->action(function () use ($timezone) {
                        $lastMonth = now()->timezone($timezone)->subMonth();
                        $this->month = $lastMonth->format('Y-m');
                        $this->dateFrom = $lastMonth->copy()->startOfMonth()->toDateString();
                        $this->dateTo = $lastMonth->copy()->endOfMonth()->toDateString();
                        $this->resetTable();
                    }),

                Action::make('last7Days')
                    ->label('7 Hari Terakhir')
                    ->icon(Heroicon::OutlinedSparkles)
                    ->action(function () use ($timezone) {
                        $now = now()->timezone($timezone);
                        $this->month = null;
                        $this->dateFrom = $now->copy()->subDays(6)->toDateString();
                        $this->dateTo = $now->copy()->toDateString();
                        $this->resetTable();
                    }),
            ])
                ->label('Periode Cepat')
                ->icon(Heroicon::OutlinedCalendarDays)
                ->color('gray')
                ->button(),

            Action::make('filterRange')
                ->label('Ubah Rentang')
                ->icon(Heroicon::OutlinedAdjustmentsHorizontal)
                ->color('gray')
                ->outlined()
                ->fillForm(fn (): array => [
                    'date_from' => $this->dateFrom,
                    'date_to' => $this->dateTo,
                ])
                ->schema([
                    DatePicker::make('date_from')
                        ->label('Dari Tanggal')
                        ->required()
                        ->native(false)
                        ->default(fn () => $this->dateFrom),
                    DatePicker::make('date_to')
                        ->label('Sampai Tanggal')
                        ->required()
                        ->native(false)
                        ->default(fn () => $this->dateTo),
                ])
                ->action(function (array $data): void {
                    $this->month = null;
                    $this->dateFrom = (string) $data['date_from'];
                    $this->dateTo = (string) $data['date_to'];
                    $this->resetTable();
                }),

            ActionGroup::make([
                Action::make('exportExcel')
                    ->label('Ekspor Excel (.xlsx)')
                    ->icon(Heroicon::OutlinedDocumentArrowDown)
                    ->action(fn (): BinaryFileResponse => $this->exportExcel()),

                Action::make('exportCsv')
                    ->label('Ekspor CSV (.csv)')
                    ->icon(Heroicon::OutlinedTableCells)
                    ->action(fn (): BinaryFileResponse => $this->exportCsv()),
            ])
                ->label('Unduh Laporan')
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->color('primary')
                ->button(),
        ];
    }

    public function exportExcel(): BinaryFileResponse
    {
        $restaurant = $this->restaurant();
        abort_unless($restaurant instanceof Restaurant, 404);

        $from = Carbon::parse($this->dateFrom ?: now());
        $to = Carbon::parse($this->dateTo ?: now());

        return app(CommissionReconciliationExport::class)->downloadExcel($restaurant, $from, $to);
    }

    public function exportCsv(): BinaryFileResponse
    {
        $restaurant = $this->restaurant();
        abort_unless($restaurant instanceof Restaurant, 404);

        $from = Carbon::parse($this->dateFrom ?: now());
        $to = Carbon::parse($this->dateTo ?: now());

        return app(CommissionReconciliationExport::class)->downloadCsv($restaurant, $from, $to);
    }

    public function table(Table $table): Table
    {
        $restaurant = $this->restaurant();
        $timezone = $restaurant?->timezone ?: 'Asia/Jakarta';
        $dailyOmzet = app(DailyOmzetService::class);
        $reconciliationService = app(CommissionReconciliationService::class);

        return $table
            ->extraAttributes(['class' => 'vision-table-card'])
            ->query($this->getTableQuery())
            ->heading('Rincian Transaksi Penjualan & Perhitungan Komisi')
            ->description('Setiap baris mencerminkan pesanan yang masuk dan rincian pemotongan komisi platform secara transparan.')
            ->defaultSort('paid_at', 'desc')
            ->columns([
                TextColumn::make('number')
                    ->label('Pesanan')
                    ->prefix('#')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('paid_at')
                    ->label('Waktu Bayar')
                    ->formatStateUsing(fn ($state) => $state ? Carbon::parse($state)->timezone($timezone)->format('d M Y, H:i') : '—')
                    ->sortable(),

                TextColumn::make('visit.diningTable.code')
                    ->label('Lokasi')
                    ->formatStateUsing(fn ($state) => filled($state) ? 'Meja '.$state : 'Bungkus / Kasir')
                    ->badge()
                    ->icon(fn ($state) => filled($state) ? 'heroicon-m-map-pin' : 'heroicon-m-shopping-bag')
                    ->color('gray'),

                TextColumn::make('payment_method')
                    ->label('Metode')
                    ->badge()
                    ->icon(fn (?string $state): string => match (strtolower((string) $state)) {
                        'cash' => 'heroicon-m-banknotes',
                        'qris' => 'heroicon-m-qr-code',
                        default => 'heroicon-m-credit-card',
                    })
                    ->formatStateUsing(fn (?string $state): string => match (strtolower((string) $state)) {
                        'cash' => 'Tunai',
                        'qris' => 'QRIS',
                        default => strtoupper((string) $state),
                    })
                    ->color(fn (?string $state): string => match (strtolower((string) $state)) {
                        'cash' => 'warning',
                        'qris' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->alignEnd()
                    ->formatStateUsing(fn ($state): string => 'Rp '.number_format((int) $state, 0, ',', '.')),

                TextColumn::make('discount_amount')
                    ->label('Diskon')
                    ->alignEnd()
                    ->formatStateUsing(fn ($state): string => (int) $state > 0 ? '-Rp '.number_format((int) $state, 0, ',', '.') : '—')
                    ->color(fn ($state): ?string => (int) $state > 0 ? 'warning' : null),

                TextColumn::make('void_cut')
                    ->label('Void Cut')
                    ->alignEnd()
                    ->state(function (Order $record): int {
                        return (int) $record->items
                            ->filter(fn (OrderItem $item): bool => $item->void_omzet_policy === 'cut')
                            ->sum(fn (OrderItem $item): int => (int) $item->unit_price * (int) $item->qty);
                    })
                    ->formatStateUsing(fn ($state): string => (int) $state > 0 ? '-Rp '.number_format((int) $state, 0, ',', '.') : '—')
                    ->color(fn ($state): ?string => (int) $state > 0 ? 'danger' : null),

                TextColumn::make('net_sales')
                    ->label('Penjualan Bersih')
                    ->alignEnd()
                    ->weight('bold')
                    ->state(fn (Order $record): int => $dailyOmzet->netMenuOmzet($record))
                    ->formatStateUsing(fn ($state): string => 'Rp '.number_format((int) $state, 0, ',', '.')),

                TextColumn::make('commission_amount')
                    ->label('Komisi Platform')
                    ->alignEnd()
                    ->state(function (Order $record) use ($reconciliationService, $restaurant): int {
                        if (! $restaurant) {
                            return 0;
                        }
                        $detail = $reconciliationService->orderCommissionDetail($record, $restaurant);

                        return $detail['commission_amount'];
                    })
                    ->formatStateUsing(function ($state, Order $record) use ($reconciliationService, $restaurant): string {
                        if (! $restaurant) {
                            return 'Rp 0';
                        }
                        $detail = $reconciliationService->orderCommissionDetail($record, $restaurant);
                        if ($detail['is_exempt']) {
                            return 'Rp 0';
                        }

                        return 'Rp '.number_format((int) $state, 0, ',', '.');
                    })
                    ->description(function (Order $record) use ($reconciliationService, $restaurant): ?string {
                        if (! $restaurant) {
                            return null;
                        }
                        $detail = $reconciliationService->orderCommissionDetail($record, $restaurant);

                        return $detail['is_exempt'] ? $detail['exempt_reason'] : ($detail['commission_rate'].'%');
                    })
                    ->color(fn ($state): ?string => (int) $state > 0 ? 'danger' : 'gray'),

                TextColumn::make('net_resto')
                    ->label('Bersih Resto')
                    ->alignEnd()
                    ->weight('bold')
                    ->color('success')
                    ->state(function (Order $record) use ($reconciliationService, $restaurant): int {
                        if (! $restaurant) {
                            return 0;
                        }
                        $detail = $reconciliationService->orderCommissionDetail($record, $restaurant);

                        return $detail['net_resto'];
                    })
                    ->formatStateUsing(fn ($state): string => 'Rp '.number_format((int) $state, 0, ',', '.')),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->icon(fn (string $state): ?string => match ($state) {
                        Order::STATUS_PAID, Order::STATUS_COMPLETED => 'heroicon-m-check-circle',
                        Order::STATUS_IN_PRODUCTION => 'heroicon-m-clock',
                        Order::STATUS_VOIDED => 'heroicon-m-x-circle',
                        default => null,
                    })
                    ->formatStateUsing(fn (string $state): string => Order::STATUSES[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        Order::STATUS_PAID, Order::STATUS_COMPLETED => 'success',
                        Order::STATUS_IN_PRODUCTION => 'warning',
                        Order::STATUS_VOIDED => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('payment_method')
                    ->label('Metode Bayar')
                    ->options([
                        'cash' => 'Tunai (Kasir)',
                        'qris' => 'QRIS / Digital',
                    ]),

                SelectFilter::make('status')
                    ->label('Status Transaksi')
                    ->options([
                        Order::STATUS_COMPLETED => 'Selesai',
                        Order::STATUS_PAID => 'Lunas',
                        Order::STATUS_IN_PRODUCTION => 'Sedang Dimasak',
                        Order::STATUS_VOIDED => 'Void / Dibatalkan',
                    ]),
            ])
            ->paginated([25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    protected function getTableQuery(): Builder
    {
        $restaurant = $this->restaurant();

        if (! $restaurant) {
            return Order::query()->whereRaw('1 = 0');
        }

        $service = app(CommissionReconciliationService::class);
        $from = Carbon::parse($this->dateFrom ?: now());
        $to = Carbon::parse($this->dateTo ?: now());

        return $service->ordersQuery($restaurant, $from, $to);
    }
}
