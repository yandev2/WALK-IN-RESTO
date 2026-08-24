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
use Illuminate\Validation\ValidationException;
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

    protected static ?string $pluralModelLabel = 'pesan WhatsApp';

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
                TextColumn::make('kind')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'receipt' => 'Struk',
                        default => $state,
                    }),
                TextColumn::make('to_wa')->label('Tujuan')->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'queued' => 'Antrian',
                        'sent' => 'Terkirim',
                        'failed' => 'Gagal',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'sent' => 'success',
                        'failed' => 'danger',
                        'queued' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('attempts')->label('Percobaan'),
                TextColumn::make('last_error')
                    ->label('Error terakhir')
                    ->limit(50)
                    ->tooltip(fn (?string $state): ?string => filled($state) ? $state : null)
                    ->placeholder('-'),
                TextColumn::make('order.number')->label('Order'),
            ])
            ->defaultSort('queued_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'queued' => 'Antrian',
                        'sent' => 'Terkirim',
                        'failed' => 'Gagal',
                    ]),
            ]);

        return TableRightClick::apply($table, fn (): array => [
            Action::make('resend')
                ->label('Kirim ulang')
                ->icon(Heroicon::OutlinedArrowPath)
                ->visible(fn (WhatsappMessage $record): bool => self::canResendRecord($record))
                ->requiresConfirmation()
                ->action(function (WhatsappMessage $record, OrderReceiptService $receipts): void {
                    try {
                        $receipts->resendMessage($record, auth()->user());

                        Notification::make()
                            ->title('Struk masuk antrian kirim ulang')
                            ->success()
                            ->send();
                    } catch (ValidationException $exception) {
                        $detail = collect($exception->errors())->flatten()->first()
                            ?: 'Tidak bisa kirim ulang struk.';

                        Notification::make()
                            ->title('Gagal kirim ulang struk')
                            ->body($detail)
                            ->danger()
                            ->send();
                    }
                }),
        ]);
    }

    public static function canResendRecord(WhatsappMessage $record): bool
    {
        if (! in_array($record->status, ['failed', 'sent'], true) || blank($record->order_id)) {
            return false;
        }

        $order = $record->order()->with(['restaurant', 'visit'])->first();

        if (! $order || blank($order->paid_at)) {
            return false;
        }

        $restaurant = $order->restaurant;

        if (! $restaurant?->hasFonnteKey() || ! $restaurant->fonnteApiKey()) {
            return false;
        }

        $to = $order->visit?->customer_wa ?: $order->receipt_wa_snapshot;

        return filled($to);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageWhatsappMessages::route('/'),
        ];
    }
}
