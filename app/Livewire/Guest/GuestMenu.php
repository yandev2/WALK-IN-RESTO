<?php

namespace App\Livewire\Guest;

use App\Models\CmsBanner;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\VisitCartItem;
use App\Services\GuestCartService;
use App\Support\GuestContext;
use App\Support\MenuSearch;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest-order', ['title' => 'Menu'])]
class GuestMenu extends Component
{
    public ?int $pickingItemId = null;

    public ?int $variantId = null;

    public array $selectedModifierIds = [];

    public int $pickingQty = 1;

    public string $notes = '';

    public string $flash = '';

    public string $search = '';

    public ?int $categoryId = null;

    public function setCategory(?int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    public function openPicker(int $itemId): void
    {
        $visit = GuestContext::visit();
        if (! $visit) {
            return;
        }

        $item = $this->findMenuItem($visit->outlet_id, $itemId);

        if (! $item || $item->is_out_of_stock) {
            return;
        }

        $this->pickingItemId = $itemId;
        $this->variantId = $item->variants->where('is_active', true)->first()?->id;
        $this->selectedModifierIds = [];
        $this->pickingQty = 1;
        $this->notes = '';
    }

    public function setVariant(int $variantId): void
    {
        $this->variantId = $variantId;
    }

    public function toggleModifier(int $modifierId): void
    {
        $modifierId = (int) $modifierId;

        if (in_array($modifierId, $this->selectedModifierIds, true)) {
            $this->selectedModifierIds = array_values(array_filter(
                $this->selectedModifierIds,
                fn (int $id): bool => $id !== $modifierId,
            ));
        } else {
            $this->selectedModifierIds[] = $modifierId;
        }
    }

    public function incrementPickingQty(): void
    {
        if ($this->pickingQty < 99) {
            $this->pickingQty++;
        }
    }

    public function decrementPickingQty(): void
    {
        if ($this->pickingQty > 1) {
            $this->pickingQty--;
        }
    }

    public function cancelPicking(): void
    {
        $this->pickingItemId = null;
        $this->variantId = null;
        $this->selectedModifierIds = [];
        $this->pickingQty = 1;
        $this->notes = '';
    }

    public function confirmAdd(GuestCartService $cart): void
    {
        if (! $this->pickingItemId) {
            return;
        }

        $visit = GuestContext::visit();
        if (! $visit) {
            return;
        }

        try {
            $cart->add(
                $visit,
                $this->pickingItemId,
                $this->variantId,
                $this->pickingQty,
                $this->notes !== '' ? $this->notes : null,
                $this->selectedModifierIds,
            );
            $this->flash = 'Masuk keranjang.';
            $this->cancelPicking();
        } catch (ValidationException $e) {
            $this->flash = collect($e->errors())->flatten()->first() ?: 'Tidak bisa menambah.';
        }
    }

    public function plus(int $id, GuestCartService $cart): void
    {
        $visit = GuestContext::visit();
        if (! $visit) {
            return;
        }

        $item = VisitCartItem::query()->findOrFail($id);
        $cart->updateQty($visit, $item, $item->qty + 1);
    }

    public function minus(int $id, GuestCartService $cart): void
    {
        $visit = GuestContext::visit();
        if (! $visit) {
            return;
        }

        $item = VisitCartItem::query()->findOrFail($id);
        $cart->updateQty($visit, $item, $item->qty - 1);
    }

    public function remove(int $id, GuestCartService $cart): void
    {
        $visit = GuestContext::visit();
        if (! $visit) {
            return;
        }

        $item = VisitCartItem::query()->findOrFail($id);
        $cart->remove($visit, $item);
    }

    public function render()
    {
        $visit = GuestContext::visit();
        $outletId = $visit?->outlet_id;
        $restaurant = $visit?->outlet?->restaurant;

        $categories = $outletId
            ? MenuCategory::query()
                ->where('outlet_id', $outletId)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get()
            : collect();

        $normalizedSearch = MenuSearch::normalize($this->search);

        $menuItems = $outletId
            ? MenuItem::query()
                ->where('outlet_id', $outletId)
                ->where('is_active', true)
                ->with([
                    'category',
                    'photos',
                    'variants' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order'),
                    'modifierGroups.modifiers' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order'),
                ])
                ->when($normalizedSearch !== '', fn ($query) => $query->whereRaw(
                    "LOWER(REPLACE(name, ' ', '')) LIKE ?",
                    ['%'.$normalizedSearch.'%'],
                ))
                ->when($this->categoryId, fn ($query) => $query->where('category_id', $this->categoryId))
                ->orderByLandingPriority()
                ->orderBy('sort_order')
                ->get()
            : collect();

        $banners = $restaurant
            ? CmsBanner::query()
                ->where('restaurant_id', $restaurant->id)
                ->currentlyLive()
                ->orderBy('sort_order')
                ->get()
            : collect();

        $cartItems = $visit
            ? $visit->cartItems()->with(['menuItem.photos', 'variant', 'modifiers'])->get()
            : collect();

        $subtotal = (int) $cartItems->sum(fn (VisitCartItem $item) => $item->lineTotal());
        $cartCount = (int) $cartItems->sum('qty');

        $pickingItem = $this->pickingItemId && $visit
            ? ($menuItems->firstWhere('id', $this->pickingItemId)
                ?? $this->findMenuItem($visit->outlet_id, $this->pickingItemId))
            : null;

        $pickerTotal = $pickingItem
            ? $this->pickerLineTotal($pickingItem)
            : 0;

        $customer = null;
        if ($visit && filled($visit->customer_wa)) {
            $phone = \App\Support\WhatsAppNumber::normalize($visit->customer_wa);
            if (is_string($phone) && \App\Support\WhatsAppNumber::isValid($phone)) {
                $customer = \App\Models\Customer::withoutRestaurantScope()
                    ->where('restaurant_id', $visit->restaurant_id)
                    ->where('phone', $phone)
                    ->first();
            }
        }

        return view('livewire.guest.menu', [
            'visit' => $visit,
            'restaurant' => $restaurant,
            'categories' => $categories,
            'menuItems' => $menuItems,
            'banners' => $banners,
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'cartCount' => $cartCount,
            'pickingItem' => $pickingItem,
            'pickerTotal' => $pickerTotal,
            'customer' => $customer,
            'loyaltyEnabled' => (bool) ($restaurant?->loyaltySettings()['enabled'] ?? true),
        ]);
    }

    private function pickerLineTotal(MenuItem $item): int
    {
        $variant = $item->variants->firstWhere('id', $this->variantId);
        $delta = (int) ($variant?->price_delta ?? 0);
        $extras = (int) $item->modifierGroups
            ->flatMap(fn ($group) => $group->modifiers)
            ->whereIn('id', $this->selectedModifierIds)
            ->sum('price');

        return ($item->effectivePrice() + $delta + $extras) * $this->pickingQty;
    }

    private function findMenuItem(int $outletId, int $itemId): ?MenuItem
    {
        return MenuItem::query()
            ->where('outlet_id', $outletId)
            ->whereKey($itemId)
            ->with([
                'category',
                'photos',
                'variants' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order'),
                'modifierGroups.modifiers' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order'),
            ])
            ->first();
    }
}
