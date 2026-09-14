<?php

namespace App\Filament\Resources\CashierShifts;

use App\Filament\Resources\CashierShifts\Pages\ListCashierShifts;
use App\Filament\Resources\CashierShifts\Pages\ViewCashierShift;
use App\Models\CashierShift;
use App\Models\User;
use App\Support\CmsMedia;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class CashierShiftResource extends Resource
{
    protected static ?string $model = CashierShift::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static string|UnitEnum|null $navigationGroup = 'Operasional';

    protected static ?string $navigationLabel = 'Shift kasir';

    protected static ?string $modelLabel = 'shift kasir';

    protected static ?string $pluralModelLabel = 'shift kasir';

    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        if ($user->isPlatformOperator()) {
            return true;
        }

        return $user->can('order.create')
            || $user->can('order.verify_payment')
            || $user->can('analytics.view');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#ID')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Kasir')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        CashierShift::STATUS_OPEN => 'Aktif (Open)',
                        CashierShift::STATUS_CLOSED => 'Ditutup (Closed)',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        CashierShift::STATUS_OPEN => 'success',
                        CashierShift::STATUS_CLOSED => 'gray',
                        default => 'primary',
                    }),

                TextColumn::make('opened_at')
                    ->label('Buka')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('closed_at')
                    ->label('Tutup')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('starting_cash')
                    ->label('Modal')
                    ->formatStateUsing(fn (int $state): string => CmsMedia::formatIdr($state))
                    ->sortable(),

                TextColumn::make('cash_sales')
                    ->label('Tunai')
                    ->formatStateUsing(fn (int $state): string => CmsMedia::formatIdr($state))
                    ->sortable(),

                TextColumn::make('non_cash_sales')
                    ->label('QRIS / Online')
                    ->formatStateUsing(fn (int $state): string => CmsMedia::formatIdr($state))
                    ->sortable(),

                TextColumn::make('expected_ending_cash')
                    ->label('Saldo Sistem')
                    ->formatStateUsing(function (CashierShift $record): string {
                        $viewer = auth()->user();
                        $isOwnerOrOperator = $viewer && ($viewer->isPlatformOperator() || $viewer->hasRole('owner'));

                        if ($record->isOpen() && ! $isOwnerOrOperator) {
                            return 'Disembunyikan';
                        }

                        $calc = app(\App\Services\CashierShiftService::class)->calculateExpectedCash($record);

                        return CmsMedia::formatIdr($calc['expected_cash']);
                    })
                    ->sortable(),

                TextColumn::make('actual_ending_cash')
                    ->label('Uang Fisik')
                    ->formatStateUsing(fn (?int $state): string => $state !== null ? CmsMedia::formatIdr($state) : '-')
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('cash_difference')
                    ->label('Selisih')
                    ->badge()
                    ->placeholder('-')
                    ->formatStateUsing(function (?int $state, CashierShift $record): string {
                        if ($record->isOpen()) {
                            return 'Shift Aktif';
                        }
                        if ($state === null || $state === 0) {
                            return 'PAS (Rp 0)';
                        }
                        if ($state < 0) {
                            return 'MINUS '.CmsMedia::formatIdr(abs($state));
                        }

                        return 'LEBIH +'.CmsMedia::formatIdr($state);
                    })
                    ->color(function (?int $state, CashierShift $record): string {
                        if ($record->isOpen()) {
                            return 'info';
                        }
                        if ($state === null || $state === 0) {
                            return 'success';
                        }
                        if ($state < 0) {
                            return 'danger';
                        }

                        return 'primary';
                    }),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status shift')
                    ->options([
                        CashierShift::STATUS_OPEN => 'Aktif (Open)',
                        CashierShift::STATUS_CLOSED => 'Ditutup (Closed)',
                    ]),
            ])
            ->actions([
                ViewAction::make(),
                Action::make('print')
                    ->label('Struk shift')
                    ->icon(Heroicon::OutlinedPrinter)
                    ->color('gray')
                    ->url(fn (CashierShift $record): string => route('shifts.print', ['shift' => $record->public_id]))
                    ->openUrlInNewTab(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make([
                    'default' => 1,
                    'lg' => 3,
                ])
                    ->columnSpanFull()
                    ->schema([
                        Section::make('Informasi Shift')
                            ->icon(Heroicon::OutlinedInformationCircle)
                            ->columnSpan([
                                'default' => 1,
                                'lg' => 1,
                            ])
                            ->schema([
                                TextEntry::make('id')
                                    ->label('ID Shift')
                                    ->prefix('#')
                                    ->weight('bold'),

                                TextEntry::make('user.name')
                                    ->label('Kasir Bertugas')
                                    ->weight('bold'),

                                TextEntry::make('status')
                                    ->label('Status Shift')
                                    ->badge()
                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                        CashierShift::STATUS_OPEN => 'Aktif (Open)',
                                        CashierShift::STATUS_CLOSED => 'Ditutup (Closed)',
                                        default => ucfirst($state),
                                    })
                                    ->color(fn (string $state): string => match ($state) {
                                        CashierShift::STATUS_OPEN => 'success',
                                        CashierShift::STATUS_CLOSED => 'gray',
                                        default => 'primary',
                                    }),

                                TextEntry::make('opened_at')
                                    ->label('Jam Buka')
                                    ->dateTime('d/m/Y H:i:s'),

                                TextEntry::make('closed_at')
                                    ->label('Jam Tutup')
                                    ->dateTime('d/m/Y H:i:s')
                                    ->placeholder('Shift Masih Aktif'),

                                TextEntry::make('closedByUser.name')
                                    ->label('Ditutup Oleh')
                                    ->placeholder('-'),

                                TextEntry::make('notes')
                                    ->label('Catatan Shift')
                                    ->placeholder('-'),
                            ]),

                        Section::make('Rekonsiliasi Kas Laci (Cash Drawer)')
                            ->icon(Heroicon::OutlinedBanknotes)
                            ->columnSpan([
                                'default' => 1,
                                'lg' => 2,
                            ])
                            ->schema([
                                Grid::make([
                                    'default' => 1,
                                    'sm' => 2,
                                ])
                                    ->schema([
                                        TextEntry::make('starting_cash')
                                            ->label('Modal Awal Kasir')
                                            ->formatStateUsing(fn (int $state): string => CmsMedia::formatIdr($state)),

                                        TextEntry::make('cash_sales')
                                            ->label('Total Penjualan Tunai')
                                            ->formatStateUsing(fn (int $state): string => CmsMedia::formatIdr($state)),

                                        TextEntry::make('cash_in')
                                            ->label('Kas Masuk (Tambah Modal dll)')
                                            ->formatStateUsing(fn (int $state): string => CmsMedia::formatIdr($state)),

                                        TextEntry::make('cash_out')
                                            ->label('Kas Keluar (Petty Cash/Operasional)')
                                            ->formatStateUsing(fn (int $state): string => CmsMedia::formatIdr($state)),

                                        TextEntry::make('expected_ending_cash')
                                            ->label('Saldo Kas Seharusnya (Sistem)')
                                            ->weight('bold')
                                            ->formatStateUsing(function (CashierShift $record): string {
                                                $viewer = auth()->user();
                                                $isOwnerOrOperator = $viewer && ($viewer->isPlatformOperator() || $viewer->hasRole('owner'));

                                                if ($record->isOpen() && ! $isOwnerOrOperator) {
                                                    return 'Disembunyikan (Shift Aktif)';
                                                }

                                                $calc = app(\App\Services\CashierShiftService::class)->calculateExpectedCash($record);

                                                return CmsMedia::formatIdr($calc['expected_cash']);
                                            }),

                                        TextEntry::make('actual_ending_cash')
                                            ->label('Uang Fisik Kasir (Hasil Hitung)')
                                            ->weight('bold')
                                            ->formatStateUsing(fn (?int $state): string => $state !== null ? CmsMedia::formatIdr($state) : '-')
                                            ->placeholder('-'),

                                        TextEntry::make('cash_difference')
                                            ->label('Selisih Kas (+/-)')
                                            ->badge()
                                            ->formatStateUsing(function (?int $state, CashierShift $record): string {
                                                if ($record->isOpen()) {
                                                    return 'Shift Masih Aktif';
                                                }
                                                if ($state === null || $state === 0) {
                                                    return 'PAS (Rp 0)';
                                                }
                                                if ($state < 0) {
                                                    return 'MINUS '.CmsMedia::formatIdr(abs($state));
                                                }

                                                return 'LEBIH +'.CmsMedia::formatIdr($state);
                                            })
                                            ->color(function (?int $state, CashierShift $record): string {
                                                if ($record->isOpen()) {
                                                    return 'info';
                                                }
                                                if ($state === null || $state === 0) {
                                                    return 'success';
                                                }
                                                if ($state < 0) {
                                                    return 'danger';
                                                }

                                                return 'primary';
                                            }),

                                        TextEntry::make('difference_reason')
                                            ->label('Alasan Selisih Kas')
                                            ->placeholder('Tidak ada catatan selisih'),

                                        TextEntry::make('non_cash_sales')
                                            ->label('Penjualan Non-Tunai (QRIS / Transfer)')
                                            ->formatStateUsing(fn (int $state): string => CmsMedia::formatIdr($state)),

                                        TextEntry::make('points_discount_display')
                                            ->label('Diskon Poin Loyalty (Member)')
                                            ->badge()
                                            ->color(fn (CashierShift $record): string => (app(\App\Services\CashierShiftService::class)->calculateExpectedCash($record)['points_redeemed'] ?? 0) > 0 ? 'warning' : 'gray')
                                            ->state(function (CashierShift $record): string {
                                                $calc = app(\App\Services\CashierShiftService::class)->calculateExpectedCash($record);
                                                if (($calc['points_redeemed'] ?? 0) <= 0) {
                                                    return 'Rp 0 (0 Poin)';
                                                }

                                                return CmsMedia::formatIdr($calc['points_discount_amount']).' ('.$calc['points_redeemed'].' Poin)';
                                            })
                                            ->helperText('Dipotong melalui poin loyalty, bukan selisih laci kasir.'),

                                        TextEntry::make('total_sales_display')
                                            ->label('Total Omset Riil Shift (Tunai + QRIS)')
                                            ->weight('bold')
                                            ->badge()
                                            ->color('success')
                                            ->state(fn (CashierShift $record): string => CmsMedia::formatIdr(
                                                (int) $record->cash_sales + (int) $record->non_cash_sales
                                            )),
                                    ]),
                            ]),
                    ]),

                Section::make('Rincian Mutasi Kas (Petty Cash Movements)')
                    ->icon(Heroicon::OutlinedArrowsRightLeft)
                    ->description('Riwayat penambahan modal kas (kas masuk) dan pengeluaran darurat/operasional (kas keluar) pada laci kasir selama shift ini.')
                    ->columnSpanFull()
                    ->schema([
                        RepeatableEntry::make('movements')
                            ->hiddenLabel()
                            ->placeholder('Belum ada riwayat mutasi kas (petty cash) pada shift ini.')
                            ->columnSpanFull()
                            ->schema([
                                Grid::make([
                                    'default' => 1,
                                    'sm' => 2,
                                    'md' => 5,
                                ])
                                    ->schema([
                                        TextEntry::make('created_at')
                                            ->label('Waktu')
                                            ->dateTime('d/m/Y H:i:s'),

                                        TextEntry::make('type')
                                            ->label('Tipe Mutasi')
                                            ->badge()
                                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                                'cash_in' => 'KAS MASUK (+)',
                                                'cash_out' => 'KAS KELUAR (-)',
                                                default => strtoupper($state),
                                            })
                                            ->color(fn (string $state): string => match ($state) {
                                                'cash_in' => 'success',
                                                'cash_out' => 'danger',
                                                default => 'gray',
                                            }),

                                        TextEntry::make('category')
                                            ->label('Kategori')
                                            ->badge()
                                            ->color('gray')
                                            ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state))),

                                        TextEntry::make('amount')
                                            ->label('Nominal')
                                            ->weight('bold')
                                            ->formatStateUsing(function ($state, $record): string {
                                                $prefix = ($record?->type === 'cash_in') ? '+' : '-';

                                                return $prefix.CmsMedia::formatIdr((int) $state);
                                            })
                                            ->color(fn ($record): string => $record?->type === 'cash_in' ? 'success' : 'danger'),

                                        TextEntry::make('user.name')
                                            ->label('Dicatat Oleh')
                                            ->placeholder('-'),

                                        TextEntry::make('notes')
                                            ->label('Keterangan / Catatan')
                                            ->columnSpanFull()
                                            ->placeholder('-'),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCashierShifts::route('/'),
            'view' => ViewCashierShift::route('/{record}'),
        ];
    }
}
