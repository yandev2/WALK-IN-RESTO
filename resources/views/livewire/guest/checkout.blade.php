<div
    class="mx-auto max-w-md px-5 pb-24 pt-4"
    x-data="{
        submitting: false,
        async submit() {
            if (this.submitting) return;
            this.submitting = true;
            let gps = {};
            if ($wire.method === 'cash') {
                gps = await new Promise((resolve) => {
                    if (! navigator.geolocation) {
                        resolve({ gps_status: 'denied' });
                        return;
                    }
                    navigator.geolocation.getCurrentPosition(
                        (pos) => resolve({
                            lat: pos.coords.latitude,
                            lng: pos.coords.longitude,
                            accuracy: pos.coords.accuracy,
                        }),
                        () => resolve({ gps_status: 'denied' }),
                        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                    );
                });
            }
            await $wire.place(gps);
            this.submitting = false;
        }
    }"
>
    <p class="customer-section-label">Checkout</p>
    <h1 class="mt-1 font-display text-3xl font-bold text-body">Bayar</h1>

    @if ($items->isEmpty())
        <p class="mt-8 text-muted">Keranjang kosong.</p>
        <a href="{{ route('guest.cart') }}" class="mt-3 inline-block font-semibold text-primary">Kembali ke keranjang</a>
    @else
        @if ($error)
            <p class="mt-4 rounded-2xl bg-primary/10 px-4 py-3 text-sm text-primary-dark">{{ $error }}</p>
        @endif

        <div class="customer-card mt-6 p-4">
            <p class="text-sm font-semibold text-muted">Ringkasan pesanan</p>
            <ul class="mt-3 space-y-2 text-sm">
                @foreach ($items as $item)
                    <li class="flex justify-between gap-3">
                        <span>{{ $item->qty }}× {{ $item->menuItem?->name }}@if ($item->variant) ({{ $item->variant->name }}) @endif</span>
                        <span>{{ \App\Support\CmsMedia::formatIdr($item->lineTotal()) }}</span>
                    </li>
                @endforeach
            </ul>

            <dl class="mt-4 space-y-1 border-t border-border-subtle pt-4 text-sm">
                <div class="flex justify-between"><dt>Subtotal</dt><dd>{{ \App\Support\CmsMedia::formatIdr($subtotal) }}</dd></div>
                @if (($discountAmount ?? 0) > 0)
                    <div class="flex justify-between text-emerald-600 font-semibold">
                        <dt>Diskon Poin ({{ $pointsToRedeem }} Poin)</dt>
                        <dd>-{{ \App\Support\CmsMedia::formatIdr($discountAmount) }}</dd>
                    </div>
                @endif
                <div class="flex justify-between"><dt>Service</dt><dd>{{ \App\Support\CmsMedia::formatIdr($service) }}</dd></div>
                <div class="flex justify-between"><dt>PB1</dt><dd>{{ \App\Support\CmsMedia::formatIdr($pb1) }}</dd></div>
                <div class="flex justify-between text-base font-bold text-primary"><dt>Total</dt><dd>{{ \App\Support\CmsMedia::formatIdr($grand) }}</dd></div>
            </dl>
        </div>

        @if ($loyaltyEnabled && $customer)
            <div class="mt-6 space-y-3">
                {{-- Member Card Berdasarkan Tier --}}
                <x-customer-member-card :customer="$customer" :compact="true" />

                {{-- Box Tukar Poin & Keterangan --}}
                <div class="customer-card p-4 border border-border-subtle/70 bg-surface-raised">
                    <div class="flex items-center justify-between gap-2 border-b border-border-subtle pb-3">
                        <div class="flex items-center gap-2">
                            <span class="flex h-7 w-7 items-center justify-center rounded-xl bg-amber-500/15 text-amber-600 font-bold text-xs">🪙</span>
                            <div>
                                <h4 class="text-xs font-bold text-body">Tukar Poin Belanja</h4>
                                <p class="text-[11px] text-muted">Gunakan saldo poin untuk potongan harga pesanan ini</p>
                            </div>
                        </div>
                    </div>

                    {{-- Keterangan Aturan Poin --}}
                    <div class="mt-3 grid grid-cols-2 gap-2 rounded-2xl bg-surface-muted p-2.5 text-xs">
                        <div>
                            <span class="text-[10px] uppercase tracking-wider text-muted font-bold block">Nilai 1 Poin</span>
                            <span class="font-extrabold text-body">Rp {{ number_format($rate, 0, ',', '.') }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase tracking-wider text-muted font-bold block">Minimal Penukaran</span>
                            <span class="font-extrabold text-body">{{ $minPoints }} Poin</span>
                        </div>
                    </div>

                    @if ($canRedeem)
                        {{-- Opsi Tukar Poin Jika Memenuhi Syarat --}}
                        <div class="mt-3 pt-2">
                            <label class="flex items-center gap-3 cursor-pointer select-none">
                                <input type="checkbox" wire:model.live="usePoints" class="rounded text-primary focus:ring-primary h-4 w-4">
                                <span class="text-sm font-semibold text-body">Tukarkan Poin untuk Potongan Belanja</span>
                            </label>

                            @if ($usePoints)
                                <div class="mt-3 pl-7 space-y-2.5">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <label class="text-xs text-muted font-medium">Jumlah Poin:</label>
                                        <input
                                            type="number"
                                            wire:model.live.debounce.300ms="pointsToRedeem"
                                            min="{{ $minPoints }}"
                                            max="{{ $maxRedeemable }}"
                                            class="w-24 rounded-xl border border-border-subtle bg-surface-base px-3 py-1.5 text-sm font-bold text-body text-center focus:border-primary focus:ring-primary"
                                        >
                                        <span class="text-xs text-muted">/ Maks. {{ $maxRedeemable }} Poin</span>
                                        <button
                                            type="button"
                                            wire:click="applyMaxPoints"
                                            class="rounded-lg bg-primary/10 hover:bg-primary/20 text-primary font-bold text-[11px] px-2.5 py-1 transition cursor-pointer"
                                        >
                                            Pakai Maksimal
                                        </button>
                                    </div>
                                    <p class="text-xs font-semibold text-emerald-600">
                                        🎉 Potongan diskon: -Rp {{ number_format($discountAmount, 0, ',', '.') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @else
                        {{-- Keterangan Jika Poin Belum Mencukupi Minimal Redeem --}}
                        <div class="mt-3 rounded-2xl border border-amber-500/20 bg-amber-500/10 p-3 text-xs text-amber-800 dark:text-amber-300">
                            @if ($customer->points_balance < $minPoints)
                                <p class="font-medium leading-relaxed">
                                    Saldo poin Anda (<strong>{{ $customer->points_balance }} Poin</strong>) belum mencapai batas minimal penukaran (<strong>{{ $minPoints }} Poin</strong>). Kumpulkan <strong>{{ max(1, $minPoints - $customer->points_balance) }} poin lagi</strong> untuk dapat menukarkan poin dengan diskon belanja!
                                </p>
                            @else
                                <p class="font-medium leading-relaxed">
                                    Subtotal pesanan Anda belum mencukupi untuk batas minimal penukaran <strong>{{ $minPoints }} Poin</strong> (maksimal diskon 50% dari subtotal). Tambah pesanan untuk menggunakan poin Anda!
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <p class="mt-2 text-xs text-muted">QRIS menambah 0–999 rupiah unik agar kasir mudah mencocokkan.</p>

        <fieldset class="mt-6 space-y-2">
            <legend class="text-sm font-bold text-body">Metode pembayaran</legend>
            <label @class(['flex cursor-pointer items-center gap-3 rounded-2xl border-2 px-4 py-3 transition', 'border-primary bg-primary/5' => $method === 'qris', 'border-transparent bg-surface-raised' => $method !== 'qris'])>
                <input type="radio" wire:model.live="method" value="qris" class="text-primary">
                <span class="text-sm">QRIS — transfer sesuai nominal unik</span>
            </label>
            <label @class(['flex cursor-pointer items-center gap-3 rounded-2xl border-2 px-4 py-3 transition', 'border-primary bg-primary/5' => $method === 'cash', 'border-transparent bg-surface-raised' => $method !== 'cash'])>
                <input type="radio" wire:model.live="method" value="cash" class="text-primary">
                <span class="text-sm">Tunai di kasir (lokasi HP dicek)</span>
            </label>
        </fieldset>

        @if ($hasFonnte)
            <label class="mt-4 flex items-center gap-3 rounded-2xl bg-surface-raised px-4 py-3 text-sm text-body">
                <input type="checkbox" wire:model="sendReceipt" class="text-primary">
                Kirim struk ke WhatsApp {{ $visit?->customer_wa }}
            </label>
        @endif

        <button type="button" @click="submit()" class="landing-btn-glow mt-6 w-full rounded-full bg-primary py-3.5 font-semibold text-white hover:bg-primary-dark" x-bind:disabled="submitting">
            <span x-show="!submitting">Kirim pesanan</span>
            <span x-cloak x-show="submitting">Memproses…</span>
        </button>
    @endif

    @include('guest.partials.nav', ['cartCount' => $cartCount, 'activeTab' => 'cart'])
</div>
