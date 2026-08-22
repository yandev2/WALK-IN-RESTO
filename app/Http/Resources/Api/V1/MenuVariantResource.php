<?php

namespace App\Http\Resources\Api\V1;

use App\Models\MenuVariant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin MenuVariant */
class MenuVariantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price_delta' => (int) $this->price_delta,
        ];
    }
}
