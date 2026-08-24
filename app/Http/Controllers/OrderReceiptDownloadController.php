<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderReceiptService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderReceiptDownloadController extends Controller
{
    public function __invoke(Request $request, Order $order, OrderReceiptService $receipts): StreamedResponse
    {
        abort_unless(filled($order->paid_at), 404);

        $receipt = $receipts->generate($order->loadMissing(['items', 'visit.diningTable', 'restaurant', 'outlet', 'payments']));

        abort_unless(Storage::disk('local')->exists($receipt->file_path), 404);

        return Storage::disk('local')->download(
            $receipt->file_path,
            'struk-'.$order->number.'.pdf',
        );
    }
}
