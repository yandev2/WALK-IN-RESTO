<?php

namespace App\Http\Controllers\Api\V1\Guest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreRestaurantReviewRequest;
use App\Http\Resources\Api\V1\RestaurantReviewResource;
use App\Services\RestaurantReviewService;
use App\Support\GuestContext;
use App\Support\VisitReviewStatus;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    public function show(): JsonResponse
    {
        $visit = GuestContext::visit();

        return response()->json([
            'data' => VisitReviewStatus::for($visit),
        ]);
    }

    public function store(StoreRestaurantReviewRequest $request, RestaurantReviewService $reviews): JsonResponse
    {
        $visit = GuestContext::visit();

        $review = $reviews->submit(
            $visit,
            (int) $request->validated('rating'),
            (string) $request->validated('comment'),
        );

        return (new RestaurantReviewResource($review))
            ->response()
            ->setStatusCode(201);
    }
}
