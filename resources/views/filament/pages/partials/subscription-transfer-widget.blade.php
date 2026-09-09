@php
    $bankName = $bankName ?? config('subscription.bank_name');
    $bankAccount = $bankAccount ?? config('subscription.bank_account');
    $bankHolder = $bankHolder ?? config('subscription.bank_holder');
    $contactEmail = $contactEmail ?? config('subscription.contact_email');
    $qrUrl = $qrUrl ?? 'https://picsum.photos/seed/founder-billing-qr/200/200';
@endphp

<div class="transfer-widget" x-data="{ copied: false, qrModalOpen: false }">
    <style>
        .transfer-widget {
            --tw-bg: #fff7ed;
            --tw-line: #fdba74;
            --tw-text: #1c1917;
            --tw-muted: #78716c;
            --tw-accent: #c2410c;
            --tw-btn: #fff;
            margin-top: 0.15rem;
        }

        .dark .transfer-widget {
            --tw-bg: color-mix(in srgb, #c2410c 12%, #1f2937);
            --tw-line: #9a3412;
            --tw-text: #fafaf9;
            --tw-muted: #d6d3d1;
            --tw-accent: #fdba74;
            --tw-btn: #111827;
        }

        .transfer-widget__box {
            display: flex;
            align-items: stretch;
            gap: 0.95rem;
            padding: 0.9rem;
            border: 1px dashed var(--tw-line);
            border-radius: 6px;
            background: var(--tw-bg);
        }

        .transfer-widget__qr {
            width: 5.5rem;
            height: 5.5rem;
            flex-shrink: 0;
            overflow: hidden;
            border: 1px solid var(--tw-line);
            border-radius: 4px;
            background: #fff;
            position: relative;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .transfer-widget__qr:hover {
            transform: scale(1.04);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .transfer-widget__qr-hint {
            position: absolute;
            inset-inline: 0;
            bottom: 0;
            padding: 2px 0;
            background: rgba(0, 0, 0, 0.65);
            color: #ffffff;
            font-size: 0.58rem;
            font-weight: 600;
            text-align: center;
            line-height: 1;
            letter-spacing: -0.01em;
            transition: opacity 0.2s ease;
        }

        .transfer-widget__qr img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .transfer-widget__body {
            min-width: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 0.28rem;
        }

        .transfer-widget__kicker {
            margin: 0;
            font-size: 0.72rem;
            color: var(--tw-muted);
        }

        .transfer-widget__holder {
            margin: 0;
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--tw-accent);
            line-height: 1.25;
        }

        .transfer-widget__row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.4rem;
        }

        .transfer-widget__account {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            color: var(--tw-accent);
            font-variant-numeric: tabular-nums;
        }

        .transfer-widget__copy {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.2rem 0.45rem;
            border: 1px solid var(--tw-line);
            border-radius: 4px;
            background: var(--tw-btn);
            color: var(--tw-text);
            font-size: 0.7rem;
            font-weight: 600;
            line-height: 1;
            cursor: pointer;
        }

        .transfer-widget__copy svg {
            width: 0.85rem;
            height: 0.85rem;
        }

        .transfer-widget__copy.is-copied {
            border-color: #047857;
            color: #047857;
        }

        .transfer-widget__contact {
            margin: 0.15rem 0 0;
            font-size: 0.75rem;
            color: var(--tw-muted);
        }
    </style>

    <div class="transfer-widget__box">
        <div
            class="transfer-widget__qr"
            @click="qrModalOpen = true"
            title="Klik untuk memperbesar QR code"
            role="button"
            tabindex="0"
            @keydown.enter="qrModalOpen = true"
            @keydown.space.prevent="qrModalOpen = true"
        >
            <img src="{{ $qrUrl }}" alt="QR pembayaran">
            <span class="transfer-widget__qr-hint">Perbesar</span>
        </div>

        <div class="transfer-widget__body">
            <p class="transfer-widget__kicker">Transfer ke {{ $bankName }}</p>
            <p class="transfer-widget__holder">{{ $bankHolder }}</p>
            <div class="transfer-widget__row">
                <p class="transfer-widget__account">{{ $bankAccount }}</p>
                <button
                    type="button"
                    class="transfer-widget__copy"
                    :class="{ 'is-copied': copied }"
                    @click="
                        navigator.clipboard.writeText(@js((string) $bankAccount)).then(() => {
                            copied = true;
                            setTimeout(() => copied = false, 1600);
                        })
                    "
                >
                    <x-filament::icon icon="heroicon-o-clipboard-document" />
                    <span x-text="copied ? 'Disalin' : 'Salin'"></span>
                </button>
            </div>
            @if (filled($contactEmail))
                <p class="transfer-widget__contact">{{ $contactEmail }}</p>
            @endif
        </div>
    </div>

    {{-- QR Lightbox Modal --}}
    <template x-teleport="body">
        <div
            x-show="qrModalOpen"
            x-cloak
            class="fixed inset-0 z-[70] flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
            role="dialog"
            aria-modal="true"
            @keydown.escape.window="qrModalOpen = false"
        >
            {{-- Backdrop --}}
            <div
                x-show="qrModalOpen"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-950/75 backdrop-blur-sm"
                @click="qrModalOpen = false"
            ></div>

            {{-- Dialog Content --}}
            <div
                x-show="qrModalOpen"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative z-10 w-full max-w-sm rounded-2xl bg-white dark:bg-gray-900 p-6 shadow-2xl border border-gray-200 dark:border-gray-800 text-center"
                @click.stop
            >
                {{-- Close Button --}}
                <button
                    type="button"
                    class="absolute top-3.5 right-3.5 inline-flex items-center justify-center rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-300 transition-colors"
                    @click="qrModalOpen = false"
                    aria-label="Tutup"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                {{-- Title & Kicker --}}
                <div class="mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">QR Code Pembayaran</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Scan melalui BCA Mobile, Livin, BRImo, GoPay, OVO, atau aplikasi pembayaran lainnya</p>
                </div>

                {{-- QR Image Container --}}
                <div class="mx-auto w-64 h-64 bg-white p-3 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-center">
                    <img
                        src="{{ $qrUrl }}"
                        alt="QR Code Pembayaran Besar"
                        class="w-full h-full object-contain"
                    />
                </div>

                {{-- Account Details Card --}}
                <div class="mt-4 rounded-xl bg-orange-50 dark:bg-orange-950/40 border border-orange-200 dark:border-orange-900/60 p-3.5 text-left text-xs space-y-1">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Bank / Penerima:</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $bankName }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Atas Nama:</span>
                        <span class="font-semibold text-orange-600 dark:text-orange-400">{{ $bankHolder }}</span>
                    </div>
                    <div class="flex items-center justify-between pt-1 border-t border-orange-200/60 dark:border-orange-900/60">
                        <span class="text-gray-500 dark:text-gray-400">No. Rekening:</span>
                        <div class="flex items-center gap-1.5">
                            <span class="font-mono font-bold text-sm text-gray-900 dark:text-white">{{ $bankAccount }}</span>
                            <button
                                type="button"
                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-white dark:bg-gray-800 border border-orange-300 dark:border-orange-800 text-[11px] font-medium text-orange-700 dark:text-orange-300 hover:bg-orange-50 dark:hover:bg-gray-700 transition-colors"
                                :class="{ '!border-emerald-600 !text-emerald-600': copied }"
                                @click="
                                    navigator.clipboard.writeText(@js((string) $bankAccount)).then(() => {
                                        copied = true;
                                        setTimeout(() => copied = false, 1600);
                                    })
                                "
                            >
                                <span x-text="copied ? 'Disalin' : 'Salin'"></span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Footer Button --}}
                <div class="mt-5">
                    <button
                        type="button"
                        class="w-full py-2.5 px-4 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold text-sm transition-colors"
                        @click="qrModalOpen = false"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
