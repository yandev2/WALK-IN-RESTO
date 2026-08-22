<?php

namespace App\Filament\Resources\Restaurants\Pages;

use App\Enums\PlanCode;
use App\Filament\Resources\Restaurants\RestaurantResource;
use App\Models\Restaurant;
use App\Services\RestaurantProvisioner;
use Filament\Resources\Pages\CreateRecord;

class CreateRestaurant extends CreateRecord
{
    protected static string $resource = RestaurantResource::class;

    protected function afterCreate(): void
    {
        /** @var Restaurant $restaurant */
        $restaurant = $this->record;

        app(RestaurantProvisioner::class)->provision(
            $restaurant,
            $restaurant->plan_code ?: PlanCode::ManagementKds->value,
        );
    }
}
