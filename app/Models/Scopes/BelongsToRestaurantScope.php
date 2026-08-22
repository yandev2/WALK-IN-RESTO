<?php

namespace App\Models\Scopes;

use App\Support\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BelongsToRestaurantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $restaurantId = TenantContext::restaurantId();

        if ($restaurantId === null) {
            return;
        }

        $builder->where($model->qualifyColumn('restaurant_id'), $restaurantId);
    }
}
