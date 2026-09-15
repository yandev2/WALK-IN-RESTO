<?php

namespace App\Filament\Resources\Customers;

use App\Filament\Resources\Customers\Pages\ListCustomers;
use App\Models\Customer;
use App\Models\Restaurant;
use App\Models\User;
use App\Services\CustomerCrmService;
use App\Support\CmsMedia;
use App\Support\SubscriptionAccess;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string|UnitEnum|null $navigationGroup = 'CRM & Pelanggan';

    protected static ?string $navigationLabel = 'Pelanggan';

    protected static ?string $pluralModelLabel = 'pelanggan';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        return ($user->isPlatformOperator() || $user->isRestaurantOwner() || $user->can('order.verify_payment'))
            && SubscriptionAccess::allows('crm');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->default('-')
                    ->weight('medium'),

                TextColumn::make('phone')
                    ->label('WhatsApp')
                    ->searchable()
                    ->formatStateUsing(fn (Customer $record): string => $record->formattedPhone())
                    ->copyable()
                    ->copyMessage('Nomor WhatsApp disalin'),

                TextColumn::make('tier')
                    ->label('Member Tier')
                    ->badge()
                    ->color(fn (Customer $record): string => $record->badgeColor())
                    ->formatStateUsing(fn (Customer $record): string => $record->tierLabel())
                    ->visible(fn (): bool => static::isLoyaltyEnabled()),

                TextColumn::make('total_orders')
                    ->label('Kunjungan')
                    ->sortable()
                    ->alignEnd(),

                TextColumn::make('total_spent')
                    ->label('Total Belanja (LTV)')
                    ->formatStateUsing(fn ($state): string => CmsMedia::formatIdr((int) $state))
                    ->sortable()
                    ->alignEnd(),

                TextColumn::make('points_balance')
                    ->label('Saldo Poin')
                    ->numeric()
                    ->sortable()
                    ->alignEnd()
                    ->visible(fn (): bool => static::isLoyaltyEnabled()),

                TextColumn::make('last_visit_at')
                    ->label('Kunjungan Terakhir')
                    ->formatStateUsing(fn ($state): string => $state ? $state->diffForHumans() : '-')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('tier')
                    ->label('Tier Member')
                    ->options([
                        'reguler' => 'Reguler',
                        'silver' => 'Silver',
                        'gold' => 'Gold',
                        'vip' => 'VIP',
                    ])
                    ->visible(fn (): bool => static::isLoyaltyEnabled()),
            ])
            ->recordActions([
                Action::make('chatWa')
                    ->label('WhatsApp')
                    ->icon(Heroicon::OutlinedChatBubbleLeftRight)
                    ->color('success')
                    ->url(fn (Customer $record): ?string => $record->waLink())
                    ->openUrlInNewTab(),

                Action::make('adjustPoints')
                    ->label('Sesuaikan Poin')
                    ->icon(Heroicon::OutlinedPlusCircle)
                    ->color('warning')
                    ->visible(function (): bool {
                        if (! static::isLoyaltyEnabled()) {
                            return false;
                        }

                        $user = auth()->user();

                        return $user instanceof User && ($user->isPlatformOperator() || $user->isRestaurantOwner());
                    })
                    ->form([
                        TextInput::make('points')
                            ->label('Nominal Poin (+/-)')
                            ->integer()
                            ->required()
                            ->helperText('Contoh: 50 untuk menambah, atau -20 untuk mengurangi.'),
                        Textarea::make('reason')
                            ->label('Alasan Penyesuaian')
                            ->required(),
                    ])
                    ->action(function (Customer $record, array $data, CustomerCrmService $crm): void {
                        $crm->adjustPoints($record, (int) $data['points'], $data['reason'], auth()->user());

                        Notification::make()
                            ->title('Saldo poin diperbarui')
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('last_visit_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomers::route('/'),
        ];
    }

    protected static function isLoyaltyEnabled(): bool
    {
        $tenant = Filament::getTenant();

        if (! $tenant instanceof Restaurant) {
            return false;
        }

        return (bool) $tenant->loyaltySettings()['enabled'];
    }
}
