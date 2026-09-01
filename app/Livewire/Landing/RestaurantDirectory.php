<?php

namespace App\Livewire\Landing;

use App\Models\Facility;
use App\Models\PlatformSetting;
use App\Support\RestaurantDirectory as DirectoryQuery;
use App\Support\RestaurantTheme;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class RestaurantDirectory extends Component
{
    use WithPagination;

    public string $search = '';

    /** @var list<int> */
    public array $categoryIds = [];

    /** @var list<string> */
    public array $facilityFilters = [];

    public string $sort = 'newest';

    public string $viewMode = 'grid';

    public ?float $userLat = null;

    public ?float $userLng = null;

    public ?float $userAccuracyM = null;

    public string $locationStatus = 'idle';

    public float $maxDistanceKm = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryIds' => ['as' => 'kategori', 'except' => []],
        'facilityFilters' => ['as' => 'fasilitas', 'except' => []],
        'sort' => ['except' => 'newest'],
        'viewMode' => ['as' => 'tampilan', 'except' => 'grid'],
    ];

    public function mount(): void
    {
        if ($this->sort === 'distance' && ! $this->hasUserLocation()) {
            $this->sort = 'newest';
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

    public function updatedFacilityFilters(): void
    {
        $this->resetPage();
    }

    public function updatedSort(): void
    {
        $this->resetPage();
    }

    public function updatedMaxDistanceKm(): void
    {
        $this->maxDistanceKm = max(1, min(10, $this->maxDistanceKm));
        $this->resetPage();
    }

    public function applySearch(): void
    {
        $this->resetPage();
    }

    public function setViewMode(string $mode): void
    {
        $this->viewMode = $mode === 'grid' ? 'grid' : 'list';
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
            $this->sort = 'newest';
        }

        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->categoryIds = [];
        $this->facilityFilters = [];
        $this->maxDistanceKm = 10;
        $this->sort = $this->hasUserLocation() ? 'distance' : 'newest';
        $this->resetPage();
    }

    #[Computed]
    public function categories()
    {
        return app(DirectoryQuery::class)->activeCategories();
    }

    #[Computed]
    public function recommendedCards(): array
    {
        return app(DirectoryQuery::class)->mapRecommendedCards();
    }

    public function render()
    {
        $directory = app(DirectoryQuery::class);
        $paginator = $directory->paginate($this->filters(), perPage: 6, page: $this->getPage());
        $cards = $directory->mapCardsForPaginator($paginator);

        return view('livewire.landing.restaurant-directory', [
            'theme' => RestaurantTheme::for(null),
            'home' => PlatformSetting::homeViewData(),
            'facilityOptions' => Facility::activeOptions(),
            'cards' => $cards,
            'recommendedCards' => $this->recommendedCards,
            'paginator' => $paginator,
            'totalCount' => $paginator->total(),
            'mapPins' => $this->pinsFromCards($cards),
        ])->layout('layouts.directory');
    }

    /**
     * @param  list<array<string, mixed>>  $cards
     * @return list<array<string, mixed>>
     */
    private function pinsFromCards(array $cards): array
    {
        return collect($cards)
            ->filter(fn (array $card) => filled($card['lat']) && filled($card['lng']))
            ->map(fn (array $card) => [
                'slug' => $card['slug'],
                'name' => $card['name'],
                'lat' => $card['lat'],
                'lng' => $card['lng'],
                'url' => $card['landing_url'],
            ])
            ->values()
            ->all();
    }

    private function hasUserLocation(): bool
    {
        return $this->locationStatus === 'granted'
            && filled($this->userLat)
            && filled($this->userLng);
    }

    /**
     * @return array{
     *     search?: string|null,
     *     categoryIds?: list<int>,
     *     facilities?: list<string>,
     *     sort?: string|null,
     *     userLat?: float|null,
     *     userLng?: float|null,
     *     maxDistanceKm?: float|null,
     * }
     */
    private function filters(): array
    {
        return [
            'search' => $this->search,
            'categoryIds' => $this->categoryIds,
            'facilities' => $this->facilityFilters,
            'sort' => $this->sort,
            'userLat' => $this->hasUserLocation() ? $this->userLat : null,
            'userLng' => $this->hasUserLocation() ? $this->userLng : null,
            'maxDistanceKm' => $this->maxDistanceKm,
        ];
    }
}
