<?php

namespace App\Filament\Resources\WhatsappMessages;

use App\Filament\Concerns\ChecksBusinessPermission;
use App\Filament\Resources\WhatsappMessages\Pages\ManageWhatsappMessages;
use App\Filament\Support\TableRightClick;
use App\Models\WhatsappMessage;
use App\Services\OrderReceiptService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class WhatsappMessageResource extends Resource
{
    use ChecksBusinessPermission;

    protected static ?string $model = WhatsappMessage::class;

    protected static string $permission = 'receipt.resend';

    protected static string $subscriptionFeature = 'settings_full';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Struk WhatsApp';

    protected static ?string $pluralModelLabel  = 'pesan WhatsApp';

    protected static ?int $navigationSort = 15;

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

    public static function table(Table $table): Table
    {
        $table = $table
            ->columns([
                TextColumn::make('queued_at')->label('Antrian')->dateTime('d M Y H:i')->sortable(),
                TextColumn::make('kind')->badge(),
                TextColumn::make('to_wa')->label('Tujuan')->searchable(),
                TextColumn::make('status')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'sent' => 'success',
                        'failed' => 'danger',
                        'queued' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('attempts')->label('Percobaan'),
                TextColumn::make('last_error')->limit(40)->placeholder('-'),
                TextColumn::make('order.number')->label('Order'),
            ])
            ->defaultSort('queued_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'queued' => 'Queued',
                        'sent' => 'Sent',
                        'failed' => 'Failed',
                    ]),
            ]);

        return TableRightClick::apply($table, fn (): array => [
            Action::make('resend')
                ->label('Kirim ulang')
                ->icon(Heroicon::OutlinedArrowPath)
                ->visible(fn (WhatsappMessage $record): bool => in_array($record->status, ['failed', 'sent'], true))
                ->requiresConfirmation()
                ->action(function (WhatsappMessage $record, OrderReceiptService $receipts): void {
                    $receipts->resendMessage($record, auth()->user());

                    Notification::make()
                        ->title('Struk masuk antrian kirim ulang')
                        ->success()
                        ->send();
                }),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageWhatsappMessages::route('/'),
        ];
    }
}
