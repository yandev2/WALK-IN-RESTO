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
}
