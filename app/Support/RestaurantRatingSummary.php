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
        $count = $restaurant->reviews_count !== null
            ? (int) $restaurant->reviews_count
            : (int) $restaurant->reviews()->published()->count();

        $average = $restaurant->reviews_avg_rating !== null
            ? $restaurant->reviews_avg_rating
            : ($count > 0 ? $restaurant->reviews()->published()->avg('rating') : null);

        return [
            'average' => $count > 0 && $average !== null ? round((float) $average, 1) : null,
            'count' => $count,
        ];
    }
}
