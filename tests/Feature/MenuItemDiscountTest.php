<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use App\Models\VisitCartItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class MenuItemDiscountTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_effective_price_applies_percentage_discount(): void
    {
        $world = $this->createGuestRestaurant();
        $item = MenuItem::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'category_id' => $world['item']->category_id,
            'station_id' => $world['item']->station_id,
            'name' => 'Diskon Test',
            'price' => 50000,
            'discount_percent' => 20,
            'sort_order' => 1,
        ]);

        $this->assertTrue($item->hasDiscount());
        $this->assertSame(40000, $item->effectivePrice());
    }

    public function test_effective_price_returns_original_when_no_discount(): void
    {
        $world = $this->createGuestRestaurant();
        $item = MenuItem::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'category_id' => $world['item']->category_id,
            'station_id' => $world['item']->station_id,
            'name' => 'Normal',
            'price' => 35000,
            'sort_order' => 1,
        ]);

        $this->assertFalse($item->hasDiscount());
        $this->assertSame(35000, $item->effectivePrice());
    }

    public function test_cart_line_total_uses_discounted_price(): void
    {
        $world = $this->createGuestRestaurant();
        $item = MenuItem::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'category_id' => $world['item']->category_id,
            'station_id' => $world['item']->station_id,
            'name' => 'Diskon Cart',
            'price' => 28000,
            'discount_percent' => 20,
            'sort_order' => 1,
        ]);

        $cartItem = new VisitCartItem(['qty' => 2]);
        $cartItem->setRelation('menuItem', $item);
        $cartItem->setRelation('modifiers', Collection::make());

        $this->assertSame(44800, $cartItem->lineTotal());
    }

    public function test_landing_shows_discount_label_and_strikethrough_price(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $response = $this->get(route('landing.show', 'resto-demo'));

        $response->assertOk();
        $response->assertSee('-20%', false);
        $response->assertSee('Rp 28.000', false);
        $response->assertSee('Rp 22.400', false);
    }
}
