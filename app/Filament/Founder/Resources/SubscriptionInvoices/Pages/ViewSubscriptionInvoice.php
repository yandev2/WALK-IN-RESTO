<?php

namespace App\Filament\Founder\Resources\SubscriptionInvoices\Pages;

use App\Enums\InvoiceStatus;
use App\Filament\Founder\Resources\SubscriptionInvoices\SubscriptionInvoiceResource;
use App\Models\User;
use App\Services\SubscriptionInvoiceService;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewSubscriptionInvoice extends ViewRecord
{
    protected static string $resource = SubscriptionInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Terima pembayaran')
                ->color('success')
                ->visible(fn (): bool => $this->record->status === InvoiceStatus::AwaitingVerification)
                ->requiresConfirmation()
                ->action(function (): void {
                    $user = auth()->user();
                    if (! $user instanceof User) {
                        return;
                    }

                    app(SubscriptionInvoiceService::class)->approve($this->record, $user);
                    Notification::make()->title('Invoice dilunasi.')->success()->send();
                    $this->refreshFormData(['status', 'paid_at']);
                }),
            Action::make('reject')
                ->label('Tolak bukti')
                ->color('danger')
                ->visible(fn (): bool => $this->record->status === InvoiceStatus::AwaitingVerification)
                ->schema([
                    Textarea::make('rejection_notes')->label('Alasan')->required(),
                ])
                ->action(function (array $data): void {
                    $user = auth()->user();
                    if (! $user instanceof User) {
                        return;
                    }

                    app(SubscriptionInvoiceService::class)->reject($this->record, $data['rejection_notes'], $user);
                    Notification::make()->title('Bukti ditolak.')->success()->send();
                    $this->refreshFormData(['status', 'rejection_notes']);
                }),
        ];
    }
}
