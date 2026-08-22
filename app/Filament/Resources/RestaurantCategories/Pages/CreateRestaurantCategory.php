<?php

namespace App\Filament\Resources\RestaurantCategories\Pages;

use App\Filament\Resources\RestaurantCategories\RestaurantCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRestaurantCategory extends CreateRecord
{
    protected static string $resource = RestaurantCategoryResource::class;
}
