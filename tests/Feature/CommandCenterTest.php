<?php

namespace Tests\Feature;

use Bityukov\CommandCenter\Filament\Pages\Commands;
use Bityukov\CommandCenter\Filament\Pages\History;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;
use Tests\Concerns\CreatesSubscribedRestaurant;
use Tests\TestCase;

class CommandCenterTest extends TestCase
{
    use CreatesSubscribedRestaurant;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_founder_can_open_command_center_pages(): void
    {
        $founder = $this->makeFounder();

        $this->actingAs($founder);
        Filament::setCurrentPanel('founder');

        Livewire::test(Commands::class)
            ->assertOk()
            ->assertSee('Kedaluwarsa operasional')
            ->assertSee('Proses siklus langganan')
            ->assertSee('Daftar jadwal Artisan')
            ->assertSee('Daftar job gagal');

        Livewire::test(History::class)->assertOk();
    }

    public function test_super_admin_can_open_command_center(): void
    {
        $admin = $this->makeSuperAdmin();

        $this->actingAs($admin);
        Filament::setCurrentPanel('founder');

        Livewire::test(Commands::class)->assertOk();
    }

    public function test_owner_cannot_open_founder_command_center(): void
    {
        $restaurant = $this->makeRestaurant();
        $owner = $this->makeOwner($restaurant);

        $this->actingAs($owner);
        Filament::setCurrentPanel('founder');

        $this->get(Commands::getUrl(panel: 'founder'))->assertForbidden();
    }

    public function test_admin_panel_does_not_register_command_center(): void
    {
        $restaurant = $this->makeRestaurant();
        $owner = $this->makeOwner($restaurant);

        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        $this->get('/admin/'.$restaurant->slug.'/command-center/commands')->assertNotFound();
        $this->assertFalse(Filament::getPanel('admin')->hasPlugin('command-center'));
    }

    public function test_command_center_gates_are_defined_for_platform_operators(): void
    {
        $this->assertTrue(Gate::has('command-center:access'));
        $this->assertTrue(Gate::has('command-center:prune-history'));
        $this->assertTrue(Gate::has('command-center:manage-commands'));

        $founder = $this->makeFounder();
        $this->actingAs($founder);

        $this->assertTrue(Gate::allows('command-center:access'));
        $this->assertTrue(Gate::allows('command-center:prune-history'));

        $restaurant = $this->makeRestaurant();
        $owner = $this->makeOwner($restaurant);
        $this->actingAs($owner);

        $this->assertFalse(Gate::allows('command-center:access'));
    }

    public function test_command_center_check_passes(): void
    {
        $this->artisan('command-center:check')->assertSuccessful();
    }
}
