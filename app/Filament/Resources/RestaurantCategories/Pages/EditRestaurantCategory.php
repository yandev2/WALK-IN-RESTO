<?php

namespace App\Filament\Resources\RestaurantCategories\Pages;

use App\Filament\Resources\RestaurantCategories\RestaurantCategoryResource;
use Filament\Resources\Pages\EditRecord;

class EditRestaurantCategory extends EditRecord
{
    protected static string $resource = RestaurantCategoryResource::class;
}
