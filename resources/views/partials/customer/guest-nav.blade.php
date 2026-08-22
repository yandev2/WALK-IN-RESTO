@php
    $cartCount = $cartCount ?? 0;
    $navClass = fn (bool $active): string => $active
        ? 'text-primary'
        : 'text-muted hover:text-body';
@endphp

<nav class="pointer-events-none fixed inset-x-0 bottom-0 z-20 flex justify-center px-4 pb-[max(0.5rem,env(safe-area-inset-bottom))]">
    <div class="pointer-events-auto w-full max-w-md overflow-hidden rounded-2xl bg-surface-raised/95 shadow-[0_-8px_24px_-12px_rgba(0,0,0,0.12)] ring-1 ring-border-subtle/60 backdrop-blur">
        <div class="grid grid-cols-3 text-center text-xs font-semibold">
        <a href="{{ route('guest.menu') }}" @class(['relative flex flex-col items-center gap-1 px-2 py-2.5', $navClass(request()->routeIs('guest.menu'))])>
            @if (request()->routeIs('guest.menu'))
                <span class="absolute top-1 h-1 w-1 rounded-full bg-primary shadow-sm shadow-primary/60"></span>
            @endif
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            Menu
        </a>
        <a href="{{ route('guest.cart') }}" @class(['relative flex flex-col items-center gap-1 px-2 py-2.5', $navClass(request()->routeIs('guest.cart'))])>
            @if (request()->routeIs('guest.cart'))
                <span class="absolute top-1 h-1 w-1 rounded-full bg-primary shadow-sm shadow-primary/60"></span>
            @endif
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l3-8H6.4M7 13L5.4 5M7 13l-1.5 7h11M10 17a1 1 0 100 2 1 1 0 000-2zm6 0a1 1 0 100 2 1 1 0 000-2z"/>
            </svg>
            Keranjang
            @if ($cartCount > 0)
                <span class="absolute right-6 top-1 rounded-full bg-accent px-1.5 py-0.5 text-[10px] font-bold text-white">{{ $cartCount }}</span>
            @endif
        </a>
        <a href="{{ route('guest.status') }}" @class(['relative flex flex-col items-center gap-1 px-2 py-2.5', $navClass(request()->routeIs('guest.status'))])>
            @if (request()->routeIs('guest.status'))
                <span class="absolute top-1 h-1 w-1 rounded-full bg-primary shadow-sm shadow-primary/60"></span>
            @endif
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Status
        </a>
        </div>
    </div>
</nav>
