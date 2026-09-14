<?php

namespace App\Support;

use App\Models\Outlet;

final class CheckoutTotals
{
    /**
     * @return array{
     *     subtotal: int,
     *     discount_amount: int,
     *     subtotal_net: int,
     *     service_pct: float,
     *     pb1_pct: float,
     *     tax_mode: string,
     *     service_amount: int,
     *     pb1_amount: int,
     *     grand_before: int
     * }
     */
    public static function forSubtotal(int $subtotal, ?Outlet $outlet, int $discountAmount = 0): array
    {
        $servicePct = (float) ($outlet?->service_pct ?? 0);
        $pb1Pct = (float) ($outlet?->pb1_pct ?? 0);
        $subtotalNet = max(0, $subtotal - $discountAmount);
        $service = (int) round($subtotalNet * $servicePct / 100);
        $pb1 = (int) round(($subtotalNet + $service) * $pb1Pct / 100);

        return [
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'subtotal_net' => $subtotalNet,
            'service_pct' => $servicePct,
            'pb1_pct' => $pb1Pct,
            'tax_mode' => $outlet?->tax_mode ?: 'exclusive',
            'service_amount' => $service,
            'pb1_amount' => $pb1,
            'grand_before' => $subtotalNet + $service + $pb1,
        ];
    }
}
