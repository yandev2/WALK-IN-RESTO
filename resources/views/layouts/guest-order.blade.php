<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
    <head>
        @include('partials.customer.theme-init')
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <title>{{ $title ?? 'Pesan' }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=fraunces:600|dm-sans:400,500,600,700" rel="stylesheet" />
        <style>[x-cloak]{display:none!important}</style>
        @include('partials.customer.theme-vars')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="min-h-screen bg-surface-base pb-20 text-body antialiased transition-colors duration-300">
        @isset($guestRestaurant)
            @include('partials.customer.guest-header')
        @endisset
        {{ $slot }}
        @livewireScripts
    </body>
</html>
