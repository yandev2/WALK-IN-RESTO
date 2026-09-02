<?php

namespace App\Filament\Founder\Resources\LandingTemplates\Pages;

use App\Filament\Founder\Resources\LandingTemplates\LandingTemplateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLandingTemplates extends ListRecords
{
    protected static string $resource = LandingTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
