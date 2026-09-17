@props([
    'customer',
    'compact' => false,
])

@php
    $tier = strtolower((string) ($customer->tier ?? 'reguler'));
    if ($tier === 'regular') {
        $tier = 'reguler';
    }

    $theme = match ($tier) {
        'vip' => [
            'card' => 'relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#1a0533] via-[#380e61] to-[#0f0220] text-white border border-purple-400/40 shadow-xl shadow-purple-950/40',
            'badge' => 'bg-gradient-to-r from-amber-300 via-yellow-400 to-amber-500 text-zinc-950 font-black tracking-widest border border-amber-200/50 shadow-sm shadow-amber-400/30',
            'icon_color' => 'text-amber-300',
            'title_label' => 'VIP MEMBER',
            'text_muted' => 'text-purple-200/80',
            'stat_bg' => 'bg-purple-950/60 border border-purple-400/25 backdrop-blur-xs',
            'stat_label' => 'text-purple-300 text-[10px] uppercase font-bold tracking-wider',
            'stat_val' => 'text-white font-extrabold',
            'points_val' => 'text-amber-300 font-black',
            'shimmer' => true,
        ],
        'gold' => [
            'card' => 'relative overflow-hidden rounded-3xl bg-gradient-to-br from-amber-400 via-amber-500 to-yellow-600 text-amber-950 border border-amber-300/80 shadow-lg shadow-amber-500/25',
            'badge' => 'bg-amber-950 text-amber-300 font-black tracking-widest border border-amber-800/40 shadow-xs',
            'icon_color' => 'text-amber-950',
            'title_label' => 'GOLD MEMBER',
            'text_muted' => 'text-amber-950/80 font-medium',
            'stat_bg' => 'bg-amber-600/20 border border-amber-950/15 backdrop-blur-xs',
            'stat_label' => 'text-amber-950/80 text-[10px] uppercase font-bold tracking-wider',
            'stat_val' => 'text-amber-950 font-black',
            'points_val' => 'text-amber-950 font-black',
            'shimmer' => false,
        ],
        'silver' => [
            'card' => 'relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-100 via-zinc-200 to-slate-300 dark:from-zinc-800 dark:via-slate-700 dark:to-zinc-900 text-slate-800 dark:text-zinc-100 border border-slate-300/90 dark:border-zinc-600 shadow-md shadow-slate-400/20',
            'badge' => 'bg-gradient-to-r from-slate-700 to-zinc-800 text-slate-100 dark:bg-zinc-100 dark:text-zinc-900 font-black tracking-widest border border-slate-400/40 shadow-xs',
            'icon_color' => 'text-slate-600 dark:text-slate-300',
            'title_label' => 'SILVER MEMBER',
            'text_muted' => 'text-slate-600 dark:text-zinc-400 font-medium',
            'stat_bg' => 'bg-white/60 dark:bg-zinc-800/70 border border-slate-300/60 dark:border-zinc-600 backdrop-blur-xs',
            'stat_label' => 'text-slate-600 dark:text-zinc-400 text-[10px] uppercase font-bold tracking-wider',
            'stat_val' => 'text-slate-900 dark:text-white font-extrabold',
            'points_val' => 'text-primary dark:text-amber-400 font-black',
            'shimmer' => true,
        ],
        default => [
            'card' => 'relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-600 via-emerald-700 to-teal-800 text-white border border-emerald-400/30 shadow-md shadow-emerald-700/20',
            'badge' => 'bg-emerald-950/80 text-emerald-200 font-black tracking-widest border border-emerald-500/30 shadow-xs',
            'icon_color' => 'text-emerald-200',
            'title_label' => 'REGULER MEMBER',
            'text_muted' => 'text-emerald-100/80',
            'stat_bg' => 'bg-emerald-900/40 border border-emerald-400/20 backdrop-blur-xs',
            'stat_label' => 'text-emerald-200 text-[10px] uppercase font-bold tracking-wider',
            'stat_val' => 'text-white font-extrabold',
            'points_val' => 'text-amber-300 font-black',
            'shimmer' => false,
        ],
    };
