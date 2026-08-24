<?php

namespace Tests\Feature;

use App\Enums\PlanCode;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\UserResource;
use App\Http\Middleware\SetPermissionsTeamId;
use App\Models\Restaurant;
use App\Models\Role;
use App\Models\User;
use App\Services\SubscriptionPlanSync;
use BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\Concerns\CreatesSubscribedRestaurant;
use Tests\TestCase;

class OwnerRoleProtectionTest extends TestCase
{
    use CreatesSubscribedRestaurant;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_owner_staff_cannot_be_edited_or_deleted(): void
    {
        $restaurant = $this->makeRestaurant();
        $owner = $this->makeOwner($restaurant);
        $kasir = $this->makeStaff($restaurant, ['order.create'], 'kasir');

        app(SubscriptionPlanSync::class)->syncOwnerPermissions(
            $restaurant,
            PlanCode::ManagementKds->value,
        );

        $this->actingAsTenant($owner, $restaurant);

        $this->assertFalse(UserResource::canEdit($owner));
        $this->assertFalse(UserResource::canDelete($owner));
        $this->assertTrue(UserResource::canEdit($kasir));
        $this->assertTrue(UserResource::canDelete($kasir));

        $this->assertFalse($owner->delete());
        $this->assertNotSoftDeleted($owner);

        Livewire::test(ListUsers::class)
            ->assertOk()
            ->assertTableActionHidden('edit', $owner)
            ->assertTableActionHidden('delete', $owner)
            ->assertTableActionVisible('edit', $kasir)
            ->assertTableActionVisible('delete', $kasir);
    }

    public function test_owner_role_cannot_be_updated_or_deleted(): void
    {
        $restaurant = $this->makeRestaurant();
        $owner = $this->makeOwner($restaurant);

        app(SubscriptionPlanSync::class)->syncOwnerPermissions(
            $restaurant,
            PlanCode::ManagementKds->value,
        );

        $this->actingAsTenant($owner, $restaurant);

        $ownerRole = Role::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('name', Role::OWNER)
            ->firstOrFail();

        $kasirRole = Role::query()->create([
            'name' => 'kasir',
            'guard_name' => 'web',
            'restaurant_id' => $restaurant->id,
        ]);

        $this->assertFalse($owner->can('update', $ownerRole));
        $this->assertFalse($owner->can('delete', $ownerRole));
        $this->assertTrue($owner->can('update', $kasirRole));
        $this->assertTrue($owner->can('delete', $kasirRole));

        $this->assertFalse($ownerRole->delete());
        $this->assertDatabaseHas('roles', ['id' => $ownerRole->id, 'name' => Role::OWNER]);

        $ownerRole->name = 'bukan-owner';
        $this->assertFalse($ownerRole->save());
        $this->assertSame(Role::OWNER, $ownerRole->fresh()->name);

        Livewire::test(ListRoles::class)
            ->assertOk()
            ->assertTableActionHidden('edit', $ownerRole)
            ->assertTableActionHidden('delete', $ownerRole)
            ->assertTableActionVisible('edit', $kasirRole)
            ->assertTableActionVisible('delete', $kasirRole);
    }

    public function test_tenant_boot_syncs_owner_permissions_from_plan(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::LandingOnly->value,
        ]);
        $owner = $this->makeOwner($restaurant);

        $this->actingAsTenant($owner, $restaurant);

        $ownerRole = Role::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('name', Role::OWNER)
            ->firstOrFail();

        $this->assertSame([], $ownerRole->permissions()->pluck('name')->all());

        $request = request();
        $request->setUserResolver(fn (): User => $owner);

        app(SetPermissionsTeamId::class)->handle(
            $request,
            fn () => response('ok'),
        );

        $this->assertEqualsCanonicalizing(
            ['cms.manage', 'settings.manage'],
            $ownerRole->fresh()->permissions()->pluck('name')->all(),
        );
    }

    public function test_management_plan_gives_owner_all_custom_permissions(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
        ]);
        $this->makeOwner($restaurant);

        app(PermissionRegistrar::class)->setPermissionsTeamId($restaurant->id);

        app(SubscriptionPlanSync::class)->syncOwnerPermissions(
            $restaurant,
            PlanCode::ManagementKds->value,
        );

        $ownerRole = Role::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('name', Role::OWNER)
            ->firstOrFail();

        $this->assertEqualsCanonicalizing(
            config('filament-shield.custom_permissions'),
            $ownerRole->permissions()->pluck('name')->all(),
        );
    }

    public function test_sync_creates_missing_custom_permissions_without_failing(): void
    {
        Permission::query()->where('name', 'receipt.print')->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->assertFalse(Permission::query()->where('name', 'receipt.print')->exists());

        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
        ]);
        $this->makeOwner($restaurant);

        app(SubscriptionPlanSync::class)->syncOwnerPermissions(
            $restaurant,
            PlanCode::ManagementKds->value,
        );

        $this->assertTrue(Permission::query()->where('name', 'receipt.print')->where('guard_name', 'web')->exists());

        $ownerRole = Role::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('name', Role::OWNER)
            ->firstOrFail();

        $this->assertTrue($ownerRole->permissions()->where('name', 'receipt.print')->exists());
    }

    private function actingAsTenant(User $user, Restaurant $restaurant): void
    {
        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);
        app(PermissionRegistrar::class)->setPermissionsTeamId($restaurant->id);
    }
}
