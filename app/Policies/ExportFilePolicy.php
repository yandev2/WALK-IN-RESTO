<?php

namespace App\Policies;

use App\Models\ExportFile;
use App\Models\User;

class ExportFilePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->can('analytics.view');
    }

    public function view(User $user, ExportFile $exportFile): bool
    {
        if (! $this->viewAny($user)) {
            return false;
        }

        return $this->belongsToAccessibleTenant($user, $exportFile);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function delete(User $user, ExportFile $exportFile): bool
    {
        return $this->view($user, $exportFile);
    }

    public function restore(User $user, ExportFile $exportFile): bool
    {
        return $this->view($user, $exportFile);
    }

    public function forceDelete(User $user, ExportFile $exportFile): bool
    {
        return $this->view($user, $exportFile);
    }

    private function belongsToAccessibleTenant(User $user, ExportFile $exportFile): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        $restaurant = $exportFile->restaurant;

        if (! $restaurant) {
            return false;
        }

        return $user->canAccessTenant($restaurant);
    }
}
