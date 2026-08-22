<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\Restaurant;
use App\Models\Role;
use App\Support\TenantContext;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Schema;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\PermissionRegistrar;

class CreateUser extends CreateRecord
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

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->rolePayload = Arr::only($data, ['role_name']);

        if (($this->rolePayload['role_name'] ?? null) === Role::OWNER) {
            throw ValidationException::withMessages([
                'role_name' => 'Peran owner tidak bisa diberikan lewat staf.',
            ]);
        }

        return Arr::except($data, ['role_name']);
    }

    protected function afterCreate(): void
    {
        $tenant = Filament::getTenant();

        if (! $tenant instanceof Restaurant) {
            return;
        }

        $this->record->assertCanJoinRestaurant($tenant);

        $this->record->restaurants()->syncWithoutDetaching([
            $tenant->getKey() => ['is_active' => true],
        ]);

        $outletId = TenantContext::outletId();

        if ($outletId) {
            $this->record->outlets()->syncWithoutDetaching([
                $outletId => ['restaurant_id' => $tenant->getKey()],
            ]);
        }

        app(PermissionRegistrar::class)->setPermissionsTeamId($tenant->getKey());
        $this->record->syncRoles([$this->rolePayload['role_name']]);
    }
}
