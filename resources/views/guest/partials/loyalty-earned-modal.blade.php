@props(['loyaltyPoint'])

@if ($loyaltyPoint)
    <div
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/65 backdrop-blur-xs transition-opacity duration-200"
        x-data
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        role="dialog"
        aria-modal="true"
    >
        <div
            class="relative w-full max-w-sm rounded-3xl bg-surface-raised dark:bg-zinc-900 p-6 text-center shadow-2xl border border-amber-500/30 overflow-hidden"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
        >
            {{-- Decorative Confetti/Ambient Elements --}}
            <div class="pointer-events-none absolute -top-12 -right-12 h-32 w-32 rounded-full bg-amber-400/20 blur-xl"></div>
            <div class="pointer-events-none absolute -bottom-12 -left-12 h-32 w-32 rounded-full bg-purple-500/20 blur-xl"></div>

            {{-- Icon Celebration --}}
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-tr from-amber-500 to-yellow-300 text-amber-950 shadow-lg shadow-amber-500/30">
                <span class="text-3xl">🎉</span>
            </div>

            {{-- Header --}}
            <h3 class="mt-4 font-display text-xl font-black text-body dark:text-white">
                Selamat! Kamu Mendapatkan Poin!
            </h3>

            {{-- Body Explanation --}}
            <p class="mt-2 text-xs text-muted dark:text-zinc-300 leading-relaxed">
                Kamu berhasil mendapatkan
                <span class="inline-block rounded-md bg-amber-500/15 px-1.5 py-0.5 text-xs font-black text-amber-600 dark:text-amber-400 border border-amber-500/20">
                    +{{ number_format($loyaltyPoint->points, 0, ',', '.') }} Poin
                </span>
                dari total belanja
                <strong class="font-bold text-body dark:text-white">
                    {{ \App\Support\CmsMedia::formatIdr($loyaltyPoint->order?->grand_payable ?? 0) }}
                </strong>
                pada pesanan <span class="font-bold">#{{ $loyaltyPoint->order?->number }}</span>.
            </p>

            {{-- New Balance Card --}}
            <div class="mt-5 rounded-2xl border border-border-subtle bg-surface-muted dark:bg-zinc-800/80 p-3.5">
                <p class="text-[10px] font-bold uppercase tracking-wider text-muted dark:text-zinc-400">
                    Total Saldo Poin Anda Sekarang
                </p>
                <p class="mt-1 text-2xl font-black text-amber-600 dark:text-amber-400 flex items-center justify-center gap-1.5">
                    <span>🪙</span>
                    <span>{{ number_format($loyaltyPoint->balance_after, 0, ',', '.') }}</span>
                    <span class="text-xs font-bold text-muted dark:text-zinc-400">Poin</span>
                </p>
                @if ($loyaltyPoint->customer)
                    <div class="mt-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-primary/10 text-primary">
                            Tier {{ ucfirst($loyaltyPoint->customer->tier) }}
                        </span>
                    </div>
                @endif
            </div>

            {{-- Dismiss Button --}}
            <button
                type="button"
                wire:click="dismissLoyaltyAlert({{ $loyaltyPoint->order_id }})"
                class="landing-btn-glow mt-5 w-full rounded-full bg-primary hover:bg-primary-dark py-3 text-xs font-bold text-white shadow-md shadow-primary/25 transition active:scale-95 cursor-pointer"
            >
                Mantap, Terima Kasih!
            </button>
        </div>
    </div>
@endif
