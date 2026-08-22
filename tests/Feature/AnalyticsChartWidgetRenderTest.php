<?php

namespace Tests\Feature;

use App\Filament\Widgets\AnalyticsPeriodSummaryWidget;
use App\Filament\Widgets\AnalyticsRevenueBarWidget;
use App\Filament\Widgets\AnalyticsSidebarWidget;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class AnalyticsChartWidgetRenderTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_chart_widgets_render_without_error(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = User::factory()->create(['username' => 'chart-'.uniqid()]);

        app(PermissionRegistrar::class)->setPermissionsTeamId($world['restaurant']->id);

        $role = Role::query()->create([
            'name' => 'chart-'.uniqid(),
            'guard_name' => 'web',
            'restaurant_id' => $world['restaurant']->id,
        ]);
        $role->syncPermissions(['analytics.view']);
        $user->assignRole($role);

        $this->actingAs($user);

        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        Livewire::test(AnalyticsRevenueBarWidget::class)
            ->assertOk()
            ->assertSee('Tren omzet');

        Livewire::test(AnalyticsSidebarWidget::class)
            ->assertOk()
            ->assertSee('Metode bayar');

        Livewire::test(AnalyticsPeriodSummaryWidget::class)
            ->assertOk()
            ->assertSee('Ringkasan periode');
    }

    public function test_period_summary_aggregates_trend_days(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = User::factory()->create(['username' => 'period-'.uniqid()]);

        app(PermissionRegistrar::class)->setPermissionsTeamId($world['restaurant']->id);

        $role = Role::query()->create([
            'name' => 'period-'.uniqid(),
            'guard_name' => 'web',
            'restaurant_id' => $world['restaurant']->id,
        ]);
        $role->syncPermissions(['analytics.view']);
        $user->assignRole($role);

        $this->actingAs($user);

        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        $component = Livewire::test(AnalyticsPeriodSummaryWidget::class, [
            'pageFilters' => [
                'date_from' => now()->subDays(6)->toDateString(),
                'date_to' => now()->toDateString(),
            ],
        ]);

        $component->assertOk()->assertSee('7 hari');
    }

    public function test_revenue_chart_labels_follow_page_date_filters(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $world = $this->createGuestRestaurant();
        $user = User::factory()->create(['username' => 'chart-filter-'.uniqid()]);

        app(PermissionRegistrar::class)->setPermissionsTeamId($world['restaurant']->id);

        $role = Role::query()->create([
            'name' => 'chart-filter-'.uniqid(),
            'guard_name' => 'web',
            'restaurant_id' => $world['restaurant']->id,
        ]);
        $role->syncPermissions(['analytics.view']);
        $user->assignRole($role);

        $this->actingAs($user);

        Filament::setCurrentPanel('admin');
        Filament::setTenant($world['restaurant']);

        $from = now()->startOfMonth()->toDateString();
        $to = now()->startOfMonth()->addDays(6)->toDateString();
        $expectedStartLabel = now()->startOfMonth()->translatedFormat('j M');
        $defaultStartLabel = now()->subDays(6)->translatedFormat('j M');

        Livewire::test(AnalyticsRevenueBarWidget::class, [
            'pageFilters' => [
                'date_from' => $from,
                'date_to' => $to,
            ],
        ])
            ->assertOk()
            ->assertSee($expectedStartLabel, false)
            ->assertSee('wire:key="revenue-bar-'.$from.'-'.$to.'"', false);

        if ($expectedStartLabel !== $defaultStartLabel) {
            Livewire::test(AnalyticsRevenueBarWidget::class)
                ->assertOk()
                ->assertSee($defaultStartLabel, false)
                ->assertDontSee($expectedStartLabel, false);
        }
    }
}
