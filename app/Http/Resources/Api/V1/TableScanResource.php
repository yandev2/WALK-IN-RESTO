<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TableScanResource extends JsonResource
{
    /**
     * @param  array{mode: string, message: string, table: mixed, visit: mixed}  $resource
     */
    public function toArray(Request $request): array
    {
        $table = $this->resource['table'] ?? null;
        $visit = $this->resource['visit'] ?? null;
        $restaurant = $table?->outlet?->restaurant ?? $visit?->outlet?->restaurant;

        if ($restaurant && ! $restaurant->relationLoaded('cmsProfile')) {
            $restaurant->load('cmsProfile');
        }

        return [
            'mode' => $this->resource['mode'],
            'message' => $this->resource['message'],
            'table_code' => $table?->code,
            'restaurant' => $restaurant ? new RestaurantBrandResource($restaurant) : null,
            'visit' => $visit ? (new VisitResource($visit))->resolve() : null,
        ];
    }
}
