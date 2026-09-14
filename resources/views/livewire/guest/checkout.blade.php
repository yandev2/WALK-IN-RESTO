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

        @if ($canRedeem)
            <div class="customer-card mt-4 p-4 border border-amber-500/20 bg-gradient-to-br from-amber-500/5 to-primary/5">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-amber-500/15 text-amber-600 font-bold text-xs">✨</span>
                        <div>
                            <p class="text-xs font-bold text-body">Member: {{ $customer->name ?: 'Pelanggan Setia' }}</p>
                            <p class="text-[11px] text-muted">Saldo: <strong class="text-amber-600 font-bold">{{ $customer->points_balance }} Poin</strong> (Bernilai {{ \App\Support\CmsMedia::formatIdr($customer->points_balance * $rate) }})</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wide bg-amber-500/15 text-amber-700 border border-amber-500/20">
                        Tier {{ ucfirst($customer->tier) }}
                    </span>
                </div>

                <div class="mt-3 pt-3 border-t border-border-subtle">
                    <label class="flex items-center gap-3 cursor-pointer select-none">
                        <input type="checkbox" wire:model.live="usePoints" class="rounded text-primary focus:ring-primary h-4 w-4">
                        <span class="text-sm font-semibold text-body">Tukarkan Poin untuk Potongan Belanja</span>
                    </label>

                    @if ($usePoints)
                        <div class="mt-3 pl-7 space-y-2">
                            <div class="flex items-center gap-3">
                                <label class="text-xs text-muted">Jumlah Poin:</label>
                                <input
                                    type="number"
                                    wire:model.live.debounce.300ms="pointsToRedeem"
                                    min="{{ $minPoints }}"
                                    max="{{ $maxRedeemable }}"
                                    class="w-24 rounded-xl border border-border-subtle px-3 py-1.5 text-sm font-bold text-body text-center focus:border-primary focus:ring-primary"
                                >
                                <span class="text-xs text-muted">/ Maks. {{ $maxRedeemable }} Poin</span>
                            </div>
                            <p class="text-xs font-semibold text-emerald-600">
                                🎉 Hemat: -{{ \App\Support\CmsMedia::formatIdr($discountAmount) }}
                            </p>
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
