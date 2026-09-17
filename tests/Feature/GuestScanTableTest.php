<?php

namespace Tests\Feature;

use App\Livewire\Guest\ScanTable;
use App\Models\Customer;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class GuestScanTableTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    private string $deviceToken = 'test-device-token-1234567890';

    public function test_scan_table_initial_view_only_shows_whatsapp_input(): void
    {
        $world = $this->createGuestRestaurant();

        Livewire::withCookies(['guest_device' => $this->deviceToken])
            ->test(ScanTable::class, ['token' => $world['token']])
            ->assertSet('mode', 'claim')
            ->assertSet('waChecked', false)
            ->assertSet('customer_name', '')
            ->assertSee('Duduk dulu, lalu isi data.')
            ->assertSee('Nomor WhatsApp')
            ->assertDontSee('Pelanggan Terdaftar')
            ->assertDontSee('Pelanggan Baru')
            ->assertDontSee('Nama Lengkap / Panggilan');
    }

    public function test_scan_table_auto_fills_and_locks_name_for_returning_customer(): void
    {
        $world = $this->createGuestRestaurant();

        Customer::withoutRestaurantScope()->create([
            'restaurant_id' => $world['restaurant']->id,
            'phone' => '6281234567890',
            'name' => 'Pak Budi',
            'total_orders' => 2,
            'total_spent' => 50000,
        ]);

        Livewire::withCookies(['guest_device' => $this->deviceToken])
            ->test(ScanTable::class, ['token' => $world['token']])
            ->set('customer_wa', '081234567890')
            ->assertSet('waChecked', true)
            ->assertSet('isExistingCustomer', true)
            ->assertSet('isNameReadOnly', true)
            ->assertSet('customer_name', 'Pak Budi')
            ->assertSee('Pelanggan Terdaftar')
            ->assertSee('Pak Budi')
            ->assertSee('readonly');
    }

    public function test_scan_table_shows_mandatory_name_field_and_notice_for_new_customer(): void
    {
        $world = $this->createGuestRestaurant();

        Livewire::withCookies(['guest_device' => $this->deviceToken])
            ->test(ScanTable::class, ['token' => $world['token']])
            ->set('customer_wa', '089988776655')
            ->assertSet('waChecked', true)
            ->assertSet('isExistingCustomer', false)
            ->assertSet('isNameReadOnly', false)
            ->assertSet('customer_name', '')
            ->assertSee('Pelanggan Baru')
            ->assertSee('Nama Lengkap / Panggilan')
            ->assertSee('*Wajib')
            ->assertDontSee('Pelanggan Terdaftar');
    }

    public function test_scan_table_blocks_claim_if_new_customer_name_is_empty(): void
    {
        $world = $this->createGuestRestaurant();

        Livewire::withCookies(['guest_device' => $this->deviceToken])
            ->test(ScanTable::class, ['token' => $world['token']])
            ->set('customer_wa', '089988776655')
            ->set('customer_name', '')
            ->call('claim')
            ->assertHasErrors(['customer_name']);

        $this->assertSame(0, Visit::query()->count());
    }

    public function test_scan_table_succeeds_for_new_customer_with_valid_name(): void
    {
        $world = $this->createGuestRestaurant();

        Livewire::withCookies(['guest_device' => $this->deviceToken])
            ->test(ScanTable::class, ['token' => $world['token']])
            ->set('customer_wa', '089988776655')
            ->set('customer_name', 'Siti Aminah')
            ->call('claim')
            ->assertHasNoErrors()
            ->assertRedirect(route('guest.menu'));

        $this->assertSame(1, Visit::query()->count());
        $visit = Visit::query()->first();
        $this->assertSame('Siti Aminah', $visit->customer_name);
        $this->assertSame('6289988776655', $visit->customer_wa);
    }

    public function test_scan_table_resets_when_wa_is_cleared(): void
    {
        $world = $this->createGuestRestaurant();

        Customer::withoutRestaurantScope()->create([
            'restaurant_id' => $world['restaurant']->id,
            'phone' => '6281234567890',
            'name' => 'Pak Budi',
        ]);

        Livewire::withCookies(['guest_device' => $this->deviceToken])
            ->test(ScanTable::class, ['token' => $world['token']])
            ->set('customer_wa', '081234567890')
            ->assertSet('waChecked', true)
            ->assertSet('customer_name', 'Pak Budi')
            ->set('customer_wa', '')
            ->assertSet('waChecked', false)
            ->assertSet('isExistingCustomer', false)
            ->assertSet('customer_name', '')
            ->assertDontSee('Pelanggan Terdaftar');
    }
}
