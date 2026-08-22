<?php

namespace App\Providers;

use App\Livewire\Hooks\BlockGraceMutations;
use App\Models\ExportFile;
use App\Models\Role;
use App\Models\User;
use App\Observers\ExportFileObserver;
use App\Policies\ExportFilePolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use App\Support\GuestContext;
use App\Support\PermissionTeam;
use App\Support\RestaurantTheme;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\ComponentHookRegistry;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        ComponentHookRegistry::register(BlockGraceMutations::class);
    }

    public function boot(): void
    {
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(ExportFile::class, ExportFilePolicy::class);
        ExportFile::observe(ExportFileObserver::class);

        Gate::before(function ($user, string $ability, mixed $arguments = []): ?bool {
            if (! $user instanceof User) {
                return null;
            }

            $model = is_array($arguments) ? ($arguments[0] ?? null) : $arguments;

            if (
                $model instanceof Role
                && $model->isOwnerRole()
                && in_array($ability, ['update', 'delete', 'forceDelete', 'restore', 'replicate'], true)
            ) {
                return false;
            }

            if ($user->isPlatformOperator()) {
                return true;
            }

            PermissionTeam::syncFromTenant(user: $user);

            if (
                method_exists($user, 'checkPermissionTo')
                && ! in_array($ability, [
                    'viewAny', 'view', 'create', 'update', 'delete', 'deleteAny',
                    'restore', 'forceDelete', 'forceDeleteAny', 'restoreAny', 'replicate', 'reorder',
                ], true)
            ) {
                return $user->checkPermissionTo($ability) ?: null;
            }

            return null;
        });

        Gate::define('command-center:access', fn ($user): bool => $user instanceof User && $user->isPlatformOperator());
        Gate::define('command-center:prune-history', fn ($user): bool => $user instanceof User && $user->isPlatformOperator());
        Gate::define('command-center:manage-commands', fn ($user): bool => $user instanceof User && $user->isPlatformOperator());

        View::composer('layouts.guest-order', function ($view): void {
            $visit = GuestContext::visit();
            $restaurant = $visit?->outlet?->restaurant;

            if ($restaurant && ! $restaurant->relationLoaded('cmsProfile')) {
                $restaurant->load('cmsProfile');
            }

            $view->with('theme', RestaurantTheme::for($restaurant));
            $view->with('guestRestaurant', $restaurant);
            $view->with('guestVisit', $visit);
        });

        FileUpload::configureUsing(function (FileUpload $component): void {
            if ($component->getDiskName() === 'public') {
                $component->deleteUploadedFileUsing(
                    fn (string $file): bool => Storage::disk('public')->delete($file),
                );
            }
        });
    }

    private static function registerStyle(): void {}
}
