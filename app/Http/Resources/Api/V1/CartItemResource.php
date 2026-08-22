<?php

namespace App\Http\Resources\Api\V1;

use App\Models\VisitCartItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin VisitCartItem */
class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'menu_item_id' => $this->menu_item_id,
            'menu_variant_id' => $this->menu_variant_id,
            'name' => $this->menuItem?->name,
            'variant_name' => $this->variant?->name,
            'qty' => (int) $this->qty,
            'notes' => $this->notes,
            'line_total' => $this->lineTotal(),
            'modifiers' => $this->modifiers->map(fn ($modifier) => [
                'id' => $modifier->id,
                'name' => $modifier->name,
                'price' => (int) $modifier->price,
            ])->values(),
        ];
    }
}
