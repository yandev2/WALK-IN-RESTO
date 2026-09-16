@php
    $tenantFavicon = filled($logoUrl ?? null)
        ? $logoUrl
        : (filled($restaurant->logo_path ?? null)
            ? \App\Support\CmsMedia::url($restaurant->logo_path)
            : (\App\Models\PlatformSetting::homeViewData()['favicon_url'] ?? asset('favicon.ico')));

    if (filled($tenantFavicon) && ! str_starts_with($tenantFavicon, 'http://') && ! str_starts_with($tenantFavicon, 'https://')) {
        $tenantFavicon = url($tenantFavicon);
    }

    $siteName = $restaurant->name ?? config('app.name', 'Restoran');
    $metaTitle = trim($__env->yieldContent('title', $title ?? ($siteName . ' · Walk-in')));
    $metaDescription = trim($__env->yieldContent('description', $description ?? ('Kunjungi ' . $siteName . '. Lihat menu lezat, promo terbaru, dan pesan langsung di meja dengan scan QR.')));
    $canonicalUrl = trim($__env->yieldContent('canonical', $canonical ?? url()->current()));
    $ogImageUrl = $heroUrl ?? ($logoUrl ?? null);
    if (filled($ogImageUrl) && ! str_starts_with($ogImageUrl, 'http://') && ! str_starts_with($ogImageUrl, 'https://')) {
        $ogImageUrl = url($ogImageUrl);
    }

    $restaurantSchema = null;
    if (isset($restaurant) && $restaurant instanceof \App\Models\Restaurant) {
        $defaultOutlet = $outlet ?? $restaurant->defaultOutlet;
        $restaurantSchema = array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Restaurant',
            '@id' => route('landing.show', $restaurant) . '#restaurant',
            'name' => $restaurant->name,
            'url' => route('landing.show', $restaurant),
            'menu' => route('landing.menu', $restaurant),
            'image' => array_values(array_filter([$ogImageUrl])),
            'telephone' => $defaultOutlet?->phone,
            'priceRange' => '$$',
            'servesCuisine' => 'Indonesian, Casual Dining',
            'address' => filled($defaultOutlet?->address) ? array_filter([
                '@type' => 'PostalAddress',
                'streetAddress' => $defaultOutlet->address,
                'addressCountry' => 'ID',
            ]) : null,
            'geo' => ($defaultOutlet?->latitude && $defaultOutlet?->longitude) ? [
                '@type' => 'GeoCoordinates',
                'latitude' => (float) $defaultOutlet->latitude,
                'longitude' => (float) $defaultOutlet->longitude,
            ] : null,
        ]);

        if (isset($ratingSummary) && ($ratingSummary['count'] ?? 0) > 0) {
            $restaurantSchema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => (float) $ratingSummary['average'],
                'reviewCount' => (int) $ratingSummary['count'],
                'bestRating' => '5',
                'worstRating' => '1',
            ];
        }
    }
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
    <head>
        @include('partials.customer.theme-init')
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $metaTitle }}</title>
        <meta name="description" content="{{ $metaDescription }}">
        <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
        <link rel="canonical" href="{{ $canonicalUrl }}">

        {{-- Open Graph / Facebook --}}
        <meta property="og:locale" content="id_ID">
        <meta property="og:type" content="restaurant.restaurant">
        <meta property="og:site_name" content="{{ $siteName }}">
        <meta property="og:title" content="{{ $metaTitle }}">
        <meta property="og:description" content="{{ $metaDescription }}">
        <meta property="og:url" content="{{ $canonicalUrl }}">
        @if (filled($ogImageUrl))
            <meta property="og:image" content="{{ $ogImageUrl }}">
            <meta property="og:image:alt" content="{{ $siteName }}">
        @endif

        {{-- Twitter Cards --}}
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $metaTitle }}">
        <meta name="twitter:description" content="{{ $metaDescription }}">
        @if (filled($ogImageUrl))
            <meta name="twitter:image" content="{{ $ogImageUrl }}">
        @endif

        {{-- Favicon & App Icons --}}
        @if (filled($tenantFavicon))
            <link rel="icon" href="{{ $tenantFavicon }}">
            <link rel="shortcut icon" href="{{ $tenantFavicon }}">
            <link rel="apple-touch-icon" href="{{ $tenantFavicon }}">
        @endif

        {{-- Schema.org Structured Data --}}
        @if ($restaurantSchema)
            <script type="application/ld+json">
                {!! json_encode($restaurantSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
            </script>
        @endif

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=fraunces:500,600,700|dm-sans:400,500,600,700|caveat:500,600,700" rel="stylesheet" />
        @include('partials.customer.theme-vars', ['theme' => $theme ?? \App\Support\RestaurantTheme::for(null)])
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
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
        @include('partials.customer.image-preview-alpine')
        @livewireScripts
    </body>
</html>
