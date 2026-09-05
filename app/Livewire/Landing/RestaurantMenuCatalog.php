<?php

namespace App\Livewire\Landing;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Support\CmsMedia;
use App\Support\LandingLayout;
use App\Support\MenuSearch;
use App\Support\RestaurantTheme;
use Livewire\Component;
use Livewire\WithPagination;

class RestaurantMenuCatalog extends Component
{
    use WithPagination;

    public Restaurant $restaurant;

    public string $search = '';

    public ?int $categoryId = null;

    public string $priceSort = 'asc';

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryId' => ['as' => 'kategori', 'except' => null],
        'priceSort' => ['as' => 'sort', 'except' => 'asc'],
    ];

    public function mount(Restaurant $restaurant): void
    {
        abort_unless($restaurant->isLandingPublic(), 404);

        $this->restaurant = $restaurant->load([
            'cmsProfile',
            'cmsFaqs' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order')->orderBy('id'),
            'cmsGalleryImages' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order')->orderBy('id'),
            'defaultOutlet.operatingHours',
        ]);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategoryId(): void
    {
        $this->resetPage();
    }

    public function updatedPriceSort(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $outlet = $this->restaurant->defaultOutlet;
        $profile = $this->restaurant->cmsProfile;

        $categories = $outlet
            ? MenuCategory::query()
                ->where('outlet_id', $outlet->id)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get()
            : collect();

        $normalizedSearch = MenuSearch::normalize($this->search);

        $menuItems = $outlet
            ? MenuItem::query()
                ->where('outlet_id', $outlet->id)
                ->where('is_active', true)
                ->with(['category', 'photos'])
                ->when($normalizedSearch !== '', fn ($query) => $query->whereRaw(
                    "LOWER(REPLACE(name, ' ', '')) LIKE ?",
                    ['%'.$normalizedSearch.'%'],
                ))
                ->when($this->categoryId, fn ($query) => $query->where('category_id', $this->categoryId))
                ->orderByLandingPriority()
                ->orderByEffectivePrice($this->priceSort)
                ->orderBy('sort_order')
                ->paginate(12)
            : MenuItem::query()->whereRaw('0 = 1')->paginate(12);

        $ctaUrl = $profile?->cta_url
            ?: CmsMedia::mapsSearchUrl($outlet?->address, $outlet?->latitude, $outlet?->longitude);
        $mapsUrl = CmsMedia::mapsSearchUrl($outlet?->address, $outlet?->latitude, $outlet?->longitude);
        $whatsappUrl = CmsMedia::whatsappUrl($outlet?->phone);
        $instagramUrl = CmsMedia::instagramUrl($outlet?->instagram);
        $layout = LandingLayout::for($profile);
        $visibleSections = $layout->visibleIds([
            'hero' => true,
            'banners' => false,
            'menu' => $menuItems->total() > 0,
            'reviews' => false,
            'how_to' => true,
            'about' => filled($profile?->about_html),
            'gallery' => $this->restaurant->cmsGalleryImages->isNotEmpty(),
            'hours' => true,
            'location' => true,
            'faq' => $this->restaurant->cmsFaqs->isNotEmpty(),
            'cta' => filled($mapsUrl) || filled($whatsappUrl),
        ]);

        $this->restaurant->loadAvg('reviews', 'rating');
        $this->restaurant->loadCount('reviews');

        $ratingSummary = \App\Support\RestaurantRatingSummary::for($this->restaurant);
        $template = $profile?->landing_template ?: 'classic';

        return view('livewire.landing.restaurant-menu-catalog', [
            'outlet' => $outlet,
            'profile' => $profile,
            'categories' => $categories,
            'menuItems' => $menuItems,
            'logoUrl' => CmsMedia::url($this->restaurant->logo_path),
            'ctaUrl' => $ctaUrl,
            'ctaLabel' => $profile?->cta_label ?: 'Lihat lokasi',
            'heroUrl' => CmsMedia::url($profile?->hero_image_path),
            'howToImageUrl' => CmsMedia::url($profile?->how_to_image_path),
            'aboutImageUrl' => CmsMedia::url($profile?->about_image_path),
            'mapsUrl' => $mapsUrl,
            'whatsappUrl' => $whatsappUrl,
            'instagramUrl' => $instagramUrl,
            'isOpenNow' => (bool) $outlet?->isOpenNow(),
            'ratingSummary' => $ratingSummary,
            'template' => $template,
            'previewMenuItems' => collect(),
            'layout' => $layout,
            'visibleSections' => $visibleSections,
        ])->layout('layouts.landing', [
            'theme' => RestaurantTheme::for($this->restaurant),
            'restaurant' => $this->restaurant,
            'logoUrl' => CmsMedia::url($this->restaurant->logo_path),
        ]);
    }
}
