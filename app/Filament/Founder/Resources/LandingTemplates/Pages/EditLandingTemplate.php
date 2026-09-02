<?php

namespace App\Filament\Founder\Resources\LandingTemplates\Pages;

use App\Filament\Founder\Resources\LandingTemplates\LandingTemplateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLandingTemplate extends EditRecord
{
    protected static string $resource = LandingTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
