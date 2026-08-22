<?php

namespace App\Policies;

use App\Models\Restaurant;
use App\Models\User;
use App\Support\SubscriptionAccess;
use Filament\Facades\Filament;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isPlatformOperator() || $user->can('settings.manage');
    }

    public function view(User $user, User $staff): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user)
            && SubscriptionAccess::allows('settings_full', mutate: true);
    }

    public function update(User $user, User $staff): bool
    {
        if ($this->isProtectedOwner($staff)) {
            return false;
        }

        return $this->create($user);
    }

    public function delete(User $user, User $staff): bool
    {
        if ($staff->is($user) || $this->isProtectedOwner($staff)) {
            return false;
        }

        return $this->create($user);
    }

    public function restore(User $user, User $staff): bool
    {
        return $this->viewAny($user) && ! $this->isProtectedOwner($staff);
    }

    public function forceDelete(User $user, User $staff): bool
    {
        return $this->delete($user, $staff);
    }

    private function isProtectedOwner(User $staff): bool
    {
        $tenant = Filament::getTenant();

        return $staff->isRestaurantOwner($tenant instanceof Restaurant ? $tenant : null);
    }
}
