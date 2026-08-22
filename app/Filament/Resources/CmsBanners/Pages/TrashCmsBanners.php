<?php

namespace App\Filament\Resources\CmsBanners\Pages;

use App\Filament\Pages\SoftDeleteTrashPage;
use App\Filament\Resources\CmsBanners\CmsBannerResource;
use Filament\Tables\Columns\TextColumn;

class TrashCmsBanners extends SoftDeleteTrashPage
{
    protected static string $resource = CmsBannerResource::class;

    protected function getTrashTableColumns(): array
    {
        return [
            TextColumn::make('title')->label('Judul')->searchable(),
            TextColumn::make('sort_order')->label('Urutan'),
        ];
    }
}
