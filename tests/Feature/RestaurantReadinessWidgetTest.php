<?php

namespace Tests\Feature;

use App\Filament\Pages\Dashboard;
use App\Filament\Widgets\RestaurantReadinessWidget;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class RestaurantReadinessWidgetTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_readiness_widget_renders_metrics(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = User::factory()->create(['username' => 'ready-'.uniqid()]);

        app(PermissionRegistrar::class)->setPermissionsTeamId($world['restaurant']->id);

        $role = Role::query()->create([
            'name' => 'ready-'.uniqid(),
            'guard_name' => 'web',
            'restaurant_id' => $world['restaurant']->id,
        ]);
        $role->syncPermissions(['analytics.view']);
        $user->assignRole($role);

        $this->actingAs($user);

        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        $this->assertTrue(RestaurantReadinessWidget::canView());

        Livewire::test(RestaurantReadinessWidget::class)
            ->assertOk()
            ->assertSee('Kesiapan Restoran')
            ->assertSee('Menu Aktif')
            ->assertSee('Meja Siap')
            ->assertSee('Rating Ulasan')
            ->assertSee('Status Operasional Prima');

        $html = Livewire::test(Dashboard::class)->html();
        $this->assertStringContainsString('RestaurantReadinessWidget', $html);
    }
}
