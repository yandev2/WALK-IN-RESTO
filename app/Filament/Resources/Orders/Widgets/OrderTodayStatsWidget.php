<?php

namespace App\Filament\Resources\Orders\Widgets;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use App\Models\Restaurant;
use App\Support\RestaurantTheme;
use Filament\Facades\Filament;
use Filament\Widgets\Concerns\CanPoll;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class OrderTodayStatsWidget extends Widget
{
    use CanPoll;

    protected static bool $isLazy = false;

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.resources.orders.widgets.order-today-stats-widget';

    public static function canView(): bool
    {
        return OrderResource::canViewAny();
    }

    protected function getPollingInterval(): ?string
    {
        return '15s';
    }

    /**
     * @return array{primary: string, primary_dark: string, accent: string, ink: string}
     */
    public function analyticsTheme(): array
    {
        $restaurant = Filament::getTenant();
        $theme = RestaurantTheme::for($restaurant instanceof Restaurant ? $restaurant : null);

        return [
            ...$theme,
            'ink' => RestaurantTheme::darken($theme['primary'], 0.55),
        ];
    }

    /**
     * @return list<array{
     *     status: string,
     *     label: string,
     *     value: string,
     *     hint: string,
     *     tint: string,
     *     icon: string,
     *     filter_url: string,
     * }>
     */
    public function getCards(): array
    {
        $restaurant = Filament::getTenant();

        if (! $restaurant instanceof Restaurant) {
            return [];
        }

        $timezone = $restaurant->timezone ?: 'Asia/Jakarta';
        $today = Carbon::now($timezone);
        $startOfDay = $today->copy()->startOfDay()->utc();
        $endOfDay = $today->copy()->endOfDay()->utc();

        $counts = Order::query()
            ->where('restaurant_id', $restaurant->id)
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();

        return [
            [
                'status' => 'awaiting_cashier',
                'label' => 'Menunggu Kasir',
                'value' => (string) ($counts['awaiting_cashier'] ?? 0),
                'hint' => 'Perlu konfirmasi',
                'tint' => '#f59e0b',
                'icon' => 'awaiting_cashier',
                'filter_url' => OrderResource::getUrl('index', [
                    'tenant' => $restaurant,
                    'tableFilters' => ['status' => ['value' => 'awaiting_cashier']],
                ]),
            ],
            [
                'status' => 'paid',
                'label' => 'Lunas (Antrian)',
                'value' => (string) ($counts['paid'] ?? 0),
                'hint' => 'Antrian dapur',
                'tint' => '#0284c7',
                'icon' => 'paid',
                'filter_url' => OrderResource::getUrl('index', [
                    'tenant' => $restaurant,
                    'tableFilters' => ['status' => ['value' => 'paid']],
                ]),
            ],
            [
                'status' => 'in_production',
                'label' => 'Sedang Dimasak',
                'value' => (string) ($counts['in_production'] ?? 0),
                'hint' => 'Proses dapur',
                'tint' => '#8b5cf6',
                'icon' => 'in_production',
                'filter_url' => OrderResource::getUrl('index', [
                    'tenant' => $restaurant,
                    'tableFilters' => ['status' => ['value' => 'in_production']],
                ]),
            ],
            [
                'status' => 'completed',
                'label' => 'Selesai',
                'value' => (string) ($counts['completed'] ?? 0),
                'hint' => 'Selesai disajikan',
                'tint' => '#10b981',
                'icon' => 'completed',
                'filter_url' => OrderResource::getUrl('index', [
                    'tenant' => $restaurant,
                    'tableFilters' => ['status' => ['value' => 'completed']],
                ]),
            ],
            [
                'status' => 'pending_payment',
                'label' => 'Pending',
                'value' => (string) ($counts['pending_payment'] ?? 0),
                'hint' => 'Menunggu bayar',
                'tint' => '#64748b',
                'icon' => 'pending_payment',
                'filter_url' => OrderResource::getUrl('index', [
                    'tenant' => $restaurant,
                    'tableFilters' => ['status' => ['value' => 'pending_payment']],
                ]),
            ],
            [
                'status' => 'rejected',
                'label' => 'Ditolak',
                'value' => (string) ($counts['rejected'] ?? 0),
                'hint' => 'Pembayaran ditolak',
                'tint' => '#f43f5e',
                'icon' => 'rejected',
                'filter_url' => OrderResource::getUrl('index', [
                    'tenant' => $restaurant,
                    'tableFilters' => ['status' => ['value' => 'rejected']],
                ]),
            ],
            [
                'status' => 'cancelled',
                'label' => 'Batal',
                'value' => (string) ($counts['cancelled'] ?? 0),
                'hint' => 'Dibatalkan',
                'tint' => '#94a3b8',
                'icon' => 'cancelled',
                'filter_url' => OrderResource::getUrl('index', [
                    'tenant' => $restaurant,
                    'tableFilters' => ['status' => ['value' => 'cancelled']],
                ]),
            ],
            [
                'status' => 'voided',
                'label' => 'Void',
                'value' => (string) ($counts['voided'] ?? 0),
                'hint' => 'Pesanan di-void',
                'tint' => '#b91c1c',
                'icon' => 'voided',
                'filter_url' => OrderResource::getUrl('index', [
                    'tenant' => $restaurant,
                    'tableFilters' => ['status' => ['value' => 'voided']],
                ]),
            ],
        ];
    }
}
