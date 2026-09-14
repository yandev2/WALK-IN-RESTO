<?php

namespace App\Http\Controllers;

use App\Models\CashierShift;
use App\Models\User;
use App\Services\CashierShiftService;
use App\Support\PermissionCheck;
use Illuminate\Http\Request;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\Response;

class CashierShiftPrintController extends Controller
{
    public function __invoke(Request $request, CashierShift $shift, CashierShiftService $shiftService): Response
    {
        $this->authorizeShift($request, $shift);

        return $shiftService->streamPdf($shift);
    }

    private function authorizeShift(Request $request, CashierShift $shift): void
    {
        $user = $request->user();
        abort_unless($user instanceof User, 403);

        $restaurant = $shift->restaurant;
        abort_unless($restaurant !== null, 404);
        abort_unless($user->isPlatformOperator() || $user->canAccessTenant($restaurant), 403);

        app(PermissionRegistrar::class)->setPermissionsTeamId($shift->restaurant_id);
        $user->unsetRelation('roles');
        $user->unsetRelation('permissions');

        abort_unless(
            $user->isPlatformOperator()
            || $user->id === $shift->user_id
            || PermissionCheck::allowsAny($user, ['order.create', 'order.view_any', 'report.view']),
            403,
        );
    }
}
