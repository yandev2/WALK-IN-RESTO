<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ExportFiles\ExportFileResource;
use App\Filament\Resources\MenuItems\MenuItemResource;
use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Widgets\Concerns\RendersAnalyticsDashboard;
use App\Models\Order;
use App\Models\Restaurant;
use App\Support\CmsMedia;
use Filament\Facades\Filament;
use Filament\Widgets\Concerns\CanPoll;
use Filament\Widgets\Widget;
use Illuminate\Support\Str;

class AnalyticsKpiWidget extends Widget
{
    use CanPoll;
    use RendersAnalyticsDashboard;

    protected static bool $isLazy = false;

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = [
        'default' => 'full',
    ];

    protected string $view = 'filament.widgets.analytics.kpi';

    public static function canView(): bool
    {
        return self::canViewAnalytics();
    }

    protected function getPollingInterval(): ?string
    {
        return '30s';
    }

    /**
     * @return list<array{label: string, value: string, hint: string|null, hint_tone: string, icon: string}>
     */
    public function getStatCards(): array
    {
        return $this->todayKpiCards();
    }

    /**
     * @return array{
     *     cards: list<array{
     *         title: string,
     *         label: string,
     *         value: string,
     *         badge: string|null,
     *         badge_tone: string,
     *         icon_class: string,
     *         icon: string,
     *         footer_text: string,
     *         action_label: string|null,
     *         action_url: string|null,
     *     }>,
     *     pills: list<array{label: string, value: string, icon: string}>
     * }
     */
    public function getVisionKpiData(): array
    {
        $snapshot = $this->analyticsSnapshot();
        $restaurant = Filament::getTenant();

        $today = $snapshot['today'] ?? ['omzet' => 0, 'count' => 0, 'qris' => 0, 'cash' => 0, 'voided' => 0, 'waste' => 0];
        $comparison = $snapshot['comparison'] ?? ['omzet_delta_pct' => null, 'count_delta_pct' => null, 'aov_today' => 0, 'aov_yesterday' => 0];
        $topMenu = $snapshot['top_menu_items'] ?? [];

        $omzetDelta = $this->formatKpiDelta($comparison['omzet_delta_pct'] ?? null, 'vs kemarin');
        $countDelta = $this->formatKpiDelta($comparison['count_delta_pct'] ?? null, 'vs kemarin');

        $pendingCount = 0;
        if ($restaurant instanceof Restaurant) {
            $pendingCount = Order::query()
                ->where('restaurant_id', $restaurant->id)
                ->where('status', 'awaiting_cashier')
                ->count();
        }

        $totalPortions = (int) array_sum(array_column($topMenu, 'total_qty'));
        $topMenuName = ! empty($topMenu[0]['name']) ? $topMenu[0]['name'] : 'Menu favorit';

        $orderUrl = null;
        $menuUrl = null;
        $exportUrl = null;
        try {
            $orderUrl = OrderResource::getUrl();
            $menuUrl = MenuItemResource::getUrl();
            $exportUrl = ExportFileResource::getUrl();
        } catch (\Throwable) {
            // Safe fallback
        }

        $tenant = $restaurant instanceof Restaurant ? $restaurant : null;
        $hasOverdue = $tenant && ($tenant->hasOverdueCashierInvoice() || $tenant->hasUnpaidOverdueInvoice());
        $isKdsActive = ! $hasOverdue && \App\Support\SubscriptionAccess::allows('operations');

        return [
            'cards' => [
                [
                    'title' => 'PORTOFOLIO OMZET',
                    'label' => 'Omzet hari ini',
                    'value' => CmsMedia::formatShortIdr($today['omzet'] ?? 0),
                    'raw_value' => CmsMedia::formatIdr($today['omzet'] ?? 0),
                    'badge' => $omzetDelta['text'] ?? 'Hari ini',
                    'badge_tone' => $omzetDelta['tone'] ?? 'neutral',
                    'icon_class' => 'vision-icon-box-blue',
                    'icon' => 'heroicon-o-banknotes',
                    'footer_text' => 'Omzet hari ini bersih setelah void',
                    'action_label' => 'Kelola →',
                    'action_url' => $exportUrl ?? $orderUrl,
                ],
                [
                    'title' => 'ORDER LUNAS',
                    'label' => 'Order lunas',
                    'value' => (string) ($today['count'] ?? 0),
                    'badge' => ($today['count'] ?? 0).' Selesai',
                    'badge_tone' => 'up',
                    'icon_class' => 'vision-icon-box-green',
                    'icon' => 'heroicon-o-check-badge',
                    'footer_text' => 'Rata-rata: '.CmsMedia::formatIdr($comparison['aov_today'] ?? 0),
                    'action_label' => 'Lihat →',
                    'action_url' => $orderUrl,
                ],
                $isKdsActive
                    ? [
                        'title' => 'ANTRIAN KASIR',
                        'label' => 'Antrian kasir',
                        'value' => (string) $pendingCount,
                        'badge' => $pendingCount > 0 ? 'Perlu Bayar' : 'Semua Lunas',
                        'badge_tone' => $pendingCount > 0 ? 'warning' : 'neutral',
                        'icon_class' => 'vision-icon-box-orange',
                        'icon' => 'heroicon-o-clock',
                        'footer_text' => 'Total antrian: '.$pendingCount.' pesanan',
                        'action_label' => 'Buka Kasir →',
                        'action_url' => $orderUrl,
                    ]
                    : [
                        'title' => 'LAYANAN KASIR',
                        'label' => 'Antrian kasir',
                        'value' => 'Non-Aktif',
                        'badge' => 'Ada Tunggakan',
                        'badge_tone' => 'danger',
                        'icon_class' => 'vision-icon-box-orange',
                        'icon' => 'heroicon-o-lock-closed',
                        'footer_text' => 'Layanan kasir dinonaktifkan',
                        'action_label' => 'Bayar Tagihan →',
                        'action_url' => \App\Filament\Pages\SubscriptionStatus::getUrl(),
                    ],
                [
                    'title' => 'MENU TERJUAL',
                    'label' => 'Menu terjual',
                    'value' => (string) $totalPortions,
                    'badge' => Str::limit($topMenuName, 13),
                    'badge_tone' => 'info',
                    'icon_class' => 'vision-icon-box-purple',
                    'icon' => 'heroicon-o-sparkles',
                    'footer_text' => 'Menu terlaris hari ini',
                    'action_label' => 'Kelola →',
                    'action_url' => $menuUrl,
                ],
            ],
            'pills' => [
                [
                    'label' => 'QRIS hari ini',
                    'value' => CmsMedia::formatIdr($today['qris'] ?? 0),
                    'icon' => 'heroicon-o-qr-code',
                ],
                [
                    'label' => 'Tunai hari ini',
                    'value' => CmsMedia::formatIdr($today['cash'] ?? 0),
                    'icon' => 'heroicon-o-banknotes',
                ],
                [
                    'label' => 'Void / waste',
                    'value' => ($today['voided'] ?? 0).' void ('.CmsMedia::formatIdr($today['waste'] ?? 0).' waste)',
                    'icon' => 'heroicon-o-trash',
                ],
            ],
        ];
    }
}
