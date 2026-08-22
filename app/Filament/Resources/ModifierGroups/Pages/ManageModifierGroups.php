<?php

namespace App\Filament\Resources\ModifierGroups\Pages;

use App\Filament\Resources\ModifierGroups\ModifierGroupResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Schema;

class ManageModifierGroups extends ManageRecords
{
    protected static string $resource = ModifierGroupResource::class;

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
            ModifierGroupResource::trashPageAction(),
            CreateAction::make(),
        ];
    }
}
