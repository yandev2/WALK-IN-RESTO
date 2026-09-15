<?php

namespace App\Filament\Pages;

use App\Filament\Resources\CustomerReviews\CustomerReviewResource;
use App\Models\Restaurant;
use App\Models\User;
use App\Services\CustomerCrmService;
use App\Support\SubscriptionAccess;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class CustomerSatisfactionAnalytics extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFaceSmile;

    protected static string|UnitEnum|null $navigationGroup = 'CRM & Pelanggan';

    protected static ?string $navigationLabel = 'Analitik Kepuasan';

    protected static ?string $title = 'Analitik Kepuasan Pelanggan (CSAT)';

    protected static ?string $slug = 'kepuasan-pelanggan';

    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.pages.customer-satisfaction-analytics';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        return ($user->isPlatformOperator() || $user->isRestaurantOwner() || $user->can('order.verify_payment'))
            && SubscriptionAccess::allows('crm');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('viewReviewsTable')
                ->label('Buka Daftar Ulasan & Rating')
                ->icon(Heroicon::OutlinedStar)
                ->color('primary')
                ->url(fn (): string => CustomerReviewResource::getUrl('index')),
        ];
    }

    public function getViewData(): array
    {
        $restaurant = Filament::getTenant();

        if (! $restaurant instanceof Restaurant) {
            return [
                'analytics' => [
                    'total_reviews' => 0,
                    'avg_rating' => 0.0,
                    'satisfaction_rate' => 0.0,
                    'positive_count' => 0,
                    'neutral_count' => 0,
                    'neutral_rate' => 0.0,
                    'critical_count' => 0,
                    'critical_rate' => 0.0,
                    'star_counts' => [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0],
                    'star_percentages' => [5 => 0.0, 4 => 0.0, 3 => 0.0, 2 => 0.0, 1 => 0.0],
                    'this_month_reviews' => 0,
                    'this_month_avg' => 0.0,
                    'last_month_reviews' => 0,
                    'last_month_avg' => 0.0,
                    'by_outlet' => collect(),
                    'recent_reviews' => collect(),
                ],
                'restaurant' => null,
            ];
        }

        $crm = app(CustomerCrmService::class);

        return [
            'analytics' => $crm->getSatisfactionAnalytics($restaurant),
            'restaurant' => $restaurant,
        ];
    }
}
