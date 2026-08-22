<?php

namespace Tests\Feature;

use App\Enums\PlanCode;
use App\Enums\SubscriptionStatus;
use App\Livewire\Auth\RegisterRestaurant;
use App\Models\KdsStation;
use App\Models\PlatformSetting;
use App\Models\Restaurant;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class RegisterRestaurantTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_registration_creates_trial_restaurant_and_owner(): void
    {
        Livewire::test(RegisterRestaurant::class)
            ->set('name', 'Owner Baru')
            ->set('email', 'owner.baru@example.com')
            ->set('password', 'password12')
            ->set('password_confirmation', 'password12')
            ->call('nextFromAccount')
            ->assertSet('step', 2)
            ->set('restaurant_name', 'Warung Baru')
            ->set('slug', 'warung-baru')
            ->call('nextFromRestaurant')
            ->assertSet('step', 3)
            ->call('selectPlan', PlanCode::LandingOnly->value)
            ->assertSet('plan_code', PlanCode::LandingOnly->value)
            ->call('register')
            ->assertRedirect('/admin/warung-baru');

        $restaurant = Restaurant::query()->where('slug', 'warung-baru')->first();
        $this->assertNotNull($restaurant);
        $this->assertSame(PlanCode::LandingOnly->value, $restaurant->plan_code);
        $this->assertSame(SubscriptionStatus::Trial, $restaurant->subscription_status);
        $this->assertTrue($restaurant->trial_ends_at->gt(now()->addDays(29)));
        $this->assertTrue($restaurant->trial_ends_at->lte(now()->addDays(30)->addMinute()));
        $this->assertNotNull($restaurant->defaultOutlet);
        $this->assertSame(0, KdsStation::query()->where('restaurant_id', $restaurant->id)->count());

        $this->assertTrue(User::query()->where('email', 'owner.baru@example.com')->exists());
        $this->assertAuthenticated();

        app(PermissionRegistrar::class)->setPermissionsTeamId($restaurant->id);

        $ownerRole = Role::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('name', Role::OWNER)
            ->first();

        $this->assertNotNull($ownerRole);
        $this->assertEqualsCanonicalizing(
            ['cms.manage', 'settings.manage'],
            $ownerRole->permissions()->pluck('name')->all(),
        );
    }

    public function test_registration_uses_platform_trial_days(): void
    {
        PlatformSetting::current()->update(['trial_days' => 14]);

        Livewire::test(RegisterRestaurant::class)
            ->assertSee('Uji coba 14 hari', false)
            ->set('name', 'Owner Trial')
            ->set('email', 'owner.trial@example.com')
            ->set('password', 'password12')
            ->set('password_confirmation', 'password12')
            ->set('restaurant_name', 'Warung Trial')
            ->set('slug', 'warung-trial')
            ->set('plan_code', PlanCode::LandingOnly->value)
            ->call('register');

        $restaurant = Restaurant::query()->where('slug', 'warung-trial')->first();
        $this->assertNotNull($restaurant);
        $this->assertTrue($restaurant->trial_ends_at->gt(now()->addDays(13)));
        $this->assertTrue($restaurant->trial_ends_at->lt(now()->addDays(15)));
    }

    public function test_plan_step_preselects_and_allows_switching_plans(): void
    {
        Livewire::test(RegisterRestaurant::class)
            ->set('name', 'Owner Paket')
            ->set('email', 'owner.paket@example.com')
            ->set('password', 'password12')
            ->set('password_confirmation', 'password12')
            ->call('nextFromAccount')
            ->set('restaurant_name', 'Warung Paket')
            ->set('slug', 'warung-paket')
            ->call('nextFromRestaurant')
            ->assertSet('step', 3)
            ->assertSet('plan_code', PlanCode::LandingOnly->value)
            ->assertSee('Landing Page Only', false)
            ->call('selectPlan', PlanCode::ManagementKds->value)
            ->assertSet('plan_code', PlanCode::ManagementKds->value);
    }

    public function test_registration_rejects_reserved_slug(): void
    {
        Livewire::test(RegisterRestaurant::class)
            ->set('name', 'Owner Baru')
            ->set('email', 'owner2@example.com')
            ->set('password', 'password12')
            ->set('password_confirmation', 'password12')
            ->set('restaurant_name', 'Admin Resto')
            ->set('slug', 'admin')
            ->set('plan_code', PlanCode::LandingOnly->value)
            ->call('register')
            ->assertHasErrors(['slug']);
    }
}
