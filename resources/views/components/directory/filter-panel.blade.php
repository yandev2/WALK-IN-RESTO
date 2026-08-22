@props([
    'categories',
    'facilityOptions',
    'sort' => 'newest',
    'locationStatus' => 'idle',
    'maxDistanceKm' => 10,
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

<aside @class([
    'directory-filter customer-card relative z-10 overflow-visible p-5 ring-1 ring-[color:var(--border-subtle)]',
    'hidden lg:block' => ! $mobile,
])>
    <div class="mb-5 flex items-center justify-between gap-3">
        <h2 class="text-base font-bold text-body">Filter</h2>
        <button type="button" wire:click="resetFilters" class="text-xs font-semibold text-primary">Reset</button>
    </div>

    <div class="space-y-6">
        <div>
            <p class="mb-2 text-sm font-semibold text-body">Urutkan</p>
            <x-customer.dropdown
                class="rounded-xl bg-surface-muted"
                property="sort"
                :value="$sort"
                :options="$sortOptions"
            />
        </div>

        <div>
            <div class="mb-2 flex items-center justify-between gap-2">
                <p class="text-sm font-semibold text-body">Jarak</p>
                @if ($locationStatus !== 'granted')
                    <span class="text-xs text-muted">Butuh izin lokasi</span>
                @endif
            </div>
            <input
                type="range"
                min="1"
                max="10"
                step="1"
                wire:model.live="maxDistanceKm"
                @disabled($locationStatus !== 'granted')
                @class([
                    'w-full accent-primary',
                    'opacity-50' => $locationStatus !== 'granted',
                ])
            >
            <p class="mt-1 text-xs text-muted">{{ number_format($maxDistanceKm, 0, ',', '.') }} km</p>
        </div>

        <div>
            <p class="mb-3 text-sm font-semibold text-body">Kategori</p>
            <div class="space-y-2">
                @foreach ($categories as $category)
                    <label class="flex items-center gap-2 text-sm text-body">
                        <input
                            type="checkbox"
                            wire:model.live="categoryIds"
                            value="{{ $category->id }}"
                            class="rounded border-border-subtle text-primary focus:ring-primary/30"
                        >
                        <span>{{ $category->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div>
            <p class="mb-3 text-sm font-semibold text-body">Fasilitas</p>
            <div class="space-y-2">
                @foreach ($facilityOptions as $key => $label)
                    <label class="flex items-center gap-2 text-sm text-body">
                        <input
                            type="checkbox"
                            wire:model.live="facilityFilters"
                            value="{{ $key }}"
                            class="rounded border-border-subtle text-primary focus:ring-primary/30"
                        >
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    </div>
</aside>
