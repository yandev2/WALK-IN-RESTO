<?php

namespace App\Http\Resources\Api\V1;

use App\Models\Restaurant;
use App\Support\CmsMedia;
use App\Support\RestaurantTheme;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Restaurant */
class RestaurantBrandResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $this->loadMissing('cmsProfile');

        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'logo_url' => CmsMedia::url($this->logo_path),
            'theme' => RestaurantTheme::for($this->resource),
        ];
    }
}
