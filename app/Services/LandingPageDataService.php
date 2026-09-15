<?php

namespace App\Services;

use App\Models\LandingTemplate;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Support\CmsMedia;
use App\Support\LandingLayout;
use App\Support\RestaurantRatingSummary;
use App\Support\RestaurantTheme;

class LandingPageDataService
{
    /**
     * Get complete, standardized landing page data payload for a restaurant.
     *
     * @return array<string, mixed>
     */
    public function getPayload(Restaurant $restaurant): array
    {
        $restaurant->loadMissing([
            'cmsProfile',
            'defaultOutlet.operatingHours',
            'defaultOutlet.closedDates',
            'defaultOutlet.restaurant',
            'cmsFaqs' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order')->orderBy('id'),
            'cmsGalleryImages' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order')->orderBy('id'),
            'cmsBanners' => fn ($query) => $query->currentlyLive()->orderBy('sort_order')->orderBy('id'),
            'reviews' => fn ($query) => $query->published()->latest('submitted_at')->latest('id')->limit(6),
        ]);

        $restaurant->loadAvg(['reviews' => fn ($q) => $q->published()], 'rating');
        $restaurant->loadCount(['reviews' => fn ($q) => $q->published()]);

        $outlet = $restaurant->defaultOutlet;
        $profile = $restaurant->cmsProfile;

        $ctaUrl = $profile?->cta_url
            ?: CmsMedia::mapsSearchUrl($outlet?->address, $outlet?->latitude, $outlet?->longitude);

        $menuItems = collect();
        $bestSellerItems = collect();

        if ($outlet) {
            $baseQuery = fn () => MenuItem::query()
                ->where('outlet_id', $outlet->id)
                ->where('is_active', true)
                ->with(['category', 'photos']);

            $menuItems = $baseQuery()
                ->orderByLandingPriority()
                ->orderBy('sort_order')
                ->orderByDesc('id')
                ->limit(8)
                ->get();

            $bestSellerItems = $baseQuery()
                ->orderByLandingPriority()
                ->orderBy('sort_order')
                ->orderByDesc('id')
                ->limit(6)
                ->get();

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
        $instagramUrl = CmsMedia::instagramUrl($outlet?->instagram);
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

        $template = $this->resolveTemplate($profile?->landing_template);

        return [
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
            'instagramUrl' => $instagramUrl,
            'isOpenNow' => (bool) $outlet?->isOpenNow(),
            'todayHours' => $outlet?->todayHours(),
            'dayNames' => $dayNames,
            'menuItems' => $menuItems,
            'bestSellerItems' => $bestSellerItems,
            'menuCategories' => $menuCategories,
            'theme' => RestaurantTheme::for($restaurant),
            'ratingSummary' => $ratingSummary,
            'layout' => $layout,
            'visibleSections' => $visibleSections,
            'template' => $template,
        ];
    }

    /**
     * Resolve the active landing template record.
     */
    public function resolveTemplate(?string $templateSlug): LandingTemplate
    {
        $slug = $templateSlug ?: LandingTemplate::DEFAULT_TEMPLATE;

        $template = LandingTemplate::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if ($template) {
            return $template;
        }

        return LandingTemplate::query()
            ->where('slug', LandingTemplate::DEFAULT_TEMPLATE)
            ->where('is_active', true)
            ->first()
            ?? LandingTemplate::query()->where('is_active', true)->orderBy('sort_order')->first()
            ?? new LandingTemplate([
                'slug' => 'classic',
                'name' => 'Classic Elegant',
                'view_path' => 'landing.templates.classic.show',
                'is_active' => true,
            ]);
    }
}
