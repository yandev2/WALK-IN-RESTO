<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
    <head>
        @include('partials.customer.theme-init')
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Restoran')</title>
        <meta name="description" content="@yield('description', 'Datang, duduk, scan QR di meja.')">
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
        @livewireScripts
    </body>
</html>
