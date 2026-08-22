<?php

namespace App\Http\Resources\Api\V1;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin OrderItem */
class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name_snapshot,
            'variant_name' => $this->variant_name_snapshot,
            'qty' => (int) $this->qty,
            'unit_price' => (int) $this->unit_price,
            'notes' => $this->notes,
            'kds_status' => $this->kds_status,
            'timer_minutes' => $this->elapsedMinutes(),
            'timer_band' => $this->timerBand(),
            'modifiers' => $this->whenLoaded('modifiers', fn () => $this->modifiers->map(fn ($modifier) => [
                'name' => $modifier->name_snapshot,
                'price' => (int) $modifier->price_snapshot,
            ])->values()),
        ];
    }
}
