<?php

namespace App\Support;

use App\Models\Order;
use App\Models\OrderItem;

final class OrderReceiptWhatsappMessage
{
    public static function compose(Order $order): string
    {
        $order->loadMissing([
            'items.modifiers',
            'items.menuItem',
            'visit.diningTable',
            'restaurant',
        ]);

        $timezone = $order->restaurant?->timezone ?: 'Asia/Jakarta';
        $table = $order->visit?->diningTable?->code ?: '-';
        $lines = [
            'Struk '.($order->restaurant?->name ?: 'Restoran'),
            'Order #'.$order->number.' · Meja '.$table,
        ];

        if ($order->paid_at) {
            $lines[] = $order->paid_at->timezone($timezone)->format('d/m/Y H:i');
        }

        $lines[] = '';
        $lines[] = 'Pesanan:';

        $items = $order->items->where('kds_status', '!=', 'voided');

        if ($items->isEmpty()) {
            $lines[] = '(tidak ada item)';
        }

        foreach ($items as $item) {
            $lines[] = self::formatItemLine($item);
        }

        $lines[] = '';
        $lines[] = 'Subtotal: Rp '.self::money($order->subtotal);

        if ((int) $order->discount_amount > 0) {
            $lines[] = 'Diskon order: -Rp '.self::money($order->discount_amount);
        }

        $lines[] = 'Service: Rp '.self::money($order->service_amount);
        $lines[] = 'PB1: Rp '.self::money($order->pb1_amount);
        $lines[] = 'Total: Rp '.self::money($order->grand_payable);

        $downloadUrl = OrderReceiptDownloadUrl::signed($order);

        if (filled($downloadUrl)) {
            $lines[] = '';
            $lines[] = 'Unduh PDF ('.OrderReceiptDownloadUrl::LINK_VALID_DAYS.' hari):';
            $lines[] = $downloadUrl;
        }

        return implode("\n", $lines);
    }

    private static function formatItemLine(OrderItem $item): string
    {
        $lineTotal = (int) $item->unit_price * (int) $item->qty;
        $discount = self::itemDiscountLabel($item);

        return sprintf(
            '• %s x%d Rp %s = Rp %s%s',
            $item->displayName(),
            $item->qty,
            self::money($item->unit_price),
            self::money($lineTotal),
            $discount !== null ? ' ('.$discount.')' : '',
        );
    }

    private static function itemDiscountLabel(OrderItem $item): ?string
    {
        $menuItem = $item->menuItem;

        if (! $menuItem?->hasDiscount()) {
            return null;
        }

        return 'diskon '.(int) $menuItem->discount_percent.'%';
    }

    private static function money(int|string $amount): string
    {
        return number_format((int) $amount, 0, ',', '.');
    }
}
