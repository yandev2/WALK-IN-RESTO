@props([
    'home' => [],
])

@php
    $home = $home !== [] ? $home : \App\Models\PlatformSetting::homeViewData();
@endphp

<header class="directory-header sticky top-0 z-40 border-b border-border-subtle bg-surface-base/95 backdrop-blur">
    <div class="landing-container flex h-16 items-center justify-between gap-4">
        <a href="{{ route('home') }}" class="inline-flex min-w-0 items-center gap-2.5">
            @if (filled($home['logo_url'] ?? null))
                <img
                    src="{{ $home['logo_url'] }}"
                    alt="{{ $home['site_name'] }}"
                    class="h-9 w-auto max-w-[220px] object-contain"
                >
            @else
                <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary text-white shadow-[var(--card-shadow)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M9.384 3.576a1 1 0 011.232 0l6.5 4.75A1 1 0 0117 9.25v6.5a1 1 0 01-1.384.926L10 15.382l-5.616 1.294A1 1 0 013 15.75v-6.5a1 1 0 01.384-.924l6.5-4.75z" clip-rule="evenodd" />
                    </svg>
                </span>
                <span class="truncate font-display text-lg font-bold text-body">{{ $home['site_name'] }}</span>
            @endif
        </a>

        <nav class="hidden items-center gap-6 md:flex">
            <a href="{{ route('home') }}" class="text-sm font-semibold text-primary">Beranda</a>
            <a href="{{ route('register.restaurant') }}" class="text-sm font-semibold text-muted hover:text-body">{{ $home['cta_register_label'] }}</a>
        </nav>

        <x-customer.theme-toggle />
    </div>
</header>
