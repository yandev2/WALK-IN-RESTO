<?php

namespace App\Filament\Resources\CmsFaqs\Pages;

use App\Filament\Resources\CmsFaqs\CmsFaqResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCmsFaqs extends ManageRecords
{
    protected static string $resource = CmsFaqResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CmsFaqResource::trashPageAction(),
            CreateAction::make()->modalWidth('2xl'),
        ];
    }
}
