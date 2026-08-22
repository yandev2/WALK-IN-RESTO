<?php

namespace App\Http\Controllers\Api\V1\Guest;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\MenuCategoryResource;
use App\Models\MenuCategory;
use App\Support\GuestContext;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    public function index(): JsonResponse
    {
        $visit = GuestContext::visit();

        $categories = MenuCategory::query()
            ->where('outlet_id', $visit?->outlet_id)
            ->where('is_active', true)
            ->with([
                'items' => fn ($query) => $query
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->with([
                        'variants' => fn ($variants) => $variants->where('is_active', true)->orderBy('sort_order'),
                        'modifierGroups.modifiers' => fn ($modifiers) => $modifiers->where('is_active', true)->orderBy('sort_order'),
                    ]),
            ])
            ->orderBy('sort_order')
            ->get();

        return MenuCategoryResource::collection($categories)->response();
    }
}
