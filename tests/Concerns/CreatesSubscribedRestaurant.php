<?php

namespace Tests\Concerns;

use App\Enums\PlanCode;
use App\Enums\SubscriptionStatus;
use App\Models\Restaurant;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

trait CreatesSubscribedRestaurant
{
    protected function makeRestaurant(array $overrides = []): Restaurant
    {
        return Restaurant::query()->create(array_merge([
            'name' => 'Resto Billing',
            'slug' => 'resto-billing-'.uniqid(),
            'timezone' => 'Asia/Jakarta',
            'currency' => 'IDR',
            'is_active' => true,
            'listed_in_directory' => true,
            'landing_enabled' => true,
            'plan_code' => PlanCode::ManagementKds->value,
            'subscription_status' => SubscriptionStatus::Trial,
            'trial_ends_at' => now()->addDays(30),
        ], $overrides));
    }

    /**
     * @param  list<string>  $permissions
     */
    protected function makeStaff(Restaurant $restaurant, array $permissions, string $roleName = 'owner'): User
    {
        $user = User::factory()->create();
        $restaurant->users()->attach($user->id, ['is_active' => true]);

        app(PermissionRegistrar::class)->setPermissionsTeamId($restaurant->id);

        $role = Role::query()->create([
            'name' => $roleName.'-'.uniqid(),
            'guard_name' => 'web',
            'restaurant_id' => $restaurant->id,
        ]);
        $role->syncPermissions($permissions);
        $user->assignRole($role);

        app(PermissionRegistrar::class)->setPermissionsTeamId(0);

        return $user;
    }

    protected function makeOwner(Restaurant $restaurant): User
    {
        $user = User::factory()->create();
        $restaurant->users()->attach($user->id, ['is_active' => true]);

        app(PermissionRegistrar::class)->setPermissionsTeamId($restaurant->id);

        Role::query()->firstOrCreate(
            [
                'name' => 'owner',
                'guard_name' => 'web',
                'restaurant_id' => $restaurant->id,
            ],
        );
        $user->assignRole('owner');

        app(PermissionRegistrar::class)->setPermissionsTeamId(0);

        return $user;
    }

    protected function makeSuperAdmin(): User
    {
        $user = User::factory()->create();
        app(PermissionRegistrar::class)->setPermissionsTeamId(0);
        $user->assignRole('super_admin');

        return $user;
    }

    protected function makeFounder(): User
    {
        $user = User::factory()->create();
        app(PermissionRegistrar::class)->setPermissionsTeamId(0);
        $user->assignRole('founder');

        return $user;
    }
}
