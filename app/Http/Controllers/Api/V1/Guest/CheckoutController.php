<?php

namespace App\Http\Controllers\Api\V1\Guest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CheckoutRequest;
use App\Http\Resources\Api\V1\OrderResource;
use App\Services\GuestCheckoutService;
use App\Support\GuestContext;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function store(CheckoutRequest $request, GuestCheckoutService $checkout): JsonResponse
    {
        $idempotencyKey = $request->header('Idempotency-Key') ?: $request->validated('idempotency_key');

        if (blank($idempotencyKey)) {
            throw ValidationException::withMessages([
                'idempotency_key' => 'Idempotency-Key wajib.',
            ]);
        }

        $order = $checkout->checkout(
            GuestContext::visit(),
            (string) $request->validated('method'),
            (bool) ($request->validated('send_receipt') ?? false),
            (string) $idempotencyKey,
            $request->validated('gps') ?? [],
        );

        return (new OrderResource($order->load(['items.modifiers', 'payments'])))
            ->response()
            ->setStatusCode(201);
    }
}
