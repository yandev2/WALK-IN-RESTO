<?php

namespace App\Filament\Resources\Restaurants\Pages;

use App\Filament\Pages\Dashboard;
use App\Filament\Resources\Restaurants\RestaurantResource;
use App\Models\Restaurant;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditRestaurant extends EditRecord
{
    protected static string $resource = RestaurantResource::class;

    protected function getHeaderActions(): array
    {
        /** @var Restaurant $record */
        $record = $this->getRecord();

        return [
            Action::make('openPanel')
                ->label('Buka panel')
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->url(Dashboard::getUrl(tenant: $record)),
            DeleteAction::make(),
        ];
    }
}
