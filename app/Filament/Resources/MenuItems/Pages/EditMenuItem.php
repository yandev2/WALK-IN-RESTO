<?php

namespace App\Filament\Resources\MenuItems\Pages;

use App\Filament\Resources\MenuItems\MenuItemResource;
use App\Support\ActivityLogger;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;

class EditMenuItem extends EditRecord
{
    protected static string $resource = MenuItemResource::class;

    protected mixed $previousPrice = null;

    public function defaultForm(Schema $schema): Schema
    {
        return parent::defaultForm($schema)->columns(1);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function beforeSave(): void
    {
        $this->previousPrice = $this->record->price;
    }

    protected function afterSave(): void
    {
        if ($this->previousPrice != $this->record->price) {
            ActivityLogger::log('menu.update_price', [
                'old' => ['price' => $this->previousPrice],
                'new' => ['price' => $this->record->price, 'menu_item_id' => $this->record->id],
            ]);
        }
    }
}
