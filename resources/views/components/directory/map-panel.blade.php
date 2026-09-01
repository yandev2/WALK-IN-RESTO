@props([
    'mapId' => 'directory-map',
    'mobile' => false,
    'locationStatus' => 'idle',
])

<div @class([
    'directory-map customer-card overflow-hidden ring-1 ring-[color:var(--border-subtle)] shadow-md transition-shadow',
    'hidden lg:block' => ! $mobile,
    'h-full' => $mobile,
])>
    <div class="flex items-center justify-between gap-3 border-b border-border-subtle/80 bg-surface-muted/60 px-5 py-3.5">
        <div class="flex items-center gap-2.5">
            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary/10 text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </span>
            <div>
                <h2 class="text-sm font-bold text-body">Peta restoran</h2>
                <p class="text-xs text-muted">
                    @if ($locationStatus === 'granted')
                        Pin restoran & lokasi terkini Anda
                    @else
                        Pin dari lokasi outlet default
                    @endif
                </p>
            </div>
        </div>
        <button
            type="button"
            @if ($locationStatus === 'granted')
                wire:click="disableLocationSearch"
                x-on:click="window.markDirectoryLocationSkipped?.()"
            @else
                x-on:click="$dispatch('request-directory-location')"
            @endif
            @class([
                'rounded-full px-3.5 py-1.5 text-xs font-semibold ring-1 transition shadow-sm',
                'bg-emerald-500/10 text-emerald-700 ring-emerald-500/20 hover:bg-emerald-500/20 dark:text-emerald-400' => $locationStatus === 'granted',
                'bg-surface-raised text-body ring-[color:var(--border-subtle)] hover:bg-surface-muted' => $locationStatus !== 'granted',
            ])
        >
            {{ $locationStatus === 'granted' ? 'Matikan lokasi' : 'Gunakan lokasi saya' }}
        </button>
    </div>
    <div id="{{ $mapId }}" @class(['directory-map-canvas w-full', 'h-56 md:h-64 lg:h-72' => ! $mobile, 'h-full min-h-[20rem]' => $mobile])></div>
</div>
