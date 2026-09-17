<div
    class="mx-auto max-w-md px-5 pb-24 pt-4"
    @if ($order->status === 'awaiting_cashier' && ! $pausePoll)
        wire:poll.3s
    @endif
>
    <p class="customer-section-label">Pesanan #{{ $order->number }}</p>

    @if (in_array($order->status, ['paid', 'in_production', 'completed'], true))
        <div class="customer-card mt-4 p-6 text-center">
            <h1 class="text-2xl font-bold text-body">Kasir sudah terima.</h1>
            <p class="mt-2 text-muted">Dapur sedang memasak. Pantau status item di halaman status.</p>
            <a href="{{ route('guest.status') }}" class="landing-btn-glow mt-6 inline-flex rounded-full bg-primary px-5 py-3 font-semibold text-white hover:bg-primary-dark">Lihat status</a>
        </div>
    @elseif (in_array($order->status, ['rejected', 'cancelled'], true))
        <div class="customer-card mt-4 p-6">
            <h1 class="text-2xl font-bold text-body">Pesanan tidak dilanjutkan.</h1>
            <p class="mt-2 text-muted">{{ $payment?->reject_reason ?: 'Hubungi kasir jika ini kekeliruan.' }}</p>
            <a href="{{ route('guest.menu') }}" class="mt-6 inline-block font-semibold text-primary">Pesan lagi</a>
        </div>
    @else
        <h1 class="mt-2 font-display text-3xl font-bold text-body">Menunggu kasir.</h1>
        @if ($order->payment_method === 'qris')
            <p class="mt-2 text-sm text-muted">Transfer tepat sebesar nominal di bawah, lalu unggah bukti jika ada.</p>
            <div class="customer-card mt-6 p-5 text-center">
                @if ($src = \App\Support\CmsMedia::url($payment?->qris_image_path_snapshot))
                    <img src="{{ $src }}" alt="QRIS" class="mx-auto w-56 rounded-2xl">
                @else
                    <p class="text-sm text-muted">QRIS resto belum diunggah. Tunjukkan nominal ini ke kasir.</p>
                @endif
                <p class="mt-6 text-sm text-muted">Bayar tepat</p>
                <p class="text-4xl font-bold text-primary">{{ \App\Support\CmsMedia::formatIdr($order->grand_payable) }}</p>
                <button
                    type="button"
                    class="mx-auto mt-3 block text-sm font-semibold text-primary"
                    x-data
                    @click="navigator.clipboard.writeText('{{ $order->grand_payable }}')"
                >Salin nominal</button>
            </div>

            <div class="customer-card mt-6 p-4">
                <p class="font-semibold text-body">Bukti transfer</p>
                <p class="mt-1 text-sm text-muted">Opsional. Screenshot mutasi atau foto struk QRIS. Boleh diganti.</p>

                @if ($proofMessage)
                    <p class="mt-3 rounded-2xl bg-moss/10 px-3 py-2 text-sm text-moss">{{ $proofMessage }}</p>
                @endif
                @error('proof')
                    <p class="mt-3 text-sm text-primary-dark">{{ $message }}</p>
                @enderror

                @if (filled($payment?->proof_image_path))
                    @php
                        $proofUrl = route('payments.proof.show', ['order' => $order->public_id, 'payment' => $payment->public_id]);
                    @endphp
                    <img src="{{ $proofUrl }}" alt="Bukti transfer" class="mt-4 w-full rounded-2xl object-cover">
                    <button type="button" wire:click="removeProof" class="mt-3 text-sm font-semibold text-primary">Hapus foto</button>
                @endif

                <label class="mt-4 block">
                    <span class="inline-flex w-full cursor-pointer items-center justify-center rounded-full bg-primary py-3 text-sm font-semibold text-white hover:bg-primary-dark">
                        {{ filled($payment?->proof_image_path) ? 'Ganti foto' : 'Unggah bukti' }}
                    </span>
                    <input
                        type="file"
                        accept="image/*"
                        wire:model="proof"
                        x-on:change="$wire.set('pausePoll', true)"
                        class="sr-only"
                    >
                </label>
                <p wire:loading wire:target="proof" class="mt-2 text-center text-xs text-muted">Mengunggah…</p>
            </div>
        @else
            <div class="customer-card mt-6 p-6 text-center">
                <p class="text-sm text-muted">Serahkan uang tunai ke kasir. Total:</p>
                <p class="mt-4 text-4xl font-bold text-primary">{{ \App\Support\CmsMedia::formatIdr($order->grand_payable) }}</p>
            </div>
        @endif
        <p class="mt-6 text-center text-xs text-muted">Halaman ini diperbarui otomatis.</p>
    @endif

    @include('guest.partials.loyalty-earned-modal', ['loyaltyPoint' => $unclaimedLoyaltyPoint ?? null])

    @include('guest.partials.nav', ['cartCount' => $cartCount, 'activeTab' => 'cart'])
</div>
