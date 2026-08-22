<?php

namespace App\Filament\Resources\KdsStations\Pages;

use App\Filament\Pages\SoftDeleteTrashPage;
use App\Filament\Resources\KdsStations\KdsStationResource;
use Filament\Tables\Columns\TextColumn;

class TrashKdsStations extends SoftDeleteTrashPage
{
    protected static string $resource = KdsStationResource::class;

    protected function getTrashTableColumns(): array
    {
        return [
            TextColumn::make('name')->label('Nama')->searchable(),
            TextColumn::make('slug')->label('Slug')->searchable(),
            TextColumn::make('sort_order')->label('Urutan'),
        ];
    }
}
