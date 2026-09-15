<?php

namespace App\Filament\Resources\CustomerReviews\Pages;

use App\Filament\Resources\CustomerReviews\CustomerReviewResource;
use App\Models\RestaurantReview;
use App\Support\TenantContext;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListCustomerReviews extends ListRecords
{
    protected static string $resource = CustomerReviewResource::class;

    /**
     * @return array<string, Tab>
     */
    public function getTabs(): array
    {
        $restaurantId = TenantContext::restaurantId();

        $base = RestaurantReview::withoutRestaurantScope()
            ->when($restaurantId, fn ($q) => $q->where('restaurant_id', $restaurantId));

        return [
            'all' => Tab::make('Semua Ulasan')
                ->badge((clone $base)->count()),

            'stars_5' => Tab::make('⭐⭐⭐⭐⭐ (5 Bintang)')
                ->badge((clone $base)->where('rating', 5)->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('rating', 5)),

            'stars_4' => Tab::make('⭐⭐⭐⭐ (4 Bintang)')
                ->badge((clone $base)->where('rating', 4)->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('rating', 4)),

            'stars_3' => Tab::make('⭐⭐⭐ (3 Bintang)')
                ->badge((clone $base)->where('rating', 3)->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('rating', 3)),

            'critical' => Tab::make('⚠️ Perlu Perhatian (⭐ 1–2)')
                ->badge((clone $base)->where('rating', '<=', 2)->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('rating', '<=', 2)),
        ];
    }
}
