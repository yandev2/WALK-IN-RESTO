<?php

namespace App\Filament\Resources\DiningTables;

use App\Filament\Concerns\ChecksBusinessPermission;
use App\Filament\Concerns\HasSoftDeletesResource;
use App\Filament\Resources\DiningTables\Pages\ManageDiningTables;
use App\Filament\Resources\DiningTables\Pages\TrashDiningTables;
use App\Filament\Support\TableRightClick;
use App\Models\DiningTable;
use App\Models\Visit;
use App\Services\TableOpsService;
use App\Support\ActivityLogger;
use App\Support\TableQrToken;
use App\Support\TenantContext;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\Unique;
use Symfony\Component\HttpFoundation\StreamedResponse;
use UnitEnum;

class DiningTableResource extends Resource
{
    use ChecksBusinessPermission;
    use HasSoftDeletesResource;

    protected static ?string $model = DiningTable::class;

    protected static string $permission = 'table.manage';

    protected static string $subscriptionFeature = 'operations';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTableCells;

    protected static string|UnitEnum|null $navigationGroup = 'Operasional';

    protected static ?string $navigationLabel = 'Meja';

    protected static ?string $pluralModelLabel  = 'meja';

    protected static ?string $slug = 'tables';

    protected static ?string $recordTitleAttribute = 'code';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas meja')
                    ->description('Kode meja dipakai di denah, QR stiker, dan layar operasional.')
                    ->icon(Heroicon::OutlinedTableCells)
                    ->columns(2)
                    ->schema([
                        TextInput::make('code')
                            ->label('Nomor / kode meja')
                            ->placeholder('A1')
                            ->required()
                            ->maxLength(32)
                            ->unique(
                                table: DiningTable::class,
                                column: 'code',
                                ignoreRecord: true,
                                modifyRuleUsing: fn (Unique $rule) => $rule->where('outlet_id', TenantContext::outletId()),
                            )
                            ->helperText('Unik per outlet. Contoh: 01, A1, VIP-2.'),
                        TextInput::make('capacity')
                            ->label('Kapasitas')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(20)
                            ->default(2)
                            ->required()
                            ->suffix('orang')
                            ->helperText('Jumlah kursi maksimal untuk meja ini.'),
                        TextInput::make('area')
                            ->label('Area / zona')
                            ->placeholder('Indoor')
                            ->maxLength(64)
                            ->helperText('Opsional. Misalnya Indoor, Outdoor, VIP, Rooftop.')
                            ->columnSpanFull(),
                    ]),
                Section::make('Operasional')
                    ->description('Meja out of service tidak bisa dipesan tamu via QR.')
                    ->icon(Heroicon::OutlinedNoSymbol)
                    ->compact()
                    ->schema([
                        Toggle::make('is_out_of_service')
                            ->label('Out of service')
                            ->helperText('Aktifkan jika meja rusak atau sedang tidak dipakai. Visit terbuka harus ditutup dulu.')
                            ->inline(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        $table = $table
            ->columns([])
            ->content(view('filament.resources.dining-tables.floor-plan'))
            ->paginated(false)
            ->poll('15s')
            ->defaultSort('code')
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['openVisit.orders']));

        return TableRightClick::apply($table, fn (): array => static::recordActions());
    }

