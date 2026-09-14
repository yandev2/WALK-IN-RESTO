<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use App\Support\CashierMenuCatalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class CashierMenuCatalogTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_select_options_are_cached_and_forgotten_when_menu_changes(): void
    {
        $world = $this->createGuestRestaurant();
        $restaurantId = $world['restaurant']->id;

        Cache::flush();
        CashierMenuCatalog::forget($restaurantId);

        $options = CashierMenuCatalog::selectOptions($restaurantId);

        $this->assertNotEmpty($options);
        $this->assertArrayHasKey($world['item']->id, $options);
        $this->assertStringContainsString('Es Teh', $options[$world['item']->id]);
        $this->assertTrue(Cache::has(CashierMenuCatalog::optionsKey($restaurantId)));
        $this->assertTrue(Cache::has(CashierMenuCatalog::itemsKey($restaurantId)));

        MenuItem::query()->whereKey($world['item']->id)->update(['name' => 'Es Teh Lama']);

        $this->assertStringContainsString(
            'Es Teh',
            CashierMenuCatalog::selectOptions($restaurantId)[$world['item']->id],
        );
        $this->assertStringNotContainsString(
            'Es Teh Lama',
            CashierMenuCatalog::selectOptions($restaurantId)[$world['item']->id],
        );

        $item = $world['item']->fresh();
        $item->name = 'Es Teh Spesial';
        $item->save();

        $this->assertFalse(Cache::has(CashierMenuCatalog::optionsKey($restaurantId)));

        $fresh = CashierMenuCatalog::selectOptions($restaurantId);

        $this->assertStringContainsString('Es Teh Spesial', $fresh[$world['item']->id]);
    }

    public function test_second_select_options_call_reuses_request_memo(): void
    {
        $world = $this->createGuestRestaurant();
        $restaurantId = $world['restaurant']->id;

        CashierMenuCatalog::forget($restaurantId);

        $first = CashierMenuCatalog::selectOptions($restaurantId);
        $second = CashierMenuCatalog::selectOptions($restaurantId);

        $this->assertSame($first, $second);
        $this->assertSame(
            $first[$world['item']->id],
            CashierMenuCatalog::optionLabel($restaurantId, $world['item']->id),
        );
        $this->assertSame('Es Teh', CashierMenuCatalog::itemName($restaurantId, $world['item']->id));
        $this->assertSame('Item baru', CashierMenuCatalog::itemName($restaurantId, null));
    }

    public function test_pos_payload_is_cached_separately_and_forgotten_with_menu(): void
    {
        $world = $this->createGuestRestaurant();
        $restaurantId = $world['restaurant']->id;

        Cache::flush();
        CashierMenuCatalog::forget($restaurantId);

        $payload = CashierMenuCatalog::posPayload($restaurantId);
        $item = collect($payload)->firstWhere('id', $world['item']->id);

        $this->assertNotNull($item);
        $this->assertSame('Es Teh', $item['name']);
        $this->assertArrayHasKey('category_id', $item);
        $this->assertArrayHasKey('photo_url', $item);
        $this->assertArrayHasKey('has_modifiers', $item);
        $this->assertArrayHasKey('modifiers', $item);
        $this->assertTrue(Cache::has(CashierMenuCatalog::posKey($restaurantId)));
        $this->assertFalse(Cache::has(CashierMenuCatalog::itemsKey($restaurantId)));

        CashierMenuCatalog::selectOptions($restaurantId);
        $this->assertTrue(Cache::has(CashierMenuCatalog::itemsKey($restaurantId)));
        $this->assertTrue(Cache::has(CashierMenuCatalog::optionsKey($restaurantId)));
        $this->assertTrue(Cache::has(CashierMenuCatalog::posKey($restaurantId)));

        $categories = CashierMenuCatalog::categories($restaurantId);
        $this->assertContains('Minuman', array_column($categories, 'name'));
        $this->assertTrue(Cache::has(CashierMenuCatalog::categoriesKey($restaurantId)));

        $item = $world['item']->fresh();
        $item->name = 'Es Teh Grid';
        $item->save();

        $this->assertFalse(Cache::has(CashierMenuCatalog::posKey($restaurantId)));
        $this->assertFalse(Cache::has(CashierMenuCatalog::itemsKey($restaurantId)));
        $this->assertFalse(Cache::has(CashierMenuCatalog::categoriesKey($restaurantId)));

        $fresh = collect(CashierMenuCatalog::posPayload($restaurantId))->firstWhere('id', $world['item']->id);
        $this->assertSame('Es Teh Grid', $fresh['name']);
    }

    public function test_cashier_menu_catalog_orders_best_seller_first_in_pos_and_select_options(): void
    {
        $world = $this->createGuestRestaurant();
        $restaurantId = $world['restaurant']->id;

        // 1. Regular item
        $regular = MenuItem::query()->create([
            'restaurant_id' => $restaurantId,
            'outlet_id' => $world['outlet']->id,
            'category_id' => $world['item']->category_id,
            'station_id' => $world['item']->station_id,
            'name' => 'Menu Biasa',
            'price' => 15000,
            'is_active' => true,
            'is_out_of_stock' => false,
            'is_best_seller' => false,
            'sort_order' => 1,
        ]);

        // 2. Discounted item
        $discounted = MenuItem::query()->create([
            'restaurant_id' => $restaurantId,
            'outlet_id' => $world['outlet']->id,
            'category_id' => $world['item']->category_id,
            'station_id' => $world['item']->station_id,
            'name' => 'Menu Diskon',
            'price' => 20000,
            'discount_percent' => 10,
            'is_active' => true,
            'is_out_of_stock' => false,
            'is_best_seller' => false,
            'sort_order' => 5,
        ]);

        // 3. Best Seller item
        $bestSeller = MenuItem::query()->create([
            'restaurant_id' => $restaurantId,
            'outlet_id' => $world['outlet']->id,
            'category_id' => $world['item']->category_id,
            'station_id' => $world['item']->station_id,
            'name' => 'Menu Best Seller Kasir',
            'price' => 25000,
            'is_active' => true,
            'is_out_of_stock' => false,
            'is_best_seller' => true,
            'sort_order' => 10,
        ]);

        CashierMenuCatalog::forget($restaurantId);

        // POS payload check
        $posPayload = CashierMenuCatalog::posPayload($restaurantId);
        $posNames = array_column($posPayload, 'name');

        $this->assertSame('Menu Best Seller Kasir', $posNames[0]);
        $this->assertTrue($posPayload[0]['is_best_seller']);

        // Form select options check
        $options = CashierMenuCatalog::selectOptions($restaurantId);
        $optionKeys = array_keys($options);

        $this->assertSame($bestSeller->id, $optionKeys[0]);
        $this->assertStringContainsString('Best Seller', $options[$bestSeller->id]);
    }

    public function test_pos_payload_includes_variants_and_variant_select_options(): void
    {
        $world = $this->createGuestRestaurant();
        $restaurantId = $world['restaurant']->id;
        $item = $world['item'];

        $variant = \App\Models\MenuVariant::query()->create([
            'restaurant_id' => $restaurantId,
            'outlet_id' => $world['outlet']->id,
            'menu_item_id' => $item->id,
            'name' => 'Ukuran Besar',
            'price_delta' => 4000,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        CashierMenuCatalog::forget($restaurantId);

        $payload = CashierMenuCatalog::posPayload($restaurantId);
        $found = collect($payload)->firstWhere('id', $item->id);

        $this->assertNotNull($found);
        $this->assertTrue($found['has_variants']);
        $this->assertTrue($found['has_options']);
        $this->assertCount(1, $found['variants']);
        $this->assertSame('Ukuran Besar', $found['variants'][0]['name']);
        $this->assertSame(4000, $found['variants'][0]['price_delta']);
        $this->assertStringContainsString('Ukuran Besar (+Rp 4.000)', $found['variants'][0]['label']);

        $this->assertTrue(CashierMenuCatalog::hasVariants($item->id));
        $this->assertFalse(CashierMenuCatalog::hasVariants(999999));

        $variantOptions = CashierMenuCatalog::variantSelectOptions($item->id);
        $this->assertArrayHasKey($variant->id, $variantOptions);
        $this->assertStringContainsString('Ukuran Besar', $variantOptions[$variant->id]);
    }
}
