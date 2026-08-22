<?php

namespace App\Services;

use App\Models\MenuItem;
use App\Models\MenuVariant;
use App\Models\Visit;
use App\Models\VisitCartItem;
use Illuminate\Validation\ValidationException;

class GuestCartService
{
    public function __construct(private MenuModifierService $modifiers) {}

    /**
     * @param  list<int|string>  $modifierIds
     */
    public function add(
        Visit $visit,
        int $menuItemId,
        ?int $variantId = null,
        int $qty = 1,
        ?string $notes = null,
        array $modifierIds = [],
    ): VisitCartItem {
        if ($qty < 1) {
            throw ValidationException::withMessages(['qty' => 'Jumlah tidak valid.']);
        }

        $item = MenuItem::query()
            ->where('outlet_id', $visit->outlet_id)
            ->whereKey($menuItemId)
            ->firstOrFail();

        $this->assertPurchasable($item);

        $variant = null;

        if ($item->variants()->where('is_active', true)->exists()) {
            if (! $variantId) {
                throw ValidationException::withMessages(['variant' => 'Pilih varian dulu.']);
            }

            $variant = MenuVariant::query()
                ->where('menu_item_id', $item->id)
                ->where('is_active', true)
                ->whereKey($variantId)
                ->firstOrFail();
        }

        $selected = $this->modifiers->resolve($item, $modifierIds);
        $wanted = $selected->pluck('id')->map(fn ($id): int => (int) $id)->sort()->values();

        $existing = VisitCartItem::query()
            ->where('visit_id', $visit->id)
            ->where('menu_item_id', $item->id)
            ->where('menu_variant_id', $variant?->id)
            ->where('notes', $notes)
            ->with('modifiers')
            ->get()
            ->first(function (VisitCartItem $row) use ($wanted): bool {
                $have = $row->modifiers->pluck('id')->map(fn ($id): int => (int) $id)->sort()->values();

                return $have->all() === $wanted->all();
            });

        if ($existing) {
            $existing->increment('qty', $qty);

            return $existing->refresh()->load(['menuItem', 'variant', 'modifiers']);
        }

        $cartItem = VisitCartItem::query()->create([
            'restaurant_id' => $visit->restaurant_id,
            'outlet_id' => $visit->outlet_id,
            'visit_id' => $visit->id,
            'menu_item_id' => $item->id,
            'menu_variant_id' => $variant?->id,
            'qty' => $qty,
            'notes' => $notes,
        ]);

        if ($selected->isNotEmpty()) {
            $cartItem->modifiers()->attach(
                $selected->mapWithKeys(fn ($modifier): array => [
                    $modifier->id => [
                        'restaurant_id' => $visit->restaurant_id,
                        'outlet_id' => $visit->outlet_id,
                    ],
                ])->all(),
            );
        }

        return $cartItem->load(['menuItem', 'variant', 'modifiers']);
    }

    public function updateQty(Visit $visit, VisitCartItem $cartItem, int $qty): void
    {
        abort_unless($cartItem->visit_id === $visit->id, 403);

        if ($qty < 1) {
            $cartItem->delete();

            return;
        }

        $cartItem->update(['qty' => $qty]);
    }

    public function remove(Visit $visit, VisitCartItem $cartItem): void
    {
        abort_unless($cartItem->visit_id === $visit->id, 403);
        $cartItem->delete();
    }

    private function assertPurchasable(MenuItem $item): void
    {
        if (! $item->is_active || $item->is_out_of_stock) {
            throw ValidationException::withMessages(['item' => 'Menu ini sedang tidak tersedia.']);
        }
    }
}