@endphp

<div {{ $attributes->merge(['class' => $theme['card'] . ($compact ? ' p-4' : ' p-5')]) }}>
    {{-- Decorative Background Glow / Watermark --}}
    @if ($tier === 'vip')
        <div class="pointer-events-none absolute -right-10 -top-10 h-40 w-40 rounded-full bg-purple-500/20 blur-2xl"></div>
        <div class="pointer-events-none absolute -left-10 -bottom-10 h-40 w-40 rounded-full bg-amber-400/15 blur-2xl"></div>
    @elseif ($tier === 'gold')
        <div class="pointer-events-none absolute -right-10 -top-10 h-40 w-40 rounded-full bg-yellow-200/30 blur-2xl"></div>
    @elseif ($tier === 'silver')
        <div class="pointer-events-none absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white/40 dark:bg-zinc-500/20 blur-2xl"></div>
    @endif

    <div class="relative z-10">
        {{-- Card Header: Tier Badge & Brand/Card Type --}}
        <div class="flex items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                @if ($tier === 'vip')
                    <div class="flex h-7 w-7 items-center justify-center rounded-xl bg-purple-900/70 border border-purple-400/40 text-amber-300 shadow-xs">
                        {{-- Crown Icon --}}
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5m14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/>
                        </svg>
                    </div>
                @elseif ($tier === 'gold')
                    <div class="flex h-7 w-7 items-center justify-center rounded-xl bg-amber-600/30 border border-amber-950/20 text-amber-950">
                        {{-- Sparkles / Star Icon --}}
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l2.4 7.2h7.6l-6 4.8 2.4 7.2-6.4-4.8-6.4 4.8 2.4-7.2-6-4.8h7.6z"/>
                        </svg>
                    </div>
                @elseif ($tier === 'silver')
                    <div class="flex h-7 w-7 items-center justify-center rounded-xl bg-slate-300/60 dark:bg-zinc-700/60 border border-slate-400/40 text-slate-700 dark:text-zinc-200">
                        {{-- Shield / Ribbon Icon --}}
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
                        </svg>
                    </div>
                @else
                    <div class="flex h-7 w-7 items-center justify-center rounded-xl bg-emerald-800/70 border border-emerald-400/30 text-emerald-200">
                        {{-- User / Member Check Icon --}}
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                @endif

                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] {{ $theme['badge'] }}">
                        {{ $theme['title_label'] }}
                    </span>
                </div>
            </div>

            <span class="text-[11px] font-mono {{ $theme['text_muted'] }} tracking-wider">
                MEMBER CARD
            </span>
        </div>

        {{-- Member Name --}}
        <div class="mt-3">
            <p class="text-[11px] {{ $theme['text_muted'] }}">Nama Member</p>
            <h3 class="font-display {{ $compact ? 'text-lg' : 'text-xl' }} font-bold tracking-tight truncate">
                {{ $customer->name ?: 'Pelanggan Setia' }}
            </h3>
        </div>

        {{-- Metrics: Total Spent & Points Balance --}}
        <div class="mt-4 grid grid-cols-2 gap-2.5">
            <div class="rounded-2xl {{ $theme['stat_bg'] }} p-2.5">
                <p class="{{ $theme['stat_label'] }}">Total Dibelanjakan</p>
                <p class="{{ $theme['stat_val'] }} {{ $compact ? 'text-xs' : 'text-sm' }} mt-0.5">
                    {{ \App\Support\CmsMedia::formatIdr($customer->total_spent) }}
                </p>
            </div>

            <div class="rounded-2xl {{ $theme['stat_bg'] }} p-2.5">
                <p class="{{ $theme['stat_label'] }}">Jumlah Poin</p>
                <p class="{{ $theme['points_val'] }} {{ $compact ? 'text-xs' : 'text-sm' }} mt-0.5 flex items-center gap-1">
                    <span>{{ number_format($customer->points_balance, 0, ',', '.') }}</span>
                    <span class="text-[11px] font-bold">Poin</span>
                </p>
            </div>
        </div>
    </div>
</div>
