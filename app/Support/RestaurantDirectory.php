<?php

namespace App\Support;

use App\Models\Restaurant;
use App\Models\RestaurantCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Collection;

final class RestaurantDirectory
{
    /**
     * @param  array{
     *     search?: string|null,
     *     categoryIds?: list<int>,
     *     facilities?: list<string>,
     *     sort?: string|null,
     *     userLat?: float|null,
     *     userLng?: float|null,
     *     maxDistanceKm?: float|null,
     * }  $filters
     */
    public function paginate(array $filters = [], int $perPage = 6, int $page = 1): LengthAwarePaginator
    {
        $perPage = max(1, $perPage);
        $page = max(1, $page);
        $restaurants = $this->filteredRestaurants($filters);
        $total = $restaurants->count();
        $items = $restaurants
            ->slice(($page - 1) * $perPage, $perPage)
            ->values()
            ->all();

        return new Paginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()],
        );
    }

    /**
     * @param  array{
     *     search?: string|null,
     *     categoryIds?: list<int>,
     *     facilities?: list<string>,
     *     sort?: string|null,
     *     userLat?: float|null,
     *     userLng?: float|null,
     *     maxDistanceKm?: float|null,
     * }  $filters
     */
    public function count(array $filters = []): int
    {
        return $this->filteredRestaurants($filters)->count();
    }

    /**
     * @param  array{
     *     search?: string|null,
     *     categoryIds?: list<int>,
     *     facilities?: list<string>,
     *     sort?: string|null,
     *     userLat?: float|null,
     *     userLng?: float|null,
     *     maxDistanceKm?: float|null,
     * }  $filters
     * @return list<array<string, mixed>>
     */
    public function mapCardsForPaginator(LengthAwarePaginator $paginator): array
    {
        return collect($paginator->items())
            ->map(fn (Restaurant $restaurant) => $this->toCard($restaurant))
            ->values()
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function mapCards(Collection $restaurants): array
    {
        return $restaurants
            ->map(fn (Restaurant $restaurant) => $this->toCard($restaurant))
            ->values()
            ->all();
    }

    /**
     * @return Collection<int, Restaurant>
     */
    public function recommendedRestaurants(int $limit = 6): Collection
    {
        return Restaurant::query()
            ->recommended()
            ->with([
                'cmsProfile',
                'categories',
                'defaultOutlet.operatingHours',
                'defaultOutlet.closedDates',
                'defaultOutlet.restaurant',
            ])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderBy('name')
            ->limit($limit)
            ->get();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function mapRecommendedCards(int $limit = 6): array
    {
        return $this->recommendedRestaurants($limit)
            ->map(function (Restaurant $restaurant): array {
                $card = $this->toCard($restaurant);
                $card['hero_url'] = CmsMedia::url($restaurant->cmsProfile?->hero_image_path)
                    ?: $card['cover_url'];

                return $card;
            })
            ->values()
            ->all();
    }

    /**
     * @return Collection<int, RestaurantCategory>
     */
    public function activeCategories(): Collection
    {
        return RestaurantCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    /**
     * @param  array{
     *     search?: string|null,
     *     categoryIds?: list<int>,
     *     facilities?: list<string>,
     *     sort?: string|null,
     *     userLat?: float|null,
     *     userLng?: float|null,
     *     maxDistanceKm?: float|null,
     * }  $filters
     * @return Collection<int, Restaurant>
     */
    private function filteredRestaurants(array $filters): Collection
    {
        $sort = (string) ($filters['sort'] ?? 'newest');
        $userLat = $this->nullableFloat($filters['userLat'] ?? null);
        $userLng = $this->nullableFloat($filters['userLng'] ?? null);
        $rawMaxDistance = $filters['maxDistanceKm'] ?? null;
        $maxDistanceKm = filled($rawMaxDistance) && (float) $rawMaxDistance > 0 ? (float) $rawMaxDistance : null;
        $hasUserLocation = $userLat !== null && $userLng !== null;

        $restaurants = $this->baseQuery($filters)->get();

        if (! $hasUserLocation) {
            return $this->sortRestaurants($restaurants, $sort);
        }

        $restaurants = $restaurants->map(function (Restaurant $restaurant) use ($userLat, $userLng): Restaurant {
            $outlet = $restaurant->defaultOutlet;

            if (! $outlet || blank($outlet->latitude) || blank($outlet->longitude)) {
                $restaurant->setAttribute('_distance_km', null);

                return $restaurant;
            }

            $restaurant->setAttribute(
                '_distance_km',
                GeoDistance::kilometers(
                    $userLat,
                    $userLng,
                    (float) $outlet->latitude,
                    (float) $outlet->longitude,
                ),
            );

            return $restaurant;
        });

        if ($maxDistanceKm !== null) {
            $restaurants = $restaurants->filter(function (Restaurant $restaurant) use ($maxDistanceKm): bool {
                $distanceKm = $restaurant->getAttribute('_distance_km');

                return $distanceKm !== null && $distanceKm <= $maxDistanceKm;
            });
        }

        return $this->sortRestaurants($restaurants, $sort);
    }

    /**
     * @param  Collection<int, Restaurant>  $restaurants
     * @return Collection<int, Restaurant>
     */
    private function sortRestaurants(Collection $restaurants, string $sort): Collection
    {
        return match ($sort) {
            'distance' => $restaurants
                ->sortBy(fn (Restaurant $restaurant) => $restaurant->getAttribute('_distance_km') ?? PHP_FLOAT_MAX)
                ->values(),
            'rating' => $restaurants
                ->sort(function (Restaurant $a, Restaurant $b): int {
                    $ratingCompare = ((float) ($b->reviews_avg_rating ?? 0)) <=> ((float) ($a->reviews_avg_rating ?? 0));
                    if ($ratingCompare !== 0) {
                        return $ratingCompare;
                    }

                    $countCompare = ((int) ($b->reviews_count ?? 0)) <=> ((int) ($a->reviews_count ?? 0));
                    if ($countCompare !== 0) {
                        return $countCompare;
                    }

                    return strcmp($a->name, $b->name);
                })
                ->values(),
            'name' => $restaurants->sortBy('name')->values(),
            default => $restaurants
                ->sort(function (Restaurant $a, Restaurant $b): int {
                    $dateCompare = $b->created_at <=> $a->created_at;
                    if ($dateCompare !== 0) {
                        return $dateCompare;
                    }

                    return strcmp($a->name, $b->name);
                })
                ->values(),
        };
    }

    /**
     * @param  array{
     *     search?: string|null,
     *     categoryIds?: list<int>,
     *     facilities?: list<string>,
     *     sort?: string|null,
     * }  $filters
     */
    private function baseQuery(array $filters): Builder
    {
        $search = trim((string) ($filters['search'] ?? ''));
        $categoryIds = collect($filters['categoryIds'] ?? [])
            ->filter(fn ($id) => filled($id))
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();
        $facilities = collect($filters['facilities'] ?? [])
            ->filter(fn ($key) => filled($key))
            ->values()
            ->all();

        $query = Restaurant::query()
            ->listedInDirectory()
            ->with([
                'cmsProfile',
                'categories',
                'defaultOutlet.operatingHours',
                'defaultOutlet.closedDates',
                'defaultOutlet.restaurant',
            ])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        if ($search !== '') {
            $likeOperator = \Illuminate\Support\Facades\DB::connection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';

            $query->where(function (Builder $builder) use ($search, $likeOperator): void {
                $term = '%'.$search.'%';

                $builder
                    ->where('name', $likeOperator, $term)
                    ->orWhereHas('cmsProfile', fn (Builder $profile) => $profile->where('headline', $likeOperator, $term))
                    ->orWhereHas('defaultOutlet', fn (Builder $outlet) => $outlet->where('address', $likeOperator, $term));
            });
        }

        if ($categoryIds !== []) {
            $query->whereHas('categories', fn (Builder $categories) => $categories->whereIn('restaurant_categories.id', $categoryIds));
        }

        foreach ($facilities as $facility) {
            $query->where("facilities->{$facility}", true);
        }

        return $query;
    }

    /**
     * @return array<string, mixed>
     */
    public function toCard(Restaurant $restaurant): array
    {
        $outlet = $restaurant->defaultOutlet;
        $ratingSummary = RestaurantRatingSummary::for($restaurant);
        $todayHours = $outlet?->todayHours();
        $distanceKm = $restaurant->getAttribute('_distance_km');

        return [
            'slug' => $restaurant->slug,
            'name' => $restaurant->name,
            'headline' => $restaurant->cmsProfile?->headline,
            'cover_url' => CmsMedia::url($restaurant->cmsProfile?->hero_image_path)
                ?: CmsMedia::url($restaurant->logo_path),
            'logo_url' => CmsMedia::url($restaurant->logo_path),
            'categories_label' => $restaurant->displayCategoriesLabel(),
            'price_label' => $restaurant->priceLevelLabel(),
            'rating_average' => $ratingSummary['average'],
            'rating_count' => $ratingSummary['count'],
            'is_open_now' => (bool) $outlet?->isOpenNow(),
            'hours_label' => $this->hoursLabel($outlet?->isOpenNow(), $todayHours),
            'address' => $outlet?->address,
            'lat' => $outlet?->latitude ? (float) $outlet->latitude : null,
            'lng' => $outlet?->longitude ? (float) $outlet->longitude : null,
            'distance_km' => $distanceKm,
            'distance_label' => GeoDistance::label(is_numeric($distanceKm) ? (float) $distanceKm : null),
            'landing_url' => route('landing.show', $restaurant),
        ];
    }

    private function hoursLabel(?bool $isOpen, $todayHours): string
    {
        if (! $isOpen) {
            return 'Tutup';
        }

        if (! $todayHours || blank($todayHours->opens_at) || blank($todayHours->closes_at)) {
            return 'Buka';
        }

        $open = \Illuminate\Support\Carbon::parse($todayHours->opens_at)->format('H.i');
        $close = \Illuminate\Support\Carbon::parse($todayHours->closes_at)->format('H.i');

        return "{$open}–{$close}";
    }

    private function nullableFloat(mixed $value): ?float
    {
        if (! filled($value)) {
            return null;
        }

        return (float) $value;
    }
}
