<?php

namespace Tests\Feature;

use App\Enums\PlanCode;
use App\Enums\SubscriptionStatus;
use App\Filament\Pages\ManageCmsProfile;
use App\Filament\Pages\SubscriptionStatus as SubscriptionStatusPage;
use App\Filament\Resources\DiningTables\Pages\ManageDiningTables;
use App\Filament\Resources\MenuItems\MenuItemResource;
use App\Http\Middleware\EnsureTenantSubscription;
use App\Models\Restaurant;
use App\Models\User;
use App\Support\SubscriptionWriteGuard;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Livewire\Livewire;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\Concerns\CreatesSubscribedRestaurant;
use Tests\TestCase;

class GraceReadOnlyTest extends TestCase
{
    use CreatesGuestRestaurant;
    use CreatesSubscribedRestaurant;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_grace_allows_view_but_blocks_create(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
            'subscription_status' => SubscriptionStatus::Grace,
            'grace_ends_at' => now()->addDays(5),
        ]);
        $owner = $this->makeStaff($restaurant, ['menu.manage', 'cms.manage']);

        $this->actingAsTenant($owner, $restaurant);

        $this->assertTrue(MenuItemResource::canViewAny());
        $this->assertFalse(MenuItemResource::canCreate());
    }

    public function test_grace_blocks_cms_save(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::LandingOnly->value,
            'subscription_status' => SubscriptionStatus::Grace,
            'grace_ends_at' => now()->addDays(5),
        ]);
        $owner = $this->makeStaff($restaurant, ['cms.manage']);

        $this->actingAsTenant($owner, $restaurant);

        Livewire::test(ManageCmsProfile::class)
            ->call('save')
            ->assertForbidden();
    }

    public function test_write_guard_blocks_mutations_except_billing_and_table_reads(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
            'subscription_status' => SubscriptionStatus::Grace,
            'grace_ends_at' => now()->addDays(5),
        ]);
        $owner = $this->makeStaff($restaurant, ['cms.manage', 'table.manage']);

        $this->actingAsTenant($owner, $restaurant);

        $this->assertTrue(SubscriptionWriteGuard::shouldBlockCall(ManageCmsProfile::class, 'save'));
        $this->assertTrue(SubscriptionWriteGuard::shouldBlockCall(ManageDiningTables::class, 'saveTablePosition'));
        $this->assertTrue(SubscriptionWriteGuard::shouldBlockCall(ManageDiningTables::class, 'callMountedAction'));
        $this->assertFalse(SubscriptionWriteGuard::shouldBlockCall(ManageDiningTables::class, 'gotoPage'));
        $this->assertFalse(SubscriptionWriteGuard::shouldBlockCall(ManageDiningTables::class, 'updatedActiveTab'));
        $this->assertFalse(SubscriptionWriteGuard::shouldBlockCall(SubscriptionStatusPage::class, 'create'));
        $this->assertFalse(SubscriptionWriteGuard::shouldBlockCall(SubscriptionStatusPage::class, 'submitProof'));
    }

    public function test_grace_blocks_dining_table_layout_save(): void
    {
        $world = $this->createGuestRestaurant();
        $restaurant = $world['restaurant'];
        $restaurant->forceFill([
            'plan_code' => PlanCode::ManagementKds->value,
            'subscription_status' => SubscriptionStatus::Grace,
            'grace_ends_at' => now()->addDays(5),
        ])->save();

        $owner = $this->makeStaff($restaurant, ['table.manage']);
        $table = $world['table']->fresh();
        $table->forceFill([
            'floor_x_pct' => 10,
            'floor_y_pct' => 20,
        ])->save();

        $this->actingAsTenant($owner, $restaurant);

        Livewire::test(ManageDiningTables::class)
            ->set('isEditingLayout', true)
            ->call('saveTablePosition', $table->id, 42.5, 18.25)
            ->assertForbidden();

        $table->refresh();
        $this->assertSame(10.0, $table->floor_x_pct);
        $this->assertSame(20.0, $table->floor_y_pct);
    }

    public function test_grace_allows_dashboard_get_but_middleware_blocks_post(): void
    {
        $restaurant = $this->makeRestaurant([
            'plan_code' => PlanCode::ManagementKds->value,
            'subscription_status' => SubscriptionStatus::Grace,
            'grace_ends_at' => now()->addDays(5),
        ]);
        $owner = $this->makeStaff($restaurant, ['analytics.view']);

        $this->actingAs($owner)
            ->get('/admin/'.$restaurant->slug)
            ->assertOk();

        $this->actingAs($owner);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);

        $post = Request::create('/admin/'.$restaurant->slug.'/tables', 'POST');
        $post->setUserResolver(fn () => $owner);

        try {
            app(EnsureTenantSubscription::class)->handle($post, fn () => response('ok'));
            $this->fail('Expected 403 when posting outside Livewire during grace.');
        } catch (HttpException $exception) {
            $this->assertSame(403, $exception->getStatusCode());
        }

        $livewire = Request::create('/livewire/update', 'POST');
        $livewire->setUserResolver(fn () => $owner);
        $passed = app(EnsureTenantSubscription::class)->handle($livewire, fn () => response('ok'));
        $this->assertSame('ok', $passed->getContent());
    }

    private function actingAsTenant(User $user, Restaurant $restaurant): void
    {
        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
        Filament::setTenant($restaurant);
        app(PermissionRegistrar::class)->setPermissionsTeamId($restaurant->id);
    }
}
