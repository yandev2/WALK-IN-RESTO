<?php

namespace App\Livewire\Guest;

use App\Models\Customer;
use App\Models\VisitCartItem;
use App\Services\GuestCheckoutService;
use App\Support\CheckoutTotals;
use App\Support\GuestContext;
use App\Support\WhatsAppNumber;
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

    public bool $usePoints = false;

    public int $pointsToRedeem = 0;

    public function mount(): void
    {
        $this->idempotencyKey = (string) Str::uuid();
        $visit = GuestContext::visit();
        $this->sendReceipt = (bool) $visit?->outlet?->restaurant?->hasFonnteKey();

        $this->syncPoints();
    }

    public function updatedUsePoints(): void
    {
        $this->syncPoints();
    }

    public function updatedPointsToRedeem(): void
    {
        $this->syncPoints();
    }

    private function syncPoints(): void
    {
        $customer = $this->getCustomer();
        if (! $customer) {
            $this->usePoints = false;
            $this->pointsToRedeem = 0;

            return;
        }

        $visit = GuestContext::visit();
        $items = $visit ? $visit->cartItems()->get() : collect();
        $subtotal = $items->sum(fn (VisitCartItem $item) => $item->lineTotal());
        $restaurant = $visit?->outlet?->restaurant;
        $settings = $restaurant?->loyaltySettings() ?? [];

        if (! ($settings['enabled'] ?? true)) {
            $this->usePoints = false;
            $this->pointsToRedeem = 0;

            return;
        }

        $rate = (int) ($settings['point_redemption_rate'] ?? 1000);
        $minPoints = (int) ($settings['min_redeem_points'] ?? 10);
        $maxPct = (int) ($settings['max_redeem_percentage'] ?? 50);

        $maxDiscount = (int) floor($subtotal * ($maxPct / 100));
        $maxPointsForDiscount = (int) floor($maxDiscount / $rate);
        $maxRedeemable = min((int) $customer->points_balance, $maxPointsForDiscount);

        if ($maxRedeemable < $minPoints) {
            $this->usePoints = false;
            $this->pointsToRedeem = 0;

            return;
        }

        if ($this->usePoints) {
            if ($this->pointsToRedeem < $minPoints || $this->pointsToRedeem > $maxRedeemable) {
                $this->pointsToRedeem = $maxRedeemable;
            }
        } else {
            $this->pointsToRedeem = 0;
        }
    }

    public function getCustomer(): ?Customer
    {
        $visit = GuestContext::visit();
        if (! $visit || blank($visit->customer_wa)) {
            return null;
        }

        $phone = WhatsAppNumber::normalize($visit->customer_wa);
        if (! is_string($phone) || ! WhatsAppNumber::isValid($phone)) {
            return null;
        }

        return Customer::withoutRestaurantScope()
            ->where('restaurant_id', $visit->restaurant_id)
            ->where('phone', $phone)
            ->first();
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
                $this->usePoints ? $this->pointsToRedeem : 0,
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
        $restaurant = $outlet?->restaurant;
        $settings = $restaurant?->loyaltySettings() ?? [];

        $customer = $this->getCustomer();
        $rate = (int) ($settings['point_redemption_rate'] ?? 1000);
        $minPoints = (int) ($settings['min_redeem_points'] ?? 10);
        $maxPct = (int) ($settings['max_redeem_percentage'] ?? 50);

        $maxDiscount = (int) floor($subtotal * ($maxPct / 100));
        $maxPointsForDiscount = (int) floor($maxDiscount / $rate);
        $maxRedeemable = $customer ? min((int) $customer->points_balance, $maxPointsForDiscount) : 0;
        $canRedeem = $customer && ($settings['enabled'] ?? true) && $maxRedeemable >= $minPoints;

        $discountAmount = 0;
        if ($this->usePoints && $canRedeem) {
            $pointsUsed = min($this->pointsToRedeem, $maxRedeemable);
            $discountAmount = $pointsUsed * $rate;
        }

        $totals = CheckoutTotals::forSubtotal($subtotal, $outlet, $discountAmount);

        return view('livewire.guest.checkout', [
            'visit' => $visit,
            'items' => $items,
            'subtotal' => $totals['subtotal'],
            'discountAmount' => $totals['discount_amount'],
            'service' => $totals['service_amount'],
            'pb1' => $totals['pb1_amount'],
            'grand' => $totals['grand_before'],
            'hasFonnte' => $restaurant?->hasFonnteKey() ?? false,
            'cartCount' => $items->sum('qty'),
            'customer' => $customer,
            'canRedeem' => $canRedeem,
            'minPoints' => $minPoints,
            'maxRedeemable' => $maxRedeemable,
            'rate' => $rate,
            'loyaltyEnabled' => (bool) ($settings['enabled'] ?? true),
        ]);
    }
}
