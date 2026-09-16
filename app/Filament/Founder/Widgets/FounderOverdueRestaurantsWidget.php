<?php

namespace App\Filament\Founder\Widgets;

use App\Enums\SubscriptionStatus;
use App\Filament\Founder\Resources\Tenants\TenantResource;
use App\Models\Restaurant;
use App\Services\FounderAnalyticsService;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseTableWidget;

class FounderOverdueRestaurantsWidget extends BaseTableWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->extraAttributes(['class' => 'vision-table-card'])
            ->heading('Restoran Mangkir & Perlu Perhatian')
            ->description('Tenant dengan masa langganan tenggang (grace), expired, atau memiliki tagihan sewa melewati batas jatuh tempo')
            ->query(app(FounderAnalyticsService::class)->getOverdueRestaurantsQuery())
            ->emptyStateHeading('Tidak ada restoran yang mangkir')
            ->emptyStateDescription('Semua restoran memiliki status berlangganan aktif dan tidak ada tunggakan.')
            ->emptyStateIcon('heroicon-o-shield-check')
            ->columns([
                TextColumn::make('name')
                    ->label('Restoran')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (Restaurant $record): string => $record->legal_name ?: ('@'.$record->slug))
                    ->url(fn (Restaurant $record): string => TenantResource::getUrl('edit', ['record' => $record])),

                TextColumn::make('subscription_status')
                    ->label('Status Langganan')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof SubscriptionStatus ? $state->label() : (string) $state)
                    ->color(fn ($state) => $state instanceof SubscriptionStatus ? $state->color() : 'gray'),

                TextColumn::make('overdue_amount')
                    ->label('Tunggakan')
                    ->weight('bold')
                    ->color('danger')
                    ->formatStateUsing(fn ($state): string => 'Rp '.number_format((int) $state, 0, ',', '.')),

                TextColumn::make('expiry_hint')
                    ->label('Batas Waktu')
                    ->state(function (Restaurant $record): string {
                        if ($record->subscription_status === SubscriptionStatus::Grace && $record->grace_ends_at) {
                            return 'Grace s/d '.$record->grace_ends_at->format('d M Y');
                        }
                        if ($record->subscription_status === SubscriptionStatus::Trial && $record->trial_ends_at) {
                            return 'Trial habis '.$record->trial_ends_at->format('d M Y');
                        }
                        if ($record->subscribed_until) {
                            return 'Habis '.$record->subscribed_until->format('d M Y');
                        }
                        $firstInvoice = $record->subscriptionInvoices->first();
                        if ($firstInvoice && $firstInvoice->due_at) {
                            return 'Jatuh tempo '.$firstInvoice->due_at->format('d M Y');
                        }

                        return 'Menunggak';
                    })
                    ->badge()
                    ->color('danger'),

                TextColumn::make('owner_phone')
                    ->label('Kontak Owner')
                    ->state(function (Restaurant $record): ?string {
                        $outlet = $record->outlets()->where('is_default', true)->first() ?: $record->outlets()->first();
                        if ($outlet && filled($outlet->phone)) {
                            return $outlet->phone;
                        }
                        $owner = $record->users()->wherePivot('is_active', true)->first();

                        return $owner?->email ?? '—';
                    })
                    ->icon('heroicon-m-phone')
                    ->placeholder('—'),
            ])
            ->recordActions([
                Action::make('whatsapp')
                    ->label('Chat WA')
                    ->icon('heroicon-m-chat-bubble-left-ellipsis')
                    ->color('success')
                    ->button()
                    ->size('xs')
                    ->visible(function (Restaurant $record): bool {
                        $outlet = $record->outlets()->where('is_default', true)->first() ?: $record->outlets()->first();

                        return (bool) ($outlet && filled($outlet->phone));
                    })
                    ->url(function (Restaurant $record): string {
                        $outlet = $record->outlets()->where('is_default', true)->first() ?: $record->outlets()->first();
                        $phone = preg_replace('/[^0-9]/', '', (string) ($outlet?->phone ?? ''));
                        if (str_starts_with($phone, '0')) {
                            $phone = '62'.substr($phone, 1);
                        }
                        $message = rawurlencode("Halo {$record->name}, kami dari pengelola platform Walk-In Resto ingin mengonfirmasi status pembayaran langganan restoran Anda. Mohon untuk segera melakukan penyelesaian invoice. Terima kasih.");

                        return "https://wa.me/{$phone}?text={$message}";
                    })
                    ->openUrlInNewTab(),

                Action::make('manage')
                    ->label('Kelola')
                    ->icon('heroicon-m-pencil-square')
                    ->color('gray')
                    ->url(fn (Restaurant $record): string => TenantResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
