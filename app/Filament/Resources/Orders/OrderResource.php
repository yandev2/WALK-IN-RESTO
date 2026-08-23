<?php

namespace App\Filament\Resources\Orders;

use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\Orders\Pages\ViewOrder;
use App\Filament\Support\TableRightClick;
use App\Models\Order;
use App\Models\User;
use App\Support\SubscriptionAccess;
use BackedEnum;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
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

    protected static ?string $pluralModelLabel  = 'pesanan';

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
                    ->schema([
                        TextEntry::make('number')->label('Nomor'),
                        TextEntry::make('status')->badge(),
                        TextEntry::make('source')->label('Sumber'),
                        TextEntry::make('payment_method')->label('Metode'),
                        TextEntry::make('visit.diningTable.code')->label('Meja'),
                        TextEntry::make('visit.customer_wa')->label('WhatsApp tamu'),
                        TextEntry::make('visit.customer_name')->label('Nama tamu')->placeholder('-'),
                        TextEntry::make('created_at')->label('Dibuat')->dateTime('d M Y H:i'),
                    ])
                    ->columns(2),
                Section::make('Tagihan')
                    ->schema([
                        TextEntry::make('subtotal')->money('IDR', locale: 'id'),
                        TextEntry::make('service_amount')->label('Service')->money('IDR', locale: 'id'),
                        TextEntry::make('pb1_amount')->label('PB1')->money('IDR', locale: 'id'),
                        TextEntry::make('grand_before')->label('Omzet (grand before)')->money('IDR', locale: 'id'),
                        TextEntry::make('grand_payable')->label('Dibayar tamu')->money('IDR', locale: 'id'),
                        TextEntry::make('send_receipt')->label('Kirim struk WA')->badge(),
                    ])
                    ->columns(3),
                Section::make('Item')
                    ->schema([
                        RepeatableEntry::make('items')
                            ->schema([
                                TextEntry::make('name_snapshot')->label('Item'),
                                TextEntry::make('variant_name_snapshot')->label('Varian')->placeholder('-'),
                                TextEntry::make('qty'),
                                TextEntry::make('unit_price')->label('Harga')->money('IDR', locale: 'id'),
                                TextEntry::make('kds_status')->label('KDS')->badge(),
                                TextEntry::make('void_omzet_policy')->label('Omzet void')->placeholder('-'),
                                TextEntry::make('void_reason')->label('Alasan void')->placeholder('-'),
                                TextEntry::make('notes')->placeholder('-'),
                            ])
                            ->columns(6),
                    ]),
                Section::make('Pembayaran')
                    ->schema([
                        RepeatableEntry::make('payments')
                            ->schema([
                                TextEntry::make('method'),
                                TextEntry::make('status')->badge(),
                                TextEntry::make('amount')->money('IDR', locale: 'id'),
                                TextEntry::make('unique_add')->label('Digit unik'),
                                TextEntry::make('gps_status')->label('GPS')->badge(),
                                TextEntry::make('gps_override_reason')->label('Alasan override')->placeholder('-'),
                                TextEntry::make('reject_reason')->label('Alasan tolak')->placeholder('-'),
                                ImageEntry::make('proof_image_path')
                                    ->label('Bukti transfer')
                                    ->disk('public')
                                    ->imageHeight(220)
                                    ->columnSpanFull()
                                    ->placeholder('Belum diunggah'),
                            ])
                            ->columns(4),
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
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'awaiting_cashier' => 'warning',
                        'paid' => 'success',
                        'rejected', 'cancelled', 'voided' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('payment_method')
                    ->label('Metode'),
                TextColumn::make('has_proof')
                    ->label('Bukti')
                    ->badge()
                    ->state(fn(Order $record): string => filled($record->payments->sortByDesc('id')->first()?->proof_image_path) ? 'Ada' : '—')
                    ->color(fn(string $state): string => $state === 'Ada' ? 'success' : 'gray'),
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
                SelectFilter::make('status')
                    ->native(false)
                    ->options([
                        'awaiting_cashier' => 'Menunggu kasir',
                        'paid' => 'Paid',
                        'rejected' => 'Ditolak',
                        'cancelled' => 'Batal',
                        'pending_payment' => 'Pending',
                        'voided' => 'Void',
                    ]),
                SelectFilter::make('payment_method')
                    ->native(false)
                    ->options([
                        'qris' => 'QRIS',
                        'cash' => 'Tunai',
                    ]),
            ]);

        return TableRightClick::apply($table, fn(): array => [
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
            ->with(['visit.diningTable', 'payments', 'items']);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'view' => ViewOrder::route('/{record}'),
        ];
    }
}
