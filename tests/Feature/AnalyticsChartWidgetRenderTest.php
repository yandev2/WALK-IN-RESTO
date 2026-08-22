<?php

namespace Tests\Feature;

use App\Filament\Widgets\AnalyticsPeriodSummaryWidget;
use App\Filament\Widgets\AnalyticsRevenueBarWidget;
use App\Filament\Widgets\AnalyticsSidebarWidget;
use App\Models\User;
use App\Support\RestaurantAnalyticsPeriod;
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

        $defaults = RestaurantAnalyticsPeriod::defaultLocalDateRange($world['restaurant']);
        $from = $defaults['from']->copy()->subMonth()->startOfMonth();
        $to = $from->copy()->addDays(6);
        $filteredStartLabel = $from->translatedFormat('j M');
        $defaultStartLabel = $defaults['from']->translatedFormat('j M');

        $filtered = Livewire::test(AnalyticsRevenueBarWidget::class, [
            'pageFilters' => [
                'date_from' => $from->toDateString(),
                'date_to' => $to->toDateString(),
            ],
        ]);

        $filtered
            ->assertOk()
            ->assertSee('wire:key="revenue-bar-'.$from->toDateString().'-'.$to->toDateString().'"', false);

        $this->assertContains($filteredStartLabel, $filtered->instance()->getChartData()['labels']);

        $unfiltered = Livewire::test(AnalyticsRevenueBarWidget::class);

        $unfiltered
            ->assertOk()
            ->assertSee(
                'wire:key="revenue-bar-'.$defaults['from']->toDateString().'-'.$defaults['to']->toDateString().'"',
                false,
            );

        $this->assertContains($defaultStartLabel, $unfiltered->instance()->getChartData()['labels']);
        $this->assertNotContains($filteredStartLabel, $unfiltered->instance()->getChartData()['labels']);
    }
}
