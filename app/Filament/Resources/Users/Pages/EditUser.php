<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\Restaurant;
use Filament\Actions\DeleteAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;
use Illuminate\Support\Arr;
use Spatie\Permission\PermissionRegistrar;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    /**
     * @var array<string, mixed>
     */
    protected array $rolePayload = [];

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

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['role_name'] = $this->record->roles->first()?->name;

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->rolePayload = Arr::only($data, ['role_name']);

        return Arr::except($data, ['role_name']);
    }

    protected function afterSave(): void
    {
        $tenantId = Filament::getTenant()?->getKey();

        if (! $tenantId || blank($this->rolePayload['role_name'] ?? null)) {
            return;
        }

        if ($this->record->isRestaurantOwner(
            Filament::getTenant() instanceof Restaurant
                ? Filament::getTenant()
                : null,
        )) {
            return;
        }

        app(PermissionRegistrar::class)->setPermissionsTeamId($tenantId);
        $this->record->syncRoles([$this->rolePayload['role_name']]);
    }
}
