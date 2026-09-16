@php
    $home = \App\Models\PlatformSetting::homeViewData();
    $currentLocale = app()->getLocale();
    $metaTitle = trim($__env->yieldContent('title', 'Blog Kuliner & Restoran · ' . ($home['site_name'] ?? 'Cita Rasa Kita')));
    $metaDescription = trim($__env->yieldContent('description', 'Temukan artikel, tips kuliner, resep, dan panduan restoran terbaik di Indonesia.'));
    $metaKeywords = trim($__env->yieldContent('keywords', ''));
    $metaRobots = trim($__env->yieldContent('robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1'));
    $ogImage = trim($__env->yieldContent('og_image', $home['og_image_url'] ?? asset('favicon.ico')));
    $canonical = trim($__env->yieldContent('canonical', url()->current()));
    $ogType = trim($__env->yieldContent('og_type', 'website'));
@endphp
<!DOCTYPE html>
<html lang="{{ $currentLocale }}" class="scroll-smooth">
<head>
    @include('partials.customer.theme-init')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    @if (filled($metaKeywords))
        <meta name="keywords" content="{{ $metaKeywords }}">
    @endif
    <meta name="robots" content="{{ $metaRobots }}">
    <link rel="canonical" href="{{ $canonical }}">

    {{-- Multilingual Hreflang Alternates --}}
    @yield('hreflang')

    {{-- Open Graph / Social --}}
    <meta property="og:locale" content="{{ $currentLocale === 'id' ? 'id_ID' : 'en_US' }}">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:site_name" content="{{ $home['site_name'] ?? config('app.name', 'Cita Rasa Kita') }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonical }}">
    @if (filled($ogImage))
        <meta property="og:image" content="{{ $ogImage }}">
    @endif
    @yield('article_meta')

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    @if (filled($ogImage))
        <meta name="twitter:image" content="{{ $ogImage }}">
    @endif

    {{-- Favicon --}}
    @if (filled($home['favicon_url'] ?? null))
        <link rel="icon" href="{{ $home['favicon_url'] }}">
    @endif

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=fraunces:500,600,700|dm-sans:400,500,600,700" rel="stylesheet" />

    @include('partials.customer.theme-vars', ['theme' => \App\Support\RestaurantTheme::for(null)])
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important}</style>
    @stack('schema')
    @stack('head')
    <x-ads.head />
</head>
<body class="min-h-screen bg-surface-base text-body antialiased transition-colors duration-300">
    {{-- Header / Navbar --}}
    <header role="banner" class="directory-header sticky top-0 z-40 border-b border-border-subtle/80 bg-surface-base/85 backdrop-blur-md transition-colors duration-200">
        <div class="landing-container flex h-16 items-center justify-between gap-4">
            <div class="flex items-center gap-4 min-w-0">
                <a href="{{ route('home') }}" class="inline-flex min-w-0 items-center gap-2.5" aria-label="Beranda {{ $home['site_name'] }}">
                    @if (filled($home['logo_url'] ?? null))
                        <img
                            src="{{ $home['logo_url'] }}"
                            alt="{{ $home['site_name'] }}"
                            class="h-9 w-auto max-w-[200px] object-contain"
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
                <span class="hidden sm:inline-block text-xs font-semibold uppercase tracking-wider text-primary border-l border-border-subtle pl-3">
                    Blog
                </span>
            </div>

            <nav aria-label="Navigasi Utama" class="hidden items-center gap-1 md:flex">
                <a href="{{ route('home') }}" class="rounded-full px-3.5 py-1.5 text-sm font-medium text-muted hover:bg-surface-muted hover:text-body transition">
                    {{ __('portfolio.nav.home') ?? 'Beranda' }}
                </a>
                <a href="{{ route('blog.index') }}" class="rounded-full px-3.5 py-1.5 text-sm font-bold bg-primary/10 text-primary transition">
                    Blog
                </a>
                <a href="{{ route('page.about') }}" class="rounded-full px-3.5 py-1.5 text-sm font-medium text-muted hover:bg-surface-muted hover:text-body transition">
                    Tentang
                </a>
                <a href="{{ route('page.terms') }}" class="rounded-full px-3.5 py-1.5 text-sm font-medium text-muted hover:bg-surface-muted hover:text-body transition">
                    Syarat & Ketentuan
                </a>
            </nav>

            <div class="flex items-center gap-2.5">
                {{-- Language Switcher --}}
                <div class="flex items-center rounded-full border border-border-subtle bg-surface-raised p-0.5 text-xs font-semibold">
                    <a
                        href="{{ request()->fullUrlWithQuery(['lang' => 'id']) }}"
                        @class([
                            'rounded-full px-2.5 py-1 transition-all',
                            'bg-primary text-white shadow-sm' => $currentLocale === 'id',
                            'text-muted hover:text-body' => $currentLocale !== 'id',
                        ])
                        aria-label="Bahasa Indonesia"
                    >
                        ID
                    </a>
                    <a
                        href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}"
                        @class([
                            'rounded-full px-2.5 py-1 transition-all',
                            'bg-primary text-white shadow-sm' => $currentLocale === 'en',
                            'text-muted hover:text-body' => $currentLocale !== 'en',
                        ])
                        aria-label="English"
                    >
                        EN
                    </a>
                </div>

                <x-customer.theme-toggle />

                @auth
                    @php
                        $authUser = auth()->user();
                        $panelUrl = $authUser instanceof \App\Models\User && $authUser->isSuperAdmin()
                            ? url('/founder')
                            : ($authUser?->hasRole('blogger') ? url('/blogger') : url('/admin'));
                    @endphp
                    <a href="{{ $panelUrl }}" class="hidden sm:inline-flex rounded-full bg-primary px-3.5 py-1.5 text-xs font-bold text-white shadow-sm hover:bg-primary-dark transition">
                        Panel
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <x-directory.footer :home="$home" />

    @stack('scripts')
</body>
</html>
