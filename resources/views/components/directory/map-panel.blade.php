@props([
    'mapId' => 'directory-map',
    'mobile' => false,
    'locationStatus' => 'idle',
])

<div @class([
    'directory-map customer-card overflow-hidden ring-1 ring-[color:var(--border-subtle)]',
    'hidden lg:block' => ! $mobile,
    'h-full' => $mobile,
])>
    <div class="flex items-center justify-between gap-3 border-b border-border-subtle px-4 py-3">
        <div>
            <h2 class="text-sm font-bold text-body">Peta restoran</h2>
            <p class="text-xs text-muted">
                @if ($locationStatus === 'granted')
                    Pin restoran dan lokasi Anda
                @else
                    Pin dari lokasi outlet default
                @endif
            </p>
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
                'rounded-full px-3 py-1.5 text-xs font-semibold ring-1 ring-[color:var(--border-subtle)]',
                'bg-emerald-500/10 text-emerald-700 hover:bg-emerald-500/20 dark:text-emerald-400' => $locationStatus === 'granted',
                'bg-surface-muted text-body hover:bg-surface-raised' => $locationStatus !== 'granted',
            ])
        >
            {{ $locationStatus === 'granted' ? 'Matikan lokasi' : 'Gunakan lokasi saya' }}
        </button>
    </div>
    <div id="{{ $mapId }}" @class(['directory-map-canvas w-full', 'h-56 md:h-64 lg:h-72' => ! $mobile, 'h-full min-h-[20rem]' => $mobile])></div>
</div>
