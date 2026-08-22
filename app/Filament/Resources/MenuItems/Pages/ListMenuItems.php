<?php

namespace App\Filament\Resources\MenuItems\Pages;

use App\Filament\Resources\MenuItems\MenuItemResource;
use App\Models\KdsStation;
use App\Support\TenantContext;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListMenuItems extends ListRecords
{
    protected static string $resource = MenuItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            MenuItemResource::trashPageAction(),
            CreateAction::make(),
        ];
    }

    /**
     * @return array<string, Tab>
     */
    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make('Semua')
                ->badge(fn (): int => $this->tabCount())
                ->modifyQueryUsing(fn (Builder $query): Builder => $query),
        ];

        $restaurantId = TenantContext::restaurantId();

        if (! $restaurantId) {
            return $tabs;
        }

        $stations = KdsStation::query()
            ->where('restaurant_id', $restaurantId)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        foreach ($stations as $station) {
            $stationId = $station->getKey();

            $tabs['station-'.$stationId] = Tab::make($station->name)
                ->badge(fn (): int => $this->tabCount($stationId))
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('station_id', $stationId));
        }

        return $tabs;
    }

    private function tabCount(?int $stationId = null): int
    {
        $query = MenuItemResource::getEloquentQuery();

        if ($stationId) {
            $query->where('station_id', $stationId);
        }

        return $query->count();
    }

    public function showsStationColumn(): bool
    {
        return ($this->activeTab ?? 'all') === 'all';
    }
}
