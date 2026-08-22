<?php

namespace App\Http\Controllers\Api\V1\Guest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StorePaymentProofRequest;
use App\Http\Resources\Api\V1\OrderResource;
use App\Http\Resources\Api\V1\PaymentResource;
use App\Models\Order;
use App\Services\PaymentProofService;
use App\Support\GuestContext;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function index(): JsonResponse
    {
        $orders = GuestContext::visit()
            ? GuestContext::visit()->orders()->with(['items.modifiers', 'payments'])->latest('id')->get()
            : collect();

        return OrderResource::collection($orders)->response();
    }

    public function show(Order $order): JsonResponse
    {
        $this->assertOwned($order);

        return (new OrderResource($order->load(['items.modifiers', 'payments'])))->response();
    }

    public function storeProof(StorePaymentProofRequest $request, Order $order, PaymentProofService $proofs): JsonResponse
    {
        $this->assertOwned($order);

        $payment = $proofs->store(GuestContext::visit(), $order, $request->file('proof'));

        return (new PaymentResource($payment))->response();
    }

    private function assertOwned(Order $order): void
    {
        abort_unless((int) $order->visit_id === (int) GuestContext::visit()?->id, 403);
    }
}