    /**
     * @return array<int, Action|DeleteAction|EditAction>
     */
    public static function recordActions(): array
    {
        return [
            Action::make('openGuestOrder')
                ->label('Buka pemesanan')
                ->icon(Heroicon::OutlinedQrCode)
                ->url(fn (DiningTable $record): string => TableQrToken::url($record))
                ->openUrlInNewTab(),
            Action::make('copyGuestOrder')
                ->label('Salin tautan QR')
                ->icon(Heroicon::OutlinedClipboardDocument)
                ->action(function (DiningTable $record, $livewire): void {
                    $url = TableQrToken::url($record);
                    $escapedUrl = json_encode($url);

                    $livewire->js(<<<JS
                        (function() {
                            const text = {$escapedUrl};
                            function fallbackCopy(val) {
                                const ta = document.createElement('textarea');
                                ta.value = val;
                                ta.setAttribute('readonly', '');
                                ta.style.position = 'fixed';
                                ta.style.left = '-9999px';
                                ta.style.top = '-9999px';
                                ta.style.opacity = '0';
                                document.body.appendChild(ta);
                                ta.focus();
                                ta.select();
                                ta.setSelectionRange(0, 99999);
                                try {
                                    document.execCommand('copy');
                                } catch (e) {
                                    console.error('Copy fallback failed:', e);
                                }
                                document.body.removeChild(ta);
                            }

                            if (navigator.clipboard && window.isSecureContext) {
                                navigator.clipboard.writeText(text).catch(function() {
                                    fallbackCopy(text);
                                });
                            } else {
                                fallbackCopy(text);
                            }
                        })();
                    JS);

                    Notification::make()
                        ->title('Tautan pemesanan disalin')
                        ->body($url)
                        ->success()
                        ->send();
                }),
            Action::make('markReady')
                ->label('Meja siap')
                ->icon(Heroicon::OutlinedCheck)
                ->visible(fn (DiningTable $record): bool => $record->needs_cleaning)
                ->requiresConfirmation()
                ->action(function (DiningTable $record): void {
                    $record->update(['needs_cleaning' => false]);
                    ActivityLogger::log('table.mark_ready', [
                        'order_id' => null,
                        'new' => ['table_id' => $record->id],
                    ]);
                }),
            Action::make('resetPin')
                ->label('Reset PIN')
                ->icon(Heroicon::OutlinedKey)
                ->visible(fn (DiningTable $record): bool => filled($record->open_visit_id))
                ->requiresConfirmation()
                ->action(function (DiningTable $record, TableOpsService $ops): void {
                    $visit = $record->openVisit;

                    if (! $visit instanceof Visit) {
                        return;
                    }

                    $pin = $ops->resetPin($visit, auth()->user());
                    Notification::make()
                        ->title('PIN baru: '.$pin)
                        ->success()
                        ->send();
                }),
            Action::make('moveVisit')
                ->label('Pindah meja')
                ->icon(Heroicon::OutlinedArrowsRightLeft)
                ->visible(fn (DiningTable $record): bool => filled($record->open_visit_id))
                ->form([
                    Select::make('destination_table_id')
                        ->label('Meja tujuan')
                        ->options(fn (DiningTable $record): array => static::availableTableOptions($record->id))
                        ->searchable()
                        ->native(false)
                        ->required(),
                ])
                ->action(function (DiningTable $record, array $data, TableOpsService $ops): void {
                    $visit = $record->openVisit;
                    $dest = DiningTable::query()->findOrFail($data['destination_table_id']);

                    if (! $visit instanceof Visit) {
                        return;
                    }

                    $ops->moveVisit($visit, $dest, auth()->user());
                    Notification::make()->title('Visit dipindah ke meja '.$dest->code)->success()->send();
                }),
            Action::make('regenerateQr')
                ->label('Regenerate QR')
                ->icon(Heroicon::OutlinedArrowPath)
                ->requiresConfirmation()
                ->modalDescription('Token lama mati. Visit terbuka tetap jalan. Cetak stiker baru.')
                ->action(function (DiningTable $record, TableOpsService $ops): void {
                    $ops->regenerateQr($record, auth()->user());
                    Notification::make()->title('QR meja diperbarui. Unduh stiker baru.')->success()->send();
                }),
            Action::make('downloadQr')
                ->label('Unduh QR PNG')
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->action(function (DiningTable $record, TableOpsService $ops): StreamedResponse {
                    $png = $ops->qrPng($record);

                    return response()->streamDownload(function () use ($png): void {
                        echo $png;
                    }, 'qr-meja-'.$record->code.'.png', [
                        'Content-Type' => 'image/png',
                    ]);
                }),
            Action::make('downloadQrPdf')
                ->label('Unduh QR PDF')
                ->icon(Heroicon::OutlinedDocument)
                ->action(function (DiningTable $record, TableOpsService $ops): StreamedResponse {
                    $pdf = $ops->qrPdf($record);

                    return response()->streamDownload(function () use ($pdf): void {
                        echo $pdf;
                    }, 'stiker-meja-'.$record->code.'.pdf', [
                        'Content-Type' => 'application/pdf',
                    ]);
                }),
            EditAction::make(),
            DeleteAction::make()
                ->disabled(fn (DiningTable $record): bool => filled($record->open_visit_id))
                ->after(function (DiningTable $record): void {
                    ActivityLogger::log('table.delete', [
                        'old' => ['code' => $record->code],
                    ]);
                }),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageDiningTables::route('/'),
            'trash' => TrashDiningTables::route('/trash'),
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function availableTableOptions(?int $exceptId = null): array
    {
        return DiningTable::query()
            ->where('is_out_of_service', false)
            ->where('needs_cleaning', false)
            ->whereNull('open_visit_id')
            ->when($exceptId, fn ($query) => $query->whereKeyNot($exceptId))
            ->when(TenantContext::restaurantId(), fn ($query, $id) => $query->where('restaurant_id', $id))
            ->orderBy('code')
            ->pluck('code', 'id')
            ->all();
    }
}
