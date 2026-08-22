<?php

namespace Tests\Feature;

use App\Filament\Resources\DiningTables\Pages\TrashDiningTables;
use App\Filament\Resources\MenuCategories\Pages\TrashMenuCategories;
use App\Filament\Resources\MenuItems\Pages\TrashMenuItems;
use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\Users\Pages\TrashUsers;
use App\Models\DiningTable;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use App\Models\Visit;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class SoftDeleteTrashTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_menu_category_soft_delete_appears_in_trash_and_restores(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = $this->staffUser($world['restaurant'], ['menu.manage']);

        $category = MenuCategory::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'name' => 'Kosong',
            'sort_order' => 99,
            'is_active' => true,
        ]);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        $category->delete();

        $this->assertSoftDeleted($category);
        $this->assertNull(MenuCategory::query()->find($category->id));
        $this->assertNotNull(MenuCategory::onlyTrashed()->find($category->id));

        Livewire::test(TrashMenuCategories::class)
            ->assertOk()
            ->assertCanSeeTableRecords([$category->fresh()]);

        $category->restore();

        $this->assertNull($category->fresh()->deleted_at);
        $this->assertNotNull(MenuCategory::query()->find($category->id));
    }

    public function test_menu_category_with_items_cannot_be_deleted(): void
    {
        $world = $this->createGuestRestaurant();
        $category = MenuCategory::query()->findOrFail($world['item']->category_id);

        $this->expectException(ValidationException::class);
        $category->delete();
    }

    public function test_user_soft_delete_trash_and_restore(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $admin = $this->staffUser($world['restaurant'], ['settings.manage']);
        $target = $this->staffUser($world['restaurant'], ['menu.manage']);

        $this->actingAs($admin);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        $target->delete();

        $this->assertSoftDeleted($target);
        $this->assertFalse($target->fresh()->canAccessPanel(Filament::getCurrentOrDefaultPanel()));

        Livewire::test(TrashUsers::class)
            ->assertOk()
            ->assertCanSeeTableRecords([$target->fresh()]);

        $target->restore();

        $this->assertNull($target->fresh()->deleted_at);
    }

    public function test_dining_table_soft_delete_trash_and_restore(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = $this->staffUser($world['restaurant'], ['table.manage']);
        $table = $world['otherTable'];

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        $table->delete();

        $this->assertSoftDeleted($table);
        $this->assertNull(DiningTable::query()->find($table->id));

        Livewire::test(TrashDiningTables::class)
            ->assertOk()
            ->assertCanSeeTableRecords([$table->fresh()]);

        $table->restore();

        $this->assertNotNull(DiningTable::query()->find($table->id));
    }

    public function test_dining_table_with_open_visit_cannot_be_deleted(): void
    {
        $world = $this->createGuestRestaurant();
        $table = $world['table'];

        $visit = Visit::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'table_id' => $table->id,
            'status' => 'open',
            'join_pin' => '1234',
            'customer_wa' => '6281234567890',
            'claimed_at' => now(),
            'claim_expires_at' => now()->addHour(),
        ]);

        $table->update(['open_visit_id' => $visit->id]);

        $this->expectException(ValidationException::class);
        $table->delete();
    }

    public function test_menu_item_soft_delete_hidden_from_landing(): void
    {
        $world = $this->createGuestRestaurant();
        $item = $world['item'];

        $this->get(route('landing.show', $world['restaurant']->slug))
            ->assertOk()
            ->assertSee($item->name, false);

        $item->delete();

        $this->assertSoftDeleted($item);
        $this->assertNull(MenuItem::query()->find($item->id));

        $this->get(route('landing.show', $world['restaurant']->slug))
            ->assertOk()
            ->assertDontSee($item->name, false);
    }

    public function test_menu_item_trash_page_lists_soft_deleted(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = $this->staffUser($world['restaurant'], ['menu.manage']);
        $world['item']->delete();

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        Livewire::test(TrashMenuItems::class)
            ->assertOk()
            ->assertCanSeeTableRecords([$world['item']->fresh()]);
    }

    public function test_order_resource_has_no_trash_page_and_cannot_delete(): void
    {
        $this->assertArrayNotHasKey('trash', OrderResource::getPages());
        $this->assertFalse(OrderResource::canDelete(new Order));
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
            'name' => 'soft-delete-test-'.uniqid(),
            'guard_name' => 'web',
            'restaurant_id' => $restaurant->id,
        ]);
        $role->syncPermissions($permissions);
        $user->assignRole($role);

        return $user;
    }
}
