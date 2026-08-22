<?php

namespace App\Http\Controllers\Api\V1\Guest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AddCartItemRequest;
use App\Http\Requests\Api\V1\UpdateCartItemRequest;
use App\Http\Resources\Api\V1\CartItemResource;
use App\Http\Resources\Api\V1\CartResource;
use App\Models\VisitCartItem;
use App\Services\GuestCartService;
use App\Support\GuestContext;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    public function show(): JsonResponse
    {
        return $this->cartResponse();
    }

    public function store(AddCartItemRequest $request, GuestCartService $cart): JsonResponse
    {
        $item = $cart->add(
            GuestContext::visit(),
            (int) $request->validated('menu_item_id'),
            $request->validated('menu_variant_id') ? (int) $request->validated('menu_variant_id') : null,
            (int) ($request->validated('qty') ?? 1),
            $request->validated('notes'),
            $request->validated('modifier_ids') ?? [],
        );

        return (new CartItemResource($item->load(['menuItem', 'variant', 'modifiers'])))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateCartItemRequest $request, VisitCartItem $cartItem, GuestCartService $cart): JsonResponse
    {
        $visit = GuestContext::visit();
        abort_unless((int) $cartItem->visit_id === (int) $visit?->id, 404);

        $cart->updateQty($visit, $cartItem, (int) $request->validated('qty'));

        return $this->cartResponse();
    }

    public function destroy(VisitCartItem $cartItem, GuestCartService $cart): JsonResponse
    {
        $visit = GuestContext::visit();
        abort_unless((int) $cartItem->visit_id === (int) $visit?->id, 404);

        $cart->remove($visit, $cartItem);

        return $this->cartResponse();
    }

    private function cartResponse(): JsonResponse
    {
        $visit = GuestContext::visit();
        $items = $visit
            ? $visit->cartItems()->with(['menuItem', 'variant', 'modifiers'])->get()
            : collect();

        $visit?->loadMissing('outlet');

        return (new CartResource([
            'items' => $items,
            'subtotal' => $items->sum(fn (VisitCartItem $item) => $item->lineTotal()),
            'outlet' => $visit?->outlet,
        ]))->response();
    }
}
