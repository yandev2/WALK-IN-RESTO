<?php

namespace App\Filament\Resources\MenuCategories\Pages;

use App\Filament\Pages\SoftDeleteTrashPage;
use App\Filament\Resources\MenuCategories\MenuCategoryResource;
use Filament\Tables\Columns\TextColumn;

class TrashMenuCategories extends SoftDeleteTrashPage
{
    protected static string $resource = MenuCategoryResource::class;

    protected function getTrashTableColumns(): array
    {
        return [
            TextColumn::make('name')->label('Nama')->searchable(),
            TextColumn::make('sort_order')->label('Urutan'),
        ];
    }
}
