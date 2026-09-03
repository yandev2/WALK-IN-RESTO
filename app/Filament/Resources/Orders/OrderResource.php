<?php

namespace App\Filament\Resources\Orders;

use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\Orders\Pages\ViewOrder;
use App\Filament\Resources\Orders\Widgets\OrderTodayStatsWidget;
use App\Filament\Support\TableRightClick;
use App\Models\Order;
use App\Models\User;
use App\Support\SubscriptionAccess;
use BackedEnum;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\ImageEntry;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static string|UnitEnum|null $navigationGroup = 'Operasional';

    protected static ?string $navigationLabel = 'Pesanan';

    protected static ?string $pluralModelLabel = 'pesanan';

    protected static ?string $recordTitleAttribute = 'number';

    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        if ($user->isPlatformOperator()) {
            return true;
        }

        return ($user->can('order.verify_payment')
            || $user->can('order.reject_payment')
            || $user->can('order.create')
            || $user->can('order.void'))
            && SubscriptionAccess::allows('operations');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canView(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pesanan')
                    ->columns([
                        'default' => 1,
                        'sm' => 1,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 4,
                        '2xl' => 4,
                    ])
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('number')->label('Nomor')->badge()->color('success'),
                        TextEntry::make('visit.diningTable.code')->label('Meja')->badge()->color('success'),
                        TextEntry::make('status')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'awaiting_cashier' => 'Menunggu kasir',
                                'paid' => 'Lunas',
                                'in_production' => 'Sedang dimasak',
                                'completed' => 'Selesai',
                                'pending_payment' => 'Pending bayar',
                                'rejected' => 'Ditolak',
                                'cancelled' => 'Batal',
                                'voided' => 'Void',
                                default => ucfirst(str_replace('_', ' ', $state)),
                            })
                            ->color(fn (string $state): string => match ($state) {
                                'awaiting_cashier' => 'warning',
                                'paid' => 'info',
                                'in_production' => 'primary',
                                'completed' => 'success',
                                'rejected', 'cancelled', 'voided' => 'danger',
                                default => 'gray',
                            }),
                        TextEntry::make('source')->label('Sumber'),
                        TextEntry::make('visit.customer_name')->label('Nama tamu')->placeholder('-'),
                        TextEntry::make('visit.customer_wa')->label('WhatsApp tamu'),
                        TextEntry::make('created_at')->label('Dibuat')->dateTime('d M Y H:i'),
                    ]),
                Section::make('Tagihan')
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('subtotal')->money('IDR', locale: 'id')->badge()->color('success'),
                        TextEntry::make('service_amount')->label('Service')->money('IDR', locale: 'id')->badge()->color('success'),
                        TextEntry::make('pb1_amount')->label('PB1')->money('IDR', locale: 'id')->badge()->color('success'),
                        TextEntry::make('grand_before')->label('Omzet (grand before)')->money('IDR', locale: 'id')->badge()->color('success'),
                        TextEntry::make('grand_payable')->label('Dibayar tamu')->money('IDR', locale: 'id')->badge()->color('success'),
                        TextEntry::make('send_receipt')->label('Kirim struk WA')->badge(),
                    ])
                    ->columns([
                        'default' => 1,
                        'sm' => 1,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 3,
                        '2xl' => 3,
                    ]),

                Section::make('Pembayaran')
                    ->columnSpanFull()
                    ->schema([
                        RepeatableEntry::make('payments')
                            ->hiddenLabel()
                            ->columnSpanFull()
                            ->contained(false)
                            ->schema([
                                TextEntry::make('method')->badge()->color('success'),
                                TextEntry::make('status')->badge(),
                                TextEntry::make('amount')->money('IDR', locale: 'id')->badge()->color('success'),
                                TextEntry::make('unique_add')->label('Digit unik')->badge()->color('success'),
                                TextEntry::make('gps_status')->label('GPS')->badge()->color('success'),
                                Grid::make(2)
                                    ->columnSpan(3)
                                    ->schema([
                                        TextEntry::make('gps_override_reason')->label('Alasan override')->placeholder('-'),
                                        TextEntry::make('reject_reason')->label('Alasan tolak')->placeholder('-'),
                                    ]),
                                ImageEntry::make('proof_image_path')
                                    ->label('Bukti transfer')
                                    ->disk('public')
                                    ->imageHeight(220)
                                    ->columnSpanFull()
                                    ->placeholder('Belum diunggah'),
                            ])
                            ->columns([
                                'default' => 1,
                                'sm' => 1,
                                'md' => 2,
                                'lg' => 2,
                                'xl' => 4,
                                '2xl' => 4,
                            ]),
                    ]),

                Section::make('Item')
                ->columnSpanFull()
                    ->schema([
                        RepeatableEntry::make('items')
                            ->schema([
                                TextEntry::make('name_snapshot')->label('Item'),
                                TextEntry::make('variant_name_snapshot')->label('Varian')->placeholder('-'),
                                TextEntry::make('qty')->badge()->color('success'),
                                TextEntry::make('unit_price')->label('Harga')->money('IDR', locale: 'id')->badge()->color('success'),
                                TextEntry::make('kds_status')->label('KDS')->badge(),
                                TextEntry::make('void_omzet_policy')->label('Omzet void')->placeholder('-')->badge()->color('success'),
                                TextEntry::make('void_reason')->label('Alasan void')->placeholder('-'),
                                TextEntry::make('notes')->placeholder('-'),
                            ])
                            ->columns([
                                'default' => 1,
                                'sm' => 2,
                                'md' => 3,
                                'lg' => 3,
                                'xl' => 6,
                                '2xl' => 6,
                            ]),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        $table = $table
            ->columns([
                TextColumn::make('number')
                    ->label('No')
                    ->sortable(),
                TextColumn::make('visit.diningTable.code')
                    ->label('Meja'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'awaiting_cashier' => 'Menunggu kasir',
                        'paid' => 'Lunas',
                        'in_production' => 'Sedang dimasak',
                        'completed' => 'Selesai',
                        'pending_payment' => 'Pending bayar',
                        'rejected' => 'Ditolak',
                        'cancelled' => 'Batal',
                        'voided' => 'Void',
                        default => ucfirst(str_replace('_', ' ', $state)),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'awaiting_cashier' => 'warning',
                        'paid' => 'info',
                        'in_production' => 'primary',
                        'completed' => 'success',
                        'rejected', 'cancelled', 'voided' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('payment_method')
                    ->label('Metode'),
                TextColumn::make('has_proof')
                    ->label('Bukti')
                    ->badge()
                    ->state(fn (Order $record): string => filled($record->payments->sortByDesc('id')->first()?->proof_image_path) ? 'Ada' : '—')
                    ->color(fn (string $state): string => $state === 'Ada' ? 'success' : 'gray'),
                TextColumn::make('grand_payable')
                    ->label('Tagihan')
                    ->money('IDR', locale: 'id'),
                TextColumn::make('visit.customer_wa')
                    ->searchable()
                    ->label('WA'),
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Dari tanggal')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                        DatePicker::make('created_until')
                            ->label('Sampai tanggal')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $restaurant = Filament::getTenant();
                        $tz = $restaurant?->timezone ?: 'Asia/Jakarta';

                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->where(
                                    'created_at',
                                    '>=',
                                    Carbon::parse($date, $tz)->startOfDay()->utc(),
                                ),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->where(
                                    'created_at',
                                    '<=',
                                    Carbon::parse($date, $tz)->endOfDay()->utc(),
                                ),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Dari: ' . Carbon::parse($data['created_from'])->format('d/m/Y');
                        }

                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Sampai: ' . Carbon::parse($data['created_until'])->format('d/m/Y');
                        }

                        return $indicators;
                    }),
                SelectFilter::make('status')
                    ->label('Status')
                    ->native(false)
                    ->options([
                        'awaiting_cashier' => 'Menunggu kasir',
                        'paid' => 'Lunas (Antrian dapur)',
                        'in_production' => 'Sedang dimasak',
                        'completed' => 'Selesai',
                        'pending_payment' => 'Pending bayar',
                        'rejected' => 'Ditolak',
                        'cancelled' => 'Batal',
                        'voided' => 'Void',
                    ]),
                SelectFilter::make('payment_method')
                    ->native(false)
                    ->options([
                        'qris' => 'QRIS',
                        'cash' => 'Tunai',
                    ]),
            ])
            ->groups([
                Group::make('created_at')
                    ->label('Tanggal')
                    ->date()
                    ->collapsible()
                    ->orderQueryUsing(fn (Builder $query, string $direction = 'desc') => $query->orderBy('created_at', 'desc')->orderBy('id', 'desc')),
            ])
            ->defaultGroup('created_at');

        return TableRightClick::apply($table, fn (): array => [
            ViewAction::make(),
        ]);
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getEloquentQuery()
            ->where('status', 'awaiting_cashier')
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['visit.diningTable', 'payments', 'items'])
            ->latest('created_at')
            ->latest('id');
    }

    public static function getWidgets(): array
    {
        return [
            OrderTodayStatsWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'view' => ViewOrder::route('/{record}'),
        ];
    }
}
