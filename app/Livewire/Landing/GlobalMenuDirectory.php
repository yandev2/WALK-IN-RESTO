<?php

namespace App\Livewire\Landing;

use App\Models\MenuItem;
use App\Models\PlatformSetting;
use App\Support\CmsMedia;
use App\Support\GeoDistance;
use App\Support\RestaurantDirectory as DirectoryQuery;
use App\Support\RestaurantTheme;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class GlobalMenuDirectory extends Component
{
    use WithPagination;

    public string $search = '';

    /** @var list<int> */
    public array $categoryIds = [];

    public string $sort = 'popular';

    public ?float $userLat = null;

    public ?float $userLng = null;

    public ?float $userAccuracyM = null;

    public string $locationStatus = 'idle';

    public ?float $maxDistanceKm = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryIds' => ['as' => 'kategori', 'except' => []],
        'sort' => ['except' => 'popular'],
        'maxDistanceKm' => ['as' => 'jarak', 'except' => null],
    ];

    public function mount(): void
    {
        if ($this->sort === 'distance' && ! $this->hasUserLocation()) {
            $this->sort = 'popular';
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategoryIds(): void
    {
        $this->resetPage();
    }

    public function updatedSort(): void
    {
        $this->resetPage();
    }

    public function updatedMaxDistanceKm(): void
    {
        if (blank($this->maxDistanceKm) || (string) $this->maxDistanceKm === 'all') {
            $this->maxDistanceKm = null;
        } else {
            $this->maxDistanceKm = max(1, min(100, (float) $this->maxDistanceKm));
        }

        $this->resetPage();
    }

    public function applySearch(): void
    {
        $this->resetPage();
    }

    public function setCategoryFilter(mixed $id = null): void
    {
        $this->categoryIds = filled($id) ? [(int) $id] : [];
        $this->resetPage();
    }

    public function beginLocationRequest(): void
    {
        if ($this->locationStatus === 'granted') {
            return;
        }

        $this->locationStatus = 'pending';
    }

    public function setUserLocation(?float $lat = null, ?float $lng = null, ?float $accuracy = null): void
    {
        if ($lat === null || $lng === null) {
            $this->clearUserLocation('denied');

            return;
        }

        $this->userLat = $lat;
        $this->userLng = $lng;
        $this->userAccuracyM = $accuracy;
        $this->locationStatus = 'granted';
        $this->sort = 'distance';
        $this->resetPage();
    }

    public function reportLocationUnsupported(): void
    {
        $this->clearUserLocation('unsupported');
    }

    public function reportLocationInsecure(): void
    {
        $this->clearUserLocation('insecure');
    }

    public function reportLocationError(): void
    {
        $this->clearUserLocation('error');
    }

    public function disableLocationSearch(): void
    {
        $this->clearUserLocation('idle');
    }

    public function clearUserLocation(string $status = 'denied'): void
    {
        $this->userLat = null;
        $this->userLng = null;
        $this->userAccuracyM = null;
        $this->locationStatus = $status;

        if ($this->sort === 'distance') {
            $this->sort = 'popular';
        }

        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->categoryIds = [];
        $this->maxDistanceKm = null;
        $this->sort = $this->hasUserLocation() ? 'distance' : 'popular';
        $this->resetPage();
    }

    public function hasUserLocation(): bool
    {
        return $this->locationStatus === 'granted'
            && filled($this->userLat)
            && filled($this->userLng);
    }

    #[Computed]
    public function categories(): Collection
    {
        return app(DirectoryQuery::class)->activeCategories();
    }

    public function setSort(string $sort): void
    {
        $this->sort = $sort;
        $this->resetPage();
    }

    public function setMaxDistance(mixed $distance = null): void
    {
        if (blank($distance) || (string) $distance === 'all' || (string) $distance === 'null') {
            $this->maxDistanceKm = null;
        } else {
            $this->maxDistanceKm = max(1, min(100, (float) $distance));
        }

        $this->resetPage();
    }

    #[Computed]
    public function sortOptions(): array
    {
        $options = [
            ['value' => 'popular', 'label' => 'Terpopuler'],
        ];

        if ($this->hasUserLocation()) {
            $options[] = ['value' => 'distance', 'label' => 'Jarak Terdekat'];
        }

        $options[] = ['value' => 'price_asc', 'label' => 'Harga: Termurah'];
        $options[] = ['value' => 'price_desc', 'label' => 'Harga: Termahal'];
        $options[] = ['value' => 'newest', 'label' => 'Menu Terbaru'];

        return $options;
    }

    #[Computed]
    public function distanceOptions(): array
    {
        return [
            ['value' => null, 'label' => 'Semua Jarak'],
            ['value' => 3, 'label' => 'Radius 3 km'],
            ['value' => 5, 'label' => 'Radius 5 km'],
            ['value' => 10, 'label' => 'Radius 10 km'],
            ['value' => 25, 'label' => 'Radius 25 km'],
        ];
    }

    public function render()
    {
        $paginator = $this->queryMenuItems(perPage: 16);
        $home = PlatformSetting::homeViewData();

        $sortLabels = [
            'popular' => 'Terpopuler',
            'distance' => 'Jarak Terdekat',
            'price_asc' => 'Harga: Termurah',
            'price_desc' => 'Harga: Termahal',
            'newest' => 'Menu Terbaru',
        ];

        return view('livewire.landing.global-menu-directory', [
            'theme' => RestaurantTheme::for(null),
            'home' => $home,
            'categories' => $this->categories,
            'paginator' => $paginator,
            'items' => $paginator->items(),
            'totalCount' => $paginator->total(),
            'currentSortLabel' => $sortLabels[$this->sort] ?? 'Terpopuler',
            'currentDistanceLabel' => $this->maxDistanceKm ? "Radius {$this->maxDistanceKm} km" : 'Semua Jarak',
            'hasActiveFilters' => ! empty($this->categoryIds) || filled($this->search) || filled($this->maxDistanceKm),
        ])->layout('layouts.directory', [
            'home' => $home,
            'theme' => RestaurantTheme::for(null),
            'title' => 'Daftar Menu Restoran & Kuliner - ' . ($home['site_name'] ?? 'RestoTerdekat'),
            'description' => 'Jelajahi seluruh daftar menu makanan dan minuman lezat dari restoran terbaik di sekitar Anda.',
        ]);
    }

    protected function queryMenuItems(int $perPage = 16): LengthAwarePaginator
    {
        $query = MenuItem::query()
            ->where('is_active', true)
            ->whereHas('restaurant', fn (Builder $r) => $r->listedInDirectory())
            ->with([
                'restaurant.defaultOutlet',
                'restaurant.categories',
                'photos',
            ]);

        if (! empty($this->categoryIds)) {
            $query->whereHas('restaurant.categories', function (Builder $c) {
                $c->whereIn('restaurant_categories.id', $this->categoryIds);
            });
        }

        if (filled($this->search)) {
            $driver = DB::connection()->getDriverName();
            $likeOperator = $driver === 'pgsql' ? 'ilike' : 'like';
            $term = '%' . trim($this->search) . '%';

            $query->where(function (Builder $builder) use ($term, $likeOperator) {
                $builder->where('name', $likeOperator, $term)
                    ->orWhereHas('restaurant', fn (Builder $r) => $r->where('name', $likeOperator, $term));
            });
        }

        $collection = $query->get();

        if ($this->hasUserLocation()) {
            $userLat = (float) $this->userLat;
            $userLng = (float) $this->userLng;

            $collection = $collection->map(function (MenuItem $item) use ($userLat, $userLng) {
                $outlet = $item->restaurant?->defaultOutlet;
                $dist = ($outlet && filled($outlet->latitude) && filled($outlet->longitude))
                    ? GeoDistance::kilometers($userLat, $userLng, (float) $outlet->latitude, (float) $outlet->longitude)
                    : null;
                $item->setAttribute('_distance_km', $dist);

                return $item;
            });

            if ($this->maxDistanceKm !== null) {
                $collection = $collection->filter(function (MenuItem $item) {
                    $dist = $item->getAttribute('_distance_km');

                    return $dist !== null && $dist <= $this->maxDistanceKm;
                });
            }
        }

        // Sorting
        $collection = match ($this->sort) {
            'distance' => $collection->sortBy(fn (MenuItem $item) => $item->getAttribute('_distance_km') ?? PHP_FLOAT_MAX)->values(),
            'price_asc' => $collection->sortBy(fn (MenuItem $item) => $item->effectivePrice())->values(),
            'price_desc' => $collection->sortByDesc(fn (MenuItem $item) => $item->effectivePrice())->values(),
            'newest' => $collection->sortByDesc('created_at')->values(),
            default => $collection->sort(function (MenuItem $a, MenuItem $b): int {
                $sellerA = (int) ($a->is_best_seller ?? 0);
                $sellerB = (int) ($b->is_best_seller ?? 0);
                if ($sellerA !== $sellerB) {
                    return $sellerB <=> $sellerA;
                }

                return $b->created_at <=> $a->created_at;
            })->values(),
        };

        $page = max(1, (int) $this->getPage());
        $total = $collection->count();
        $slice = $collection->slice(($page - 1) * $perPage, $perPage)->values();

        $mappedCards = $slice->map(function (MenuItem $item) {
            $hidePrices = (bool) ($item->restaurant?->defaultOutlet?->hide_landing_menu_prices ?? false);

            return [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'hide_price' => $hidePrices,
                'price' => $hidePrices ? null : $item->price,
                'price_formatted' => $hidePrices ? null : 'Rp ' . number_format((float) $item->price, 0, ',', '.'),
                'has_discount' => ! $hidePrices && $item->hasDiscount(),
                'discount_percent' => ! $hidePrices ? $item->discount_percent : null,
                'effective_price' => $hidePrices ? null : $item->effectivePrice(),
                'effective_price_formatted' => $hidePrices ? null : 'Rp ' . number_format((float) $item->effectivePrice(), 0, ',', '.'),
                'is_best_seller' => (bool) $item->is_best_seller,
                'photo_url' => CmsMedia::url($item->photo_path),
                'photos' => $item->photoUrls(),
                'restaurant_name' => $item->restaurant?->name,
                'restaurant_slug' => $item->restaurant?->slug,
                'restaurant_categories' => $item->restaurant?->displayCategoriesLabel(),
                'landing_url' => $item->restaurant ? route('landing.show', $item->restaurant) : null,
                'distance_km' => $item->getAttribute('_distance_km'),
                'distance_label' => GeoDistance::label($item->getAttribute('_distance_km')),
            ];
        })->all();

        return new Paginator(
            $mappedCards,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()],
        );
    }
}
