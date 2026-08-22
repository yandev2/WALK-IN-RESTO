<?php

namespace App\Filament\Resources\CmsBanners\Pages;

use App\Filament\Resources\CmsBanners\CmsBannerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCmsBanners extends ManageRecords
{
    protected static string $resource = CmsBannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CmsBannerResource::trashPageAction(),
            CreateAction::make(),
        ];
    }
}
