<?php

namespace Tests\Feature;

use App\Filament\Resources\DiningTables\Pages\ManageDiningTables;
use App\Models\DiningTable;
use App\Models\Outlet;
use App\Models\Restaurant;
use App\Models\User;
use App\Support\TableFloorPlan;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class TableFloorPlanTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_save_table_position_updates_floor_coordinates(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $table = $world['table']->fresh();
        $user = $this->staffUser($world['restaurant'], ['table.manage']);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        Livewire::test(ManageDiningTables::class)
            ->set('isEditingLayout', true)
            ->call('saveTablePosition', $table->id, 42.5, 18.25)
            ->assertHasNoErrors();

        $table->refresh();

        $this->assertSame(42.5, $table->floor_x_pct);
        $this->assertSame(18.25, $table->floor_y_pct);
    }

    public function test_save_table_position_rejects_other_outlet_table(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $other = $this->otherRestaurant();
        $otherOutlet = $this->outletFor($other);

        $foreignTable = DiningTable::query()->create([
            'restaurant_id' => $other->id,
            'outlet_id' => $otherOutlet->id,
            'code' => 'X1',
            'capacity' => 2,
            'floor_x_pct' => 50,
            'floor_y_pct' => 50,
            'qr_version' => 1,
            'qr_secret' => Str::random(64),
        ]);

        $user = $this->staffUser($world['restaurant'], ['table.manage']);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        Livewire::test(ManageDiningTables::class)
            ->set('isEditingLayout', true)
            ->call('saveTablePosition', $foreignTable->id, 10, 10)
            ->assertNotified();

        $foreignTable->refresh();

        $this->assertSame(50.0, $foreignTable->floor_x_pct);
        $this->assertSame(50.0, $foreignTable->floor_y_pct);
    }

    public function test_area_tabs_filter_tables_by_area(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();

        $world['table']->update(['area' => 'Indoor', 'floor_x_pct' => 5, 'floor_y_pct' => 5]);
        $world['otherTable']->update(['area' => 'Outdoor', 'floor_x_pct' => 27, 'floor_y_pct' => 5]);

        $indoorIds = TableFloorPlan::applyAreaScope(
            DiningTable::query()->where('outlet_id', $world['outlet']->id),
            'Indoor',
        )->pluck('id')->all();

        $this->assertContains($world['table']->id, $indoorIds);
        $this->assertNotContains($world['otherTable']->id, $indoorIds);
    }

    public function test_area_tab_key_maps_blank_area_to_none(): void
    {
        $world = $this->createGuestRestaurant();

        $world['table']->update(['area' => null]);

        $this->assertSame('area-none', TableFloorPlan::areaTabKey(null));
        $this->assertContains(null, TableFloorPlan::areasForOutlet($world['outlet']->id));
    }

    public function test_auto_layout_assigns_grid_positions_for_area(): void
    {
        $world = $this->createGuestRestaurant();

        $world['table']->update(['area' => 'Indoor', 'floor_x_pct' => null, 'floor_y_pct' => null]);
        $world['otherTable']->update(['area' => 'Indoor', 'floor_x_pct' => null, 'floor_y_pct' => null]);

        $updated = TableFloorPlan::autoLayoutArea($world['outlet']->id, 'Indoor');

        $this->assertSame(2, $updated);

        $first = DiningTable::autoGridPosition(0);
        $second = DiningTable::autoGridPosition(1);

        $world['table']->refresh();
        $world['otherTable']->refresh();

        $this->assertEquals($first['x'], $world['table']->floor_x_pct);
        $this->assertEquals($first['y'], $world['table']->floor_y_pct);
        $this->assertEquals($second['x'], $world['otherTable']->floor_x_pct);
        $this->assertEquals($second['y'], $world['otherTable']->floor_y_pct);
    }

    public function test_new_table_gets_auto_floor_position_on_create(): void
    {
        $world = $this->createGuestRestaurant();

        DiningTable::query()
            ->where('outlet_id', $world['outlet']->id)
            ->where('area', 'Indoor')
            ->delete();

        DiningTable::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'code' => 'A1',
            'capacity' => 4,
            'area' => 'Indoor',
            'qr_version' => 1,
            'qr_secret' => Str::random(64),
        ]);

        $table = DiningTable::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'code' => 'A2',
            'capacity' => 4,
            'area' => 'Indoor',
            'qr_version' => 1,
            'qr_secret' => Str::random(64),
        ]);

        $expected = DiningTable::autoGridPosition(1);

        $this->assertEquals($expected['x'], $table->floor_x_pct);
        $this->assertEquals($expected['y'], $table->floor_y_pct);
    }

    public function test_canvas_min_height_grows_with_table_count(): void
    {
        $world = $this->createGuestRestaurant();

        $small = collect([$world['table'], $world['otherTable']]);
        $many = collect();

        for ($i = 1; $i <= 12; $i++) {
            $many->push(DiningTable::query()->create([
                'restaurant_id' => $world['restaurant']->id,
                'outlet_id' => $world['outlet']->id,
                'code' => (string) (100 + $i),
                'capacity' => 2,
                'area' => 'Indoor',
                'qr_version' => 1,
                'qr_secret' => Str::random(64),
            ]));
        }

        $smallHeight = TableFloorPlan::canvasMinHeightRem($small);
        $manyHeight = TableFloorPlan::canvasMinHeightRem($many);

        $this->assertGreaterThan($smallHeight, $manyHeight);
        $this->assertGreaterThanOrEqual(32.0, $smallHeight);
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
            'name' => 'floor-plan-'.uniqid(),
            'guard_name' => 'web',
            'restaurant_id' => $restaurant->id,
        ]);
        $role->syncPermissions($permissions);
        $user->assignRole($role);

        return $user;
    }

    private function otherRestaurant(): Restaurant
    {
        return Restaurant::query()->create([
            'name' => 'Resto Lain',
            'slug' => 'resto-lain',
            'timezone' => 'Asia/Jakarta',
            'currency' => 'IDR',
            'is_active' => true,
        ]);
    }

    private function outletFor(Restaurant $restaurant): Outlet
    {
        return Outlet::query()->create([
            'restaurant_id' => $restaurant->id,
            'code' => 'MAIN',
            'name' => 'Utama',
            'address' => 'Jl. Demo',
            'is_default' => true,
            'is_open' => true,
            'is_active' => true,
            'latitude' => -6.2,
            'longitude' => 106.8166667,
            'geofence_radius_m' => 30,
            'gps_accuracy_max_m' => 50,
            'pb1_pct' => 10,
            'service_pct' => 5,
            'tax_mode' => 'exclusive',
        ]);
    }
}
