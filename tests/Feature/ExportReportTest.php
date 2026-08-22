<?php

namespace Tests\Feature;

use App\Filament\Exports\PdfExporter;
use App\Jobs\ExportReportJob;
use App\Models\ExportFile;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Outlet;
use App\Models\Restaurant;
use App\Models\User;
use App\Services\Export\ExportFinishedNotifier;
use App\Services\Export\ReportExportBuilder;
use App\Services\Export\ReportExportDispatcher;
use App\Services\ExportService;
use App\Support\TenantContext;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class ExportReportTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_dispatcher_queues_export_for_current_restaurant_only(): void
    {
        $this->seed(RolePermissionSeeder::class);
        Queue::fake();

        $world = $this->createGuestRestaurant();
        $user = $this->staffUser($world['restaurant'], ['analytics.view']);

        TenantContext::set($world['restaurant']);

        $export = app(ReportExportDispatcher::class)->dispatch(
            $world['restaurant'],
            $user,
            ExportFile::MODULE_OMZET_HARIAN,
            ExportFile::FORMAT_EXCEL,
            ['date_from' => now('Asia/Jakarta')->toDateString(), 'date_to' => now('Asia/Jakarta')->toDateString()],
        );

        TenantContext::clear();

        $this->assertSame($world['restaurant']->id, $export->restaurant_id);
        $this->assertSame(ExportFile::STATUS_QUEUED, $export->status);
        $this->assertStringStartsWith('restaurants/'.$world['restaurant']->id.'/export/', $export->file_path);

        Queue::assertPushed(ExportReportJob::class, fn (ExportReportJob $job): bool => $job->exportFileId === $export->id);
    }

    public function test_katalog_menu_filters_respect_category_active_and_stock(): void
    {
        $world = $this->createGuestRestaurant();
        $otherCategory = MenuCategory::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'name' => 'Makanan',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        MenuItem::query()->whereKey($world['item']->id)->update([
            'is_active' => true,
            'is_out_of_stock' => false,
        ]);

        $inactive = MenuItem::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'category_id' => $world['item']->category_id,
            'station_id' => $world['item']->station_id,
            'name' => 'Nonaktif',
            'price' => 1000,
            'is_active' => false,
            'is_out_of_stock' => false,
            'sort_order' => 3,
        ]);

        $oos = MenuItem::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'category_id' => $otherCategory->id,
            'station_id' => $world['item']->station_id,
            'name' => 'Habis',
            'price' => 2000,
            'is_active' => true,
            'is_out_of_stock' => true,
            'sort_order' => 4,
        ]);

        $payload = app(ReportExportBuilder::class)->build(
            $world['restaurant'],
            ExportFile::MODULE_KATALOG_MENU,
            ExportFile::FORMAT_EXCEL,
            [
                'category_id' => $world['item']->category_id,
                'is_active' => true,
                'is_out_of_stock' => false,
            ],
        );

        $names = collect($payload['data']['rows'])->pluck('name')->all();

        $this->assertContains('Es Teh', $names);
        $this->assertNotContains($inactive->name, $names);
        $this->assertNotContains($oos->name, $names);
    }

    public function test_katalog_menu_rejects_foreign_category_id(): void
    {
        $a = $this->createGuestRestaurant();
        $b = Restaurant::query()->create([
            'name' => 'Other',
            'slug' => 'other-resto',
            'timezone' => 'Asia/Jakarta',
            'currency' => 'IDR',
            'is_active' => true,
        ]);
        $outletB = Outlet::query()->create([
            'restaurant_id' => $b->id,
            'code' => 'MAIN',
            'name' => 'Utama',
            'is_default' => true,
            'is_open' => true,
            'is_active' => true,
            'pb1_pct' => 10,
            'service_pct' => 5,
            'tax_mode' => 'exclusive',
        ]);
        $foreignCategory = MenuCategory::query()->create([
            'restaurant_id' => $b->id,
            'outlet_id' => $outletB->id,
            'name' => 'Foreign',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->seed(RolePermissionSeeder::class);
        $user = $this->staffUser($a['restaurant'], ['analytics.view']);

        $this->expectException(ValidationException::class);

        app(ReportExportDispatcher::class)->dispatch(
            $a['restaurant'],
            $user,
            ExportFile::MODULE_KATALOG_MENU,
            ExportFile::FORMAT_EXCEL,
            ['category_id' => $foreignCategory->id],
        );
    }

    public function test_download_is_denied_for_other_tenant_user(): void
    {
        $this->seed(RolePermissionSeeder::class);
        Storage::fake('public');

        $worldA = $this->createGuestRestaurant();
        $worldB = Restaurant::query()->create([
            'name' => 'Resto B',
            'slug' => 'resto-b',
            'timezone' => 'Asia/Jakarta',
            'currency' => 'IDR',
            'is_active' => true,
        ]);

        $userA = $this->staffUser($worldA['restaurant'], ['analytics.view']);
        $userB = $this->staffUser($worldB, ['analytics.view']);

        $path = 'restaurants/'.$worldA['restaurant']->id.'/export/omzet.xlsx';
        Storage::disk('public')->put($path, 'dummy');

        $export = ExportFile::query()->create([
            'restaurant_id' => $worldA['restaurant']->id,
            'user_id' => $userA->id,
            'filename' => 'omzet.xlsx',
            'file_path' => $path,
            'disk' => 'public',
            'module' => ExportFile::MODULE_OMZET_HARIAN,
            'format' => ExportFile::FORMAT_EXCEL,
            'status' => ExportFile::STATUS_COMPLETED,
            'filters' => [],
            'mime_type' => 'application/octet-stream',
            'file_size' => 5,
        ]);

        $this->actingAs($userB)
            ->get(route('export-files.download', $export->id))
            ->assertForbidden();

        $this->actingAs($userA)
            ->get(route('export-files.download', $export->id))
            ->assertOk();
    }

    public function test_export_job_builds_file_and_marks_completed(): void
    {
        $this->seed(RolePermissionSeeder::class);
        Storage::fake('public');

        $world = $this->createGuestRestaurant();
        $user = $this->staffUser($world['restaurant'], ['analytics.view']);

        $path = 'restaurants/'.$world['restaurant']->id.'/export/katalog.xlsx';

        $export = ExportFile::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'user_id' => $user->id,
            'filename' => 'katalog.xlsx',
            'file_path' => $path,
            'disk' => 'public',
            'module' => ExportFile::MODULE_KATALOG_MENU,
            'format' => ExportFile::FORMAT_EXCEL,
            'status' => ExportFile::STATUS_QUEUED,
            'filters' => [
                'category_id' => null,
                'is_active' => null,
                'is_out_of_stock' => null,
            ],
        ]);

        (new ExportReportJob($export->id))->handle(
            app(ExportService::class),
            app(ReportExportBuilder::class),
            app(ExportFinishedNotifier::class),
        );

        $export->refresh();

        $this->assertSame(ExportFile::STATUS_COMPLETED, $export->status);
        Storage::disk('public')->assertExists($path);
        $this->assertGreaterThan(0, (int) $export->file_size);

        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
        ]);

        $payload = json_decode(
            (string) DB::table('notifications')
                ->where('notifiable_id', $user->id)
                ->latest('id')
                ->value('data'),
            true,
        );

        $this->assertSame('filament', $payload['format'] ?? null);
        $this->assertSame('Ekspor selesai', $payload['title'] ?? null);
    }

    public function test_export_view_renders_branded_header_and_summary(): void
    {
        $world = $this->createGuestRestaurant();

        $payload = app(ReportExportBuilder::class)->build(
            $world['restaurant'],
            ExportFile::MODULE_KATALOG_MENU,
            ExportFile::FORMAT_PDF,
            [],
        );

        $html = view($payload['view'], $payload['data'])->render();

        $this->assertStringContainsString('Katalog item menu', $html);
        $this->assertStringContainsString($world['restaurant']->name, $html);
        $this->assertStringContainsString('Es Teh', $html);
        $this->assertStringContainsString('Diekspor:', $html);
        $this->assertStringContainsString('Total item', $html);
        $this->assertStringContainsString('Rp ', $html);
        $this->assertStringContainsString('Dokumen ini digenerate otomatis', $html);

        $omzet = app(ReportExportBuilder::class)->build(
            $world['restaurant'],
            ExportFile::MODULE_OMZET_HARIAN,
            ExportFile::FORMAT_EXCEL,
            [
                'date_from' => now('Asia/Jakarta')->toDateString(),
                'date_to' => now('Asia/Jakarta')->toDateString(),
            ],
        );

        $omzetHtml = view($omzet['view'], $omzet['data'])->render();
        $this->assertStringContainsString('Ringkasan omzet harian', $omzetHtml);
        $this->assertStringContainsString('Periode:', $omzetHtml);

        Storage::fake('public');
        (new PdfExporter($payload['data'], $payload['view'], 'exports/katalog-preview.pdf'))->export();
        Storage::disk('public')->assertExists('exports/katalog-preview.pdf');
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
            'name' => 'export-test-'.uniqid(),
            'guard_name' => 'web',
            'restaurant_id' => $restaurant->id,
        ]);
        $role->syncPermissions($permissions);
        $user->assignRole($role);

        return $user;
    }
}
