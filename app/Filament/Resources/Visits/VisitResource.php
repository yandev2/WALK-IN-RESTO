<?php

namespace App\Filament\Resources\Visits;

use App\Filament\Concerns\ChecksBusinessPermission;
use App\Filament\Resources\DiningTables\DiningTableResource;
use App\Filament\Resources\Visits\Pages\ManageVisits;
use App\Filament\Support\TableRightClick;
use App\Models\DiningTable;
use App\Models\Visit;
use App\Services\TableOpsService;
use App\Services\VisitLifecycleService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class VisitResource extends Resource
{
    use ChecksBusinessPermission;

    protected static ?string $model = Visit::class;

    protected static string $permission = 'table.manage';

    protected static string $subscriptionFeature = 'operations';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;

    protected static string|UnitEnum|null $navigationGroup = 'Operasional';

    protected static ?string $navigationLabel = 'Visit';

    protected static ?string $pluralModelLabel  = 'visit';

    protected static ?int $navigationSort = 3;

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

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('diningTable.code')->label('Meja'),
                TextEntry::make('status')->badge(),
                TextEntry::make('customer_wa')->label('WhatsApp'),
                TextEntry::make('customer_name')->label('Nama')->placeholder('-'),
                TextEntry::make('join_pin')->label('PIN gabung'),
                TextEntry::make('claimed_at')->dateTime('d M Y H:i'),
                TextEntry::make('claim_expires_at')->dateTime('d M Y H:i'),
                TextEntry::make('closed_at')->dateTime('d M Y H:i')->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        $table = $table
            ->columns([
                TextColumn::make('diningTable.code')->label('Meja')->badge()->color('primary'),
                TextColumn::make('status')->badge(),
                TextColumn::make('customer_wa')->label('WA')->searchable(),
                TextColumn::make('customer_name')->label('Nama')->placeholder('-')->searchable(),
                TextColumn::make('join_pin')->label('PIN'),
                TextColumn::make('claimed_at')->label('Klaim')->since(),
            ])
            ->defaultSort('claimed_at', 'desc')
            ->filtersFormWidth('sm')
            ->filters([
                SelectFilter::make('status')
                ->columnSpanFull()
                    ->options([
                        'open' => 'Open',
                        'closed' => 'Closed',
                    ]),
            ]);

        return TableRightClick::apply($table, fn(): array => static::recordActions());
    }

    /**
     * @return array<int, Action>
     */
    public static function recordActions(): array
    {
        return [
            Action::make('updateWa')
                ->label('Ganti WA')
                ->icon(Heroicon::OutlinedChatBubbleLeftRight)
                ->visible(fn(Visit $record): bool => $record->status === 'open')
                ->form([
                    TextInput::make('customer_wa')
                        ->label('WhatsApp tamu')
                        ->required()
                        ->default(fn(Visit $record): ?string => $record->customer_wa),
                ])
                ->action(function (Visit $record, array $data, TableOpsService $ops): void {
                    $ops->updateCustomerWa($record, auth()->user(), $data['customer_wa']);
                    Notification::make()->title('Nomor WhatsApp visit diperbarui')->success()->send();
                }),
            Action::make('resetPin')
                ->label('Reset PIN')
                ->icon(Heroicon::OutlinedKey)
                ->visible(fn(Visit $record): bool => $record->status === 'open')
                ->requiresConfirmation()
                ->action(function (Visit $record, TableOpsService $ops): void {
                    $pin = $ops->resetPin($record, auth()->user());
                    Notification::make()
                        ->title('PIN baru: ' . $pin)
                        ->success()
                        ->send();
                }),
            Action::make('move')
                ->label('Pindah meja')
                ->icon(Heroicon::OutlinedArrowsRightLeft)
                ->visible(fn(Visit $record): bool => $record->status === 'open')
                ->form([
                    Select::make('destination_table_id')
                        ->label('Meja tujuan')
                        ->options(fn(Visit $record): array => DiningTableResource::availableTableOptions($record->table_id))
                        ->required(),
                ])
                ->action(function (Visit $record, array $data, TableOpsService $ops): void {
                    $dest = DiningTable::query()->findOrFail($data['destination_table_id']);
                    $ops->moveVisit($record, $dest, auth()->user());
                    Notification::make()->title('Visit dipindah ke meja ' . $dest->code)->success()->send();
                }),
            Action::make('close')
                ->label('Tutup visit')
                ->icon(Heroicon::OutlinedLockClosed)
                ->requiresConfirmation()
                ->modalHeading('Tutup visit?')
                ->modalDescription(fn(Visit $record): string => $record->hasUnservedKitchenItems()
                    ? 'Masih ada hidangan berjalan di KDS. Item sisa tetap dimasak sampai served/void. Meja masuk cleaning.'
                    : 'Visit ditutup. Meja masuk cleaning.')
                ->visible(fn(Visit $record): bool => $record->status === 'open')
                ->disabled(fn(Visit $record): bool => $record->orders()
                    ->whereIn('status', ['awaiting_cashier', 'pending_payment'])
                    ->exists())
                ->tooltip('Tidak bisa ditutup jika masih ada order menunggu kasir.')
                ->action(function (Visit $record, VisitLifecycleService $lifecycle): void {
                    $lifecycle->closeByCashier($record, auth()->user());
                    Notification::make()->title('Visit ditutup')->success()->send();
                }),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageVisits::route('/'),
        ];
    }
}
