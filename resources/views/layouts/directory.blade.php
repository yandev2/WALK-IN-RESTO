@php
    $home = $home ?? \App\Models\PlatformSetting::homeViewData();
    $metaTitle = trim($__env->yieldContent('title', $title ?? ($home['meta_title'] ?? 'Temukan Restoran Terdekat')));
    $metaTitle = html_entity_decode($metaTitle, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $metaDescription = trim($__env->yieldContent('description', $description ?? ($home['meta_description'] ?? 'Temukan restoran terdekat, lihat menu, dan datang langsung.')));
    $metaKeywords = $home['meta_keywords'] ?? 'restoran terdekat, kuliner terdekat, menu restoran, cafe terdekat, walk-in resto';
    $defaultCanonical = request()->routeIs('home') ? ($home['canonical_url'] ?? url()->current()) : url()->current();
    $canonicalUrl = \App\Models\PlatformSetting::canonicalizeUrl(trim($__env->yieldContent('canonical', $canonical ?? $defaultCanonical)));
    $faviconUrl = $home['favicon_url'] ?? ($home['logo_url'] ?? asset('favicon.ico'));
    if (filled($faviconUrl) && ! str_starts_with($faviconUrl, 'http://') && ! str_starts_with($faviconUrl, 'https://')) {
        $faviconUrl = url($faviconUrl);
    }
    $ogImageUrl = $home['og_image_url'] ?? ($home['hero_image_url'] ?? ($home['logo_url'] ?? null));

    $schemaOrg = [
        '@context' => 'https://schema.org',
        '@graph' => array_values(array_filter([
            [
                '@type' => 'WebSite',
                '@id' => url('/').'#website',
                'url' => url('/'),
                'name' => $home['site_name'] ?? 'RestoTerdekat',
                'description' => $home['meta_description'] ?? 'Direktori kuliner dan restoran terdekat.',
                'inLanguage' => 'id-ID',
            ],
            array_filter([
                '@type' => 'Organization',
                '@id' => url('/').'#organization',
                'name' => $home['site_name'] ?? 'RestoTerdekat',
                'url' => url('/'),
                'logo' => $home['logo_url'] ?? null,
                'image' => $home['logo_url'] ?? null,
                'email' => $home['footer_email'] ?? null,
                'telephone' => $home['footer_phone'] ?? null,
                'sameAs' => filled($home['footer_instagram_url'] ?? null) ? [$home['footer_instagram_url']] : null,
                'description' => $home['footer_about'] ?? 'Platform direktori restoran dan pemesanan walk-in.',
            ]),
        ])),
    ];
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
    <head>
        @include('partials.customer.theme-init')
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $metaTitle }}</title>
        <meta name="description" content="{{ $metaDescription }}">
        @if (filled($metaKeywords))
            <meta name="keywords" content="{{ $metaKeywords }}">
        @endif
        <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
        <link rel="canonical" href="{{ $canonicalUrl }}">

        {{-- Favicon & App Icons --}}
        @if (filled($faviconUrl))
            <link rel="icon" href="{{ $faviconUrl }}">
            <link rel="shortcut icon" href="{{ $faviconUrl }}">
            <link rel="apple-touch-icon" href="{{ $faviconUrl }}">
        @endif

        {{-- Open Graph / Facebook --}}
        <meta property="og:locale" content="id_ID">
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="{{ $home['site_name'] ?? config('app.name', 'RestoTerdekat') }}">
        <meta property="og:title" content="{{ $metaTitle }}">
        <meta property="og:description" content="{{ $metaDescription }}">
        <meta property="og:url" content="{{ $canonicalUrl }}">
        @if (filled($ogImageUrl))
            <meta property="og:image" content="{{ $ogImageUrl }}">
            <meta property="og:image:alt" content="{{ $home['site_name'] ?? 'RestoTerdekat' }}">
        @endif

        {{-- Twitter Cards --}}
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $metaTitle }}">
        <meta name="twitter:description" content="{{ $metaDescription }}">
        @if (filled($ogImageUrl))
            <meta name="twitter:image" content="{{ $ogImageUrl }}">
        @endif

        {{-- Schema.org JSON-LD Structured Data --}}
        <script type="application/ld+json">{!! json_encode($schemaOrg, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=fraunces:500,600,700|dm-sans:400,500,600,700|caveat:500,600,700" rel="stylesheet" />
        @include('partials.customer.theme-vars', ['theme' => $theme ?? \App\Support\RestaurantTheme::for(null)])
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
        <style>[x-cloak]{display:none!important}</style>
        @stack('head')
    </head>
    <body class="min-h-screen bg-surface-base text-body antialiased transition-colors duration-300">
        @isset($slot)
            {{ $slot }}
        @else
            @yield('body')
        @endisset
        @stack('scripts')
        @livewireScripts
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    </body>
</html>
