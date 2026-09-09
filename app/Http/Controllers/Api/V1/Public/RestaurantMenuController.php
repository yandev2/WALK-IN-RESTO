<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\MenuCategoryResource;
use App\Http\Resources\Api\V1\MenuItemResource;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Support\MenuSearch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RestaurantMenuController extends Controller
{
    public function index(Request $request, Restaurant $restaurant): JsonResponse
    {
        abort_unless($restaurant->isLandingPublic(), 404);

        $outlet = $restaurant->defaultOutlet;

        if (! $outlet) {
            return response()->json([
                'data' => [
                    'categories' => [],
                    'items' => [],
                ],
                'meta' => [
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => 12,
                    'total' => 0,
                ],
            ]);
        }

        $categories = MenuCategory::query()
            ->where('outlet_id', $outlet->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'name']);

        $normalizedSearch = MenuSearch::normalize((string) $request->query('search', ''));
        $categoryId = $request->query('category_id');
        $sort = strtolower((string) $request->query('sort', 'asc')) === 'desc' ? 'desc' : 'asc';
        $perPage = min(48, max(1, (int) $request->query('per_page', 12)));

        $hidePrices = (bool) ($outlet->hide_landing_menu_prices ?? false);

        $items = MenuItem::query()
            ->where('outlet_id', $outlet->id)
            ->where('is_active', true)
            ->with(['category', 'photos'])
            ->when($normalizedSearch !== '', fn ($query) => $query->whereRaw(
                "LOWER(REPLACE(name, ' ', '')) LIKE ?",
                ['%'.$normalizedSearch.'%'],
            ))
            ->when(filled($categoryId), fn ($query) => $query->where('category_id', $categoryId))
            ->when(! $hidePrices, fn ($query) => $query->orderByEffectivePrice($sort))
            ->orderBy('sort_order')
            ->paginate($perPage);

        if ($hidePrices) {
            $items->getCollection()->transform(function (MenuItem $item) {
                $item->price_hidden = true;

                return $item;
            });
        }

        return response()->json([
            'data' => [
                'categories' => MenuCategoryResource::collection($categories),
                'items' => MenuItemResource::collection($items),
            ],
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ],
        ]);
    }
}
