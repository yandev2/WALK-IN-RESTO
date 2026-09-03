@php
    $cartCount = $cartCount ?? 0;
    $activeTab = $activeTab ?? null;

    if (! $activeTab) {
        if (request()->routeIs('guest.cart') || request()->routeIs('guest.checkout') || request()->routeIs('guest.pay') || str_contains(url()->current(), '/cart') || str_contains(request()->header('Referer', ''), '/cart')) {
            $activeTab = 'cart';
        } elseif (request()->routeIs('guest.status') || request()->routeIs('guest.review') || str_contains(url()->current(), '/status') || str_contains(request()->header('Referer', ''), '/status')) {
            $activeTab = 'status';
        } else {
            $activeTab = 'menu';
        }
    }

    $isMenu = $activeTab === 'menu';
    $isCart = $activeTab === 'cart';
    $isStatus = $activeTab === 'status';
@endphp

<nav class="fixed inset-x-0 bottom-0 z-40 bg-surface-raised/95 dark:bg-zinc-900/95 border-t border-border-subtle/70 dark:border-white/10 backdrop-blur-md pb-[env(safe-area-inset-bottom,0px)] shadow-[0_-4px_20px_rgba(0,0,0,0.06)]">
    <div class="mx-auto flex max-w-md items-center justify-around px-3 py-2 gap-2">
        {{-- Menu Tab --}}
        <a
            href="{{ route('guest.menu') }}"
            @class([
                'flex flex-1 flex-col items-center justify-center gap-1 rounded-2xl py-2 text-xs transition duration-200',
                $isMenu
                    ? 'bg-primary text-white font-bold shadow-sm shadow-primary/30'
                    : 'text-muted dark:text-zinc-400 hover:text-body dark:hover:text-white font-medium hover:bg-surface-muted/50 dark:hover:bg-zinc-800/40',
            ])
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ $isMenu ? '2.5' : '1.8' }}" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <span>Menu</span>
        </a>

        {{-- Keranjang Tab --}}
        <a
            href="{{ route('guest.cart') }}"
            @class([
                'relative flex flex-1 flex-col items-center justify-center gap-1 rounded-2xl py-2 text-xs transition duration-200',
                $isCart
                    ? 'bg-primary text-white font-bold shadow-sm shadow-primary/30'
                    : 'text-muted dark:text-zinc-400 hover:text-body dark:hover:text-white font-medium hover:bg-surface-muted/50 dark:hover:bg-zinc-800/40',
            ])
        >
            @if ($cartCount > 0)
                <span @class([
                    'absolute right-3 top-1 flex h-4 min-w-4 items-center justify-center rounded-full px-1 text-[10px] font-extrabold',
                    $isCart ? 'bg-white text-primary' : 'bg-rose-500 text-white shadow-xs',
                ])>
                    {{ $cartCount }}
                </span>
            @endif
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ $isCart ? '2.5' : '1.8' }}" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l3-8H6.4M7 13L5.4 5M7 13l-1.5 7h11M10 17a1 1 0 100 2 1 1 0 000-2zm6 0a1 1 0 100 2 1 1 0 000-2z"/>
            </svg>
            <span>Keranjang</span>
        </a>

        {{-- Status Tab --}}
        <a
            href="{{ route('guest.status') }}"
            @class([
                'flex flex-1 flex-col items-center justify-center gap-1 rounded-2xl py-2 text-xs transition duration-200',
                $isStatus
                    ? 'bg-primary text-white font-bold shadow-sm shadow-primary/30'
                    : 'text-muted dark:text-zinc-400 hover:text-body dark:hover:text-white font-medium hover:bg-surface-muted/50 dark:hover:bg-zinc-800/40',
            ])
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ $isStatus ? '2.5' : '1.8' }}" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Status</span>
        </a>
    </div>
</nav>
