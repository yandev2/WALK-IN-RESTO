<?php

namespace App\Http\Resources\Api\V1;

use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Support\CmsMedia;
use App\Support\LandingLayout;
use App\Support\RestaurantRatingSummary;
use App\Support\RestaurantTheme;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Restaurant */
class RestaurantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $outlet = $this->defaultOutlet;
        $profile = $this->cmsProfile;
        $ctaUrl = $profile?->cta_url
            ?: CmsMedia::mapsSearchUrl($outlet?->address, $outlet?->latitude, $outlet?->longitude);

        $dayNames = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];

        $hours = $outlet?->operatingHours?->map(fn ($row) => [
            'day_of_week' => $row->day_of_week,
            'day_name' => $dayNames[$row->day_of_week] ?? (string) $row->day_of_week,
            'opens_at' => $row->opens_at,
            'closes_at' => $row->closes_at,
            'is_closed' => (bool) $row->is_closed,
        ])->values();

        $todayHours = $outlet?->todayHours();
        $todayHoursPayload = $todayHours ? [
            'day_of_week' => $todayHours->day_of_week,
            'day_name' => $dayNames[$todayHours->day_of_week] ?? (string) $todayHours->day_of_week,
            'opens_at' => $todayHours->opens_at,
            'closes_at' => $todayHours->closes_at,
            'is_closed' => (bool) $todayHours->is_closed,
        ] : null;

        $closedDates = $outlet?->closedDates
            ? $outlet->closedDates
                ->sortBy('closed_on')
                ->map(fn ($date) => [
                    'date' => $date->closed_on?->toDateString(),
                    'reason' => $date->reason,
                ])
                ->values()
            : collect();

        $hidePrices = (bool) ($outlet?->hide_landing_menu_prices ?? false);

        $featuredMenu = $outlet
            ? MenuItem::query()
                ->where('outlet_id', $outlet->id)
                ->where('is_active', true)
                ->with(['category', 'photos'])
                ->orderBy('sort_order')
                ->limit(6)
                ->get()
                ->map(fn (MenuItem $item) => [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'price' => $hidePrices ? null : $item->effectivePrice(),
                    'original_price' => $hidePrices ? null : (int) $item->price,
                    'discount_percent' => $item->hasDiscount() ? (int) $item->discount_percent : null,
                    'photo_url' => CmsMedia::url($item->photo_path),
                    'photo_urls' => $item->photoUrls(),
                    'category_name' => $item->category?->name,
                    'is_out_of_stock' => (bool) $item->is_out_of_stock,
                ])
            : [];

        $ratingSummary = RestaurantRatingSummary::for($this->resource);

        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'logo_url' => CmsMedia::url($this->logo_path),
            'theme' => RestaurantTheme::for($this->resource),
            'headline' => $profile?->headline,
            'about_html' => $profile?->about_html,
            'hero_url' => CmsMedia::url($profile?->hero_image_path),
            'how_to_image_url' => CmsMedia::url($profile?->how_to_image_path),
            'about_image_url' => CmsMedia::url($profile?->about_image_path),
            'cta_label' => $profile?->cta_label ?: 'Lihat lokasi',
            'cta_url' => $ctaUrl,
            'maps_url' => CmsMedia::mapsSearchUrl($outlet?->address, $outlet?->latitude, $outlet?->longitude),
            'map_embed_url' => CmsMedia::mapsEmbedUrl(
                $profile?->map_embed_url,
                $outlet?->latitude,
                $outlet?->longitude,
            ),
            'whatsapp_url' => CmsMedia::whatsappUrl($outlet?->phone),
            'instagram' => $outlet?->instagram,
            'instagram_url' => CmsMedia::instagramUrl($outlet?->instagram),
            'is_open' => (bool) ($outlet?->is_open ?? false),
            'is_open_now' => (bool) $outlet?->isOpenNow(),
            'outlet_name' => $outlet?->name,
            'address' => $outlet?->address,
            'phone' => $outlet?->phone,
            'today_hours' => $todayHoursPayload,
            'operating_hours' => $hours,
            'closed_dates' => $closedDates,
            'gallery' => $this->cmsGalleryImages->map(fn ($image) => [
                'image_url' => CmsMedia::url($image->image_path),
                'caption' => $image->caption,
            ])->values(),
            'faqs' => $this->cmsFaqs->map(fn ($faq) => [
                'question' => $faq->question,
                'answer_html' => $faq->answer_html,
            ])->values(),
            'banners' => $this->cmsBanners->map(fn ($banner) => [
                'title' => $banner->title,
                'subtitle' => $banner->subtitle,
                'badge_text' => $banner->badge_text,
                'price_label' => $banner->price_label,
                'cta_label' => $banner->cta_label,
                'image_url' => CmsMedia::url($banner->image_path),
                'link_url' => $banner->link_url,
            ])->values(),
            'featured_menu' => $featuredMenu,
            'rating_summary' => $ratingSummary,
            'recent_reviews' => RestaurantReviewResource::collection(
                $this->whenLoaded('reviews', default: collect()),
            ),
            'landing' => LandingLayout::for($profile)->toArray(),
        ];
    }
}
