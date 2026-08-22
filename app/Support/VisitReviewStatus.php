<?php

namespace App\Support;

use App\Http\Resources\Api\V1\RestaurantReviewResource;
use App\Models\Visit;
use App\Services\RestaurantReviewService;

final class VisitReviewStatus
{
    /**
     * @return array{portal_open: bool, can_submit: bool, submitted: bool, review: RestaurantReviewResource|null}
     */
    public static function for(Visit $visit): array
    {
        $visit->loadMissing('review');

        $service = app(RestaurantReviewService::class);
        $submitted = $visit->review !== null;

        return [
            'portal_open' => $service->portalOpen($visit),
            'can_submit' => $service->canSubmit($visit),
            'submitted' => $submitted,
            'review' => $submitted
                ? (new RestaurantReviewResource($visit->review))->resolve()
                : null,
        ];
    }
}
