<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\OrderPaymentService;
use App\Services\OrderReceiptService;
use App\Services\OrderVoidService;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Terima pembayaran')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->color('success')
                ->visible(fn (): bool => $this->canApprove())
                ->form(function (): array {
                    if (! $this->needsGpsOverride()) {
                        return [];
                    }

                    return [
                        Textarea::make('gps_override_reason')
                            ->label('Alasan override GPS')
                            ->required()
                            ->helperText('Akurasi GPS buruk atau izin lokasi ditolak. Pastikan tamu ada di meja.'),
                    ];
                })
                ->requiresConfirmation()
                ->action(function (array $data, OrderPaymentService $service): void {
                    /** @var Order $order */
                    $order = $this->record;
                    $service->approve($order, auth()->user(), $data['gps_override_reason'] ?? null);
                    $this->record->refresh();
                    $this->refreshFormData(['status', 'paid_at']);
                    Notification::make()->title('Pembayaran diterima')->success()->send();
                }),
            Action::make('reject')
                ->label('Tolak')
                ->icon(Heroicon::OutlinedXCircle)
                ->color('danger')
                ->visible(fn (): bool => $this->canReject())
                ->form([
                    Textarea::make('reason')
                        ->label('Alasan')
                        ->required(),
                ])
                ->action(function (array $data, OrderPaymentService $service): void {
                    /** @var Order $order */
                    $order = $this->record;
                    $service->reject($order, auth()->user(), $data['reason']);
                    $this->record->refresh();
                    $this->refreshFormData(['status']);
                    Notification::make()->title('Pesanan ditolak')->danger()->send();
                }),
            Action::make('downloadReceipt')
                ->label('Unduh struk PDF')
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->visible(fn (): bool => $this->canDownloadReceipt())
                ->action(function (OrderReceiptService $receipts): Response {
                    /** @var Order $order */
                    $order = $this->record;
                    $receipt = $receipts->generate($order);
                    $this->record->refresh();

                    return Storage::disk('local')->download(
                        $receipt->file_path,
                        'struk-'.$order->number.'.pdf',
                    );
                }),
            Action::make('resendReceipt')
                ->label('Kirim ulang struk WA')
                ->icon(Heroicon::OutlinedChatBubbleLeftRight)
                ->visible(fn (): bool => $this->canResendReceipt())
                ->requiresConfirmation()
                ->action(function (OrderReceiptService $receipts): void {
                    try {
                        /** @var Order $order */
                        $order = $this->record;
                        $receipts->resend($order, auth()->user());
                        Notification::make()->title('Struk masuk antrian kirim ulang')->success()->send();
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
            Action::make('voidItem')
                ->label('Void item')
                ->icon(Heroicon::OutlinedMinusCircle)
                ->color('warning')
                ->visible(fn (): bool => $this->canVoid())
                ->form([
                    Select::make('order_item_id')
                        ->label('Item')
                        ->options(fn (): array => $this->voidableItemOptions())
                        ->required(),
                    Textarea::make('reason')
                        ->label('Alasan')
                        ->required(),
                ])
                ->action(function (array $data, OrderVoidService $service): void {
                    $item = OrderItem::query()
                        ->where('order_id', $this->record->id)
                        ->whereKey($data['order_item_id'])
                        ->firstOrFail();

                    $service->voidItem($item, auth()->user(), $data['reason']);
                    $this->record->refresh();
                    $this->refreshFormData(['status', 'voided_at']);
                    Notification::make()->title('Item di-void')->warning()->send();
                }),
            Action::make('voidOrder')
                ->label('Void pesanan')
                ->icon(Heroicon::OutlinedNoSymbol)
                ->color('danger')
                ->visible(fn (): bool => $this->canVoid())
                ->form([
                    Textarea::make('reason')
                        ->label('Alasan')
                        ->required(),
                ])
                ->requiresConfirmation()
                ->action(function (array $data, OrderVoidService $service): void {
                    /** @var Order $order */
                    $order = $this->record;
                    $service->voidOrder($order, auth()->user(), $data['reason']);
                    $this->record->refresh();
                    $this->refreshFormData(['status', 'voided_at']);
                    Notification::make()->title('Pesanan di-void')->danger()->send();
                }),
        ];
    }

    protected function canApprove(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User || $this->record->status !== 'awaiting_cashier') {
            return false;
        }

        return $user->isSuperAdmin() || $user->can('order.verify_payment');
    }

    protected function canReject(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User || $this->record->status !== 'awaiting_cashier') {
            return false;
        }

        return $user->isSuperAdmin() || $user->can('order.reject_payment');
    }

    protected function needsGpsOverride(): bool
    {
        $payment = $this->record->payments()->latest('id')->first();

        if (! $payment || $payment->method !== 'cash') {
            return false;
        }

        return in_array($payment->gps_status, ['low_accuracy', 'denied'], true)
            && blank($payment->gps_overridden_at);
    }

    protected function canVoid(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User || ! $this->record->isAccepted()) {
            return false;
        }

        return $user->isSuperAdmin() || $user->can('order.void');
    }

    protected function canDownloadReceipt(): bool
    {
        return filled($this->record->paid_at);
    }

    protected function canResendReceipt(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User || blank($this->record->paid_at)) {
            return false;
        }

        if (! $user->isSuperAdmin() && ! $user->can('receipt.resend')) {
            return false;
        }

        /** @var Order $order */
        $order = $this->record;
        $order->loadMissing(['restaurant', 'visit']);

        if (! $order->restaurant?->hasFonnteKey() || ! $order->restaurant->fonnteApiKey()) {
            return false;
        }

        $to = $order->visit?->customer_wa ?: $order->receipt_wa_snapshot;

        return filled($to);
    }

    /**
     * @return array<int, string>
     */
    protected function voidableItemOptions(): array
    {
        return $this->record->items
            ->filter(fn (OrderItem $item): bool => $item->kds_status !== 'voided')
            ->mapWithKeys(fn (OrderItem $item): array => [
                $item->id => $item->displayName().' ×'.$item->qty.' ('.$item->kds_status.')',
            ])
            ->all();
    }
}
