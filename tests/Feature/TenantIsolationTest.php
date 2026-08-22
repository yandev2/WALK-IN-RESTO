<?php

namespace Tests\Feature;

use App\Filament\Pages\Dashboard;
use App\Filament\Pages\ManageCmsProfile;
use App\Models\CmsProfile;
use App\Models\Restaurant;
use App\Models\RestaurantCategory;
use App\Models\User;
use Database\Seeders\RestaurantCategorySeeder;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_owner_cannot_open_another_restaurant_admin(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $other = $this->otherRestaurant();
        $owner = $this->staffUser($world['restaurant'], ['cms.manage']);

        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');

        $this->get(Dashboard::getUrl(tenant: $other))->assertNotFound();
        $this->get(Dashboard::getUrl(tenant: $world['restaurant']))->assertOk();
    }

    public function test_super_admin_can_open_another_restaurant_panel(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $other = $this->otherRestaurant();
        $admin = $this->superAdmin();

        $this->actingAs($admin);
        Filament::setCurrentPanel('admin');

        $this->get(Dashboard::getUrl(tenant: $world['restaurant']))->assertOk();
        $this->get(Dashboard::getUrl(tenant: $other))->assertOk();
        $this->assertStringContainsString(
            $other->slug,
            Dashboard::getUrl(tenant: $other),
        );
    }

    public function test_cms_profile_saves_restaurant_identity_and_landing_copy(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $this->seed(RestaurantCategorySeeder::class);

        $world = $this->createGuestRestaurant();
        $owner = $this->staffUser($world['restaurant'], ['cms.manage']);
        $indonesiaId = RestaurantCategory::query()->where('slug', 'indonesia')->value('id');

        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        Livewire::test(ManageCmsProfile::class)
            ->assertOk()
            ->assertFormFieldExists('how_to_image_path')
            ->assertFormFieldExists('about_image_path')
            ->assertFormFieldExists('price_level')
            ->assertFormFieldExists('category_ids')
            ->assertFormFieldExists('facilities')
            ->fillForm([
                'name' => 'Resto Baru',
                'slug' => 'resto-api',
                'timezone' => 'Asia/Jakarta',
                'currency' => 'IDR',
                'headline' => 'Headline baru',
                'price_level' => 2,
                'category_ids' => [$indonesiaId],
                'facilities' => ['wifi', 'parking'],
            ])
            ->call('save');

        $restaurant = $world['restaurant']->fresh();
        $this->assertSame('Resto Baru', $restaurant->name);
        $this->assertSame(2, $restaurant->price_level);
        $this->assertTrue($restaurant->hasFacility('wifi'));
        $this->assertTrue($restaurant->hasFacility('parking'));
        $this->assertFalse($restaurant->hasFacility('outdoor'));
        $this->assertEqualsCanonicalizing(
            [$indonesiaId],
            $restaurant->categories()->pluck('restaurant_categories.id')->all(),
        );
        $this->assertSame(
            'Headline baru',
            CmsProfile::query()->where('restaurant_id', $world['restaurant']->id)->value('headline'),
        );
    }

    public function test_tenant_profile_route_and_header_menu_are_gone(): void
    {
        Filament::setCurrentPanel('admin');

        $this->assertFalse(Filament::getCurrentOrDefaultPanel()->hasTenantMenu());
        $this->assertFalse(Filament::getCurrentOrDefaultPanel()->hasTenantProfile());
        $this->assertFalse(Route::has('filament.admin.tenant.profile'));
    }

    public function test_staff_user_cannot_belong_to_two_restaurants(): void
    {
        $world = $this->createGuestRestaurant();
        $other = $this->otherRestaurant();
        $user = User::factory()->create();

        $world['restaurant']->users()->attach($user->id, ['is_active' => true]);

        $this->assertTrue($user->isBoundToOtherRestaurant($other));

        try {
            $user->assertCanJoinRestaurant($other);
            $this->fail('Expected ValidationException');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('email', $exception->errors());
        }

        $this->expectException(QueryException::class);
        $other->users()->attach($user->id, ['is_active' => true]);
    }

    public function test_global_scope_limits_queries_when_filament_tenant_is_set(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $other = $this->otherRestaurant();
        $owner = $this->staffUser($world['restaurant'], ['cms.manage']);

        $otherOutlet = \App\Models\Outlet::query()->create([
            'restaurant_id' => $other->id,
            'code' => 'MAIN',
            'name' => 'Utama Lain',
            'is_default' => true,
            'is_open' => true,
            'is_active' => true,
        ]);

        $categoryB = \App\Models\MenuCategory::query()->create([
            'restaurant_id' => $other->id,
            'outlet_id' => $otherOutlet->id,
            'name' => 'Kategori B',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $stationB = \App\Models\KdsStation::query()->create([
            'restaurant_id' => $other->id,
            'outlet_id' => $otherOutlet->id,
            'slug' => 'kitchen',
            'name' => 'Dapur B',
        ]);

        $itemB = \App\Models\MenuItem::query()->create([
            'restaurant_id' => $other->id,
            'outlet_id' => $otherOutlet->id,
            'category_id' => $categoryB->id,
            'station_id' => $stationB->id,
            'name' => 'Menu Tenant B',
            'price' => 20000,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $itemA = $world['item'];

        Filament::setCurrentPanel('admin');

        $openIds = \App\Models\MenuItem::query()->pluck('id')->all();
        $this->assertContains($itemA->id, $openIds);
        $this->assertContains($itemB->id, $openIds);

        $this->actingAs($owner);
        Filament::setTenant($world['restaurant']);

        $scopedIds = \App\Models\MenuItem::query()->pluck('id')->all();

        $this->assertContains($itemA->id, $scopedIds);
        $this->assertNotContains($itemB->id, $scopedIds);
        $this->assertNull(\App\Models\MenuItem::query()->find($itemB->id));

        $unscopedIds = \App\Models\MenuItem::query()->withoutRestaurantScope()->pluck('id')->all();
        $this->assertContains($itemA->id, $unscopedIds);
        $this->assertContains($itemB->id, $unscopedIds);
    }

    public function test_global_scope_does_not_break_public_landing_queries(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        Filament::setCurrentPanel('admin');
        Filament::setTenant(null);

        $this->get(route('landing.show', 'resto-demo'))
            ->assertOk()
            ->assertSee('Nasi Goreng', false);
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

    private function superAdmin(): User
    {
        $user = User::factory()->create([
            'username' => 'superadmin-test',
        ]);

        app(PermissionRegistrar::class)->setPermissionsTeamId(0);
        $user->assignRole('super_admin');

        return $user;
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
            'name' => 'owner-test-'.uniqid(),
            'guard_name' => 'web',
            'restaurant_id' => $restaurant->id,
        ]);
        $role->syncPermissions($permissions);
        $user->assignRole($role);

        return $user;
    }
}
