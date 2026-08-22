<?php

namespace App\Models\Concerns;

use App\Models\Scopes\BelongsToRestaurantScope;
use Illuminate\Database\Eloquent\Builder;

trait ScopedToRestaurant
{
    protected static function bootScopedToRestaurant(): void
    {
        static::addGlobalScope(new BelongsToRestaurantScope);
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeWithoutRestaurantScope(Builder $query): Builder
    {
        return $query->withoutGlobalScope(BelongsToRestaurantScope::class);
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeForRestaurant(Builder $query, int $restaurantId): Builder
    {
        return $query
            ->withoutGlobalScope(BelongsToRestaurantScope::class)
            ->where($query->getModel()->qualifyColumn('restaurant_id'), $restaurantId);
    }
}
