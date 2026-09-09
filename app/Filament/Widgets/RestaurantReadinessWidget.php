<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\DiningTables\DiningTableResource;
use App\Filament\Resources\MenuItems\MenuItemResource;
use App\Models\DiningTable;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\MenuCategory;
use App\Models\RestaurantReview;
use App\Support\SubscriptionAccess;
use Filament\Facades\Filament;
use Filament\Widgets\Widget;

class RestaurantReadinessWidget extends Widget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'xl' => 5,
    ];

    public function getColumnSpan(): int|string|array
    {
        if (! PendingPaymentsWidget::canView()) {
            return 'full';
        }

        return $this->columnSpan;
    }

    protected string $view = 'filament.widgets.restaurant-readiness';

    public static function canView(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ($user->isSuperAdmin() || $user->can('analytics.view') || $user->can('cms.manage'))
            && SubscriptionAccess::allows('cms');
    }

    /**
     * @return array{
     *     percentage: int,
     *     status_title: string,
     *     active_menu_count: int,
     *     ready_tables_count: int,
     *     reviews_count: int,
     *     avg_rating: string,
     *     menu_url: string|null,
     *     tables_url: string|null,
     * }
     */
    public function getReadinessData(): array
    {
        $restaurant = Filament::getTenant();

        if (! $restaurant instanceof Restaurant) {
            return [
                'percentage' => 100,
                'status_title' => 'Sangat Siap & Lengkap 🚀',
                'active_menu_count' => 0,
                'ready_tables_count' => 0,
                'reviews_count' => 0,
                'avg_rating' => '5.0',
                'menu_url' => null,
                'tables_url' => null,
            ];
        }

        $activeMenuCount = MenuItem::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('is_active', true)
            ->count();

        $readyTablesCount = DiningTable::query()
            ->where('restaurant_id', $restaurant->id)
            ->count();

        $categoriesCount = MenuCategory::query()
            ->where('restaurant_id', $restaurant->id)
            ->count();

        $reviewsCount = RestaurantReview::query()
            ->where('restaurant_id', $restaurant->id)
            ->count();

        $avgRating = (float) (RestaurantReview::query()
            ->where('restaurant_id', $restaurant->id)
            ->avg('rating') ?: 5.0);

        // Calculate score
        $score = 0;
        if ($activeMenuCount > 0) $score += 30;
        if ($readyTablesCount > 0) $score += 30;
        if ($categoriesCount > 0) $score += 20;
        if (filled($restaurant->name) && filled($restaurant->slug)) $score += 20;

        $score = max(10, min(100, $score));

        $statusTitle = match (true) {
            $score >= 90 => 'Sangat Siap & Lengkap 🚀',
            $score >= 70 => 'Kesiapan Baik & Aktif ✨',
            $score >= 40 => 'Perlu Tambah Menu / Meja 📝',
            default => 'Lengkapi Profil & Menu ⚙️',
        };

        $menuUrl = null;
        $tablesUrl = null;
        try {
            $menuUrl = MenuItemResource::getUrl();
            $tablesUrl = DiningTableResource::getUrl();
        } catch (\Throwable) {
            // fallback
        }

        return [
            'percentage' => $score,
            'status_title' => $statusTitle,
            'active_menu_count' => $activeMenuCount,
            'ready_tables_count' => $readyTablesCount,
            'reviews_count' => $reviewsCount,
            'avg_rating' => number_format($avgRating, 1, '.', ''),
            'menu_url' => $menuUrl,
            'tables_url' => $tablesUrl,
        ];
    }
}
