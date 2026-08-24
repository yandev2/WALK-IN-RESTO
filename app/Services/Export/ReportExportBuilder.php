<?php

namespace App\Services\Export;

use App\Models\ExportFile;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Restaurant;
use App\Services\DailyOmzetService;
use App\Support\RestaurantAnalyticsPeriod;
use App\Support\RestaurantTheme;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class ReportExportBuilder
{
    public function __construct(
        private readonly DailyOmzetService $omzet,
    ) {}

    /**
     * @param  array<string, mixed>  $filters
     * @return array{view: string, data: array<string, mixed>}
     */
    public function build(Restaurant $restaurant, string $module, string $format, array $filters): array
    {
        return match ($module) {
            ExportFile::MODULE_PENJUALAN_ITEM => $this->penjualanItem($restaurant, $format, $filters),
            ExportFile::MODULE_PENJUALAN_ORDER => $this->penjualanOrder($restaurant, $format, $filters),
            ExportFile::MODULE_VOID_ITEM => $this->voidItem($restaurant, $format, $filters),
            ExportFile::MODULE_VOID_ORDER => $this->voidOrder($restaurant, $format, $filters),
            ExportFile::MODULE_OMZET_HARIAN => $this->omzetHarian($restaurant, $format, $filters),
            ExportFile::MODULE_KATALOG_MENU => $this->katalogMenu($restaurant, $format, $filters),
            default => throw ValidationException::withMessages([
                'module' => 'Jenis laporan tidak dikenal.',
            ]),
        };
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array{from: Carbon, to: Carbon, startUtc: Carbon, endUtc: Carbon}
     */
    private function dateWindow(Restaurant $restaurant, array $filters): array
    {
        $range = RestaurantAnalyticsPeriod::normalizeLocalDateRange(
            $restaurant,
            isset($filters['date_from']) ? (string) $filters['date_from'] : null,
            isset($filters['date_to']) ? (string) $filters['date_to'] : null,
        );

        $utc = RestaurantAnalyticsPeriod::utcRangeForLocalDates($restaurant, $range['from'], $range['to']);

        return [
            'from' => $range['from'],
            'to' => $range['to'],
            'startUtc' => $utc['start'],
            'endUtc' => $utc['end'],
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array{view: string, data: array<string, mixed>}
     */
    private function penjualanItem(Restaurant $restaurant, string $format, array $filters): array
    {
        $window = $this->dateWindow($restaurant, $filters);
        $tz = RestaurantAnalyticsPeriod::timezone($restaurant);

        $items = OrderItem::query()
            ->forRestaurant($restaurant->id)
            ->with(['order.visit.diningTable'])
            ->whereHas('order', function ($query) use ($restaurant, $window): void {
                $query->forRestaurant($restaurant->id)
                    ->whereNotNull('paid_at')
                    ->whereIn('status', [...Order::ACCEPTED_STATUSES, 'voided'])
                    ->whereBetween('paid_at', [$window['startUtc'], $window['endUtc']]);
            })
            ->orderBy('id')
            ->get();

        $rows = $items->map(function (OrderItem $item) use ($tz): array {
            $order = $item->order;
            $line = (int) $item->unit_price * (int) $item->qty;
            $isCut = $item->voided_at && $item->void_omzet_policy === 'cut';
            $effective = $isCut ? 0 : $line;

            return [
                'paid_at' => $order?->paid_at?->timezone($tz)->format('d/m/Y H:i') ?? '—',
                'order_number' => $order?->number ?? '—',
                'table' => $order?->visit?->diningTable?->code ?? '—',
                'source' => $order?->source ?? '—',
                'item_name' => trim(($item->name_snapshot ?? '').' '.($item->variant_name_snapshot ?? '')),
                'qty' => (int) $item->qty,
                'unit_price' => (int) $item->unit_price,
                'line_total' => $line,
                'effective_total' => $effective,
                'payment_method' => $order?->payment_method ?? '—',
                'kds_status' => $item->kds_status,
                'void_policy' => $item->void_omzet_policy ?? '—',
                'void_reason' => $item->void_reason ?? '—',
                'voided_at' => $item->voided_at?->timezone($tz)->format('d/m/Y H:i') ?? '—',
            ];
        });

        return [
            'view' => 'exports.penjualan-item',
            'data' => $this->wrap($restaurant, 'Daftar penjualan (item)', $window, [
                'rows' => $rows,
                'total_qty' => $rows->sum('qty'),
                'total_line' => $rows->sum('line_total'),
                'total_effective' => $rows->sum('effective_total'),
            ], 'Rincian item terjual per pesanan.', [
                'Baris' => (string) $rows->count(),
                'Qty' => (string) $rows->sum('qty'),
                'Omzet efektif' => $this->rupiah($rows->sum('effective_total')),
            ]),
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array{view: string, data: array<string, mixed>}
     */
    private function penjualanOrder(Restaurant $restaurant, string $format, array $filters): array
    {
        $window = $this->dateWindow($restaurant, $filters);
        $tz = RestaurantAnalyticsPeriod::timezone($restaurant);

        $orders = $this->omzet->paidOrdersQuery($restaurant, $window['startUtc'], $window['endUtc'])
            ->with(['items', 'visit.diningTable', 'createdBy', 'payments.paidByUser'])
            ->orderBy('paid_at')
            ->get();

        $rows = $orders->map(function (Order $order) use ($tz): array {
            return [
                'paid_at' => $order->paid_at?->timezone($tz)->format('d/m/Y H:i') ?? '—',
                'order_number' => $order->number,
                'table' => $order->visit?->diningTable?->code ?? '—',
                'status' => $order->status,
                'payment_method' => $order->payment_method ?? '—',
                'source' => $order->source ?? '—',
                'subtotal' => (int) $order->subtotal,
                'discount' => (int) $order->discount_amount,
                'service' => (int) $order->service_amount,
                'pb1' => (int) $order->pb1_amount,
                'grand_before' => (int) $order->grand_before,
                'omzet_net' => $this->omzet->netOmzet($order),
                'waste' => $this->omzet->wasteAmount($order),
                'kasir' => $order->createdBy?->name
                    ?? $order->payments->sortByDesc('id')->first()?->paidByUser?->name
                    ?? '—',
                'voided_at' => $order->voided_at?->timezone($tz)->format('d/m/Y H:i') ?? '—',
            ];
        });

        return [
            'view' => 'exports.penjualan-order',
            'data' => $this->wrap($restaurant, 'Daftar penjualan (order)', $window, [
                'rows' => $rows,
                'total_omzet' => $rows->sum('omzet_net'),
                'total_waste' => $rows->sum('waste'),
                'order_count' => $rows->count(),
            ], 'Rekap pesanan yang sudah dibayar.', [
                'Order' => (string) $rows->count(),
                'Omzet net' => $this->rupiah($rows->sum('omzet_net')),
                'Waste' => $this->rupiah($rows->sum('waste')),
            ]),
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array{view: string, data: array<string, mixed>}
     */
    private function voidItem(Restaurant $restaurant, string $format, array $filters): array
    {
        $window = $this->dateWindow($restaurant, $filters);
        $tz = RestaurantAnalyticsPeriod::timezone($restaurant);

        $items = OrderItem::query()
            ->forRestaurant($restaurant->id)
            ->with(['order.visit.diningTable'])
            ->whereNotNull('voided_at')
            ->whereBetween('voided_at', [$window['startUtc'], $window['endUtc']])
            ->orderBy('voided_at')
            ->get();

        $rows = $items->map(function (OrderItem $item) use ($tz): array {
            $order = $item->order;
            $line = (int) $item->unit_price * (int) $item->qty;

            return [
                'voided_at' => $item->voided_at?->timezone($tz)->format('d/m/Y H:i') ?? '—',
                'order_number' => $order?->number ?? '—',
                'table' => $order?->visit?->diningTable?->code ?? '—',
                'item_name' => trim(($item->name_snapshot ?? '').' '.($item->variant_name_snapshot ?? '')),
                'qty' => (int) $item->qty,
                'line_total' => $line,
                'void_policy' => $item->void_omzet_policy ?? '—',
                'void_reason' => $item->void_reason ?? '—',
                'order_status' => $order?->status ?? '—',
                'void_scope' => $order?->status === 'voided' ? 'Pesanan penuh' : 'Item',
            ];
        });

        return [
            'view' => 'exports.void-item',
            'data' => $this->wrap($restaurant, 'Laporan void item', $window, [
                'rows' => $rows,
                'total_cut' => $rows->where('void_policy', 'cut')->sum('line_total'),
                'total_waste' => $rows->where('void_policy', 'waste')->sum('line_total'),
            ], 'Item yang dibatalkan beserta kebijakan omzet.', [
                'Item void' => (string) $rows->count(),
                'Cut' => $this->rupiah($rows->where('void_policy', 'cut')->sum('line_total')),
                'Waste' => $this->rupiah($rows->where('void_policy', 'waste')->sum('line_total')),
            ]),
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array{view: string, data: array<string, mixed>}
     */
    private function voidOrder(Restaurant $restaurant, string $format, array $filters): array
    {
        $window = $this->dateWindow($restaurant, $filters);
        $tz = RestaurantAnalyticsPeriod::timezone($restaurant);

        $orders = Order::query()
            ->forRestaurant($restaurant->id)
            ->with(['items', 'visit.diningTable'])
            ->where('status', 'voided')
            ->whereNotNull('voided_at')
            ->whereBetween('voided_at', [$window['startUtc'], $window['endUtc']])
            ->orderBy('voided_at')
            ->get();

        $rows = $orders->map(function (Order $order) use ($tz): array {
            $cut = $order->items
                ->where('void_omzet_policy', 'cut')
                ->sum(fn (OrderItem $item): int => (int) $item->unit_price * (int) $item->qty);
            $waste = $order->items
                ->where('void_omzet_policy', 'waste')
                ->sum(fn (OrderItem $item): int => (int) $item->unit_price * (int) $item->qty);
            $reason = $order->items->firstWhere('void_reason', '!=', null)?->void_reason
                ?? $order->items->first()?->void_reason
                ?? '—';

            return [
                'voided_at' => $order->voided_at?->timezone($tz)->format('d/m/Y H:i') ?? '—',
                'order_number' => $order->number,
                'table' => $order->visit?->diningTable?->code ?? '—',
                'grand_before' => (int) $order->grand_before,
                'omzet_net' => $this->omzet->netOmzet($order),
                'cut_total' => (int) $cut,
                'waste_total' => (int) $waste,
                'item_count' => $order->items->count(),
                'void_reason' => $reason,
            ];
        });

        return [
            'view' => 'exports.void-order',
            'data' => $this->wrap($restaurant, 'Laporan void pesanan', $window, [
                'rows' => $rows,
                'order_count' => $rows->count(),
                'total_cut' => $rows->sum('cut_total'),
                'total_waste' => $rows->sum('waste_total'),
            ], 'Pesanan yang di-void beserta dampak omzet.', [
                'Order void' => (string) $rows->count(),
                'Cut' => $this->rupiah($rows->sum('cut_total')),
                'Waste' => $this->rupiah($rows->sum('waste_total')),
            ]),
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array{view: string, data: array<string, mixed>}
     */
    private function omzetHarian(Restaurant $restaurant, string $format, array $filters): array
    {
        $window = $this->dateWindow($restaurant, $filters);
        $rows = collect();

        for ($cursor = $window['from']->copy(); $cursor->lte($window['to']); $cursor->addDay()) {
            $summary = $this->omzet->forRestaurant($restaurant, $cursor);
            $rows->push([
                'date' => $summary['date'],
                'timezone' => $summary['timezone'],
                'omzet' => $summary['omzet'],
                'count' => $summary['count'],
                'qris' => $summary['qris'],
                'cash' => $summary['cash'],
                'voided' => $summary['voided'],
                'waste' => $summary['waste'],
            ]);
        }

        return [
            'view' => 'exports.omzet-harian',
            'data' => $this->wrap($restaurant, 'Ringkasan omzet harian', $window, [
                'rows' => $rows,
                'total_omzet' => $rows->sum('omzet'),
                'total_orders' => $rows->sum('count'),
                'total_waste' => $rows->sum('waste'),
            ], 'Omzet, jumlah order, dan metode bayar per hari.', [
                'Hari' => (string) $rows->count(),
                'Omzet' => $this->rupiah($rows->sum('omzet')),
                'Order' => (string) $rows->sum('count'),
            ]),
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array{view: string, data: array<string, mixed>}
     */
    private function katalogMenu(Restaurant $restaurant, string $format, array $filters): array
    {
        $query = MenuItem::query()
            ->forRestaurant($restaurant->id)
            ->with(['category', 'station'])
            ->orderBy('sort_order')
            ->orderBy('name');

        if (filled($filters['category_id'] ?? null)) {
            $query->where('category_id', (int) $filters['category_id']);
        }

        if (array_key_exists('is_active', $filters) && $filters['is_active'] !== null && $filters['is_active'] !== '') {
            $query->where('is_active', filter_var($filters['is_active'], FILTER_VALIDATE_BOOLEAN));
        }

        if (array_key_exists('is_out_of_stock', $filters) && $filters['is_out_of_stock'] !== null && $filters['is_out_of_stock'] !== '') {
            $query->where('is_out_of_stock', filter_var($filters['is_out_of_stock'], FILTER_VALIDATE_BOOLEAN));
        }

        $rows = $query->get()->map(fn (MenuItem $item): array => [
            'name' => $item->name,
            'category' => $item->category?->name ?? '—',
            'station' => $item->station?->name ?? '—',
            'price' => (int) $item->price,
            'discount_percent' => (int) ($item->discount_percent ?? 0),
            'is_active' => $item->is_active ? 'Aktif' : 'Nonaktif',
            'stock' => $item->is_out_of_stock ? 'Habis' : 'Ready',
            'sort_order' => (int) $item->sort_order,
        ]);

        return [
            'view' => 'exports.katalog-menu',
            'data' => $this->meta($restaurant, 'Katalog item menu', [
                'period_label' => null,
                'filters_label' => $this->menuFiltersLabel($restaurant, $filters),
                'rows' => $rows,
                'total_items' => $rows->count(),
            ], 'Daftar item menu restoran.', [
                'Total item' => (string) $rows->count(),
                'Aktif' => (string) $rows->where('is_active', 'Aktif')->count(),
                'Ready' => (string) $rows->where('stock', 'Ready')->count(),
            ]),
        ];
    }

    /**
     * @param  array{from: Carbon, to: Carbon, startUtc?: Carbon, endUtc?: Carbon}  $window
     * @param  array<string, mixed>  $extra
     * @param  array<string, string>  $summary
     * @return array<string, mixed>
     */
    private function wrap(Restaurant $restaurant, string $title, array $window, array $extra, string $subtitle, array $summary = []): array
    {
        return $this->meta($restaurant, $title, [
            'period_label' => $window['from']->format('d/m/Y').' — '.$window['to']->format('d/m/Y'),
            'filters_label' => null,
            ...$extra,
        ], $subtitle, $summary);
    }

    /**
     * @param  array<string, mixed>  $extra
     * @param  array<string, string>  $summary
     * @return array<string, mixed>
     */
    private function meta(Restaurant $restaurant, string $title, array $extra, string $subtitle, array $summary = []): array
    {
        $restaurant->loadMissing(['cmsProfile', 'defaultOutlet']);
        $theme = RestaurantTheme::for($restaurant);
        $primary = $theme['primary'];
        $primaryDark = $theme['primary_dark'];
        $border = '#CBD5E1';
        $primaryLight = '#FFF7ED';
        $td = 'border: 1px solid '.$border.'; padding: 6px 5px; vertical-align: middle; color: #1e293b;';

        return [
            'restaurant' => $restaurant,
            'title' => $title,
            'subtitle' => $subtitle,
            'theme' => $theme,
            'generated_at' => now()->timezone(RestaurantAnalyticsPeriod::timezone($restaurant))->format('d/m/Y H:i'),
            'timezone' => RestaurantAnalyticsPeriod::timezone($restaurant),
            'outlet_label' => $restaurant->defaultOutlet?->name,
            'summary' => $summary,
            'money' => fn (int|float $amount): string => 'Rp '.number_format((int) $amount, 0, ',', '.'),
            'styles' => [
                'primary' => $primary,
                'primary_dark' => $primaryDark,
                'primary_light' => $primaryLight,
                'border' => $border,
                'muted' => '#64748B',
                'th' => 'background-color: '.$primary.'; color: #ffffff; border: 1px solid '.$primaryDark.'; padding: 7px 5px; font-size: 8px; font-weight: 700; text-transform: uppercase; text-align: center; vertical-align: middle;',
                'td' => $td,
                'td_right' => $td.' text-align: right;',
                'td_center' => $td.' text-align: center;',
                'foot' => 'border: 1px solid '.$border.'; background-color: '.$primaryLight.'; color: '.$primaryDark.'; font-weight: 700; font-size: 8.5px; padding: 8px 6px;',
            ],
            ...$extra,
        ];
    }

    private function rupiah(int|float $amount): string
    {
        return 'Rp '.number_format((int) $amount, 0, ',', '.');
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function menuFiltersLabel(Restaurant $restaurant, array $filters): string
    {
        $parts = [];

        if (filled($filters['category_id'] ?? null)) {
            $name = MenuCategory::query()
                ->forRestaurant($restaurant->id)
                ->whereKey((int) $filters['category_id'])
                ->value('name');

            $parts[] = 'Kategori: '.($name ?: '#'.(int) $filters['category_id']);
        }

        if (array_key_exists('is_active', $filters) && $filters['is_active'] !== null && $filters['is_active'] !== '') {
            $parts[] = filter_var($filters['is_active'], FILTER_VALIDATE_BOOLEAN) ? 'Status: Aktif' : 'Status: Nonaktif';
        }

        if (array_key_exists('is_out_of_stock', $filters) && $filters['is_out_of_stock'] !== null && $filters['is_out_of_stock'] !== '') {
            $parts[] = filter_var($filters['is_out_of_stock'], FILTER_VALIDATE_BOOLEAN) ? 'Stok: Habis' : 'Stok: Ready';
        }

        return $parts === [] ? 'Semua item' : implode(' · ', $parts);
    }
}
