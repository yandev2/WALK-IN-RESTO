<?php

namespace Tests\Feature;

use App\Filament\Pages\Dashboard;
use App\Filament\Widgets\AnalyticsKpiWidget;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class AnalyticsKpiWidgetTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_dashboard_includes_kpi_widget_markup(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = User::factory()->create(['username' => 'stats-'.uniqid()]);

        app(PermissionRegistrar::class)->setPermissionsTeamId($world['restaurant']->id);

        $role = Role::query()->create([
            'name' => 'stats-'.uniqid(),
            'guard_name' => 'web',
            'restaurant_id' => $world['restaurant']->id,
        ]);
        $role->syncPermissions(['analytics.view']);
        $user->assignRole($role);

        $this->actingAs($user);

        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        $this->assertTrue(AnalyticsKpiWidget::canView());

        $html = Livewire::test(Dashboard::class)->html();

        $this->assertStringContainsString('AnalyticsKpiWidget', $html);
        $this->assertStringContainsString('Omzet hari ini', $html);
    }

    public function test_kpi_widget_renders_content(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = User::factory()->create(['username' => 'stats-lazy-'.uniqid()]);

        app(PermissionRegistrar::class)->setPermissionsTeamId($world['restaurant']->id);

        $role = Role::query()->create([
            'name' => 'stats-lazy-'.uniqid(),
            'guard_name' => 'web',
            'restaurant_id' => $world['restaurant']->id,
        ]);
        $role->syncPermissions(['analytics.view']);
        $user->assignRole($role);

        $this->actingAs($user);

        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        $this->assertFalse(AnalyticsKpiWidget::isLazy());

        Livewire::test(AnalyticsKpiWidget::class, [
            'pageFilters' => [
                'date_from' => now()->subDays(6)->toDateString(),
                'date_to' => now()->toDateString(),
            ],
        ])
            ->assertOk()
            ->assertSee('Omzet hari ini')
            ->assertSee('Order lunas')
            ->assertSee('Void / waste');
    }
}
