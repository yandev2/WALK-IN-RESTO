<?php

namespace App\Http\Resources\Api\V1;

use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin MenuCategory */
class MenuCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'items' => MenuItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
