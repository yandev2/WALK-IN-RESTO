<?php

namespace App\Http\Resources\Api\V1;

use App\Models\ModifierGroup;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ModifierGroup */
class ModifierGroupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'min_select' => $this->minRequired(),
            'max_select' => $this->maxAllowed(),
            'is_required' => (bool) $this->is_required,
            'modifiers' => ModifierResource::collection(
                $this->whenLoaded('modifiers', fn () => $this->modifiers->where('is_active', true)->values()),
            ),
        ];
    }
}
