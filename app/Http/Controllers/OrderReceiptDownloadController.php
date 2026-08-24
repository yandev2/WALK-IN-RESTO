<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderReceiptService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderReceiptDownloadController extends Controller
{
    public function __invoke(Request $request, Order $order, OrderReceiptService $receipts): StreamedResponse
    {
        abort_unless(filled($order->paid_at), 404);

        return $receipts->streamPdf($order, 'attachment');
    }
}
