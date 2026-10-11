@props([
    'home' => [],
])

@php
    $registerUrl = route('register.restaurant');
    $ctaLabel = $home['cta_register_label'] ?? 'Daftarkan Restoran';
@endphp

<div class="b2b-callout-card group relative flex flex-col justify-between overflow-hidden rounded-[1.6rem] bg-gradient-to-br from-surface-raised via-surface-section to-surface-muted p-6 sm:p-8 ring-1 ring-[color:var(--border-subtle)] shadow-sm">
    <div class="relative z-10">
        <span class="inline-flex items-center gap-1.5 rounded-lg bg-primary/10 border border-primary/20 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-primary">
            Untuk Pelaku Bisnis Kuliner
        </span>

        <h3 class="mt-4 font-display text-xl sm:text-2xl font-bold text-body leading-snug">
            Sistem Walk-In Cerdas: QR Meja, KDS & Kasir POS
        </h3>

        <p class="mt-2.5 text-xs sm:text-sm leading-relaxed text-muted">
            Tingkatkan efisiensi dan omzet restoran Anda. Tamu duduk, scan stiker QR di meja, langsung pesan tanpa antre, dan pesanan otomatis masuk ke layar dapur.
        </p>

        <div class="mt-4 grid grid-cols-2 gap-2 text-xs font-medium text-body">
            <div class="flex items-center gap-2">
                <span class="h-1.5 w-1.5 rounded-lg bg-emerald-500"></span>
                <span>Self-Order QR Meja</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="h-1.5 w-1.5 rounded-lg bg-emerald-500"></span>
                <span>Kitchen Display (KDS)</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="h-1.5 w-1.5 rounded-lg bg-emerald-500"></span>
                <span>Kasir & Laporan Omzet</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="h-1.5 w-1.5 rounded-lg bg-emerald-500"></span>
                <span>CRM & Kepuasan Tamu</span>
            </div>
        </div>
    </div>

    <div class="relative z-10 mt-6 pt-2">
        <a
            href="{{ $registerUrl }}"
            class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-primary to-primary-dark px-5 py-2.5 text-xs font-bold text-white shadow-md hover:from-primary-dark hover:to-primary-dark transition"
        >
            <span>{{ $ctaLabel }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
            </svg>
        </a>
    </div>
</div>
