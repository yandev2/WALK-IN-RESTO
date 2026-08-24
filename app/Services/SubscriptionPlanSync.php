<?php

namespace App\Services;

use App\Enums\PlanCode;
use App\Models\Restaurant;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class SubscriptionPlanSync
{
    public function __construct(
        private readonly RestaurantProvisioner $provisioner,
    ) {}

    /**
     * @return list<string>
     */
    public function permissionsFor(string $planCode): array
    {
        if ($planCode === PlanCode::LandingOnly->value) {
            return [
                'cms.manage',
                'settings.manage',
            ];
        }

        return array_values(array_unique(config('filament-shield.custom_permissions')));
    }

    public function apply(Restaurant $restaurant, string $planCode): void
    {
        $restaurant->forceFill(['plan_code' => $planCode])->save();

        if ($planCode === PlanCode::ManagementKds->value) {
            $this->provisioner->ensureKdsStations($restaurant);
        }

        $this->syncOwnerPermissions($restaurant, $planCode);
    }

    public function syncOwnerPermissions(Restaurant $restaurant, string $planCode): void
    {
        $registrar = app(PermissionRegistrar::class);
        $previousTeamId = $registrar->getPermissionsTeamId();
        $registrar->setPermissionsTeamId($restaurant->id);

        try {
            $role = Role::query()
                ->where('name', Role::OWNER)
                ->where('restaurant_id', $restaurant->id)
                ->first();

            if (! $role) {
                $role = Role::query()->create([
                    'name' => Role::OWNER,
                    'guard_name' => 'web',
                    'restaurant_id' => $restaurant->id,
                ]);
            }

            $desired = $this->permissionsFor($planCode);
            $this->ensurePermissionsExist($desired);

            $current = $role->permissions()->pluck('name')->all();

            sort($desired);
            sort($current);

            if ($desired === $current) {
                return;
            }

            $role->syncPermissions($this->permissionsFor($planCode));
        } finally {
            $registrar->setPermissionsTeamId($previousTeamId);
        }
    }

    /**
     * @param  list<string>  $names
     */
    private function ensurePermissionsExist(array $names): void
    {
        $registrar = app(PermissionRegistrar::class);
        $teamId = $registrar->getPermissionsTeamId();
        $registrar->setPermissionsTeamId(0);

        try {
            foreach ($names as $name) {
                Permission::findOrCreate($name, 'web');
            }

            $registrar->forgetCachedPermissions();
        } finally {
            $registrar->setPermissionsTeamId($teamId);
        }
    }
}
