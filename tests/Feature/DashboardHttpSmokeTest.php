<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class DashboardHttpSmokeTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_dashboard_http_ok(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = User::factory()->create();
        $world['restaurant']->users()->attach($user->id, ['is_active' => true]);

        app(PermissionRegistrar::class)->setPermissionsTeamId($world['restaurant']->id);

        $role = Role::query()->create([
            'name' => 'dash-'.uniqid(),
            'guard_name' => 'web',
            'restaurant_id' => $world['restaurant']->id,
        ]);
        $role->syncPermissions(['analytics.view']);
        $user->assignRole($role);

        $this->actingAs($user)
            ->get('/admin/'.$world['restaurant']->slug)
            ->assertOk();
    }

    public function test_dashboard_ok_when_permission_team_is_not_preloaded(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = User::factory()->create();
        $world['restaurant']->users()->attach($user->id, ['is_active' => true]);

        app(PermissionRegistrar::class)->setPermissionsTeamId($world['restaurant']->id);

        $role = Role::query()->create([
            'name' => 'dash-'.uniqid(),
            'guard_name' => 'web',
            'restaurant_id' => $world['restaurant']->id,
        ]);
        $role->syncPermissions(['analytics.view']);
        $user->assignRole($role);

        app(PermissionRegistrar::class)->setPermissionsTeamId(0);
        $user->unsetRelation('roles');
        $user->unsetRelation('permissions');

        $this->actingAs($user)
            ->get('/admin/'.$world['restaurant']->slug)
            ->assertOk();
    }

    public function test_admin_path_is_not_captured_by_landing_route(): void
    {
        $match = app('router')->getRoutes()->match(
            Request::create('/admin/resto-api', 'GET')
        );

        $this->assertSame('filament.admin.pages.dashboard', $match->getName());
        $this->assertSame('resto-api', $match->parameter('tenant'));
    }
}
