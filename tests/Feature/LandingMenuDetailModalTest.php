<?php

namespace Tests\Feature;

use App\Models\CmsProfile;
use App\Models\MenuItem;
use App\Models\MenuItemPhoto;
use Database\Seeders\LandingTemplateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class LandingMenuDetailModalTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_classic_template_dish_card_renders_detail_button_and_modal_dispatch(): void
    {
        $world = $this->createGuestRestaurant();
        $this->seed(LandingTemplateSeeder::class);

        $item = MenuItem::query()->findOrFail($world['item']->id);
        $item->update([
            'name' => 'Nasi Goreng Spesial',
            'description' => 'Nasi goreng lezat dengan bumbu rahasia keluarga.',
            'price' => 35000,
            'photo_path' => 'https://example.com/nasgor-1.jpg',
        ]);

        MenuItemPhoto::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'menu_item_id' => $item->id,
            'photo_path' => 'https://example.com/nasgor-2.jpg',
            'sort_order' => 0,
        ]);

        $response = $this->get(route('landing.show', $world['restaurant']));

        $response->assertOk();
        // Grid 2 rows/columns di mobile
        $response->assertSee('grid-cols-2', false);
        // Tombol Detail harus ada
        $response->assertSee('Detail', false);
        // Modal detail menu markup harus ter-render
        $response->assertSee('@open-menu-modal.window', false);
        $response->assertSee('Harga: Kunjungi restoran', false);
        // Dispatch event open-menu-modal dengan nama item
        $response->assertSee('open-menu-modal', false);
        $response->assertSee('Nasi Goreng Spesial', false);
        $response->assertSee('https://example.com/nasgor-1.jpg', false);
        $response->assertSee('https://example.com/nasgor-2.jpg', false);
    }

    public function test_foodie_template_dish_card_renders_detail_button_and_modal_dispatch(): void
    {
        $world = $this->createGuestRestaurant();
        $this->seed(LandingTemplateSeeder::class);

        CmsProfile::query()->updateOrCreate(
            ['restaurant_id' => $world['restaurant']->id],
            [
                'headline' => 'Foodie Delights',
                'landing_template' => 'foodie',
            ]
        );

        $item = MenuItem::query()->findOrFail($world['item']->id);
        $item->update([
            'name' => 'Burger Wagyu Keju',
            'description' => 'Burger daging wagyu dengan lelehan keju mozarella.',
            'price' => 50000,
        ]);

        $response = $this->get(route('landing.show', $world['restaurant']));

        $response->assertOk();
        $response->assertSee('grid-cols-2', false);
        $response->assertSee('Detail', false);
        $response->assertSee('open-menu-modal', false);
        $response->assertSee('Burger Wagyu Keju', false);
        $response->assertSee('@open-menu-modal.window', false);
    }

    public function test_glassmorphism_template_dish_card_renders_detail_button_and_modal_dispatch(): void
    {
        $world = $this->createGuestRestaurant();
        $this->seed(LandingTemplateSeeder::class);

        CmsProfile::query()->updateOrCreate(
            ['restaurant_id' => $world['restaurant']->id],
            [
                'headline' => 'Glass Dining Experience',
                'landing_template' => 'glassmorphism',
            ]
        );

        $item = MenuItem::query()->findOrFail($world['item']->id);
        $item->update([
            'name' => 'Pasta Carbonara Creamy',
            'description' => 'Pasta dengan saus krim gurih dan taburan daging asap.',
            'price' => 45000,
        ]);

        $response = $this->get(route('landing.show', $world['restaurant']));

        $response->assertOk();
        $response->assertSee('grid-cols-2', false);
        $response->assertSee('Detail', false);
        $response->assertSee('open-menu-modal', false);
        $response->assertSee('Pasta Carbonara Creamy', false);
        $response->assertSee('@open-menu-modal.window', false);
    }

    public function test_catalog_menu_dish_card_renders_detail_button_and_modal_dispatch(): void
    {
        $world = $this->createGuestRestaurant();

        $response = $this->get(route('landing.menu', $world['restaurant']));

        $response->assertOk();
        $response->assertSee('grid-cols-2', false);
        $response->assertSee('Detail', false);
        $response->assertSee('open-menu-modal', false);
        $response->assertSee('@open-menu-modal.window', false);
    }

    public function test_hidden_price_dish_card_shows_kunjungi_restoran_and_modal_payload_has_hide_price_true(): void
    {
        $world = $this->createGuestRestaurant();
        $world['outlet']->update(['hide_landing_menu_prices' => true]);

        $item = MenuItem::query()->findOrFail($world['item']->id);
        $item->update([
            'name' => 'Bebek Bakar Madu',
            'price' => 40000,
        ]);

        // 1. Landing show page
        $response = $this->get(route('landing.show', $world['restaurant']));
        $response->assertOk();
        $response->assertSee('Kunjungi Restoran', false);
        $this->assertStringContainsString('hide_price', $response->getContent());
        $response->assertDontSee('Rp 40.000', false);

        // 2. Landing menu catalog page
        $catalog = $this->get(route('landing.menu', $world['restaurant']));
        $catalog->assertOk();
        $catalog->assertSee('Kunjungi Restoran', false);
        $this->assertStringContainsString('hide_price', $catalog->getContent());
        $catalog->assertDontSee('Rp 40.000', false);
    }
}
