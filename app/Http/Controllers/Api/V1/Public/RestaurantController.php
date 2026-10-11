<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\RestaurantResource;
use App\Http\Resources\Api\V1\RestaurantSummaryResource;
use App\Models\Restaurant;
use Illuminate\Http\JsonResponse;

class RestaurantController extends Controller
{
    public function index(): JsonResponse
    {
        $restaurants = Restaurant::query()
            ->listedInDirectory()
            ->with(['cmsProfile', 'defaultOutlet.operatingHours', 'defaultOutlet.closedDates', 'defaultOutlet.restaurant'])
            ->withAvg(['reviews' => fn ($q) => $q->published()], 'rating')
            ->withCount(['reviews' => fn ($q) => $q->published()])
            ->orderBy('name')
            ->get();

        return RestaurantSummaryResource::collection($restaurants)->response();
    }

    public function show(Restaurant $restaurant): JsonResponse
    {
        abort_unless($restaurant->isLandingPublic(), 404);

        $restaurant->load([
            'cmsProfile',
            'defaultOutlet.operatingHours',
            'defaultOutlet.closedDates',
            'defaultOutlet.restaurant',
            'cmsFaqs' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order')->orderBy('id'),
            'cmsGalleryImages' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order')->orderBy('id'),
            'cmsBanners' => fn ($query) => $query->currentlyLive()->orderBy('sort_order')->orderBy('id'),
            'reviews' => fn ($query) => $query->published()->latest('submitted_at')->latest('id')->limit(6),
        ]);

        $restaurant->loadAvg(['reviews' => fn ($q) => $q->published()], 'rating');
        $restaurant->loadCount(['reviews' => fn ($q) => $q->published()]);

        return (new RestaurantResource($restaurant))->response();
    }
}
