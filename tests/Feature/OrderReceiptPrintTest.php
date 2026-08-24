<?php

namespace Tests\Feature;

use App\Models\Restaurant;
use App\Models\User;
use App\Services\CashierOrderService;
use App\Support\OrderReceiptDownloadUrl;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class OrderReceiptPrintTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    public function test_guest_cannot_open_print_shell(): void
    {
        Storage::fake('local');

        $world = $this->createGuestRestaurant();
        $order = $this->paidGuestOrder($world, 'print-guest');

        $this->get(route('receipts.print', ['order' => $order->public_id]))
            ->assertUnauthorized();
    }

    public function test_cashier_print_shell_embeds_same_pdf_file(): void
    {
        Storage::fake('local');

        $world = $this->createGuestRestaurant();
        $order = $this->paidGuestOrder($world, 'print-shell');
        $user = $this->staffUser($world['restaurant'], ['order.verify_payment']);

        $this->actingAs($user)
            ->get(route('receipts.print', ['order' => $order->public_id, 'auto' => 1]))
            ->assertOk()
            ->assertSee('Cetak struk', false)
            ->assertSee(route('receipts.print.pdf', ['order' => $order->public_id]), false)
            ->assertSee('auto = true', false)
            ->assertSee('kertas 80mm', false);

        $pdf = $this->actingAs($user)
            ->get(route('receipts.print.pdf', ['order' => $order->public_id]))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        $this->assertStringStartsWith('%PDF', $pdf->streamedContent());
        $this->assertStringContainsString('inline', (string) $pdf->headers->get('content-disposition'));
        $this->assertTrue(Storage::disk('local')->exists('receipts/'.$order->public_id.'.pdf'));
        $this->assertSame(
            Storage::disk('local')->get('receipts/'.$order->public_id.'.pdf'),
            $pdf->streamedContent(),
        );
    }

    public function test_print_and_guest_download_use_the_same_stored_pdf_path(): void
    {
        Storage::fake('local');

        $world = $this->createGuestRestaurant();
        $order = $this->paidGuestOrder($world, 'print-same-path');
        $user = $this->staffUser($world['restaurant'], ['receipt.print']);
        $path = 'receipts/'.$order->public_id.'.pdf';

        $print = $this->actingAs($user)
            ->get(route('receipts.print.pdf', ['order' => $order->public_id]))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        $this->assertStringContainsString('inline', (string) $print->headers->get('content-disposition'));
        $this->assertSame($path, $order->fresh()->receipt->file_path);
        $this->assertSame(Storage::disk('local')->get($path), $print->streamedContent());

        $download = $this->get(OrderReceiptDownloadUrl::signed($order->fresh()))
            ->assertOk();

        $this->assertStringContainsString('attachment', (string) $download->headers->get('content-disposition'));
        $this->assertSame($path, $order->fresh()->receipt->file_path);
        $this->assertSame(Storage::disk('local')->get($path), $download->streamedContent());
        $this->assertStringStartsWith('%PDF', $download->streamedContent());
    }

    public function test_unpaid_order_cannot_be_printed(): void
    {
        Storage::fake('local');

        $world = $this->createGuestRestaurant();
        $user = $this->staffUser($world['restaurant'], ['order.verify_payment']);

        $order = app(CashierOrderService::class)->create(
            $user,
            $world['table'],
            '081234567890',
            null,
            'cash',
            false,
            [['menu_item_id' => $world['item']->id, 'qty' => 1]],
        );

        $this->actingAs($user)
            ->get(route('receipts.print', ['order' => $order->public_id]))
            ->assertNotFound();
    }

    public function test_staff_from_other_restaurant_cannot_print(): void
    {
        Storage::fake('local');

        $world = $this->createGuestRestaurant();
        $order = $this->paidGuestOrder($world, 'print-other-tenant');

        $other = Restaurant::query()->create([
            'name' => 'Resto Lain',
            'slug' => 'resto-lain',
            'is_active' => true,
        ]);
        $intruder = $this->staffUser($other, ['order.verify_payment', 'receipt.print']);

        $this->actingAs($intruder)
            ->get(route('receipts.print', ['order' => $order->public_id]))
            ->assertForbidden();
    }

    public function test_kds_staff_without_print_permission_is_forbidden(): void
    {
        Storage::fake('local');

        $world = $this->createGuestRestaurant();
        $order = $this->paidGuestOrder($world, 'print-kds');
        $cook = $this->staffUser($world['restaurant'], ['kds.view']);

        $this->actingAs($cook)
            ->get(route('receipts.print.pdf', ['order' => $order->public_id]))
            ->assertForbidden();
    }

    public function test_missing_receipt_print_row_still_allows_cashier_with_verify_payment(): void
    {
        Storage::fake('local');

        $world = $this->createGuestRestaurant();
        $order = $this->paidGuestOrder($world, 'print-missing-perm');
        $user = $this->staffUser($world['restaurant'], ['order.verify_payment']);

        Permission::query()->where('name', 'receipt.print')->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->actingAs($user)
            ->get(route('receipts.print', ['order' => $order->public_id]))
            ->assertOk();
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
            'name' => 'print-test-'.uniqid(),
            'guard_name' => 'web',
            'restaurant_id' => $restaurant->id,
        ]);
        $role->syncPermissions($permissions);
        $user->assignRole($role);

        return $user;
    }
}
