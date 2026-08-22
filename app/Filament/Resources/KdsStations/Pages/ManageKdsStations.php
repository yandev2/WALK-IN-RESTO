<?php

namespace App\Filament\Resources\KdsStations\Pages;

use App\Filament\Resources\KdsStations\KdsStationResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Schema;

class ManageKdsStations extends ManageRecords
{
    protected static string $resource = KdsStationResource::class;

    public function getDefaultActionSchemaResolver(Action $action): ?\Closure
    {
        return match (true) {
            $action instanceof CreateAction, $action instanceof EditAction => fn (Schema $schema): Schema => $this->form($schema->columns(1)),
            default => parent::getDefaultActionSchemaResolver($action),
        };
    }

    protected function getHeaderActions(): array
    {
        return [
            KdsStationResource::trashPageAction(),
            CreateAction::make(),
        ];
    }
}
