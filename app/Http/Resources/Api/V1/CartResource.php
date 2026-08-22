<?php

namespace App\Http\Resources\Api\V1;

use App\Models\VisitCartItem;
use App\Support\CheckoutTotals;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class CartResource extends JsonResource
{
    /**
     * @param  array{items: Collection<int, VisitCartItem>, subtotal: int, outlet: mixed}  $resource
     */
    public function toArray(Request $request): array
    {
        $totals = CheckoutTotals::forSubtotal(
            (int) $this->resource['subtotal'],
            $this->resource['outlet'] ?? null,
        );

        return [
            ...$totals,
            'items' => CartItemResource::collection($this->resource['items']),
        ];
    }
}
