<?php

namespace Tests\Feature;

use App\Filament\Resources\Outlets\Pages\ManageOutlet;
use App\Models\Restaurant;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class OutletSettingsPageTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_ttl_fields_have_hint_actions_with_explanations(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = $this->staffUser($world['restaurant'], ['settings.manage']);

        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        $page = Livewire::test(ManageOutlet::class, ['record' => $world['outlet']->getKey()])
            ->assertOk()
            ->assertFormComponentActionExists('claim_ttl_minutes', 'claimTtlHelp')
            ->assertFormComponentActionExists('awaiting_cashier_ttl_minutes', 'awaitingCashierTtlHelp')
            ->mountFormComponentAction('claim_ttl_minutes', 'claimTtlHelp')
            ->assertFormComponentActionMounted('claim_ttl_minutes', 'claimTtlHelp');

        $this->assertStringContainsString(
            'scan QR dan mengunci meja',
            (string) $page->instance()->getMountedAction()?->getModalDescription(),
        );

        $page->unmountFormComponentAction()
            ->mountFormComponentAction('awaiting_cashier_ttl_minutes', 'awaitingCashierTtlHelp');

        $this->assertStringContainsString(
            'menunggu kasir menekan Terima atau Tolak',
            (string) $page->instance()->getMountedAction()?->getModalDescription(),
        );
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
            'name' => 'outlet-settings-'.uniqid(),
            'guard_name' => 'web',
            'restaurant_id' => $restaurant->id,
        ]);
        $role->syncPermissions($permissions);
        $user->assignRole($role);

        return $user;
    }
}
