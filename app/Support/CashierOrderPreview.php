<?php

namespace App\Support;

use App\Models\MenuItem;
use App\Models\MenuVariant;
use App\Models\Modifier;
use App\Models\Outlet;
use Illuminate\Support\Collection;

final class CashierOrderPreview
{
    /**
     * @param  list<array{menu_item_id?: int|null, variant_id?: int|null, qty?: int|null, modifier_ids?: list<int|string>|null}>  $lines
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
        $itemIds = [];
        $modifierIds = [];
        $variantIds = [];

        foreach ($lines as $line) {
            $itemId = (int) ($line['menu_item_id'] ?? 0);

            if ($itemId > 0) {
                $itemIds[$itemId] = $itemId;
            }

            if (filled($line['variant_id'] ?? null)) {
                $variantId = (int) $line['variant_id'];
                if ($variantId > 0) {
                    $variantIds[$variantId] = $variantId;
                }
            }

            foreach ($line['modifier_ids'] ?? [] as $modifierId) {
                if (filled($modifierId)) {
                    $modifierIds[(int) $modifierId] = (int) $modifierId;
                }
            }
        }

        $items = $itemIds === []
            ? collect()
            : MenuItem::query()->whereIn('id', array_values($itemIds))->get()->keyBy('id');

        $variants = $variantIds === []
            ? collect()
            : MenuVariant::query()->whereIn('id', array_values($variantIds))->where('is_active', true)->get()->keyBy('id');

        $modifiers = $modifierIds === []
            ? collect()
            : Modifier::query()
                ->whereIn('id', array_values($modifierIds))
                ->where('is_active', true)
                ->get()
                ->keyBy('id');

        $subtotal = 0;
        $lineCount = 0;
        $totalQty = 0;

        foreach ($lines as $line) {
            $lineTotal = self::lineTotal($line, $items, $modifiers, $variants);

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
     * @param  array{menu_item_id?: int|null, variant_id?: int|null, qty?: int|null, modifier_ids?: list<int|string>|null}  $line
     * @param  Collection<int, MenuItem>|null  $items
     * @param  Collection<int, Modifier>|null  $modifiers
     * @param  Collection<int, MenuVariant>|null  $variants
     */
    public static function lineTotal(array $line, $items = null, $modifiers = null, $variants = null): int
    {
        $itemId = $line['menu_item_id'] ?? null;
        $qty = (int) ($line['qty'] ?? 0);

        if (! $itemId || $qty < 1) {
            return 0;
        }

        $item = $items?->get((int) $itemId) ?? MenuItem::query()->find($itemId);

        if (! $item) {
            return 0;
        }

        $variantDelta = 0;
        $variantId = filled($line['variant_id'] ?? null) ? (int) $line['variant_id'] : null;

        if ($variantId) {
            if ($variants) {
                $variantDelta = (int) ($variants->get($variantId)?->price_delta ?? 0);
            } else {
                $variant = MenuVariant::query()
                    ->whereKey($variantId)
                    ->where('is_active', true)
                    ->first();
                $variantDelta = (int) ($variant?->price_delta ?? 0);
            }
        }

        $selectedModifierIds = collect($line['modifier_ids'] ?? [])
            ->filter()
            ->map(fn (mixed $id): int => (int) $id)
            ->values();

        if ($selectedModifierIds->isEmpty()) {
            $modifierTotal = 0;
        } elseif ($modifiers) {
            $modifierTotal = (int) $selectedModifierIds
                ->sum(fn (int $id): int => (int) ($modifiers->get($id)?->price ?? 0));
        } else {
            $modifierTotal = (int) Modifier::query()
                ->whereIn('id', $selectedModifierIds->all())
                ->where('is_active', true)
                ->sum('price');
        }

        $unit = (int) $item->effectivePrice() + $variantDelta + $modifierTotal;

        return $unit * $qty;
    }
}
