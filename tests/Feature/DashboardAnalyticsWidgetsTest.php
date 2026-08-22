<?php

namespace Tests\Feature;

use App\Filament\Pages\Dashboard;
use App\Filament\Widgets\AnalyticsKpiWidget;
use App\Filament\Widgets\PendingPaymentsWidget;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class DashboardAnalyticsWidgetsTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_analytics_widgets_visible_with_permission(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = $this->userWithPermissions($world['restaurant']->id, ['analytics.view'], 'Analis Uji');

        $this->actingAs($user);

        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        Livewire::test(Dashboard::class)
            ->assertOk()
            ->assertSee('Dari')
            ->assertSee('Sampai')
            ->assertSee('Ekspor omzet')
            ->assertDontSee('Periode analytics')
            ->assertSee('Ringkasan periode')
            ->assertSee('Menu terlaris');

        Livewire::test(AnalyticsKpiWidget::class)
            ->assertOk()
            ->assertSee('Omzet hari ini')
            ->assertSee('Order lunas')
            ->assertSee('QRIS hari ini');

        $this->assertTrue(AnalyticsKpiWidget::canView());
        $this->assertFalse(PendingPaymentsWidget::canView());
    }

    public function test_cashier_sees_pending_payments_not_analytics(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = $this->userWithPermissions($world['restaurant']->id, ['order.verify_payment'], 'Kasir Uji');

        $this->actingAs($user);

        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        Livewire::test(Dashboard::class)
            ->assertOk()
            ->assertSee('Antrian kasir')
            ->assertDontSee('Ekspor omzet')
            ->assertDontSee('Omzet hari ini');

        $this->assertFalse(AnalyticsKpiWidget::canView());
        $this->assertTrue(PendingPaymentsWidget::canView());
    }

    /**
     * @param  list<string>  $permissions
     */
    private function userWithPermissions(int $restaurantId, array $permissions, string $name = 'Staf Uji'): User
    {
        app(PermissionRegistrar::class)->setPermissionsTeamId($restaurantId);

        $user = User::factory()->create([
            'name' => $name,
            'username' => 'dash-'.uniqid(),
        ]);

        $role = Role::query()->create([
            'name' => 'dash-'.uniqid(),
            'guard_name' => 'web',
            'restaurant_id' => $restaurantId,
        ]);
        $role->syncPermissions($permissions);
        $user->assignRole($role);

        return $user;
    }
}
