<?php

namespace App\Http\Resources\Api\V1;

use App\Models\Visit;
use App\Support\GuestContext;
use App\Support\VisitReviewStatus;
use App\Support\WhatsAppNumber;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Visit */
class VisitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $this->loadMissing(['diningTable', 'outlet.restaurant.cmsProfile']);

        $restaurant = $this->outlet?->restaurant;
        $device = GuestContext::device();
        $isHost = $device ? $device->is_host : true;

        return [
            'public_id' => $this->public_id,
            'status' => $this->status,
            'join_pin' => $this->join_pin,
            'customer_wa' => $isHost ? $this->customer_wa : WhatsAppNumber::mask($this->customer_wa),
            'customer_name' => $this->customer_name,
            'table_code' => $this->diningTable?->code,
            'restaurant_slug' => $restaurant?->slug,
            'restaurant' => $restaurant ? new RestaurantBrandResource($restaurant) : null,
            'claim_expires_at' => $this->claim_expires_at?->toIso8601String(),
            'has_fonnte' => $restaurant?->hasFonnteKey() ?? false,
            'review' => VisitReviewStatus::for($this->resource),
        ];
    }
}
