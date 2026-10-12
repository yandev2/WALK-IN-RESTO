@props([
    'home' => [],
])

@php
    $home = $home !== [] ? $home : \App\Models\PlatformSetting::homeViewData();
@endphp

<header role="banner" x-data="{ mobileMenuOpen: false }" class="directory-header sticky top-0 z-40 border-b border-border-subtle/80 bg-surface-base/85 backdrop-blur-md transition-colors duration-200">
    <div class="landing-container flex h-16 items-center justify-between gap-2.5 sm:gap-4">
        <a href="{{ route('home') }}" class="inline-flex min-w-0 items-center gap-2 sm:gap-2.5" aria-label="Beranda {{ $home['site_name'] }}">
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

        <nav aria-label="Navigasi Utama" class="hidden items-center gap-2 md:flex">
            <a href="{{ route('home') }}" @class([
                'rounded-lg px-4 py-2 text-sm font-semibold transition',
                'bg-primary/10 font-bold text-primary' => request()->routeIs('home'),
                'text-muted hover:bg-surface-muted hover:text-body' => ! request()->routeIs('home'),
            ])>Beranda</a>
            <a href="{{ route('directory.menus') }}" @class([
                'rounded-lg px-4 py-2 text-sm font-semibold transition',
                'bg-primary/10 font-bold text-primary' => request()->routeIs('directory.menus'),
                'text-muted hover:bg-surface-muted hover:text-body' => ! request()->routeIs('directory.menus'),
            ])>Menu</a>
            <a href="{{ route('page.about') }}" @class([
                'rounded-lg px-4 py-2 text-sm font-semibold transition',
                'bg-primary/10 font-bold text-primary' => request()->routeIs('page.about'),
                'text-muted hover:bg-surface-muted hover:text-body' => ! request()->routeIs('page.about'),
            ])>Tentang</a>
            <a href="{{ route('page.terms') }}" @class([
                'rounded-lg px-4 py-2 text-sm font-semibold transition',
                'bg-primary/10 font-bold text-primary' => request()->routeIs('page.terms'),
                'text-muted hover:bg-surface-muted hover:text-body' => ! request()->routeIs('page.terms'),
            ])>Syarat & Ketentuan</a>
            <a href="{{ route('blog.index', ['locale' => 'id']) }}" @class([
                'rounded-lg px-4 py-2 text-sm font-semibold transition',
                'bg-primary/10 font-bold text-primary' => request()->routeIs('blog.*'),
                'text-muted hover:bg-surface-muted hover:text-body' => ! request()->routeIs('blog.*'),
            ])>Blog</a>
            @auth
                @php
                    $authUser = auth()->user();
                    $panelUrl = $authUser instanceof \App\Models\User && $authUser->isSuperAdmin()
                        ? url('/founder')
                        : ($authUser?->restaurants()->first() ? url('/admin/'.$authUser->restaurants()->first()->slug) : url('/admin'));
                @endphp
                <a href="{{ $panelUrl }}" rel="nofollow" class="rounded-lg bg-primary px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-primary-dark transition">Panel Admin</a>
            @else
                <a href="{{ url('/admin/login') }}" rel="nofollow" class="rounded-lg px-4 py-2 text-sm font-semibold text-muted hover:bg-surface-muted hover:text-body transition">Login</a>
                <a href="{{ route('register.restaurant') }}" class="rounded-lg bg-primary/10 px-4 py-2 text-sm font-semibold text-primary hover:bg-primary/20 transition">{{ $home['cta_register_label'] }}</a>
            @endauth
        </nav>

        <div class="flex items-center gap-1.5 sm:gap-2">
            @guest
                <a
                    href="{{ url('/admin/login') }}"
                    rel="nofollow"
                    class="md:hidden inline-flex items-center rounded-lg bg-surface-muted px-2.5 py-1.5 text-xs font-semibold text-body hover:bg-primary hover:text-white transition"
                >
                    Login
                </a>
            @endguest
            <x-customer.theme-toggle />

            {{-- Mobile Hamburger Button --}}
            <button
                type="button"
                x-on:click="mobileMenuOpen = ! mobileMenuOpen"
                class="inline-flex md:hidden items-center justify-center rounded-lg p-1.5 text-muted hover:bg-surface-muted hover:text-body transition"
                aria-label="Buka menu navigasi"
                :aria-expanded="mobileMenuOpen.toString()"
            >
                <svg x-show="! mobileMenuOpen" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="mobileMenuOpen" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Navigation Dropdown --}}
    <div
        x-show="mobileMenuOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="border-b border-border-subtle bg-surface-base/95 backdrop-blur-lg px-4 py-4 md:hidden shadow-lg"
    >
        <nav aria-label="Navigasi Mobile" class="flex flex-col gap-1.5 text-sm font-semibold">
            <a href="{{ route('home') }}" @class([
                'rounded-lg px-3 py-2 transition',
                'bg-primary/10 text-primary font-bold' => request()->routeIs('home'),
                'text-body hover:bg-surface-muted' => ! request()->routeIs('home'),
            ])>Beranda</a>
            <a href="{{ route('directory.menus') }}" @class([
                'rounded-lg px-3 py-2 transition',
                'bg-primary/10 text-primary font-bold' => request()->routeIs('directory.menus'),
                'text-body hover:bg-surface-muted' => ! request()->routeIs('directory.menus'),
            ])>Menu Kuliner</a>
            <a href="{{ route('page.about') }}" @class([
                'rounded-lg px-3 py-2 transition',
                'bg-primary/10 text-primary font-bold' => request()->routeIs('page.about'),
                'text-body hover:bg-surface-muted' => ! request()->routeIs('page.about'),
            ])>Tentang</a>
            <a href="{{ route('page.terms') }}" @class([
                'rounded-lg px-3 py-2 transition',
                'bg-primary/10 text-primary font-bold' => request()->routeIs('page.terms'),
                'text-body hover:bg-surface-muted' => ! request()->routeIs('page.terms'),
            ])>Syarat & Ketentuan</a>
            <a href="{{ route('blog.index', ['locale' => 'id']) }}" @class([
                'rounded-lg px-3 py-2 transition',
                'bg-primary/10 text-primary font-bold' => request()->routeIs('blog.*'),
                'text-body hover:bg-surface-muted' => ! request()->routeIs('blog.*'),
            ])>Blog</a>

            <div class="my-2 border-t border-border-subtle/70 pt-2 flex flex-col gap-2">
                @auth
                    @php
                        $authUser = auth()->user();
                        $panelUrl = $authUser instanceof \App\Models\User && $authUser->isSuperAdmin()
                            ? url('/founder')
                            : ($authUser?->restaurants()->first() ? url('/admin/'.$authUser->restaurants()->first()->slug) : url('/admin'));
                    @endphp
                    <a href="{{ $panelUrl }}" rel="nofollow" class="rounded-lg bg-primary px-3 py-2 text-center text-sm font-bold text-white shadow-sm hover:bg-primary-dark transition">Panel Admin</a>
                @else
                    <a href="{{ route('register.restaurant') }}" class="rounded-lg bg-primary/10 px-3 py-2 text-center text-sm font-semibold text-primary hover:bg-primary/20 transition">{{ $home['cta_register_label'] }}</a>
                @endauth
            </div>
        </nav>
    </div>
</header>
