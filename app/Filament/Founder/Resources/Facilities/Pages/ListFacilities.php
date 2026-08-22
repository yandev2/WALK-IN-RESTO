<?php

namespace App\Filament\Founder\Resources\Facilities\Pages;

use App\Filament\Founder\Resources\Facilities\FacilityResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFacilities extends ListRecords
{
    protected static string $resource = FacilityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
