<?php

namespace App\Services\Export;

use App\Filament\Exports\ExcelExporter;
use App\Models\Order;
use App\Models\Restaurant;
use App\Services\CommissionReconciliationService;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CommissionReconciliationExport
{
    public function __construct(
        private readonly CommissionReconciliationService $service,
    ) {}

    public function downloadExcel(Restaurant $restaurant, CarbonInterface $from, CarbonInterface $to): BinaryFileResponse
    {
        $payload = $this->buildPayload($restaurant, $from, $to);
        $filename = 'Rekonsiliasi-Penjualan-Komisi-' . $restaurant->slug . '-' . $from->format('Ymd') . '-' . $to->format('Ymd') . '.xlsx';

        return Excel::download(new ExcelExporter($payload, 'exports.commission-reconciliation'), $filename);
    }

    public function downloadCsv(Restaurant $restaurant, CarbonInterface $from, CarbonInterface $to): BinaryFileResponse
    {
        $payload = $this->buildPayload($restaurant, $from, $to);
        $filename = 'Rekonsiliasi-Penjualan-Komisi-' . $restaurant->slug . '-' . $from->format('Ymd') . '-' . $to->format('Ymd') . '.csv';

        return Excel::download(new ExcelExporter($payload, 'exports.commission-reconciliation'), $filename, \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * @return array<string, mixed>
     */
    public function buildPayload(Restaurant $restaurant, CarbonInterface $from, CarbonInterface $to): array
    {
        $timezone = $restaurant->timezone ?: 'Asia/Jakarta';
        $summary = $this->service->summaryForPeriod($restaurant, $from, $to);

        $orders = $this->service->ordersQuery($restaurant, $from, $to)
            ->orderBy('paid_at', 'asc')
            ->get();

        $rows = [];
        foreach ($orders as $order) {
            $detail = $this->service->orderCommissionDetail($order, $restaurant);
            $paidAt = $order->paid_at ? $order->paid_at->copy()->timezone($timezone)->format('d/m/Y H:i') : '-';
            $tableCode = $order->visit?->diningTable?->code ? 'Meja ' . $order->visit->diningTable->code : 'Bungkus / Kasir';

            $statusNote = Order::STATUSES[$order->status] ?? $order->status;
            if ($detail['is_exempt'] && filled($detail['exempt_reason'])) {
                $statusNote .= ' (' . $detail['exempt_reason'] . ')';
            }

            $rows[] = [
                'number' => $order->number,
                'paid_at' => $paidAt,
                'table' => $tableCode,
                'payment_method' => (string) $order->payment_method,
                'subtotal' => $detail['subtotal'],
                'discount' => $detail['discount'],
                'void_cut' => $detail['void_cut'],
                'net_sales' => $detail['net_sales'],
                'commission_amount' => $detail['commission_amount'],
                'net_resto' => $detail['net_resto'],
                'status_note' => $statusNote,
            ];
        }

        return [
            'restaurant' => $restaurant,
            'summary' => $summary,
            'rows' => $rows,
        ];
    }
}
