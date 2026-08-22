<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->can('settings.manage');
    }

    public function view(User $user, Role $role): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, Role $role): bool
    {
        if ($role->isOwnerRole()) {
            return false;
        }

        return $this->viewAny($user);
    }

    public function delete(User $user, Role $role): bool
    {
        if (in_array($role->name, Role::LOCKED_NAMES, true)) {
            return false;
        }

        return $this->viewAny($user);
    }

    public function forceDelete(User $user, Role $role): bool
    {
        return $this->delete($user, $role);
    }
}
