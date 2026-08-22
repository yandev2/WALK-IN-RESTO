<div class="mx-auto max-w-md px-5 pb-24 pt-4">
    <p class="customer-section-label">Ulasan</p>
    <h1 class="mt-1 font-display text-3xl font-bold text-body">Bagaimana pengalaman Anda?</h1>

    @if ($done && $visit?->review)
        <div class="customer-card mt-8 p-6 text-center">
            <p class="text-lg font-semibold text-body">Terima kasih, {{ $visit->review->displayName() }}!</p>
            <p class="mt-2 text-sm text-muted">Ulasan Anda sudah kami terima.</p>
            <div class="mt-4 flex justify-center gap-1" aria-label="Rating {{ $visit->review->rating }} dari 5">
                @foreach (range(1, 5) as $star)
                    <svg @class([
                        'h-6 w-6',
                        'text-primary' => $star <= $visit->review->rating,
                        'text-muted/30' => $star > $visit->review->rating,
                    ]) fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @endforeach
            </div>
            <p class="mt-4 text-left text-sm leading-relaxed text-muted">{{ $visit->review->comment }}</p>
            <a href="{{ route('guest.status') }}" class="mt-6 inline-block font-semibold text-primary">Kembali ke status</a>
        </div>
    @elseif ($done)
        <div class="customer-card mt-8 p-6 text-center">
            <p class="text-lg font-semibold text-body">Terima kasih!</p>
            <p class="mt-2 text-sm text-muted">Ulasan Anda sudah kami terima.</p>
            <a href="{{ route('guest.status') }}" class="mt-6 inline-block font-semibold text-primary">Kembali ke status</a>
        </div>
    @elseif (! $canSubmit)
        <div class="customer-card mt-8 p-6">
            <p class="font-semibold text-body">Ulasan belum bisa dikirim</p>
            <p class="mt-2 text-sm text-muted">Form ulasan terbuka setelah minimal satu pesanan berstatus selesai (<em>completed</em>).</p>
            <a href="{{ route('guest.status') }}" class="mt-4 inline-block text-sm font-semibold text-primary">Lihat status pesanan</a>
        </div>
    @else
        <p class="mt-3 text-sm text-muted">
            Ulasan atas nama <span class="font-semibold text-body">{{ filled($visit?->customer_name) ? $visit->customer_name : 'Tamu' }}</span>.
            Satu sesi meja hanya satu kali ulasan.
        </p>

        @if ($error)
            <p class="mt-4 rounded-2xl bg-primary/10 px-4 py-3 text-sm text-primary">{{ $error }}</p>
        @endif

        <form wire:submit="submit" class="customer-card mt-6 space-y-5 p-5">
            <div>
                <p class="text-sm font-semibold text-body">Rating <span class="text-primary">*</span></p>
                <div class="mt-3 flex gap-2">
                    @foreach (range(1, 5) as $star)
                        <button
                            type="button"
                            wire:click="setRating({{ $star }})"
                            @class([
                                'rounded-full p-2 transition',
                                'bg-primary/15 ring-2 ring-primary' => $rating >= $star,
                                'bg-surface-muted hover:bg-primary/10' => $rating < $star,
                            ])
                            aria-label="{{ $star }} bintang"
                        >
                            <svg @class([
                                'h-7 w-7',
                                'text-primary' => $rating >= $star,
                                'text-muted/40' => $rating < $star,
                            ]) fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </button>
                    @endforeach
                </div>
                @error('rating')
                    <p class="mt-2 text-xs text-primary">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="review-comment" class="text-sm font-semibold text-body">Komentar <span class="text-primary">*</span></label>
                <textarea
                    id="review-comment"
                    wire:model="comment"
                    rows="4"
                    class="mt-2 w-full rounded-2xl border border-border-subtle bg-surface-muted px-4 py-3 text-sm text-body"
                    placeholder="Ceritakan pengalaman makan Anda di restoran ini…"
                ></textarea>
                @error('comment')
                    <p class="mt-2 text-xs text-primary">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="landing-btn-glow w-full rounded-full bg-primary py-3 text-sm font-semibold text-white hover:bg-primary-dark">
                Kirim ulasan
            </button>
        </form>
    @endif

    @include('guest.partials.nav', ['cartCount' => $cartCount])
</div>
