<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
    <head>
        @include('partials.customer.theme-init')
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', $title ?? 'Temukan restoran terdekat')</title>
        <meta name="description" content="@yield('description', $description ?? 'Temukan restoran terdekat, lihat menu, dan datang langsung.')">
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
