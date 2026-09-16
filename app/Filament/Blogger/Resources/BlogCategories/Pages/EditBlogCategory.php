<?php

namespace App\Filament\Blogger\Resources\BlogCategories\Pages;

use App\Filament\Blogger\Concerns\HandlesTranslatableForm;
use App\Filament\Blogger\Resources\BlogCategories\BlogCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditBlogCategory extends EditRecord
{
    use HandlesTranslatableForm;

    protected static string $resource = BlogCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
