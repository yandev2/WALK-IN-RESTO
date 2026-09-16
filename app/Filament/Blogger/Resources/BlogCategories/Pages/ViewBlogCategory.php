<?php

namespace App\Filament\Blogger\Resources\BlogCategories\Pages;

use App\Filament\Blogger\Resources\BlogCategories\BlogCategoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBlogCategory extends ViewRecord
{
    protected static string $resource = BlogCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
