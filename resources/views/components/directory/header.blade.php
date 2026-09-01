@props([
    'home' => [],
])

@php
    $home = $home !== [] ? $home : \App\Models\PlatformSetting::homeViewData();
@endphp

<header class="directory-header sticky top-0 z-40 border-b border-border-subtle/80 bg-surface-base/85 backdrop-blur-md transition-colors duration-200">
    <div class="landing-container flex h-16 items-center justify-between gap-4">
        <a href="{{ route('home') }}" class="group inline-flex min-w-0 items-center gap-3 transition">
            @if (filled($home['logo_url'] ?? null))
                <img
                    src="{{ $home['logo_url'] }}"
                    alt="{{ $home['site_name'] }}"
                    class="h-9 w-auto max-w-[200px] object-contain transition duration-200 group-hover:scale-105"
                >
            @else
                <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-primary to-primary-dark text-white shadow-sm ring-1 ring-white/20 transition duration-200 group-hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M9.384 3.576a1 1 0 011.232 0l6.5 4.75A1 1 0 0117 9.25v6.5a1 1 0 01-1.384.926L10 15.382l-5.616 1.294A1 1 0 013 15.75v-6.5a1 1 0 01.384-.924l6.5-4.75z" clip-rule="evenodd" />
                    </svg>
                </span>
                <span class="truncate font-display text-lg font-bold tracking-tight text-body group-hover:text-primary transition-colors duration-200">{{ $home['site_name'] }}</span>
            @endif
        </a>

        <nav class="hidden items-center gap-2 md:flex">
            <a href="{{ route('home') }}" @class([
                'rounded-full px-4 py-2 text-sm font-semibold transition',
                'bg-primary/10 font-bold text-primary' => request()->routeIs('home'),
                'text-muted hover:bg-surface-muted hover:text-body' => ! request()->routeIs('home'),
            ])>Beranda</a>
            <a href="{{ route('page.about') }}" @class([
                'rounded-full px-4 py-2 text-sm font-semibold transition',
                'bg-primary/10 font-bold text-primary' => request()->routeIs('page.about'),
                'text-muted hover:bg-surface-muted hover:text-body' => ! request()->routeIs('page.about'),
            ])>Tentang</a>
            @auth
                @php
                    $authUser = auth()->user();
                    $panelUrl = $authUser instanceof \App\Models\User && $authUser->isSuperAdmin()
                        ? url('/founder')
                        : ($authUser?->restaurants()->first() ? url('/admin/'.$authUser->restaurants()->first()->slug) : url('/admin'));
                @endphp
                <a href="{{ $panelUrl }}" class="rounded-full bg-primary px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-primary-dark transition">Panel Admin</a>
            @else
                <a href="{{ route('register.restaurant') }}" class="rounded-full px-4 py-2 text-sm font-semibold text-muted hover:bg-surface-muted hover:text-body transition">{{ $home['cta_register_label'] }}</a>
            @endauth
        </nav>

        <div class="flex items-center gap-2">
            <x-customer.theme-toggle />
        </div>
    </div>
</header>
