@php
    $tenantFavicon = filled($logoUrl ?? null)
        ? $logoUrl
        : (filled($restaurant->logo_path ?? null)
            ? \App\Support\CmsMedia::url($restaurant->logo_path)
            : (\App\Models\PlatformSetting::homeViewData()['favicon_url'] ?? asset('favicon.ico')));

    if (filled($tenantFavicon) && ! str_starts_with($tenantFavicon, 'http://') && ! str_starts_with($tenantFavicon, 'https://')) {
        $tenantFavicon = url($tenantFavicon);
    }
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
    <head>
        @include('partials.customer.theme-init')
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Restoran')</title>
        <meta name="description" content="@yield('description', 'Datang, duduk, scan QR di meja.')">

        {{-- Favicon & App Icons --}}
        @if (filled($tenantFavicon))
            <link rel="icon" href="{{ $tenantFavicon }}">
            <link rel="shortcut icon" href="{{ $tenantFavicon }}">
            <link rel="apple-touch-icon" href="{{ $tenantFavicon }}">
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
