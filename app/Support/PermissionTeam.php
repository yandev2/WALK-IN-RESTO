<?php

namespace App\Support;

use App\Models\Restaurant;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Spatie\Permission\PermissionRegistrar;

final class PermissionTeam
{
    public static function syncFromTenant(?Restaurant $tenant = null, ?User $user = null): void
    {
        $tenant ??= Filament::getTenant() instanceof Restaurant
            ? Filament::getTenant()
            : null;

        if (! $tenant instanceof Restaurant) {
            return;
        }

        $registrar = app(PermissionRegistrar::class);
        $teamId = $tenant->getKey();

        if ((string) $registrar->getPermissionsTeamId() === (string) $teamId) {
            return;
        }

        $registrar->setPermissionsTeamId($teamId);

        $user ??= auth()->user();

        if ($user instanceof User) {
            $user->unsetRelation('roles');
            $user->unsetRelation('permissions');
        }
    }

    public static function tenantFromRequest(Request $request): ?Restaurant
    {
        $tenant = Filament::getTenant();

        if ($tenant instanceof Restaurant) {
            return $tenant;
        }

        $param = $request->route()?->parameter('tenant');

        if ($param instanceof Restaurant) {
            return $param;
        }

        if (is_string($param) && $param !== '') {
            return Restaurant::query()->where('slug', $param)->first();
        }

        return null;
    }
}
