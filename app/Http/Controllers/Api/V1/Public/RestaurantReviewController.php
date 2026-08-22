<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\RestaurantReviewResource;
use App\Models\Restaurant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RestaurantReviewController extends Controller
{
    public function index(Request $request, Restaurant $restaurant): JsonResponse
    {
        abort_unless($restaurant->isLandingPublic(), 404);

        $perPage = min(48, max(1, (int) $request->query('per_page', 12)));

        $reviews = $restaurant->reviews()
            ->latest('submitted_at')
            ->latest('id')
            ->paginate($perPage);

        return RestaurantReviewResource::collection($reviews)->response();
    }
}
