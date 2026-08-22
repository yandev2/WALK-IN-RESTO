<?php

namespace App\Filament\Resources\CmsGalleryImages\Pages;

use App\Filament\Pages\SoftDeleteTrashPage;
use App\Filament\Resources\CmsGalleryImages\CmsGalleryImageResource;
use Filament\Tables\Columns\TextColumn;

class TrashCmsGalleryImages extends SoftDeleteTrashPage
{
    protected static string $resource = CmsGalleryImageResource::class;

    protected function getTrashTableColumns(): array
    {
        return [
            TextColumn::make('caption')->label('Caption')->searchable()->placeholder('—'),
            TextColumn::make('sort_order')->label('Urutan'),
        ];
    }
}
