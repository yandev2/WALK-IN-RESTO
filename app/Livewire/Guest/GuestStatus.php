<?php

namespace App\Livewire\Guest;

use App\Services\RestaurantReviewService;
use App\Support\GuestContext;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest-order', ['title' => 'Status meja'])]
class GuestStatus extends Component
{
    public function render(RestaurantReviewService $reviews)
    {
        $visit = GuestContext::visit();
        $visit?->loadMissing('review');

        $orders = $visit
            ? $visit->orders()->with(['items.modifiers', 'payments'])->latest('id')->get()
            : collect();

        $portalOpen = $visit ? $reviews->portalOpen($visit) : false;
        $canSubmitReview = $visit ? $reviews->canSubmit($visit) : false;

        return view('livewire.guest.status', [
            'visit' => $visit,
            'orders' => $orders,
            'cartCount' => $visit?->cartItems()->sum('qty') ?? 0,
            'portalOpen' => $portalOpen,
            'canSubmitReview' => $canSubmitReview,
            'hasReview' => $visit?->review !== null,
        ]);
    }
}
