<?php

namespace App\Http\Middleware;

use App\Enums\PlanCode;
use App\Models\Restaurant;
use App\Models\User;
use App\Services\SubscriptionPlanSync;
use App\Support\PermissionTeam;
use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\Response;

class SetPermissionsTeamId
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = PermissionTeam::tenantFromRequest($request);
        $teamId = $tenant instanceof Restaurant ? $tenant->getKey() : 0;
        $registrar = app(PermissionRegistrar::class);

        if ((string) $registrar->getPermissionsTeamId() !== (string) $teamId) {
            $registrar->setPermissionsTeamId($teamId);

            $user = $request->user();

            if ($user instanceof User) {
                $user->unsetRelation('roles');
                $user->unsetRelation('permissions');
            }
        }

        if ($tenant instanceof Restaurant) {
            app(SubscriptionPlanSync::class)->syncOwnerPermissions(
                $tenant,
                $tenant->plan_code ?: PlanCode::ManagementKds->value,
            );
        }

        return $next($request);
    }
}
