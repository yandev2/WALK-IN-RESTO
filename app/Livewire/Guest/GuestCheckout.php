<?php

namespace App\Livewire\Guest;

use App\Models\VisitCartItem;
use App\Services\GuestCheckoutService;
use App\Support\CheckoutTotals;
use App\Support\GuestContext;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest-order', ['title' => 'Bayar'])]
class GuestCheckout extends Component
{
    public string $method = 'qris';

    public bool $sendReceipt = false;

    public string $idempotencyKey = '';

    public string $error = '';

    public function mount(): void
    {
        $this->idempotencyKey = (string) Str::uuid();
        $visit = GuestContext::visit();
        $this->sendReceipt = (bool) $visit?->outlet?->restaurant?->hasFonnteKey();
    }

    public function place(GuestCheckoutService $checkout, array $gps = []): mixed
    {
        $visit = GuestContext::visit();

        if (! $visit) {
            return $this->redirect(route('guest.need-scan'), navigate: true);
        }

        $this->error = '';

        try {
            $order = $checkout->checkout(
                $visit,
                $this->method,
                $this->sendReceipt,
                $this->idempotencyKey,
                $gps,
            );
        } catch (ValidationException $e) {
            $this->error = collect($e->errors())->flatten()->first() ?: 'Tidak bisa checkout.';

            return null;
        }

        return $this->redirect(route('guest.pay', $order), navigate: true);
    }

    public function render()
    {
        $visit = GuestContext::visit();
        $items = $visit
            ? $visit->cartItems()->with(['menuItem', 'variant'])->get()
            : collect();

        $subtotal = $items->sum(fn (VisitCartItem $item) => $item->lineTotal());
        $outlet = $visit?->outlet;
        $totals = CheckoutTotals::forSubtotal($subtotal, $outlet);

        return view('livewire.guest.checkout', [
            'visit' => $visit,
            'items' => $items,
            'subtotal' => $totals['subtotal'],
            'service' => $totals['service_amount'],
            'pb1' => $totals['pb1_amount'],
            'grand' => $totals['grand_before'],
            'hasFonnte' => $outlet?->restaurant?->hasFonnteKey() ?? false,
            'cartCount' => $items->sum('qty'),
        ]);
    }
}
