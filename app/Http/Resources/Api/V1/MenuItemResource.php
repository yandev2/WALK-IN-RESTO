<?php

namespace App\Http\Resources\Api\V1;

use App\Models\MenuItem;
use App\Support\CmsMedia;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin MenuItem */
class MenuItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => (int) $this->effectivePrice(),
            'original_price' => (int) $this->price,
            'discount_percent' => $this->hasDiscount() ? (int) $this->discount_percent : null,
            'photo_url' => CmsMedia::url($this->photo_path),
            'photo_urls' => $this->photoUrls(),
            'is_out_of_stock' => (bool) $this->is_out_of_stock,
            'category_id' => $this->category_id,
            'category_name' => $this->relationLoaded('category') ? $this->category?->name : null,
            'variants' => MenuVariantResource::collection($this->whenLoaded('variants')),
            'modifier_groups' => ModifierGroupResource::collection($this->whenLoaded('modifierGroups')),
        ];
    }
}
