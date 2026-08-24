<?php

namespace App\Support;

use App\Models\Order;
use Illuminate\Support\Facades\URL;

final class OrderReceiptDownloadUrl
{
    public const LINK_VALID_DAYS = 7;

    public static function signed(Order $order): ?string
    {
        if (blank($order->paid_at) || blank($order->public_id)) {
            return null;
        }

        return URL::temporarySignedRoute(
            'receipts.download',
            now()->addDays(self::LINK_VALID_DAYS),
            ['order' => $order->public_id],
        );
    }
}
