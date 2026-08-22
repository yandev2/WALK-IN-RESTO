<?php

namespace App\Filament\Resources\MenuItems\Pages;

use App\Filament\Pages\SoftDeleteTrashPage;
use App\Filament\Resources\MenuItems\MenuItemResource;
use Filament\Tables\Columns\TextColumn;

class TrashMenuItems extends SoftDeleteTrashPage
{
    protected static string $resource = MenuItemResource::class;

    protected function getTrashTableColumns(): array
    {
        return [
            TextColumn::make('name')->label('Nama')->searchable(),
            TextColumn::make('category.name')->label('Kategori'),
            TextColumn::make('price')->label('Harga')->money('IDR', locale: 'id'),
        ];
    }
}
