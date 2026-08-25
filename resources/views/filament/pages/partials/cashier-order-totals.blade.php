@php
    /** @var array $preview */
    $subtotal = (int) ($preview['subtotal'] ?? 0);
    $isEmpty = $subtotal === 0;
    $isQris = (bool) ($preview['is_qris'] ?? false);
    $qrisImageUrl = $preview['qris_image_url'] ?? null;
    $paymentLabel = $isQris ? 'QRIS' : 'Tunai';
    $lineCount = (int) ($preview['line_count'] ?? 0);
    $totalQty = (int) ($preview['total_qty'] ?? 0);
    $grandPayable = (int) ($preview['grand_payable'] ?? 0);
    $cashReceived = $cashReceived ?? null;

    $formatPct = static function (float $pct): string {
        return rtrim(rtrim(number_format($pct, 2, ',', '.'), '0'), ',');
    };
@endphp

<div class="space-y-4">
    <div class="flex items-center justify-between gap-3">
        <div class="text-sm text-gray-500 dark:text-gray-400">
            @if ($isEmpty)
                Belum ada item — tambahkan menu untuk melihat perkiraan total.
            @else
                {{ $lineCount }} {{ $lineCount === 1 ? 'baris' : 'baris' }} · {{ $totalQty }} {{ $totalQty === 1 ? 'porsi' : 'porsi' }}
            @endif
        </div>
        <span @class([
            'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold',
            'bg-success-50 text-success-700 ring-1 ring-success-600/10 dark:bg-success-400/10 dark:text-success-400 dark:ring-success-400/20' => ! $isQris,
            'bg-info-50 text-info-700 ring-1 ring-info-600/10 dark:bg-info-400/10 dark:text-info-400 dark:ring-info-400/20' => $isQris,
        ])>
            {{ $paymentLabel }}
        </span>
    </div>

    @unless ($isEmpty)
        <dl class="space-y-2 text-sm">
            <div class="flex items-baseline justify-between gap-3">
                <dt class="text-gray-500 dark:text-gray-400">Subtotal</dt>
                <dd class="font-medium tabular-nums text-gray-950 dark:text-white">{{ \App\Support\CmsMedia::formatIdr($subtotal) }}</dd>
            </div>

            @if (($preview['service_amount'] ?? 0) > 0)
                <div class="flex items-baseline justify-between gap-3">
                    <dt class="text-gray-500 dark:text-gray-400">Service ({{ $formatPct($preview['service_pct']) }}%)</dt>
                    <dd class="font-medium tabular-nums text-gray-950 dark:text-white">{{ \App\Support\CmsMedia::formatIdr($preview['service_amount']) }}</dd>
                </div>
            @endif

            @if (($preview['pb1_amount'] ?? 0) > 0)
                <div class="flex items-baseline justify-between gap-3">
                    <dt class="text-gray-500 dark:text-gray-400">PB1 ({{ $formatPct($preview['pb1_pct']) }}%)</dt>
                    <dd class="font-medium tabular-nums text-gray-950 dark:text-white">{{ \App\Support\CmsMedia::formatIdr($preview['pb1_amount']) }}</dd>
                </div>
            @endif
        </dl>

        @if ($isQris)
            <div class="rounded-lg bg-gray-50 p-3 text-center ring-1 ring-gray-950/5 dark:bg-white/5 dark:ring-white/10">
                <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Scan QRIS outlet</p>

                @if ($qrisImageUrl)
                    <img
                        src="{{ $qrisImageUrl }}"
                        alt="QRIS outlet"
                        class="mx-auto block w-full max-w-[11rem] rounded-lg bg-white ring-1 ring-gray-950/5"
                    >
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        QRIS outlet belum diunggah. Atur di menu Outlet agar kasir bisa scan di sini.
                    </p>
                @endif
            </div>
        @endif

        <div class="flex items-baseline justify-between gap-3 rounded-lg bg-gray-50 px-3 py-3 ring-1 ring-gray-950/5 dark:bg-white/5 dark:ring-white/10">
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Total bayar</span>
            <span class="text-2xl font-bold tabular-nums tracking-tight text-gray-950 dark:text-white">
                {{ \App\Support\CmsMedia::formatIdr($grandPayable) }}
            </span>
        </div>

        @if ($isQris)
            <p class="rounded-lg bg-info-50 px-3 py-2 text-xs leading-5 text-info-700 dark:bg-info-400/10 dark:text-info-400">
                QRIS: kode unik 1–999 ditambahkan saat pembayaran diproses.
            </p>
        @else
            <div
                wire:key="cashier-cash-{{ $grandPayable }}"
                x-data="{
                    total: {{ (int) $grandPayable }},
                    raw: '{{ (string) ($cashReceived ?? '') }}',
                    parsed() {
                        const digits = String(this.raw).replace(/\D+/g, '');
                        return digits === '' ? null : Number(digits);
                    },
                    change() {
                        return this.parsed() === null ? 0 : this.parsed() - this.total;
                    },
                    short() {
                        return this.parsed() !== null && this.change() < 0;
                    },
                    format(amount) {
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.trunc(amount));
                    },
                    exact() {
                        this.raw = String(this.total);
                    },
                }"
                class="space-y-2 border-t border-gray-200 pt-4 dark:border-white/10"
            >
                <div class="flex items-center justify-between gap-2">
                    <label for="cashier-cash-received" class="text-sm font-medium text-gray-950 dark:text-white">Uang diterima</label>
                    <button
                        type="button"
                        class="text-sm font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400"
                        x-on:click="exact(); $wire.set('data.cash_received', raw === '' ? null : raw, false)"
                    >
                        Uang pas
                    </button>
                </div>

                <div class="flex overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-gray-950/10 dark:bg-white/5 dark:ring-white/20">
                    <span class="flex items-center px-3 text-sm text-gray-500 dark:text-gray-400">Rp</span>
                    <input
                        id="cashier-cash-received"
                        type="text"
                        inputmode="numeric"
                        autocomplete="off"
                        placeholder="0"
                        class="min-w-0 flex-1 border-none bg-transparent py-2 pe-3 text-sm text-gray-950 outline-none ring-0 placeholder:text-gray-400 dark:text-white"
                        x-model="raw"
                        x-on:input="$wire.set('data.cash_received', raw === '' ? null : raw, false)"
                    >
                </div>

                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Isi nominal uang dari tamu. Kembalian dihitung otomatis.
                </p>

                <p x-show="parsed() === null" class="text-sm text-gray-500 dark:text-gray-400">
                    Isi uang diterima untuk menghitung kembalian.
                </p>
                <div x-show="parsed() !== null && short()" class="flex items-baseline justify-between gap-3 text-sm">
                    <span class="text-danger-600 dark:text-danger-400">Kembalian</span>
                    <span class="font-bold tabular-nums text-danger-600 dark:text-danger-400" x-text="'Kurang ' + format(Math.abs(change()))"></span>
                </div>
                <div x-show="parsed() !== null && ! short()" class="flex items-baseline justify-between gap-3 text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Kembalian</span>
                    <span class="font-bold tabular-nums text-success-600 dark:text-success-400" x-text="format(change())"></span>
                </div>
            </div>
        @endif
    @endunless
</div>
