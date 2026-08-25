<?php

namespace App\Support;

use App\Models\MenuItem;
use App\Models\Modifier;
use App\Models\Outlet;

final class CashierOrderPreview
{
    /**
     * @param  list<array{menu_item_id?: int|null, qty?: int|null, modifier_ids?: list<int|string>|null}>  $lines
     * @return array{
     *     subtotal: int,
     *     service_pct: float,
     *     pb1_pct: float,
     *     tax_mode: string,
     *     service_amount: int,
     *     pb1_amount: int,
     *     grand_before: int,
     *     grand_payable: int,
     *     payment_method: string,
     *     is_qris: bool,
     *     line_count: int,
     *     total_qty: int,
     *     qris_image_url: string|null,
     *     cash_received: int|null,
     *     change_amount: int|null,
     *     cash_short: bool
     * }
     */
    public static function estimateFromLines(array $lines, ?Outlet $outlet, string $paymentMethod = 'cash', mixed $cashReceived = null): array
    {
        $subtotal = 0;
        $lineCount = 0;
        $totalQty = 0;

        foreach ($lines as $line) {
            $lineTotal = self::lineTotal($line);

            if ($lineTotal <= 0) {
                continue;
            }

            $subtotal += $lineTotal;
            $lineCount++;
            $totalQty += (int) ($line['qty'] ?? 0);
        }

        $totals = CheckoutTotals::forSubtotal($subtotal, $outlet);
        $isQris = $paymentMethod === 'qris';
        $grandPayable = $totals['grand_before'];
        $received = $isQris ? null : IdrAmount::parse($cashReceived);
        $change = $received === null ? null : $received - $grandPayable;

        return [
            ...$totals,
            'grand_payable' => $grandPayable,
            'payment_method' => $paymentMethod,
            'is_qris' => $isQris,
            'line_count' => $lineCount,
            'total_qty' => $totalQty,
            'qris_image_url' => $isQris ? CmsMedia::url($outlet?->qris_image_path) : null,
            'cash_received' => $received,
            'change_amount' => $change,
            'cash_short' => $received !== null && $change < 0,
        ];
    }

    /**
     * @param  array{menu_item_id?: int|null, qty?: int|null, modifier_ids?: list<int|string>|null}  $line
     */
    public static function lineTotal(array $line): int
    {
        $itemId = $line['menu_item_id'] ?? null;
        $qty = (int) ($line['qty'] ?? 0);

        if (! $itemId || $qty < 1) {
            return 0;
        }

        $item = MenuItem::query()->find($itemId);

        if (! $item) {
            return 0;
        }

        $modifierIds = collect($line['modifier_ids'] ?? [])
            ->filter()
            ->map(fn (mixed $id): int => (int) $id)
            ->values()
            ->all();

        $modifierTotal = $modifierIds === []
            ? 0
            : (int) Modifier::query()
                ->whereIn('id', $modifierIds)
                ->where('is_active', true)
                ->sum('price');

        $unit = (int) $item->effectivePrice() + $modifierTotal;

        return $unit * $qty;
    }
}
