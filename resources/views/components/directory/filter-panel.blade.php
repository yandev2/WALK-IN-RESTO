@props([
    'categories',
    'facilityOptions',
    'sort' => 'newest',
    'locationStatus' => 'idle',
    'maxDistanceKm' => null,
    'mobile' => false,
])

@php
    $sortOptions = [
        ['value' => 'newest', 'label' => 'Terbaru'],
        ['value' => 'rating', 'label' => 'Rating tertinggi'],
        ['value' => 'name', 'label' => 'Nama A–Z'],
    ];

    if ($locationStatus === 'granted') {
        array_unshift($sortOptions, ['value' => 'distance', 'label' => 'Terdekat']);
    }
@endphp

<aside
    aria-label="Filter Pencarian Restoran"
    @class([
        'directory-filter customer-card relative z-10 overflow-visible p-5 ring-1 ring-[color:var(--border-subtle)] shadow-sm',
        'hidden lg:block' => ! $mobile,
    ])
>
    <div class="mb-5 flex items-center justify-between gap-3 border-b border-border-subtle/70 pb-3.5">
        <div class="flex items-center gap-2">
            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-gradient-to-br from-primary to-primary-dark text-white shadow-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
            </span>
            <h2 class="font-display text-base font-bold text-body">Filter</h2>
        </div>
        <button
            type="button"
            wire:click="resetFilters"
            class="inline-flex items-center gap-1 rounded-full bg-surface-muted px-2.5 py-1 text-xs font-semibold text-primary hover:bg-primary/10 transition duration-200"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Reset
        </button>
    </div>

    <div class="space-y-5">
        <div>
            <p class="mb-2.5 text-xs font-bold uppercase tracking-wider text-muted">Urutkan</p>
            <div class="space-y-1">
                @foreach ($sortOptions as $option)
                    <label class="filter-option-row group">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="filter-radio-box">
                                <input
                                    type="radio"
                                    wire:model.live="sort"
                                    value="{{ $option['value'] }}"
                                    class="sr-only"
                                >
                                <span class="filter-radio-dot"></span>
                            </span>
                            <span class="filter-option-label truncate text-sm text-body">{{ $option['label'] }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="border-t border-border-subtle/70 pt-4">
            <div class="mb-3 flex items-center justify-between gap-2">
                <p class="text-xs font-bold uppercase tracking-wider text-muted whitespace-nowrap">Jarak Maksimal</p>
                <div class="flex items-center gap-1.5 shrink-0">
                    @if ($locationStatus === 'granted')
                        @if ($maxDistanceKm)
                            <span class="inline-flex items-center gap-1 rounded-full bg-primary/10 px-2 py-0.5 text-[11px] font-bold text-primary shadow-xs">
                                <span class="h-1.5 w-1.5 rounded-full bg-primary animate-pulse"></span>
                                {{ number_format($maxDistanceKm, 0, ',', '.') }} km
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/10 px-2 py-0.5 text-[11px] font-bold text-emerald-600 dark:text-emerald-400 shadow-xs">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                Semua
                            </span>
                        @endif
                    @else
                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-500/10 px-2 py-0.5 text-[11px] font-semibold text-amber-600 dark:text-amber-400">
                            Nonaktif
                        </span>
                    @endif
                </div>
            </div>

            @if ($locationStatus !== 'granted')
                <div class="mb-3 rounded-xl bg-amber-500/5 p-2.5 border border-amber-500/20 text-[11px] leading-relaxed text-muted flex items-start gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-amber-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <span>Aktifkan izin lokasi untuk filter radius jarak.</span>
                        <button type="button" x-on:click="$dispatch('request-directory-location')" class="block font-bold text-primary hover:underline mt-0.5">Izinkan Lokasi →</button>
                    </div>
                </div>
            @endif

            {{-- Quick Presets --}}
            <div class="mb-3 space-y-1.5">
                {{-- Master: Semua Jarak --}}
                <button
                    type="button"
                    wire:click="$set('maxDistanceKm', null)"
                    @disabled($locationStatus !== 'granted')
                    @class([
                        'w-full flex items-center justify-center gap-1.5 rounded-lg py-1.5 px-3 text-xs font-semibold transition shadow-xs',
                        'bg-primary text-white ring-1 ring-primary shadow-primary/20' => $maxDistanceKm === null && $locationStatus === 'granted',
                        'bg-surface-muted text-muted hover:bg-surface-raised hover:text-body ring-1 ring-border-subtle' => $maxDistanceKm !== null || $locationStatus !== 'granted',
                        'opacity-50 cursor-not-allowed' => $locationStatus !== 'granted',
                    ])
                >
                    @if ($maxDistanceKm === null && $locationStatus === 'granted')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    @endif
                    <span>Semua Jarak</span>
                </button>

                {{-- Distance Milestones Grid --}}
                <div class="grid grid-cols-4 gap-1.5">
                    @foreach ([5, 10, 25, 50] as $km)
                        @php
                            $isSelected = $maxDistanceKm !== null && (float) $maxDistanceKm === (float) $km;
                        @endphp
                        <button
                            type="button"
                            wire:click="$set('maxDistanceKm', {{ $km }})"
                            @disabled($locationStatus !== 'granted')
                            @class([
                                'flex items-center justify-center rounded-lg py-1.5 text-xs font-semibold transition shadow-xs',
                                'bg-primary text-white ring-1 ring-primary shadow-primary/20' => $isSelected && $locationStatus === 'granted',
                                'bg-surface-muted text-muted hover:bg-surface-raised hover:text-body ring-1 ring-border-subtle' => ! $isSelected || $locationStatus !== 'granted',
                                'opacity-50 cursor-not-allowed' => $locationStatus !== 'granted',
                            ])
                        >
                            {{ $km }} km
                        </button>
                    @endforeach
                </div>
            </div>

            @php
                $sliderFill = $maxDistanceKm ? max(0, min(100, (($maxDistanceKm - 1) / 49) * 100)) : 100;
                $currentDistance = $maxDistanceKm ?? 50;
            @endphp
            <div
                class="px-1 py-1"
                x-data="{
                    fillPercent: {{ $sliderFill }},
                    sliderVal: {{ $currentDistance }},
                    init() {
                        this.$watch('$wire.maxDistanceKm', (val) => {
                            if (val === null || val === undefined || val === '') {
                                this.sliderVal = 50;
                                this.fillPercent = 100;
                            } else {
                                const num = Number(val);
                                this.sliderVal = num;
                                this.fillPercent = Math.max(0, Math.min(100, ((num - 1) / 49) * 100));
                            }
                        });
                    },
                    handleInput(e) {
                        const val = Number(e.target.value);
                        this.sliderVal = val;
                        this.fillPercent = Math.max(0, Math.min(100, ((val - 1) / 49) * 100));
                    },
                    handleChange(e) {
                        const val = Number(e.target.value);
                        $wire.set('maxDistanceKm', val);
                    }
                }"
            >
                <input
                    type="range"
                    min="1"
                    max="50"
                    step="1"
                    :value="sliderVal"
                    value="{{ $currentDistance }}"
                    @disabled($locationStatus !== 'granted')
                    class="directory-range-slider"
                    aria-label="Filter radius jarak"
                    x-on:input="handleInput($event)"
                    x-on:input.debounce.300ms="handleChange($event)"
                    x-on:change="handleChange($event)"
                    :style="`--slider-fill: ${fillPercent}%`"
                    style="--slider-fill: {{ $sliderFill }}%"
                >
            </div>
            <div class="mt-1 flex items-center justify-between px-1 text-[11px] font-semibold text-muted">
                <span>1 km</span>
                <span class="text-[10px] text-muted/60">25 km</span>
                <span>50 km</span>
            </div>
        </div>

        <div class="border-t border-border-subtle/70 pt-4">
            <p class="mb-2.5 text-xs font-bold uppercase tracking-wider text-muted">Kategori</p>
            <div class="space-y-1">
                @foreach ($categories as $category)
                    <label class="filter-option-row group">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="filter-checkbox-box">
                                <input
                                    type="checkbox"
                                    wire:model.live="categoryIds"
                                    value="{{ $category->id }}"
                                    class="sr-only"
                                >
                                <svg xmlns="http://www.w3.org/2000/svg" class="filter-checkbox-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </span>
                            <span class="filter-option-label truncate text-sm text-body">{{ $category->name }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="border-t border-border-subtle/70 pt-4">
            <p class="mb-2.5 text-xs font-bold uppercase tracking-wider text-muted">Fasilitas</p>
            <div class="space-y-1">
                @foreach ($facilityOptions as $key => $label)
                    <label class="filter-option-row group">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="filter-checkbox-box">
                                <input
                                    type="checkbox"
                                    wire:model.live="facilityFilters"
                                    value="{{ $key }}"
                                    class="sr-only"
                                >
                                <svg xmlns="http://www.w3.org/2000/svg" class="filter-checkbox-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </span>
                            <span class="filter-option-label truncate text-sm text-body">{{ $label }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
    </div>
</aside>

