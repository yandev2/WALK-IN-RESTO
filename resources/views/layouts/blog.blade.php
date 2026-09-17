@php
    $home = \App\Models\PlatformSetting::homeViewData();
    $currentLocale = app()->getLocale();
    $metaTitle = trim($__env->yieldContent('title', 'Blog Kuliner & Restoran · ' . ($home['site_name'] ?? 'Cita Rasa Kita')));
    $metaDescription = trim($__env->yieldContent('description', 'Temukan artikel, tips kuliner, resep, dan panduan restoran terbaik di Indonesia.'));
    $metaKeywords = trim($__env->yieldContent('keywords', ''));
    $metaRobots = trim($__env->yieldContent('robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1'));
    $ogImage = trim($__env->yieldContent('og_image', $home['og_image_url'] ?? asset('favicon.ico')));
    $canonical = \App\Models\PlatformSetting::canonicalizeUrl(trim($__env->yieldContent('canonical', url()->current())));
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
                {{-- Language Switcher (Clean Path-Based like Photo 2) --}}
                @php
                    $currentRouteName = request()->route()?->getName();
                    $targetUrlId = route('blog.index', ['locale' => 'id']);
                    $targetUrlEn = route('blog.index', ['locale' => 'en']);

                    if ($currentRouteName === 'blog.show' && isset($post)) {
                        $idT = $post->translate('id');
                        $enT = $post->translate('en');
                        $targetUrlId = ($idT && filled($idT->slug)) ? route('blog.show', ['locale' => 'id', 'slug' => $idT->slug]) : route('blog.index', ['locale' => 'id']);
                        $targetUrlEn = ($enT && filled($enT->slug)) ? route('blog.show', ['locale' => 'en', 'slug' => $enT->slug]) : route('blog.index', ['locale' => 'en']);
                    } elseif ($currentRouteName === 'blog.category' && isset($category)) {
                        $idCat = $category->translate('id');
                        $enCat = $category->translate('en');
                        $targetUrlId = ($idCat && filled($idCat->slug)) ? route('blog.category', ['locale' => 'id', 'slug' => $idCat->slug]) : route('blog.index', ['locale' => 'id']);
                        $targetUrlEn = ($enCat && filled($enCat->slug)) ? route('blog.category', ['locale' => 'en', 'slug' => $enCat->slug]) : route('blog.index', ['locale' => 'en']);
                    } elseif ($currentRouteName === 'blog.tag' && isset($tag)) {
                        $idTag = $tag->translate('id');
                        $enTag = $tag->translate('en');
                        $targetUrlId = ($idTag && filled($idTag->slug)) ? route('blog.tag', ['locale' => 'id', 'slug' => $idTag->slug]) : route('blog.index', ['locale' => 'id']);
                        $targetUrlEn = ($enTag && filled($enTag->slug)) ? route('blog.tag', ['locale' => 'en', 'slug' => $enTag->slug]) : route('blog.index', ['locale' => 'en']);
                    } elseif ($currentRouteName === 'blog.archive') {
                        $targetUrlId = route('blog.archive', ['locale' => 'id']);
                        $targetUrlEn = route('blog.archive', ['locale' => 'en']);
                    }
                @endphp
                <div class="flex items-center gap-1 rounded-full border border-border-subtle bg-surface-raised p-1 text-xs font-semibold shadow-2xs">
                    <a
                        href="{{ $targetUrlId }}"
                        @class([
                            'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 transition-all duration-200',
                            'bg-primary text-white shadow-xs font-bold' => $currentLocale === 'id',
                            'text-muted hover:text-body hover:bg-surface-muted' => $currentLocale !== 'id',
                        ])
                        aria-label="Bahasa Indonesia"
                    >
                        <span class="inline-flex items-center justify-center w-4 h-2.5 rounded-[2px] overflow-hidden border border-black/15 dark:border-white/20 shadow-2xs shrink-0" aria-hidden="true">
                            <svg viewBox="0 0 640 480" class="w-full h-full block">
                                <path fill="#E70011" d="M0 0h640v240H0z"/>
                                <path fill="#FFFFFF" d="M0 240h640v240H0z"/>
                            </svg>
                        </span>
                        <span>ID</span>
                    </a>
                    <a
                        href="{{ $targetUrlEn }}"
                        @class([
                            'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 transition-all duration-200',
                            'bg-primary text-white shadow-xs font-bold' => $currentLocale === 'en',
                            'text-muted hover:text-body hover:bg-surface-muted' => $currentLocale !== 'en',
                        ])
                        aria-label="English"
                    >
                        <span class="inline-flex items-center justify-center w-4 h-2.5 rounded-[2px] overflow-hidden border border-black/15 dark:border-white/20 shadow-2xs shrink-0" aria-hidden="true">
                            <svg viewBox="0 0 640 480" class="w-full h-full block">
                                <path fill="#012169" d="M0 0h640v480H0z"/>
                                <path fill="#FFF" d="m75 0 244 181L562 0h78v62L400 241l240 178v61h-80L320 301 81 480H0v-60l239-179L0 64V0h75z"/>
                                <path fill="#C8102E" d="m424 281 216 159v40L369 281h55zm-104-41 240 180H480L320 300v-60zM0 441l215-160h55L0 480v-39zm216-160L0 120V80l270 201h-54z"/>
                                <path fill="#FFF" d="M240 0v480h160V0H240zM0 160v160h640V160H0z"/>
                                <path fill="#C8102E" d="M267 0v480h106V0H267zM0 187v106h640V187H0z"/>
                            </svg>
                        </span>
                        <span>EN</span>
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
