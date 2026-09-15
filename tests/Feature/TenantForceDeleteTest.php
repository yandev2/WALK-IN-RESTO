<?php

namespace Tests\Feature;

use App\Console\Commands\TenantForceDeleteCommand;
use App\Jobs\ForceDeleteTenantJob;
use App\Models\CmsBanner;
use App\Models\CmsProfile;
use App\Models\Customer;
use App\Models\DiningTable;
use App\Models\ExportFile;
use App\Models\KdsStation;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\MenuItemPhoto;
use App\Models\MenuVariant;
use App\Models\Modifier;
use App\Models\ModifierGroup;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderReceipt;
use App\Models\Outlet;
use App\Models\Payment;
use App\Models\Restaurant;
use App\Models\RestaurantReview;
use App\Models\User;
use App\Models\Visit;
use App\Services\TenantPurgeService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class TenantForceDeleteTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    protected function createCustomWorld(string $slug, string $name): array
    {
        $restaurant = Restaurant::query()->create([
            'name' => $name,
            'slug' => $slug,
            'timezone' => 'Asia/Jakarta',
            'currency' => 'IDR',
            'is_active' => true,
        ]);

        $outlet = Outlet::query()->create([
            'restaurant_id' => $restaurant->id,
            'code' => 'OUTLET-' . Str::upper($slug),
            'name' => 'Outlet ' . $name,
            'address' => 'Jl. Demo',
            'is_default' => true,
            'is_open' => true,
            'is_active' => true,
            'latitude' => -6.2000000,
            'longitude' => 106.8166667,
            'geofence_radius_m' => 30,
            'gps_accuracy_max_m' => 50,
            'pb1_pct' => 10,
            'service_pct' => 5,
            'tax_mode' => 'exclusive',
        ]);

        $table = DiningTable::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'code' => 'T1',
            'status' => 'empty',
        ]);

        $category = MenuCategory::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'name' => 'Kategori ' . $name,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $station = KdsStation::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'name' => 'Station ' . $name,
            'slug' => 'station-' . $slug,
        ]);

        $item = MenuItem::query()->create([
            'restaurant_id' => $restaurant->id,
            'outlet_id' => $outlet->id,
            'category_id' => $category->id,
            'station_id' => $station->id,
            'name' => 'Menu ' . $name,
            'price' => 25000,
            'is_active' => true,
        ]);

        return [
            'restaurant' => $restaurant,
            'outlet' => $outlet,
            'table' => $table,
            'category' => $category,
            'station' => $station,
            'item' => $item,
        ];
    }

    public function test_force_delete_purges_all_database_records_and_preserves_other_tenant(): void
    {
        Storage::fake('public');
        Storage::fake('local');

        $worldTarget = $this->createCustomWorld('target-resto', 'Target Resto');
        $worldOther = $this->createCustomWorld('other-resto', 'Other Resto');

        /** @var Restaurant $target */
        $target = $worldTarget['restaurant'];
        /** @var Restaurant $other */
        $other = $worldOther['restaurant'];

        // Add additional complex data to target restaurant:
        $station = KdsStation::create([
            'restaurant_id' => $target->id,
            'outlet_id' => $worldTarget['outlet']->id,
            'name' => 'Bar Station',
            'slug' => 'bar-station',
        ]);

        $worldTarget['item']->update(['station_id' => $station->id]);

        $modGroup = ModifierGroup::create([
            'restaurant_id' => $target->id,
            'outlet_id' => $worldTarget['outlet']->id,
            'name' => 'Level Gula',
        ]);

        $modifier = Modifier::create([
            'restaurant_id' => $target->id,
            'outlet_id' => $worldTarget['outlet']->id,
            'modifier_group_id' => $modGroup->id,
            'name' => 'Less Sugar',
            'price' => 0,
        ]);

        $variant = MenuVariant::create([
            'restaurant_id' => $target->id,
            'outlet_id' => $worldTarget['outlet']->id,
            'menu_item_id' => $worldTarget['item']->id,
            'name' => 'Large',
            'price_delta' => 5000,
        ]);

        $customer = Customer::create([
            'restaurant_id' => $target->id,
            'phone' => '628111222333',
            'name' => 'Pelanggan Target',
            'tier' => 'gold',
        ]);

        $visit = Visit::create([
            'restaurant_id' => $target->id,
            'outlet_id' => $worldTarget['outlet']->id,
            'table_id' => $worldTarget['table']->id,
            'status' => 'active',
            'public_id' => (string) Str::ulid(),
            'join_pin' => '1234',
            'claimed_at' => now(),
            'claim_expires_at' => now()->addHours(2),
        ]);

        $order = Order::create([
            'restaurant_id' => $target->id,
            'outlet_id' => $worldTarget['outlet']->id,
            'visit_id' => $visit->id,
            'number' => 1,
            'public_id' => (string) Str::ulid(),
            'status' => 'completed',
            'source' => 'dine_in',
            'payment_method' => 'cash',
            'paid_at' => now(),
            'subtotal' => 25000,
            'pb1_amount' => 2500,
            'service_amount' => 1250,
            'grand_payable' => 28750,
            'grand_before' => 28750,
            'idempotency_key' => (string) Str::uuid(),
            'pb1_pct_snapshot' => 10,
            'service_pct_snapshot' => 5,
            'tax_mode_snapshot' => 'exclusive',
        ]);

        RestaurantReview::create([
            'restaurant_id' => $target->id,
            'outlet_id' => $worldTarget['outlet']->id,
            'visit_id' => $visit->id,
            'order_id' => $order->id,
            'customer_name' => 'Reviewer Target',
            'rating' => 5,
            'comment' => 'Mantap!',
            'submitted_at' => now(),
        ]);

        // Add control data to other restaurant:
        $otherVisit = Visit::create([
            'restaurant_id' => $other->id,
            'outlet_id' => $worldOther['outlet']->id,
            'table_id' => $worldOther['table']->id,
            'status' => 'active',
            'public_id' => (string) Str::ulid(),
            'join_pin' => '5678',
            'claimed_at' => now(),
            'claim_expires_at' => now()->addHours(2),
        ]);

        $otherOrder = Order::create([
            'restaurant_id' => $other->id,
            'outlet_id' => $worldOther['outlet']->id,
            'visit_id' => $otherVisit->id,
            'number' => 1,
            'public_id' => (string) Str::ulid(),
            'status' => 'completed',
            'source' => 'dine_in',
            'payment_method' => 'cash',
            'paid_at' => now(),
            'subtotal' => 25000,
            'pb1_amount' => 2500,
            'service_amount' => 1250,
            'grand_payable' => 28750,
            'grand_before' => 28750,
            'idempotency_key' => (string) Str::uuid(),
            'pb1_pct_snapshot' => 10,
            'service_pct_snapshot' => 5,
            'tax_mode_snapshot' => 'exclusive',
        ]);

        // Execute purge on target
        $service = app(TenantPurgeService::class);
        $result = $service->purge($target->id, $target->name, $target->slug);

        $this->assertTrue($result['success']);

        // Assert Target Restaurant is completely gone from DB
        $this->assertDatabaseMissing('restaurants', ['id' => $target->id]);
        $this->assertDatabaseMissing('outlets', ['restaurant_id' => $target->id]);
        $this->assertDatabaseMissing('tables', ['restaurant_id' => $target->id]);
        $this->assertDatabaseMissing('menu_categories', ['restaurant_id' => $target->id]);
        $this->assertDatabaseMissing('menu_items', ['restaurant_id' => $target->id]);
        $this->assertDatabaseMissing('kds_stations', ['restaurant_id' => $target->id]);
        $this->assertDatabaseMissing('menu_variants', ['restaurant_id' => $target->id]);
        $this->assertDatabaseMissing('modifier_groups', ['restaurant_id' => $target->id]);
        $this->assertDatabaseMissing('modifiers', ['restaurant_id' => $target->id]);
        $this->assertDatabaseMissing('visits', ['restaurant_id' => $target->id]);
        $this->assertDatabaseMissing('orders', ['restaurant_id' => $target->id]);
        $this->assertDatabaseMissing('customers', ['restaurant_id' => $target->id]);
        $this->assertDatabaseMissing('restaurant_reviews', ['restaurant_id' => $target->id]);

        // Assert Other Restaurant is 100% UNTOUCHED
        $this->assertDatabaseHas('restaurants', ['id' => $other->id]);
        $this->assertDatabaseHas('outlets', ['restaurant_id' => $other->id]);
        $this->assertDatabaseHas('tables', ['restaurant_id' => $other->id]);
        $this->assertDatabaseHas('orders', ['id' => $otherOrder->id, 'restaurant_id' => $other->id]);
    }

    public function test_force_delete_purges_physical_files_for_target_tenant_only(): void
    {
        Storage::fake('public');
        Storage::fake('local');

        $worldTarget = $this->createCustomWorld('target-files', 'Target Files');
        $worldOther = $this->createCustomWorld('other-files', 'Other Files');

        $target = $worldTarget['restaurant'];
        $other = $worldOther['restaurant'];

        // Create target files on storage
        $targetLogo = 'logos/target-logo.png';
        Storage::disk('public')->put($targetLogo, 'target-logo-content');
        $target->update(['logo_path' => $targetLogo]);

        $targetQris = 'qris/target-qris.png';
        Storage::disk('public')->put($targetQris, 'target-qris-content');
        $worldTarget['outlet']->update(['qris_image_path' => $targetQris]);

        $targetVisit = Visit::create([
            'restaurant_id' => $target->id,
            'outlet_id' => $worldTarget['outlet']->id,
            'table_id' => $worldTarget['table']->id,
            'status' => 'active',
            'public_id' => (string) Str::ulid(),
            'join_pin' => '1111',
            'claimed_at' => now(),
            'claim_expires_at' => now()->addHours(2),
        ]);

        $targetOrder = Order::create([
            'restaurant_id' => $target->id,
            'outlet_id' => $worldTarget['outlet']->id,
            'visit_id' => $targetVisit->id,
            'number' => 1,
            'public_id' => (string) Str::ulid(),
            'status' => 'completed',
            'source' => 'dine_in',
            'payment_method' => 'cash',
            'paid_at' => now(),
            'subtotal' => 25000,
            'pb1_amount' => 2500,
            'service_amount' => 1250,
            'grand_payable' => 28750,
            'grand_before' => 28750,
            'idempotency_key' => (string) Str::uuid(),
            'pb1_pct_snapshot' => 10,
            'service_pct_snapshot' => 5,
            'tax_mode_snapshot' => 'exclusive',
        ]);

        $targetReceipt = 'receipts/target-receipt.pdf';
        Storage::disk('local')->put($targetReceipt, 'target-pdf-content');
        OrderReceipt::create([
            'restaurant_id' => $target->id,
            'outlet_id' => $worldTarget['outlet']->id,
            'order_id' => $targetOrder->id,
            'file_path' => $targetReceipt,
            'generated_at' => now(),
            'created_at' => now(),
        ]);

        // Create other restaurant files on storage (control)
        $otherLogo = 'logos/other-logo.png';
        Storage::disk('public')->put($otherLogo, 'other-logo-content');
        $other->update(['logo_path' => $otherLogo]);

        $otherVisit = Visit::create([
            'restaurant_id' => $other->id,
            'outlet_id' => $worldOther['outlet']->id,
            'table_id' => $worldOther['table']->id,
            'status' => 'active',
            'public_id' => (string) Str::ulid(),
            'join_pin' => '2222',
            'claimed_at' => now(),
            'claim_expires_at' => now()->addHours(2),
        ]);

        $otherOrder = Order::create([
            'restaurant_id' => $other->id,
            'outlet_id' => $worldOther['outlet']->id,
            'visit_id' => $otherVisit->id,
            'number' => 1,
            'public_id' => (string) Str::ulid(),
            'status' => 'completed',
            'source' => 'dine_in',
            'payment_method' => 'cash',
            'paid_at' => now(),
            'subtotal' => 25000,
            'pb1_amount' => 2500,
            'service_amount' => 1250,
            'grand_payable' => 28750,
            'grand_before' => 28750,
            'idempotency_key' => (string) Str::uuid(),
            'pb1_pct_snapshot' => 10,
            'service_pct_snapshot' => 5,
            'tax_mode_snapshot' => 'exclusive',
        ]);

        $otherReceipt = 'receipts/other-receipt.pdf';
        Storage::disk('local')->put($otherReceipt, 'other-pdf-content');
        OrderReceipt::create([
            'restaurant_id' => $other->id,
            'outlet_id' => $worldOther['outlet']->id,
            'order_id' => $otherOrder->id,
            'file_path' => $otherReceipt,
            'generated_at' => now(),
            'created_at' => now(),
        ]);

        // Execute purge on target
        $service = app(TenantPurgeService::class);
        $result = $service->purge($target->id, $target->name, $target->slug);

        $this->assertTrue($result['success']);
        $this->assertGreaterThanOrEqual(3, $result['files_deleted']);

        // Target files MUST be gone
        Storage::disk('public')->assertMissing($targetLogo);
        Storage::disk('public')->assertMissing($targetQris);
        Storage::disk('local')->assertMissing($targetReceipt);

        // Other restaurant files MUST STILL EXIST
        Storage::disk('public')->assertExists($otherLogo);
        Storage::disk('local')->assertExists($otherReceipt);
    }

    public function test_force_delete_protects_platform_operators_and_deletes_orphaned_users(): void
    {
        $worldTarget = $this->createCustomWorld('target-users', 'Target Users');
        $worldOther = $this->createCustomWorld('other-users', 'Other Users');

        $target = $worldTarget['restaurant'];
        $other = $worldOther['restaurant'];

        // 1. Platform Operator (Founder / Superadmin)
        $founder = User::factory()->create(['email' => 'founder@walkin.test']);
        $founder->assignRole('founder');
        $founder->restaurants()->attach($target->id, ['is_active' => true]);

        // 2. Staff of Other Restaurant (Control)
        $otherStaff = User::factory()->create(['email' => 'otherstaff@walkin.test']);
        $otherStaff->restaurants()->attach($other->id, ['is_active' => true]);

        // 3. Exclusive / Orphaned Staff (Attached ONLY to Target)
        $exclusiveStaff = User::factory()->create(['email' => 'staff@walkin.test']);
        $exclusiveStaff->restaurants()->attach($target->id, ['is_active' => true]);

        // Execute purge
        $service = app(TenantPurgeService::class);
        $result = $service->purge($target->id, $target->name, $target->slug);

        $this->assertTrue($result['success']);
        $this->assertSame(1, $result['users_purged']);

        // Founder MUST still exist in database
        $this->assertModelExists($founder);

        // Staff of other restaurant MUST still exist in database
        $this->assertModelExists($otherStaff);
        $this->assertTrue($otherStaff->restaurants()->whereKey($other->id)->exists());

        // Exclusive staff MUST be deleted
        $this->assertModelMissing($exclusiveStaff);
    }

    public function test_force_delete_job_executes_successfully_and_sends_database_notification(): void
    {
        Storage::fake('public');
        Storage::fake('local');

        $world = $this->createCustomWorld('job-resto', 'Job Resto');
        $target = $world['restaurant'];

        $founder = User::factory()->create(['email' => 'operator@walkin.test']);
        $founder->assignRole('founder');

        $job = new ForceDeleteTenantJob($target->id, $target->name, $target->slug, $founder);
        app()->call([$job, 'handle']);

        // Assert tenant is deleted
        $this->assertDatabaseMissing('restaurants', ['id' => $target->id]);

        // Assert database notification was sent to founder
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $founder->id,
            'notifiable_type' => User::class,
        ]);
    }

    public function test_artisan_command_purges_tenant_synchronously(): void
    {
        Storage::fake('public');
        Storage::fake('local');

        $world = $this->createCustomWorld('cli-resto', 'CLI Resto');
        $target = $world['restaurant'];

        $this->artisan(TenantForceDeleteCommand::class, [
            'slug' => 'cli-resto',
            '--force' => true,
            '--sync' => true,
        ])->assertSuccessful();

        $this->assertDatabaseMissing('restaurants', ['id' => $target->id]);
    }
}
