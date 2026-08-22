<?php

namespace App\Models\Concerns;

use App\Models\Restaurant;
use App\Support\TenantContext;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToRestaurantAndOutlet
{
    use ScopedToRestaurant;

    protected static function bootBelongsToRestaurantAndOutlet(): void
    {
        static::creating(function (self $model): void {
            if (blank($model->getAttribute('restaurant_id'))) {
                $model->setAttribute('restaurant_id', TenantContext::restaurantId());
            }

            if (
                in_array('outlet_id', $model->getFillable(), true)
                && blank($model->getAttribute('outlet_id'))
            ) {
                $model->setAttribute('outlet_id', TenantContext::outletId());
            }
        });
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}
