@props([
    'categories',
    'facilityOptions',
    'sort' => 'newest',
    'locationStatus' => 'pending',
    'maxDistanceKm' => null,
])

<div
    x-data="{ open: false }"
    x-on:open-directory-filters.window="open = true"
    x-on:keydown.escape.window="open = false"
    x-cloak
>
    <div
        x-show="open"
        x-transition.opacity
        class="fixed inset-0 z-50 bg-black/45 lg:hidden"
        x-on:click="open = false"
    ></div>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full"
        x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full"
        class="fixed inset-x-0 bottom-0 z-50 max-h-[85vh] overflow-y-auto rounded-t-[1.75rem] bg-surface-base p-5 shadow-[var(--card-shadow-hover)] lg:hidden"
    >
        <div class="mb-4 flex items-center justify-between gap-3">
            <h2 class="text-lg font-bold text-body">Filter</h2>
            <button type="button" x-on:click="open = false" class="rounded-full bg-surface-muted px-3 py-1 text-sm font-semibold text-body">Tutup</button>
        </div>

        <x-directory.filter-panel
            :categories="$categories"
            :facility-options="$facilityOptions"
            :sort="$sort"
            :location-status="$locationStatus"
            :max-distance-km="$maxDistanceKm"
            mobile
        />
    </div>
</div>
