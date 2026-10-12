<?php

namespace App\Livewire\Landing;

use App\Enums\BlogPostStatus;
use App\Models\BlogPost;
use App\Models\CmsBanner;
use App\Models\Facility;
use App\Models\MenuItem;
use App\Models\PlatformSetting;
use App\Support\CmsMedia;
use App\Support\GeoDistance;
use App\Support\RestaurantDirectory as DirectoryQuery;
use App\Support\RestaurantTheme;
use Illuminate\Database\Eloquent\Builder;
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

    public ?float $maxDistanceKm = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryIds' => ['as' => 'kategori', 'except' => []],
        'facilityFilters' => ['as' => 'fasilitas', 'except' => []],
        'sort' => ['except' => 'newest'],
        'viewMode' => ['as' => 'tampilan', 'except' => 'grid'],
        'maxDistanceKm' => ['as' => 'jarak', 'except' => null],
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
        $this->maxDistanceKm = null;
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

    #[Computed]
    public function activePromoBanner(): ?array
    {
        $banner = CmsBanner::query()
            ->where('is_active', true)
            ->where(function ($q) {
                $now = now();
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($q) {
                $now = now();
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            })
            ->with('restaurant')
            ->latest()
            ->first();

        if (! $banner) {
            return null;
        }

        return [
            'id' => $banner->id,
            'title' => $banner->title,
            'subtitle' => $banner->subtitle,
            'badge_text' => $banner->badge_text ?: 'PROMO SPESIAL',
            'price_label' => $banner->price_label,
            'cta_label' => $banner->cta_label ?: 'Kunjungi Resto',
            'image_url' => CmsMedia::url($banner->image_path),
            'link_url' => $banner->link_url ?: ($banner->restaurant ? route('landing.show', $banner->restaurant) : null),
            'restaurant_name' => $banner->restaurant?->name,
        ];
    }

    #[Computed]
    public function popularMenuItems(): array
    {
        $query = MenuItem::query()
            ->where('is_active', true)
            ->whereNotNull('photo_path')
            ->whereHas('restaurant', fn (Builder $r) => $r->listedInDirectory())
            ->with(['restaurant.defaultOutlet', 'restaurant.categories']);

        if (! empty($this->categoryIds)) {
            $query->whereHas('restaurant.categories', function (Builder $c) {
                $c->whereIn('restaurant_categories.id', $this->categoryIds);
            });
        }

        if (filled($this->search)) {
            $driver = \Illuminate\Support\Facades\DB::connection()->getDriverName();
            $likeOperator = $driver === 'pgsql' ? 'ilike' : 'like';
            $term = '%' . trim($this->search) . '%';

            $query->where(function (Builder $builder) use ($term, $likeOperator) {
                $builder->where('name', $likeOperator, $term)
                    ->orWhereHas('restaurant', fn (Builder $r) => $r->where('name', $likeOperator, $term));
            });
        }

        $items = $query->orderByDesc('is_best_seller')->latest()->limit(50)->get();

        if ($this->hasUserLocation()) {
            $userLat = (float) $this->userLat;
            $userLng = (float) $this->userLng;

            $items = $items->map(function (MenuItem $item) use ($userLat, $userLng) {
                $outlet = $item->restaurant?->defaultOutlet;
                $dist = ($outlet && filled($outlet->latitude) && filled($outlet->longitude))
                    ? GeoDistance::kilometers($userLat, $userLng, (float) $outlet->latitude, (float) $outlet->longitude)
                    : null;
                $item->setAttribute('_distance_km', $dist);

                return $item;
            });

            if ($this->maxDistanceKm !== null) {
                $items = $items->filter(function (MenuItem $item) {
                    $dist = $item->getAttribute('_distance_km');

                    return $dist !== null && $dist <= $this->maxDistanceKm;
                });
            }

            $items = $items->sortBy(fn (MenuItem $item) => $item->getAttribute('_distance_km') ?? PHP_FLOAT_MAX);
        }

        return $items->take(8)
            ->map(fn (MenuItem $item) => [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'price_formatted' => 'Rp ' . number_format((float) $item->price, 0, ',', '.'),
                'discount_percent' => $item->discount_percent,
                'photo_url' => CmsMedia::url($item->photo_path),
                'restaurant_name' => $item->restaurant?->name,
                'landing_url' => $item->restaurant ? route('landing.show', $item->restaurant) : null,
                'distance_km' => $item->getAttribute('_distance_km'),
                'distance_label' => GeoDistance::label($item->getAttribute('_distance_km')),
            ])
            ->values()
            ->all();
    }

    #[Computed]
    public function latestArticles(): array
    {
        $locale = app()->getLocale() ?: 'id';

        // Query artikel yang siap tayang (bukan draft), tanpa batasan rentang waktu (misal 7 hari dll)
        $query = BlogPost::query()
            ->where(function (Builder $q) {
                $q->where('status', BlogPostStatus::Published)
                    ->orWhere(function (Builder $sub) {
                        $sub->where('status', BlogPostStatus::Scheduled)
                            ->where('published_at', '<=', now());
                    })
                    ->orWhere(function (Builder $sub) {
                        $sub->where('is_active', true)
                            ->where('status', '!=', BlogPostStatus::Draft);
                    });
            })
            ->where(function (Builder $q) {
                $q->where('is_active', true)
                    ->orWhereNull('is_active');
            })
            ->with(['category', 'translations']);

        // Prioritaskan artikel dengan translasi locale aktif, fallback ke translasi tersedia jika kosong
        $localeQuery = (clone $query)->forLocale($locale);

        $posts = $localeQuery->exists()
            ? $localeQuery
                ->orderByDesc('updated_at')
                ->orderByDesc('published_at')
                ->orderByDesc('id')
                ->limit(4)
                ->get()
            : $query
                ->whereHas('translations')
                ->orderByDesc('updated_at')
                ->orderByDesc('published_at')
                ->orderByDesc('id')
                ->limit(4)
                ->get();

        return $posts
            ->map(function (BlogPost $post) use ($locale) {
                $translation = $post->translate($locale)
                    ?? $post->translate('id')
                    ?? $post->translations->first();

                $articleLocale = $translation?->locale ?: $locale;
                $slug = $translation?->slug ?: $post->slug;
                $title = $translation?->title ?: $post->title;
                $excerpt = $translation?->excerpt ?: $post->excerpt;

                $date = $post->published_at ?? $post->updated_at ?? $post->created_at;

                return [
                    'id' => $post->id,
                    'title' => $title,
                    'excerpt' => $excerpt,
                    'slug' => $slug,
                    'image_url' => CmsMedia::url($post->featured_image),
                    'category_name' => $post->category?->name ?? 'Kuliner',
                    'published_at' => $date?->translatedFormat('d M Y') ?? $date?->format('d M Y'),
                    'reading_time' => ($post->reading_time_minutes ?: 4) . ' min baca',
                    'url' => route('blog.show', ['locale' => $articleLocale, 'slug' => $slug]),
                ];
            })
            ->all();
    }

    public function render()
    {
        $directory = app(DirectoryQuery::class);
        $paginator = $directory->paginate($this->filters(), perPage: 6, page: $this->getPage());
        $cards = $directory->mapCardsForPaginator($paginator);

        $home = PlatformSetting::homeViewData();

        return view('livewire.landing.restaurant-directory', [
            'theme' => RestaurantTheme::for(null),
            'home' => $home,
            'facilityOptions' => Facility::activeOptions(),
            'cards' => $cards,
            'recommendedCards' => $this->recommendedCards,
            'activePromoBanner' => $this->activePromoBanner,
            'popularMenuItems' => $this->popularMenuItems,
            'latestArticles' => $this->latestArticles,
            'paginator' => $paginator,
            'totalCount' => $paginator->total(),
            'mapPins' => $this->pinsFromCards($cards),
        ])->layout('layouts.directory', [
            'home' => $home,
            'theme' => RestaurantTheme::for(null),
            'title' => $home['meta_title'] ?? 'Temukan Restoran Terdekat',
            'description' => $home['meta_description'] ?? null,
        ]);
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
