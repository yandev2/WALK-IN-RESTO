<?php

namespace Tests\Feature;

use App\Models\Modifier;
use App\Models\ModifierGroup;
use App\Support\CashierOrderPreview;
use App\Support\CheckoutTotals;
use App\Support\CmsMedia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesGuestRestaurant;
use Tests\TestCase;

class CashierOrderPreviewTest extends TestCase
{
    use CreatesGuestRestaurant;
    use RefreshDatabase;

    public function test_estimate_includes_two_lines_and_modifiers(): void
    {
        $world = $this->createGuestRestaurant();
        $item = $world['item'];
        $other = $this->extraMenuItem($world, 'Kentang Goreng', 15000);

        $group = ModifierGroup::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'name' => 'Level pedas',
            'min_select' => 0,
            'max_select' => 1,
        ]);

        $modifier = Modifier::query()->create([
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
            'modifier_group_id' => $group->id,
            'name' => 'Pedas',
            'price' => 2000,
        ]);

        $item->modifierGroups()->attach($group->id, [
            'restaurant_id' => $world['restaurant']->id,
            'outlet_id' => $world['outlet']->id,
        ]);

        $lines = [
            [
                'menu_item_id' => $item->id,
                'qty' => 2,
                'modifier_ids' => [$modifier->id],
            ],
            [
                'menu_item_id' => $other->id,
                'qty' => 1,
                'modifier_ids' => [],
            ],
        ];

        $expectedSubtotal = ((int) $item->effectivePrice() + 2000) * 2 + 15000;

        $preview = CashierOrderPreview::estimateFromLines($lines, $world['outlet'], 'cash');

        $this->assertSame($expectedSubtotal, $preview['subtotal']);
        $this->assertSame(
            CheckoutTotals::forSubtotal($expectedSubtotal, $world['outlet'])['grand_before'],
            $preview['grand_payable'],
        );
    }

    public function test_empty_or_invalid_lines_are_ignored(): void
    {
        $world = $this->createGuestRestaurant();

        $preview = CashierOrderPreview::estimateFromLines([
            ['menu_item_id' => null, 'qty' => 2],
            ['menu_item_id' => 999999, 'qty' => 1],
            ['menu_item_id' => $world['item']->id, 'qty' => 0],
        ], $world['outlet']);

        $this->assertSame(0, $preview['subtotal']);
        $this->assertSame(0, $preview['grand_payable']);
    }

    public function test_qris_preview_flags_unique_code_note(): void
    {
        $world = $this->createGuestRestaurant();

        $preview = CashierOrderPreview::estimateFromLines([
            ['menu_item_id' => $world['item']->id, 'qty' => 1],
        ], $world['outlet'], 'qris');

        $this->assertTrue($preview['is_qris']);
        $this->assertSame('qris', $preview['payment_method']);
        $this->assertNull($preview['qris_image_url']);
    }

    public function test_qris_preview_includes_outlet_image_url(): void
    {
        $world = $this->createGuestRestaurant();
        $world['outlet']->update(['qris_image_path' => 'outlets/qris/cashier.jpg']);

        $preview = CashierOrderPreview::estimateFromLines([
            ['menu_item_id' => $world['item']->id, 'qty' => 1],
        ], $world['outlet'], 'qris');

        $this->assertSame(
            CmsMedia::url('outlets/qris/cashier.jpg'),
            $preview['qris_image_url'],
        );
    }

    public function test_cash_preview_does_not_include_qris_image_url(): void
    {
        $world = $this->createGuestRestaurant();
        $world['outlet']->update(['qris_image_path' => 'outlets/qris/cashier.jpg']);

        $preview = CashierOrderPreview::estimateFromLines([
            ['menu_item_id' => $world['item']->id, 'qty' => 1],
        ], $world['outlet'], 'cash');

        $this->assertNull($preview['qris_image_url']);
    }

    public function test_cash_preview_computes_change_and_shortfall(): void
    {
        $world = $this->createGuestRestaurant();

        $enough = CashierOrderPreview::estimateFromLines([
            ['menu_item_id' => $world['item']->id, 'qty' => 1],
        ], $world['outlet'], 'cash', '20.000');

        $this->assertSame(20000, $enough['cash_received']);
        $this->assertSame(20000 - $enough['grand_payable'], $enough['change_amount']);
        $this->assertFalse($enough['cash_short']);

        $short = CashierOrderPreview::estimateFromLines([
            ['menu_item_id' => $world['item']->id, 'qty' => 1],
        ], $world['outlet'], 'cash', 1000);

        $this->assertTrue($short['cash_short']);
        $this->assertLessThan(0, $short['change_amount']);
    }

    public function test_cash_tender_partial_uses_current_grand_payable(): void
    {
        $world = $this->createGuestRestaurant();
        $preview = CashierOrderPreview::estimateFromLines([
            ['menu_item_id' => $world['item']->id, 'qty' => 1],
        ], $world['outlet'], 'cash', 20000);

        $html = view('filament.pages.partials.cashier-order-totals', [
            'preview' => $preview,
            'cashReceived' => '20000',
        ])->render();

        $this->assertStringContainsString('total: '.(int) $preview['grand_payable'], $html);
        $this->assertStringNotContainsString('this.$el.dataset.total', $html);
        $this->assertStringContainsString('wire:key="cashier-cash-'.$preview['grand_payable'].'"', $html);
        $this->assertSame(20000 - $preview['grand_payable'], $preview['change_amount']);
    }

    public function test_service_and_pb1_match_outlet_settings(): void
    {
        $world = $this->createGuestRestaurant();

        $preview = CashierOrderPreview::estimateFromLines([
            ['menu_item_id' => $world['item']->id, 'qty' => 1],
        ], $world['outlet']);

        $this->assertSame(10.0, $preview['pb1_pct']);
        $this->assertSame(5.0, $preview['service_pct']);
        $this->assertGreaterThan($preview['subtotal'], $preview['grand_payable']);
    }
}
