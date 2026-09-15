<?php

namespace Tests\Feature;

use App\Filament\Founder\Resources\Tenants\Pages\EditTenant;
use App\Filament\Founder\Resources\Tenants\Pages\ListTenants;
use App\Models\Restaurant;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Spatie\Permission\PermissionRegistrar;
use Tests\Concerns\CreatesSubscribedRestaurant;
use Tests\TestCase;

class TenantResetOwnerPasswordTest extends TestCase
{
    use CreatesSubscribedRestaurant;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    protected function createTenantWithOwner(string $slug, string $name, string $ownerEmail, string $password = 'secret-12345'): array
    {
        $restaurant = Restaurant::query()->create([
            'name' => $name,
            'slug' => $slug,
            'timezone' => 'Asia/Jakarta',
            'currency' => 'IDR',
            'is_active' => true,
        ]);

        $owner = User::factory()->create([
            'name' => 'Owner ' . $name,
            'email' => $ownerEmail,
            'password' => Hash::make($password),
            'is_active' => true,
        ]);

        $restaurant->users()->attach($owner->id, ['is_active' => true]);

        app(PermissionRegistrar::class)->setPermissionsTeamId($restaurant->id);

        Role::query()->firstOrCreate([
            'name' => 'owner',
            'guard_name' => 'web',
            'restaurant_id' => $restaurant->id,
        ]);

        $owner->assignRole('owner');

        app(PermissionRegistrar::class)->setPermissionsTeamId(0);

        return [
            'restaurant' => $restaurant,
            'owner' => $owner,
        ];
    }

    protected function createFounderUser(): User
    {
        return $this->makeFounder();
    }

    public function test_restaurant_get_owners_returns_owner_users(): void
    {
        $data = $this->createTenantWithOwner('resto-alpha', 'Resto Alpha', 'alpha@resto.test');
        /** @var Restaurant $restaurant */
        $restaurant = $data['restaurant'];
        /** @var User $owner */
        $owner = $data['owner'];

        $owners = $restaurant->getOwners();

        $this->assertCount(1, $owners);
        $this->assertSame($owner->id, $owners->first()->id);
    }

    public function test_founder_can_reset_single_owner_password_via_table_action(): void
    {
        $founder = $this->createFounderUser();
        $data = $this->createTenantWithOwner('resto-beta', 'Resto Beta', 'beta@resto.test', 'super-custom-pass-99');

        /** @var Restaurant $restaurant */
        $restaurant = $data['restaurant'];
        /** @var User $owner */
        $owner = $data['owner'];

        // Confirm initial password is NOT 'password'
        $this->assertFalse(Hash::check('password', $owner->fresh()->password));

        $this->actingAs($founder);
        Filament::setCurrentPanel('founder');

        Livewire::test(ListTenants::class)
            ->callTableAction('resetOwnerPassword', $restaurant)
            ->assertHasNoTableActionErrors();

        // Fresh password must now be 'password'
        $this->assertTrue(Hash::check('password', $owner->fresh()->password));

        // Owner can authenticate using 'password'
        $this->assertTrue(Auth::attempt([
            'email' => 'beta@resto.test',
            'password' => 'password',
        ]));
    }

    public function test_founder_can_reset_owner_password_via_edit_page_header_action(): void
    {
        $founder = $this->createFounderUser();
        $data = $this->createTenantWithOwner('resto-gamma', 'Resto Gamma', 'gamma@resto.test', 'custom-gamma-password');

        /** @var Restaurant $restaurant */
        $restaurant = $data['restaurant'];
        /** @var User $owner */
        $owner = $data['owner'];

        $this->actingAs($founder);
        Filament::setCurrentPanel('founder');

        Livewire::test(EditTenant::class, ['record' => $restaurant->getKey()])
            ->callAction('resetOwnerPassword')
            ->assertHasNoActionErrors();

        $this->assertTrue(Hash::check('password', $owner->fresh()->password));
        $this->assertTrue(Auth::attempt([
            'email' => 'gamma@resto.test',
            'password' => 'password',
        ]));
    }

    public function test_founder_can_reset_specific_owner_in_multi_owner_tenant(): void
    {
        $founder = $this->createFounderUser();
        $data = $this->createTenantWithOwner('resto-delta', 'Resto Delta', 'delta1@resto.test', 'delta-pass-1');

        /** @var Restaurant $restaurant */
        $restaurant = $data['restaurant'];
        /** @var User $owner1 */
        $owner1 = $data['owner'];

        // Add second owner
        $owner2 = User::factory()->create([
            'name' => 'Owner 2 Delta',
            'email' => 'delta2@resto.test',
            'password' => Hash::make('delta-pass-2'),
            'is_active' => true,
        ]);
        $restaurant->users()->attach($owner2->id, ['is_active' => true]);
        app(PermissionRegistrar::class)->setPermissionsTeamId($restaurant->id);
        $owner2->assignRole('owner');
        app(PermissionRegistrar::class)->setPermissionsTeamId(0);

        $this->actingAs($founder);
        Filament::setCurrentPanel('founder');

        // Reset ONLY owner1
        Livewire::test(ListTenants::class)
            ->callTableAction('resetOwnerPassword', $restaurant, [
                'target_user_id' => $owner1->id,
            ])
            ->assertHasNoTableActionErrors();

        // Owner 1 password must be 'password'
        $this->assertTrue(Hash::check('password', $owner1->fresh()->password));

        // Owner 2 password must STILL be their old password
        $this->assertFalse(Hash::check('password', $owner2->fresh()->password));
        $this->assertTrue(Hash::check('delta-pass-2', $owner2->fresh()->password));
    }

    public function test_founder_can_reset_all_owners_in_multi_owner_tenant(): void
    {
        $founder = $this->createFounderUser();
        $data = $this->createTenantWithOwner('resto-epsilon', 'Resto Epsilon', 'eps1@resto.test', 'eps-pass-1');

        /** @var Restaurant $restaurant */
        $restaurant = $data['restaurant'];
        /** @var User $owner1 */
        $owner1 = $data['owner'];

        $owner2 = User::factory()->create([
            'name' => 'Owner 2 Epsilon',
            'email' => 'eps2@resto.test',
            'password' => Hash::make('eps-pass-2'),
            'is_active' => true,
        ]);
        $restaurant->users()->attach($owner2->id, ['is_active' => true]);
        app(PermissionRegistrar::class)->setPermissionsTeamId($restaurant->id);
        $owner2->assignRole('owner');
        app(PermissionRegistrar::class)->setPermissionsTeamId(0);

        $this->actingAs($founder);
        Filament::setCurrentPanel('founder');

        // Reset ALL owners
        Livewire::test(ListTenants::class)
            ->callTableAction('resetOwnerPassword', $restaurant, [
                'target_user_id' => 'all',
            ])
            ->assertHasNoTableActionErrors();

        // Both owners must now have password 'password'
        $this->assertTrue(Hash::check('password', $owner1->fresh()->password));
        $this->assertTrue(Hash::check('password', $owner2->fresh()->password));
    }
}
