<?php

namespace App\Http\Resources\Api\V1;

use App\Models\RestaurantReview;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin RestaurantReview */
class RestaurantReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_name' => $this->displayName(),
            'rating' => (int) $this->rating,
            'comment' => $this->comment,
            'submitted_at' => $this->submitted_at?->toIso8601String(),
        ];
    }
}
