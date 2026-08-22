<?php

namespace App\Livewire\Guest;

use App\Models\VisitCartItem;
use App\Services\GuestCartService;
use App\Support\GuestContext;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest-order', ['title' => 'Keranjang'])]
class GuestCart extends Component
{
    public function plus(int $id, GuestCartService $cart): void
    {
        $visit = GuestContext::visit();
        $item = VisitCartItem::query()->findOrFail($id);
        $cart->updateQty($visit, $item, $item->qty + 1);
    }

    public function minus(int $id, GuestCartService $cart): void
    {
        $visit = GuestContext::visit();
        $item = VisitCartItem::query()->findOrFail($id);
        $cart->updateQty($visit, $item, $item->qty - 1);
    }

    public function remove(int $id, GuestCartService $cart): void
    {
        $visit = GuestContext::visit();
        $item = VisitCartItem::query()->findOrFail($id);
        $cart->remove($visit, $item);
    }

    public function render()
    {
        $visit = GuestContext::visit();
        $items = $visit
            ? $visit->cartItems()->with(['menuItem.photos', 'variant', 'modifiers'])->get()
            : collect();

        return view('livewire.guest.cart', [
            'visit' => $visit,
            'items' => $items,
            'subtotal' => $items->sum(fn (VisitCartItem $item) => $item->lineTotal()),
            'cartCount' => $items->sum('qty'),
        ]);
    }
}
