<?php

namespace App\Http\Resources\Api\V1;

use App\Models\Restaurant;
use App\Support\CmsMedia;
use App\Support\RestaurantRatingSummary;
use App\Support\RestaurantTheme;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Restaurant */
class RestaurantSummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $outlet = $this->defaultOutlet;
        $ratingSummary = RestaurantRatingSummary::for($this->resource);

        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'logo_url' => CmsMedia::url($this->logo_path),
            'theme' => RestaurantTheme::for($this->resource),
            'headline' => $this->cmsProfile?->headline,
            'address' => $outlet?->address,
            'is_open_now' => (bool) $outlet?->isOpenNow(),
            'rating_average' => $ratingSummary['average'],
            'rating_count' => $ratingSummary['count'],
        ];
    }
}
