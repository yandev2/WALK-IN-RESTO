<?php

namespace App\Http\Controllers;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use App\Models\User;
use App\Services\OrderReceiptService;
use App\Support\PermissionCheck;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderReceiptPrintController extends Controller
{
    public function __invoke(Request $request, Order $order, OrderReceiptService $receipts): View
    {
        $this->authorizeCashierReceipt($request, $order);
        abort_unless(filled($order->paid_at), 404);

        $receipts->generate($order);

        return view('receipts.print-shell', [
            'order' => $order,
            'pdfUrl' => route('receipts.print.pdf', ['order' => $order->public_id]),
            'auto' => $request->boolean('auto'),
            'backUrl' => $this->backUrl($order),
        ]);
    }

    public function pdf(Request $request, Order $order, OrderReceiptService $receipts): StreamedResponse
    {
        $this->authorizeCashierReceipt($request, $order);

        return $receipts->streamPdf($order, 'inline');
    }

    private function authorizeCashierReceipt(Request $request, Order $order): void
    {
        $user = $request->user();
        abort_unless($user instanceof User, 403);

        $restaurant = $order->restaurant;
        abort_unless($restaurant !== null, 404);
        abort_unless($user->isPlatformOperator() || $user->canAccessTenant($restaurant), 403);

        app(PermissionRegistrar::class)->setPermissionsTeamId($order->restaurant_id);
        $user->unsetRelation('roles');
        $user->unsetRelation('permissions');

        abort_unless(
            $user->isPlatformOperator()
            || PermissionCheck::allowsAny($user, ['receipt.print', 'order.verify_payment']),
            403,
        );
    }

    private function backUrl(Order $order): ?string
    {
        $restaurant = $order->restaurant;

        if (! $restaurant) {
            return null;
        }

        try {
            return OrderResource::getUrl('view', ['record' => $order], tenant: $restaurant);
        } catch (\Throwable) {
            return null;
        }
    }
}
