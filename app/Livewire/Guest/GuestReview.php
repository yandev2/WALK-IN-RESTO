<?php

namespace App\Livewire\Guest;

use App\Services\RestaurantReviewService;
use App\Support\GuestContext;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest-order', ['title' => 'Ulasan restoran'])]
class GuestReview extends Component
{
    public int $rating = 0;

    public string $comment = '';

    public string $error = '';

    public bool $done = false;

    public function mount(RestaurantReviewService $reviews): mixed
    {
        $visit = GuestContext::visit();

        if (! $visit) {
            return $this->redirect(route('guest.need-scan'), navigate: true);
        }

        $visit->loadMissing('review');

        if ($visit->review) {
            $this->done = true;

            return null;
        }

        if (! $reviews->portalOpen($visit)) {
            return $this->redirect(route('guest.status'), navigate: true);
        }

        return null;
    }

    public function setRating(int $rating): void
    {
        $this->rating = max(1, min(5, $rating));
    }

    public function submit(RestaurantReviewService $reviews): void
    {
        $visit = GuestContext::visit();

        if (! $visit) {
            $this->redirect(route('guest.need-scan'), navigate: true);

            return;
        }

        $this->error = '';

        $validated = $this->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['required', 'string', 'min:10', 'max:1000'],
        ], [
            'rating.required' => 'Pilih rating 1–5 bintang.',
            'rating.between' => 'Rating harus antara 1 sampai 5.',
            'comment.required' => 'Komentar wajib diisi.',
            'comment.min' => 'Komentar minimal 10 karakter.',
            'comment.max' => 'Komentar maksimal 1000 karakter.',
        ]);

        try {
            $reviews->submit($visit, (int) $validated['rating'], (string) $validated['comment']);
        } catch (ValidationException $e) {
            $this->error = collect($e->errors())->flatten()->first() ?: 'Tidak bisa mengirim ulasan.';

            return;
        }

        $this->done = true;
    }

    public function render(RestaurantReviewService $reviews)
    {
        $visit = GuestContext::visit();
        $visit?->loadMissing('review');

        return view('livewire.guest.review', [
            'visit' => $visit,
            'canSubmit' => $visit ? $reviews->canSubmit($visit) : false,
            'cartCount' => $visit?->cartItems()->sum('qty') ?? 0,
        ]);
    }
}
