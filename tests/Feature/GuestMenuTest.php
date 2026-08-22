<?php

namespace Tests\Feature;

use App\Models\CmsBanner;
use App\Models\DiningTable;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\MenuVariant;
use App\Models\Restaurant;
use App\Models\Visit;
use App\Models\VisitCartItem;
use App\Models\VisitDevice;
use App\Services\GuestCartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class GuestMenuTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_guest_menu_renders_mobile_shell_and_data_sections(): void
    {
        [$visit, $token, $world] = $this->openGuestVisit();

        CmsBanner::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'title' => 'Promo Spesial',
            'subtitle' => 'Diskon hari ini',
            'image_path' => 'banners/promo.jpg',
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDay(),
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->withCookie('guest_device', $token)
            ->get(route('guest.menu'))
            ->assertOk()
            ->assertSee('guest-menu-shell', false)
            ->assertSee('max-w-md', false)
            ->assertSee('Mau makan apa hari ini?', false)
            ->assertSee('Promo Spesial', false)
            ->assertSee('Es Teh', false)
            ->assertSee('Meja 1', false)
            ->assertDontSee('menu-rail', false);
    }

    public function test_open_picker_shows_modal_and_confirm_add_with_notes(): void
    {
        [$visit, $token, $world] = $this->openGuestVisit();

        Livewire::withCookies(['guest_device' => $token])
            ->test(\App\Livewire\Guest\GuestMenu::class)
            ->call('openPicker', $world['item']->id)
            ->assertSet('pickingItemId', $world['item']->id)
            ->assertSee('Catatan')
            ->set('notes', 'Tanpa es batu')
            ->call('confirmAdd')
            ->assertSet('pickingItemId', null)
            ->assertSee('Masuk keranjang');

        $this->assertDatabaseHas('visit_cart_items', [
            'visit_id' => $visit->id,
            'menu_item_id' => $world['item']->id,
            'notes' => 'Tanpa es batu',
        ]);
    }

    public function test_open_picker_variant_can_be_changed(): void
    {
        [$visit, $token, $world] = $this->openGuestVisit();

        $pedas = MenuVariant::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'menu_item_id' => $world['item']->id,
            'name' => 'Pedas',
            'price_delta' => 0,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $tidakPedas = MenuVariant::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'menu_item_id' => $world['item']->id,
            'name' => 'Tidak Pedas',
            'price_delta' => 1000,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        Livewire::withCookies(['guest_device' => $token])
            ->test(\App\Livewire\Guest\GuestMenu::class)
            ->call('openPicker', $world['item']->id)
            ->assertSet('variantId', $pedas->id)
            ->call('setVariant', $tidakPedas->id)
            ->assertSet('variantId', $tidakPedas->id)
            ->call('confirmAdd')
            ->assertSet('pickingItemId', null);

        $this->assertDatabaseHas('visit_cart_items', [
            'visit_id' => $visit->id,
            'menu_item_id' => $world['item']->id,
            'menu_variant_id' => $tidakPedas->id,
        ]);
    }

    public function test_guest_menu_filters_by_category_and_search(): void
    {
        [$visit, $token, $world] = $this->openGuestVisit();

        $dessertCategory = MenuCategory::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'name' => 'Dessert',
            'sort_order' => 2,
        ]);

        MenuItem::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'category_id' => $dessertCategory->id,
            'station_id' => $world['item']->station_id,
            'name' => 'Pudding Coklat',
            'price' => 15000,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        Livewire::withCookies(['guest_device' => $token])
            ->test(\App\Livewire\Guest\GuestMenu::class)
            ->assertSee('Es Teh')
            ->assertSee('Pudding Coklat')
            ->call('setCategory', $dessertCategory->id)
            ->assertDontSee('Es Teh')
            ->assertSee('Pudding Coklat')
            ->set('search', 'es teh')
            ->call('setCategory', null)
            ->assertSee('Es Teh')
            ->assertDontSee('Pudding Coklat');
    }

    public function test_guest_menu_cart_actions_update_qty_from_order_panel(): void
    {
        [$visit, $token] = $this->openGuestVisit();

        $cartItem = app(GuestCartService::class)->add($visit, $visit->outlet->menuCategories()->first()->items()->first()->id);

        Livewire::withCookies(['guest_device' => $token])
            ->test(\App\Livewire\Guest\GuestMenu::class)
            ->assertSee('Pesanan saya')
            ->call('plus', $cartItem->id);

        $this->assertSame(2, VisitCartItem::query()->find($cartItem->id)?->qty);

        Livewire::withCookies(['guest_device' => $token])
            ->test(\App\Livewire\Guest\GuestMenu::class)
            ->call('minus', $cartItem->id);

        $this->assertSame(1, VisitCartItem::query()->find($cartItem->id)?->qty);
    }

    /**
     * @return array{0: Visit, 1: string, 2: array{restaurant: Restaurant, outlet: \App\Models\Outlet, table: DiningTable, item: MenuItem}}
     */
    private function openGuestVisit(): array
    {
        $world = $this->createGuestRestaurant();
        $token = Str::random(64);

        $visit = Visit::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'table_id' => $world['table']->id,
            'status' => 'open',
            'join_pin' => '4321',
            'customer_name' => 'Budi',
            'customer_wa' => '6281234567890',
            'claimed_at' => now(),
            'claim_expires_at' => now()->addHour(),
        ]);

        $world['table']->update(['open_visit_id' => $visit->id]);

        VisitDevice::query()->create([
            'restaurant_id' => $visit->restaurant_id,
            'outlet_id' => $visit->outlet_id,
            'visit_id' => $visit->id,
            'device_token' => $token,
            'is_host' => true,
            'user_agent' => 'test',
            'joined_at' => now(),
            'last_seen_at' => now(),
        ]);

        return [$visit, $token, $world];
    }
}
