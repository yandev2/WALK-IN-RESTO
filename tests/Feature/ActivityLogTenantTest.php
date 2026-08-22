<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\User;
use App\Support\ActivityLogger;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class ActivityLogTenantTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_activity_logger_stores_restaurant_id(): void
    {
        $world = $this->createGuestRestaurant();
        $user = User::factory()->create();

        ActivityLogger::log('visit.reset_pin', [
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'visit_id' => null,
            'user_id' => $user->id,
            'old' => ['pin_fail_count' => 1],
            'new' => ['pin_fail_count' => 0],
        ]);

        $activity = Activity::query()->withoutRestaurantScope()->latest('id')->first();

        $this->assertNotNull($activity);
        $this->assertSame($world['restaurant']->id, (int) $activity->restaurant_id);
        $this->assertSame('visit.reset_pin', $activity->event);
        $this->assertSame('visit', $activity->log_name);
    }

    public function test_menu_item_logs_activity_with_restaurant_id_when_tenant_set(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $owner = $this->staffUser($world['restaurant'], ['cms.manage']);

        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        $item = $world['item'];
        $item->update(['price' => 9999]);

        $activity = Activity::query()
            ->where('subject_type', MenuItem::class)
            ->where('subject_id', $item->id)
            ->latest('id')
            ->first();

        $this->assertNotNull($activity);
        $this->assertSame($world['restaurant']->id, (int) $activity->restaurant_id);
        $this->assertSame('menu', $activity->log_name);
        $this->assertSame('updated', $activity->event);
    }

    public function test_global_scope_hides_other_restaurant_activities(): void
    {
        $world = $this->createGuestRestaurant();
        $other = Restaurant::query()->create([
            'name' => 'Resto Lain',
            'slug' => 'resto-lain-activity',
            'timezone' => 'Asia/Jakarta',
            'currency' => 'IDR',
            'is_active' => true,
        ]);

        ActivityLogger::log('order.create', [
            'restaurant_id' => $world['restaurant']->id,
            'new' => ['source' => 'cashier'],
        ]);
        ActivityLogger::log('order.create', [
            'restaurant_id' => $other->id,
            'new' => ['source' => 'cashier'],
        ]);

        $this->seed(RolePermissionSeeder::class);
        $owner = $this->staffUser($world['restaurant'], ['audit.view']);
        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        $scopedCreates = Activity::query()->where('event', 'order.create')->get();

        $this->assertCount(1, $scopedCreates);
        $this->assertSame($world['restaurant']->id, (int) $scopedCreates->first()->restaurant_id);
        $this->assertSame(2, Activity::query()->withoutRestaurantScope()->where('event', 'order.create')->count());
    }

    /**
     * @param  list<string>  $permissions
     */
    private function staffUser(Restaurant $restaurant, array $permissions): User
    {
        $user = User::factory()->create();
        $restaurant->users()->attach($user->id, ['is_active' => true]);

        app(PermissionRegistrar::class)->setPermissionsTeamId($restaurant->id);

        $role = Role::query()->create([
            'name' => 'activity-staff-'.uniqid(),
            'guard_name' => 'web',
            'restaurant_id' => $restaurant->id,
        ]);
        $role->syncPermissions($permissions);
        $user->assignRole($role);

        return $user;
    }
}
