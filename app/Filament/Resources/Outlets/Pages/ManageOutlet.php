<?php

namespace App\Filament\Resources\Outlets\Pages;

use App\Filament\Resources\Outlets\OutletResource;
use App\Models\Outlet;
use App\Models\Restaurant;
use App\Support\ActivityLogger;
use App\Support\SubscriptionAccess;
use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;

class ManageOutlet extends EditRecord
{
    protected static string $resource = OutletResource::class;

    protected static ?string $title = 'Outlet';

    protected static ?string $navigationLabel = 'Outlet';

    protected ?bool $previousOpen = null;

    public function mount(int|string $record = 0): void
    {
        $outlet = $this->resolveDefaultOutlet();

        abort_unless($outlet instanceof Outlet, 404);

        $this->record = $outlet;

        $this->authorizeAccess();

        $this->fillForm();

        $this->previousUrl = url()->previous();
    }

    public function defaultForm(Schema $schema): Schema
    {
        return parent::defaultForm($schema)->columns(1);
    }

    protected function getFormActions(): array
    {
        if (SubscriptionAccess::isReadOnly()) {
            return [];
        }

        return [
            $this->getSaveFormAction(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        abort_if(SubscriptionAccess::isReadOnly(), 403);

        return $data;
    }

    protected function getRedirectUrl(): ?string
    {
        return null;
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Pengaturan outlet disimpan';
    }

    protected function beforeSave(): void
    {
        $this->previousOpen = (bool) $this->record->is_open;
    }

    protected function afterSave(): void
    {
        if ($this->previousOpen !== (bool) $this->record->is_open) {
            ActivityLogger::log('outlet.override_open_closed', [
                'outlet_id' => $this->record->id,
                'old' => ['is_open' => $this->previousOpen],
                'new' => ['is_open' => $this->record->is_open],
            ]);
        }
    }

    private function resolveDefaultOutlet(): ?Outlet
    {
        $tenant = Filament::getTenant();

        if (! $tenant instanceof Restaurant) {
            return null;
        }

        return Outlet::query()
            ->where('restaurant_id', $tenant->getKey())
            ->where('is_default', true)
            ->first()
            ?? Outlet::query()
                ->where('restaurant_id', $tenant->getKey())
                ->orderBy('id')
                ->first();
    }
}
