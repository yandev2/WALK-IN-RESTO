<?php

namespace App\Filament\Pages;

use App\Models\Restaurant;
use App\Models\User;
use App\Services\CustomerCrmService;
use App\Support\SubscriptionAccess;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\View\View;
use UnitEnum;

class CustomerAnalytics extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBarSquare;

    protected static string|UnitEnum|null $navigationGroup = 'CRM & Pelanggan';

    protected static ?string $navigationLabel = 'Analitik CRM';

    protected static ?string $title = 'Analitik CRM & Pelanggan';

    protected static ?string $slug = 'crm-analitik';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.pages.customer-analytics';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        return ($user->isPlatformOperator() || $user->isRestaurantOwner() || $user->can('order.verify_payment'))
            && SubscriptionAccess::allows('crm');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('guide')
                ->label('Panduan Poin & CRM')
                ->icon(Heroicon::OutlinedInformationCircle)
                ->color('info')
                ->outlined()
                ->modalWidth('3xl')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup')
                ->modalHeading('Panduan Sistem Poin, Tier & CRM Pelanggan')
                ->modalContent(function (): View {
                    $restaurant = Filament::getTenant();
                    $settings = $restaurant instanceof Restaurant ? $restaurant->loyaltySettings() : [
                        'enabled' => false,
                        'spend_per_point' => 10000,
                        'points_earned' => 1,
                        'silver_min_spent' => 500000,
                        'gold_min_spent' => 1500000,
                        'vip_min_spent' => 5000000,
                    ];

                    return view('filament.pages.info-loyalty', [
                        'settings' => $settings,
                    ]);
                }),

            Action::make('settings')
                ->label('Pengaturan Poin & Tier')
                ->icon(Heroicon::OutlinedCog6Tooth)
                ->color('primary')
                ->visible(function (): bool {
                    $user = auth()->user();

                    return $user instanceof User && ($user->isPlatformOperator() || $user->isRestaurantOwner());
                })
                ->fillForm(function (): array {
                    $restaurant = Filament::getTenant();

                    return $restaurant instanceof Restaurant
                        ? $restaurant->loyaltySettings()
                        : [];
                })
                ->form([
                    Toggle::make('enabled')
                        ->label('Aktifkan Program Poin & Tier Member')
                        ->helperText('Jika dinonaktifkan, analitik CRM tetap mencatat riwayat kunjungan & belanja tamu, namun tidak ada perhitungan poin dan struk kasir tidak mencetak data member.')
                        ->live(),

                    Section::make('Ketentuan Belanja & Poin')
                        ->description('Atur kelipatan belanja untuk mendapatkan poin loyalitas.')
                        ->visible(fn (Get $get): bool => (bool) $get('enabled'))
                        ->schema([
                            TextInput::make('spend_per_point')
                                ->label('Kelipatan Belanja (Rp)')
                                ->integer()
                                ->required()
                                ->prefix('Rp')
                                ->helperText('Contoh: Setiap belanja kelipatan Rp 10.000...'),

                            TextInput::make('points_earned')
                                ->label('Poin yang Didapat')
                                ->integer()
                                ->required()
                                ->suffix('Poin')
                                ->helperText('...pelanggan mendapatkan 1 poin.'),
                        ])
                        ->columns(2),

                    Section::make('Ketentuan Penukaran Poin (Point-to-Discount)')
                        ->description('Atur nilai tukar poin menjadi diskon tagihan di kasir POS dan scan QR meja.')
                        ->visible(fn (Get $get): bool => (bool) $get('enabled'))
                        ->schema([
                            TextInput::make('point_redemption_rate')
                                ->label('Nilai 1 Poin (Rp)')
                                ->integer()
                                ->required()
                                ->minValue(1)
                                ->prefix('Rp')
                                ->helperText('Contoh: 1 Poin = Rp 1.000 (Default: Rp 1.000).'),

                            TextInput::make('min_redeem_points')
                                ->label('Minimal Poin per Redeem')
                                ->integer()
                                ->required()
                                ->minValue(1)
                                ->suffix('Poin')
                                ->helperText('Ambang minimal penukaran (Default: 10 Poin).'),

                            TextInput::make('max_redeem_percentage')
                                ->label('Batas Maksimal Diskon (%)')
                                ->integer()
                                ->required()
                                ->minValue(1)
                                ->maxValue(100)
                                ->suffix('%')
                                ->helperText('Maksimal diskon dari subtotal (Default: 50%).'),
                        ])
                        ->columns(3),

                    Section::make('Ambang Batas Tier Member')
                        ->description('Akumulasi total belanja (LTV) untuk naik level tier secara otomatis.')
                        ->visible(fn (Get $get): bool => (bool) $get('enabled'))
                        ->schema([
                            TextInput::make('silver_min_spent')
                                ->label('Minimal Belanja Silver')
                                ->integer()
                                ->required()
                                ->prefix('Rp')
                                ->helperText('Default: Rp 500.000'),

                            TextInput::make('gold_min_spent')
                                ->label('Minimal Belanja Gold')
                                ->integer()
                                ->required()
                                ->prefix('Rp')
                                ->helperText('Default: Rp 1.500.000'),

                            TextInput::make('vip_min_spent')
                                ->label('Minimal Belanja VIP')
                                ->integer()
                                ->required()
                                ->prefix('Rp')
                                ->helperText('Default: Rp 5.000.000'),
                        ])
                        ->columns(3),
                ])
                ->action(function (array $data): void {
                    $restaurant = Filament::getTenant();

                    if ($restaurant instanceof Restaurant) {
                        $restaurant->updateLoyaltySettings($data);

                        Notification::make()
                            ->title('Pengaturan loyalitas disimpan')
                            ->success()
                            ->send();
                    }
                }),
        ];
    }

    public function getViewData(): array
    {
        $restaurant = Filament::getTenant();

        if (! $restaurant instanceof Restaurant) {
            return [
                'analytics' => [
                    'total_customers' => 0,
                    'repeat_customers' => 0,
                    'one_time_customers' => 0,
                    'repeat_rate' => 0.0,
                    'total_ltv' => 0,
                    'average_ltv' => 0,
                    'total_orders_count' => 0,
                    'average_order_value' => 0,
                    'avg_orders_per_customer' => 0.0,
                    'total_points' => 0,
                    'active_30d_customers' => 0,
                    'dormant_customers' => 0,
                    'new_this_month' => 0,
                    'tier_counts' => [
                        'reguler' => 0,
                        'silver' => 0,
                        'gold' => 0,
                        'vip' => 0,
                    ],
                    'top_frequent' => collect(),
                    'top_spenders' => collect(),
                ],
                'loyaltySettings' => [
                    'enabled' => false,
                    'spend_per_point' => 10000,
                    'points_earned' => 1,
                    'silver_min_spent' => 500000,
                    'gold_min_spent' => 1500000,
                    'vip_min_spent' => 5000000,
                ],
            ];
        }

        $crm = app(CustomerCrmService::class);

        return [
            'analytics' => $crm->getAnalytics($restaurant),
            'loyaltySettings' => $restaurant->loyaltySettings(),
        ];
    }
}
