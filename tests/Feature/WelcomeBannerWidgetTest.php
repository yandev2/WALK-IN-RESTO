<?php

namespace Tests\Feature;

use App\Filament\Pages\Dashboard;
use App\Filament\Widgets\WelcomeBannerWidget;
use App\Models\CmsProfile;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class WelcomeBannerWidgetTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_welcome_banner_shows_user_name_and_restaurant_theme(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        CmsProfile::query()->updateOrCreate(
            ['restaurant_id' => $world['restaurant']->id],
            [
                'primary_color' => '#112233',
                'accent_color' => '#AABBCC',
            ],
        );
        $world['restaurant']->load('cmsProfile');

        $user = User::factory()->create([
            'username' => 'welcome-'.uniqid(),
            'name' => 'Budi Santoso',
        ]);

        app(PermissionRegistrar::class)->setPermissionsTeamId($world['restaurant']->id);

        $role = Role::query()->create([
            'name' => 'welcome-'.uniqid(),
            'guard_name' => 'web',
            'restaurant_id' => $world['restaurant']->id,
        ]);
        $role->syncPermissions(['analytics.view']);
        $user->assignRole($role);

        $this->actingAs($user);

        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']->fresh('cmsProfile'));

        Livewire::test(WelcomeBannerWidget::class)
            ->assertOk()
            ->assertSee('Halo, Budi Santoso')
            ->assertSee($world['restaurant']->name)
            ->assertSee('--wb-primary: #112233', false)
            ->assertSee('--wb-accent: #AABBCC', false)
            ->assertSee('Waktu sekarang')
            ->assertSee('x-text="time"', false);

        Livewire::test(Dashboard::class)
            ->assertOk()
            ->assertSee('WelcomeBannerWidget')
            ->assertSee('Halo, Budi Santoso');
    }

    public function test_welcome_banner_visible_for_cashier_without_analytics(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = User::factory()->create([
            'username' => 'cashier-'.uniqid(),
            'name' => 'Kasir Utama',
        ]);

        app(PermissionRegistrar::class)->setPermissionsTeamId($world['restaurant']->id);

        $role = Role::query()->create([
            'name' => 'cashier-'.uniqid(),
            'guard_name' => 'web',
            'restaurant_id' => $world['restaurant']->id,
        ]);
        $role->syncPermissions(['order.verify_payment']);
        $user->assignRole($role);

        $this->actingAs($user);

        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        $this->assertTrue(WelcomeBannerWidget::canView());

        Livewire::test(WelcomeBannerWidget::class)
            ->assertOk()
            ->assertSee('Halo, Kasir Utama');
    }
}
