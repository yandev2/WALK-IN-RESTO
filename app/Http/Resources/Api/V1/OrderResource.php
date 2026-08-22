<?php

namespace App\Http\Resources\Api\V1;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Order */
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $this->loadMissing(['items.modifiers', 'payments']);
        $payment = $this->payments->sortByDesc('id')->first();

        return [
            'public_id' => $this->public_id,
            'number' => $this->number,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'subtotal' => (int) $this->subtotal,
            'service_amount' => (int) $this->service_amount,
            'pb1_amount' => (int) $this->pb1_amount,
            'grand_before' => (int) $this->grand_before,
            'grand_payable' => (int) $this->grand_payable,
            'send_receipt' => (bool) $this->send_receipt,
            'items' => OrderItemResource::collection($this->items),
            'payment' => $payment ? new PaymentResource($payment) : null,
        ];
    }
}
