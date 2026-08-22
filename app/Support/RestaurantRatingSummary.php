<?php

namespace App\Support;

use App\Models\Restaurant;

final class RestaurantRatingSummary
{
    /**
     * @return array{average: float|null, count: int}
     */
    public static function for(Restaurant $restaurant): array
    {
        $count = (int) ($restaurant->reviews_count ?? $restaurant->reviews()->count());
        $average = $restaurant->reviews_avg_rating ?? $restaurant->reviews()->avg('rating');

        return [
            'average' => $count > 0 ? round((float) $average, 1) : null,
            'count' => $count,
        ];
    }
}
