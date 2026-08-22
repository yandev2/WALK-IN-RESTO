<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Support\CmsMedia;
use App\Support\LandingLayout;
use App\Support\RestaurantRatingSummary;
use App\Support\RestaurantTheme;
use Illuminate\View\View;

class RestaurantLandingController extends Controller
{
    public function home(): View
    {
        $restaurants = Restaurant::query()
            ->listedInDirectory()
            ->with(['cmsProfile', 'defaultOutlet.operatingHours', 'defaultOutlet.closedDates', 'defaultOutlet.restaurant'])
            ->orderBy('name')
            ->get();

        return view('landing.home', [
            'restaurants' => $restaurants,
            'theme' => RestaurantTheme::for(null),
        ]);
    }

    public function show(Restaurant $restaurant): View
    {
        abort_unless($restaurant->isLandingPublic(), 404);

        $restaurant->load([
            'cmsProfile',
            'defaultOutlet.operatingHours',
            'defaultOutlet.closedDates',
            'defaultOutlet.restaurant',
            'cmsFaqs' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order')->orderBy('id'),
            'cmsGalleryImages' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order')->orderBy('id'),
            'cmsBanners' => fn ($query) => $query->currentlyLive()->orderBy('sort_order')->orderBy('id'),
            'reviews' => fn ($query) => $query->latest('submitted_at')->latest('id')->limit(4),
        ]);

        $restaurant->loadAvg('reviews', 'rating');
        $restaurant->loadCount('reviews');

        $outlet = $restaurant->defaultOutlet;
        $profile = $restaurant->cmsProfile;
        $ctaUrl = $profile?->cta_url
            ?: CmsMedia::mapsSearchUrl($outlet?->address, $outlet?->latitude, $outlet?->longitude);

        $menuItems = collect();

        if ($outlet) {
            $baseQuery = fn () => MenuItem::query()
                ->where('outlet_id', $outlet->id)
                ->where('is_active', true)
                ->with('category', 'photos');

            $discounted = $baseQuery()
                ->whereNotNull('discount_percent')
                ->where('discount_percent', '>', 0)
                ->orderByDesc('updated_at')
                ->orderByDesc('id')
                ->limit(4)
                ->get();

            $newest = $baseQuery()
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->limit(4)
                ->get();

            $menuItems = $discounted
                ->concat($newest)
                ->unique('id')
                ->take(4)
                ->values();

            $menuCategoryIds = $menuItems->pluck('category_id')->filter()->unique();
        }

        $menuCategories = ($outlet && $menuItems->isNotEmpty())
            ? MenuCategory::query()
                ->where('outlet_id', $outlet->id)
                ->where('is_active', true)
                ->whereIn('id', $menuCategoryIds ?? [])
                ->orderBy('sort_order')
                ->get()
            : collect();

        $dayNames = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];

        $mapsUrl = CmsMedia::mapsSearchUrl($outlet?->address, $outlet?->latitude, $outlet?->longitude);
        $whatsappUrl = CmsMedia::whatsappUrl($outlet?->phone);
        $layout = LandingLayout::for($profile);
        $ratingSummary = RestaurantRatingSummary::for($restaurant);
        $visibleSections = $layout->visibleIds([
            'hero' => true,
            'banners' => $restaurant->cmsBanners->isNotEmpty(),
            'menu' => $menuItems->isNotEmpty(),
            'reviews' => ($ratingSummary['count'] ?? 0) > 0,
            'how_to' => true,
            'about' => filled($profile?->about_html),
            'gallery' => $restaurant->cmsGalleryImages->isNotEmpty(),
            'hours' => true,
            'location' => true,
            'faq' => $restaurant->cmsFaqs->isNotEmpty(),
            'cta' => filled($mapsUrl) || filled($whatsappUrl),
        ]);

        return view('landing.show', [
            'restaurant' => $restaurant,
            'outlet' => $outlet,
            'profile' => $profile,
            'ctaUrl' => $ctaUrl,
            'ctaLabel' => $profile?->cta_label ?: 'Lihat lokasi',
            'heroUrl' => CmsMedia::url($profile?->hero_image_path),
            'howToImageUrl' => CmsMedia::url($profile?->how_to_image_path),
            'aboutImageUrl' => CmsMedia::url($profile?->about_image_path),
            'logoUrl' => CmsMedia::url($restaurant->logo_path),
            'mapEmbedUrl' => CmsMedia::mapsEmbedUrl(
                $profile?->map_embed_url,
                $outlet?->latitude,
                $outlet?->longitude,
            ),
            'mapsUrl' => $mapsUrl,
            'whatsappUrl' => $whatsappUrl,
            'isOpenNow' => (bool) $outlet?->isOpenNow(),
            'todayHours' => $outlet?->todayHours(),
            'dayNames' => $dayNames,
            'menuItems' => $menuItems,
            'menuCategories' => $menuCategories,
            'theme' => RestaurantTheme::for($restaurant),
            'ratingSummary' => $ratingSummary,
            'layout' => $layout,
            'visibleSections' => $visibleSections,
        ]);
    }
}
