<?php

namespace App\Filament\Resources\CmsGalleryImages\Pages;

use App\Filament\Resources\CmsGalleryImages\CmsGalleryImageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCmsGalleryImages extends ManageRecords
{
    protected static string $resource = CmsGalleryImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CmsGalleryImageResource::trashPageAction(),
            CreateAction::make(),
        ];
    }
}
