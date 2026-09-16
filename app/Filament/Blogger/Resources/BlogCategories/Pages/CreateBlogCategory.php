<?php

namespace App\Filament\Blogger\Resources\BlogCategories\Pages;

use App\Filament\Blogger\Concerns\HandlesTranslatableForm;
use App\Filament\Blogger\Resources\BlogCategories\BlogCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBlogCategory extends CreateRecord
{
    use HandlesTranslatableForm;

    protected static string $resource = BlogCategoryResource::class;
}
