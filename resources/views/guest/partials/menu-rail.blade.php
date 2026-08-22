@php
    $cartCount = $cartCount ?? 0;
@endphp

<aside class="hidden w-16 shrink-0 flex-col items-center gap-4 border-r border-border-subtle bg-surface-raised py-6 lg:flex">
    <a
        href="{{ route('guest.menu') }}"
        @class([
            'relative flex h-10 w-10 items-center justify-center rounded-xl transition',
            request()->routeIs('guest.menu') ? 'bg-primary/10 text-primary' : 'text-muted hover:bg-surface-muted hover:text-body',
        ])
        aria-label="Menu"
    >
        @if (request()->routeIs('guest.menu'))
            <span class="absolute -right-0.5 top-1/2 h-6 w-1 -translate-y-1/2 rounded-full bg-primary"></span>
        @endif
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h10"/>
        </svg>
    </a>
    <a
        href="{{ route('guest.cart') }}"
        @class([
            'relative flex h-10 w-10 items-center justify-center rounded-xl transition',
            request()->routeIs('guest.cart') ? 'bg-primary/10 text-primary' : 'text-muted hover:bg-surface-muted hover:text-body',
        ])
        aria-label="Keranjang"
    >
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l3-8H6.4M7 13L5.4 5M7 13l-1.5 7h11M10 17a1 1 0 100 2 1 1 0 000-2zm6 0a1 1 0 100 2 1 1 0 000-2z"/>
        </svg>
        @if ($cartCount > 0)
            <span class="absolute -right-1 -top-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-accent px-1 text-[10px] font-bold text-white">{{ $cartCount }}</span>
        @endif
    </a>
    <a
        href="{{ route('guest.status') }}"
        @class([
            'relative flex h-10 w-10 items-center justify-center rounded-xl transition',
            request()->routeIs('guest.status') ? 'bg-primary/10 text-primary' : 'text-muted hover:bg-surface-muted hover:text-body',
        ])
        aria-label="Status"
    >
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </a>
    <div class="mt-auto">
        <x-customer.theme-toggle />
    </div>
</aside>
