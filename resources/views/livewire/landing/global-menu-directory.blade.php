<div
    class="directory-shell flex min-h-screen flex-col bg-surface-base"
    x-data="{
        requestLocation() {
            if (! navigator.geolocation) {
                $wire.reportLocationUnsupported();
                return;
            }
            $wire.beginLocationRequest();
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    $wire.setUserLocation(pos.coords.latitude, pos.coords.longitude, pos.coords.accuracy);
                },
                (err) => {
                    if (err.code === err.PERMISSION_DENIED) {
                        $wire.clearUserLocation('denied');
                    } else {
                        $wire.reportLocationError();
                    }
                },
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
            );
        }
    }"
>
    <x-directory.header :home="$home" />

    {{-- Breadcrumb & Title Hero Banner --}}
    <section class="border-b border-border-subtle/60 bg-gradient-to-b from-surface-muted/40 via-surface-base to-surface-base py-8 sm:py-10">
        <div class="landing-container">
            <nav aria-label="Breadcrumb" class="mb-3 flex items-center gap-2 text-xs text-muted">
                <a href="{{ route('home') }}" class="hover:text-primary transition">Beranda</a>
                <span>/</span>
                <span class="font-semibold text-body">Daftar Menu</span>
            </nav>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-primary/10 px-3 py-0.5 text-[11px] font-bold uppercase tracking-wider text-primary">
                        Katalog Menu Kuliner
                    </span>
                    <h1 class="mt-2 font-display text-2xl font-extrabold text-body sm:text-3xl md:text-4xl">
                        Jelajahi Menu Makanan & Minuman
                    </h1>
                    <p class="mt-2 max-w-2xl text-xs sm:text-sm text-muted leading-relaxed">
                        Temukan berbagai hidangan lezat dari seluruh restoran mitra. Klik hidangan favorit untuk melihat informasi restoran dan berkunjung langsung.
                    </p>
                </div>

                @if ($hasActiveFilters)
                    <button
                        type="button"
                        wire:click="resetFilters"
                        class="self-start sm:self-auto inline-flex items-center gap-1.5 rounded-lg bg-surface-raised px-3.5 py-1.5 text-xs font-semibold text-muted ring-1 ring-[color:var(--border-subtle)] hover:bg-surface-muted hover:text-body transition"
                    >
                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>Reset Filter</span>
                    </button>
                @endif
            </div>
        </div>
    </section>

    <main class="landing-container flex-1 py-8">
        {{-- Search & Controls Toolbar --}}
        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            {{-- Search Bar --}}
            <div class="relative flex-1">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input
                    type="search"
                    wire:model.live.debounce.350ms="search"
                    placeholder="Cari nama menu (misal: Nasi Goreng, Kopi Latte) atau nama resto..."
                    class="w-full rounded-2xl border-0 bg-surface-raised py-2.5 pl-10 pr-10 text-xs sm:text-sm text-body placeholder:text-muted/60 ring-1 ring-[color:var(--border-subtle)] shadow-xs focus:ring-2 focus:ring-primary focus:outline-hidden transition"
                >
                @if (filled($search))
                    <button
                        type="button"
                        wire:click="$set('search', '')"
                        class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-muted hover:text-body transition"
                        title="Hapus pencarian"
                    >
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                @endif
            </div>

            {{-- Actions: Location & Sort --}}
            <div class="flex flex-wrap items-center gap-2.5">
                {{-- Geolocation Toggle --}}
                @if ($locationStatus === 'granted')
                    <div class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-500/10 px-3 py-1.5 text-xs font-semibold text-emerald-700 dark:text-emerald-400 ring-1 ring-emerald-500/20">
                        <span class="h-2 w-2 rounded-lg bg-emerald-500 animate-pulse"></span>
                        <span>Lokasi Aktif</span>
                        <button
                            type="button"
                            wire:click="disableLocationSearch"
                            class="ml-1 text-emerald-800 dark:text-emerald-300 hover:text-rose-500 transition"
                            title="Matikan lokasi"
                        >
                            &times;
                        </button>
                    </div>

                    {{-- Max Distance Radius Dropdown --}}
                    <div class="min-w-[8.5rem]">
                        <x-customer.dropdown
                            class="rounded-lg bg-surface-raised ring-1 ring-[color:var(--border-subtle)] shadow-xs"
                            size="compact"
                            method="setMaxDistance"
                            :value="$maxDistanceKm"
                            :label="$currentDistanceLabel"
                            :options="$this->distanceOptions"
                            align="right"
                        />
                    </div>
                @else
                    <button
                        type="button"
                        x-on:click="requestLocation()"
                        @class([
                            'inline-flex items-center gap-1.5 rounded-lg px-3.5 py-1.5 text-xs font-semibold shadow-xs transition',
                            'bg-primary text-white hover:bg-primary-dark' => $locationStatus === 'idle',
                            'bg-surface-raised text-muted ring-1 ring-[color:var(--border-subtle)]' => $locationStatus !== 'idle',
                        ])
                    >
                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ $locationStatus === 'pending' ? 'Mendeteksi...' : 'Terdekat dari Saya' }}</span>
                    </button>
                @endif

                {{-- Sort Dropdown --}}
                <div class="flex items-center gap-1.5">
                    <span class="text-xs text-muted font-medium">Urutkan:</span>
                    <div class="min-w-[9.5rem]">
                        <x-customer.dropdown
                            class="rounded-lg bg-surface-raised ring-1 ring-[color:var(--border-subtle)] shadow-xs"
                            size="compact"
                            method="setSort"
                            :value="$sort"
                            :label="$currentSortLabel"
                            :options="$this->sortOptions"
                            align="right"
                        />
                    </div>
                </div>
            </div>
        </div>

        {{-- Category Pills Bar --}}
        <x-directory.category-bar
            :categories="$categories"
            :category-ids="$categoryIds"
        />

        {{-- Results Summary --}}
        <div class="mb-4 flex items-center justify-between text-xs text-muted">
            <p>
                Menampilkan <strong class="text-body">{{ $totalCount }}</strong> hidangan
                @if (filled($search))
                    untuk "<span class="text-body font-semibold">{{ $search }}</span>"
                @endif
            </p>
        </div>

        {{-- Menu Items Grid --}}
        @if (! empty($items))
            <div class="grid grid-cols-2 gap-3 sm:gap-4 md:grid-cols-3 lg:grid-cols-4">
                @foreach ($items as $item)
                    @php
                        $targetUrl = $item['landing_url'] ?: '#';
                    @endphp
                    <a
                        href="{{ $targetUrl }}"
                        class="group flex flex-col justify-between overflow-hidden rounded-2xl bg-surface-raised p-2.5 sm:p-3 ring-1 ring-[color:var(--border-subtle)] shadow-xs hover:shadow-lg hover:ring-primary/40 transition duration-200"
                        title="Kunjungi {{ $item['restaurant_name'] ?? 'Restoran' }}"
                        wire:key="global-dish-{{ $item['id'] }}"
                    >
                        <div class="relative aspect-square overflow-hidden rounded-xl bg-surface-muted">
                            @if (! empty($item['photo_url']))
                                <img
                                    src="{{ $item['photo_url'] }}"
                                    alt="{{ $item['name'] }}"
                                    class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                    loading="lazy"
                                    onerror="this.onerror=null;this.classList.add('hidden');if(this.nextElementSibling){this.nextElementSibling.classList.remove('hidden');}"
                                >
                                <div class="hidden flex h-full w-full items-center justify-center bg-surface-muted text-muted/30">
                                    <svg class="h-10 w-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </div>
                            @else
                                <div class="flex h-full w-full items-center justify-center text-muted/30">
                                    <svg class="h-10 w-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </div>
                            @endif

                            {{-- Badges --}}
                            <div class="absolute top-2 left-2 flex flex-col gap-1 items-start">
                                @if (! empty($item['is_best_seller']))
                                    <span class="rounded-lg bg-amber-500 px-2 py-0.5 text-[10px] font-bold text-white shadow-xs">
                                        ★ Best Seller
                                    </span>
                                @endif
                                @if (! empty($item['discount_percent']) && (int) $item['discount_percent'] > 0)
                                    <span class="rounded-lg bg-rose-500 px-2 py-0.5 text-[10px] font-bold text-white shadow-xs">
                                        -{{ $item['discount_percent'] }}%
                                    </span>
                                @endif
                            </div>

                            @if (! empty($item['distance_label']))
                                <span class="absolute bottom-2 left-2 rounded-md bg-surface-base/90 backdrop-blur-xs px-1.5 py-0.5 text-[10px] font-semibold text-body ring-1 ring-border-subtle shadow-xs">
                                    📍 {{ $item['distance_label'] }}
                                </span>
                            @endif
                        </div>

                        <div class="mt-2.5 flex flex-1 flex-col justify-between">
                            <div>
                                @if (! empty($item['restaurant_name']))
                                    <div class="flex items-center justify-between gap-1">
                                        <p class="truncate text-[11px] font-medium text-muted">{{ $item['restaurant_name'] }}</p>
                                        @if (! empty($item['restaurant_categories']))
                                            <span class="shrink-0 truncate text-[10px] text-muted/70">· {{ $item['restaurant_categories'] }}</span>
                                        @endif
                                    </div>
                                @endif
                                <h2 class="mt-0.5 truncate font-display text-xs sm:text-sm font-bold text-body group-hover:text-primary transition-colors">
                                    {{ $item['name'] }}
                                </h2>
                                @if (! empty($item['description']))
                                    <p class="mt-1 line-clamp-2 text-[11px] text-muted leading-relaxed hidden sm:block">
                                        {{ $item['description'] }}
                                    </p>
                                @endif
                            </div>

                            <div class="mt-2.5 flex items-center justify-between border-t border-border-subtle/50 pt-2">
                                <div>
                                    @if (! empty($item['discount_percent']) && (int) $item['discount_percent'] > 0)
                                        <span class="text-[11px] text-muted line-through mr-1">{{ $item['price_formatted'] }}</span>
                                        <span class="text-xs sm:text-sm font-bold text-primary">{{ $item['effective_price_formatted'] }}</span>
                                    @else
                                        <span class="text-xs sm:text-sm font-bold text-primary">{{ $item['price_formatted'] }}</span>
                                    @endif
                                </div>

                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-muted group-hover:text-primary transition">
                                    <span>Resto</span>
                                    <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $paginator->links('vendor.pagination.customer') }}
            </div>
        @else
            <div class="rounded-2xl border border-dashed border-border-subtle bg-surface-raised/60 p-10 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-lg bg-primary/10 text-primary">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="mt-4 font-display text-base font-bold text-body">Tidak ada menu yang sesuai</h3>
                <p class="mt-1 text-xs sm:text-sm text-muted">Coba ubah kata kunci pencarian, pilih kategori lain, atau reset filter jarak.</p>
                <button
                    type="button"
                    wire:click="resetFilters"
                    class="mt-4 inline-flex items-center gap-2 rounded-lg bg-primary px-5 py-2 text-xs font-bold text-white shadow-xs hover:bg-primary-dark transition"
                >
                    Reset Filter
                </button>
            </div>
        @endif
    </main>

    <x-directory.footer :home="$home" />
</div>
