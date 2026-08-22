<?php

namespace App\Filament\Resources\DiningTables\Pages;

use App\Filament\Pages\SoftDeleteTrashPage;
use App\Filament\Resources\DiningTables\DiningTableResource;
use Filament\Tables\Columns\TextColumn;

class TrashDiningTables extends SoftDeleteTrashPage
{
    protected static string $resource = DiningTableResource::class;

    protected function getTrashTableColumns(): array
    {
        return [
            TextColumn::make('code')->label('Kode')->searchable(),
            TextColumn::make('area')->label('Area'),
            TextColumn::make('capacity')->label('Kapasitas'),
        ];
    }
}
