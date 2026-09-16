<?php

namespace App\Filament\Founder\Widgets;

use App\Enums\InvoiceType;
use App\Filament\Founder\Resources\SubscriptionInvoices\SubscriptionInvoiceResource;
use App\Models\SubscriptionInvoice;
use App\Models\User;
use App\Services\FounderAnalyticsService;
use App\Services\SubscriptionInvoiceService;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseTableWidget;

class FounderPendingInvoicesWidget extends BaseTableWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->extraAttributes(['class' => 'vision-table-card'])
            ->heading('Invoice Baru Menunggu Konfirmasi')
            ->description('Bukti transfer langganan / komisi yang telah dikirim tenant dan memerlukan validasi Founder')
            ->query(app(FounderAnalyticsService::class)->getPendingInvoicesQuery())
            ->emptyStateHeading('Tidak ada invoice yang menunggu konfirmasi')
            ->emptyStateDescription('Seluruh pembayaran sewa dan komisi restoran telah diverifikasi.')
            ->emptyStateIcon('heroicon-o-check-badge')
            ->columns([
                TextColumn::make('invoice_number')
                    ->label('Nomor Invoice')
                    ->searchable()
                    ->weight('bold')
                    ->color('primary')
                    ->url(fn (SubscriptionInvoice $record): string => SubscriptionInvoiceResource::getUrl('view', ['record' => $record])),

                TextColumn::make('restaurant.name')
                    ->label('Restoran / Tenant')
                    ->searchable()
                    ->description(fn (SubscriptionInvoice $record): string => $record->restaurant?->slug ? '@'.$record->restaurant->slug : '—'),

                TextColumn::make('invoice_type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof InvoiceType
                        ? ($state === InvoiceType::CashierCommission ? 'Komisi Kasir' : 'Sewa Flat')
                        : (string) $state)
                    ->color(fn ($state) => $state === InvoiceType::CashierCommission ? 'warning' : 'info'),

                TextColumn::make('period_month')
                    ->label('Periode')
                    ->placeholder('—'),

                TextColumn::make('amount')
                    ->label('Nominal')
                    ->weight('bold')
                    ->formatStateUsing(fn ($state): string => 'Rp '.number_format((int) $state, 0, ',', '.')),

                TextColumn::make('payment_submitted_at')
                    ->label('Waktu Pengajuan')
                    ->dateTime('d M Y H:i')
                    ->since()
                    ->sortable(),

                ImageColumn::make('payment_proof_path')
                    ->label('Bukti')
                    ->disk('local')
                    ->visibility('private')
                    ->circular()
                    ->defaultImageUrl(asset('images/default-avatar.png')),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Terima')
                    ->icon('heroicon-m-check')
                    ->color('success')
                    ->button()
                    ->size('xs')
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi & Lunaskan Invoice')
                    ->modalDescription('Apakah Anda yakin bukti transfer valid? Status invoice akan diubah menjadi LUNAS dan masa langganan tenant diperpanjang otomatis.')
                    ->modalSubmitActionLabel('Ya, Verifikasi Lunas')
                    ->action(function (SubscriptionInvoice $record): void {
                        $user = auth()->user();
                        if (! $user instanceof User) {
                            return;
                        }
                        app(SubscriptionInvoiceService::class)->approve($record, $user);
                        Notification::make()
                            ->title("Invoice #{$record->invoice_number} berhasil dilunasi.")
                            ->success()
                            ->send();
                    }),

                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-m-x-mark')
                    ->color('danger')
                    ->button()
                    ->size('xs')
                    ->schema([
                        Textarea::make('rejection_notes')
                            ->label('Alasan Penolakan')
                            ->placeholder('Contoh: Nominal transfer kurang, bukti buram, atau dana belum masuk rekening.')
                            ->required(),
                    ])
                    ->modalHeading('Tolak Bukti Pembayaran')
                    ->modalSubmitActionLabel('Tolak Bukti')
                    ->action(function (SubscriptionInvoice $record, array $data): void {
                        $user = auth()->user();
                        if (! $user instanceof User) {
                            return;
                        }
                        app(SubscriptionInvoiceService::class)->reject($record, $data['rejection_notes'], $user);
                        Notification::make()
                            ->title("Bukti invoice #{$record->invoice_number} ditolak.")
                            ->warning()
                            ->send();
                    }),

                Action::make('view')
                    ->label('Detail')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->color('gray')
                    ->url(fn (SubscriptionInvoice $record): string => SubscriptionInvoiceResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
