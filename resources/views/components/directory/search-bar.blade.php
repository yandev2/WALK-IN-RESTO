@props([
    'categories',
    'categoryIds' => [],
    'locationStatus' => 'idle',
    'userAccuracyM' => null,
    'home' => [],
])

@php
    $home = $home !== [] ? $home : \App\Models\PlatformSetting::homeViewData();
    $locationCta = $home['location_cta_label'] ?? 'Izinkan lokasi';
    $categoryIds = collect($categoryIds)->map(fn ($id) => (int) $id)->values()->all();
    $selectedCategoryId = count($categoryIds) === 1 ? $categoryIds[0] : null;
    $categoryLabel = match (count($categoryIds)) {
        0 => 'Semua Kategori',
        1 => $categories->firstWhere('id', $selectedCategoryId)?->name ?? 'Kategori',
        default => count($categoryIds).' kategori',
    };
    $categoryOptions = collect([['value' => null, 'label' => 'Semua Kategori']])
        ->concat($categories->map(fn ($category) => ['value' => $category->id, 'label' => $category->name]))
        ->all();
    $locationLabel = match ($locationStatus) {
        'granted' => filled($userAccuracyM) && $userAccuracyM > 500
            ? 'Lokasi aktif (perkiraan)'
            : 'Lokasi aktif',
        'pending' => 'Meminta izin lokasi...',
        'denied' => 'Izin lokasi ditolak',
        'unsupported' => 'Lokasi tidak didukung',
        'insecure' => 'Butuh HTTPS / localhost',
        'error' => 'Gagal mendeteksi lokasi',
        default => $locationCta,
    };
@endphp

<div id="directory-search" class="directory-search relative z-30 w-full pb-6">
    <div class="customer-card w-full overflow-visible p-0 ring-1 ring-[color:var(--border-subtle)]">
        @if ($locationStatus === 'idle')
            <div class="flex flex-wrap items-center justify-between gap-3 rounded-t-[1.5rem] border-b border-border-subtle bg-surface-muted px-4 py-3 text-sm">
                <div class="flex min-w-0 items-start gap-2 text-body">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Tampilkan restoran terdekat dari lokasi Anda. Klik tombol di samping — browser akan meminta izin.</span>
                </div>
                <button
                    type="button"
                    x-on:click="$dispatch('request-directory-location')"
                    class="landing-btn-glow shrink-0 rounded-full bg-primary px-4 py-2 text-xs font-bold text-white"
                >
                    {{ $locationCta }}
                </button>
            </div>
        @elseif ($locationStatus === 'pending')
            <div class="flex items-center gap-2 rounded-t-[1.5rem] border-b border-border-subtle bg-surface-muted px-4 py-3 text-sm text-muted">
                <svg class="h-4 w-4 shrink-0 animate-pulse text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Meminta izin lokasi — cek popup browser Anda.</span>
            </div>
        @elseif (in_array($locationStatus, ['denied', 'unsupported', 'insecure', 'error'], true))
            <div class="flex flex-wrap items-center justify-between gap-3 rounded-t-[1.5rem] border-b border-border-subtle bg-surface-muted px-4 py-3 text-sm">
                <span class="text-muted">
                    @if ($locationStatus === 'denied')
                        Izin lokasi ditolak — menampilkan urutan default. Klik Coba lagi lalu Allow di popup browser.
                    @elseif ($locationStatus === 'insecure')
                        Lokasi hanya tersedia di HTTPS atau <strong>http://localhost</strong>. Akses via IP LAN (HTTP) tidak didukung browser.
                    @elseif ($locationStatus === 'unsupported')
                        Browser Anda tidak mendukung deteksi lokasi.
                    @else
                        Gagal mendeteksi lokasi. Coba lagi.
                    @endif
                </span>
                @if ($locationStatus !== 'insecure')
                    <button
                        type="button"
                        x-on:click="$dispatch('request-directory-location')"
                        class="rounded-full bg-surface-raised px-3 py-1.5 text-xs font-semibold text-primary ring-1 ring-[color:var(--border-subtle)]"
                    >
                        Coba lagi
                    </button>
                @endif
            </div>
        @elseif ($locationStatus === 'granted')
            <div class="flex flex-wrap items-center justify-between gap-3 rounded-t-[1.5rem] border-b border-border-subtle bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                <div class="flex min-w-0 items-center gap-2">
                    <svg class="h-4 w-4 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                    </svg>
                    <span>Menampilkan restoran terdekat dari lokasi Anda.</span>
                </div>
                <button
                    type="button"
                    wire:click="disableLocationSearch"
                    x-on:click="window.markDirectoryLocationSkipped?.()"
                    class="shrink-0 rounded-full bg-white/80 px-3 py-1.5 text-xs font-semibold text-emerald-800 ring-1 ring-emerald-200 hover:bg-white dark:bg-emerald-900/80 dark:text-emerald-200 dark:ring-emerald-800"
                >
                    Matikan lokasi
                </button>
            </div>
        @endif

        <div class="grid gap-2 overflow-visible p-2 lg:grid-cols-[minmax(0,1.6fr)_minmax(0,1fr)_12rem_8rem]">
        <label class="relative min-w-0">
            <span class="sr-only">Cari restoran</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/>
            </svg>
            <input
                type="search"
                wire:model.live.debounce.350ms="search"
                placeholder="{{ $home['search_placeholder'] }}"
                class="h-12 w-full rounded-2xl border-0 bg-transparent py-3 pl-11 pr-4 text-sm text-body placeholder:text-muted focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/30"
            >
        </label>

        <div @class([
            'hidden items-center gap-2 rounded-2xl px-4 text-sm lg:flex',
            'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400' => $locationStatus === 'granted',
            'bg-surface-muted text-muted' => $locationStatus !== 'granted',
        ])>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M9.384 3.576a1 1 0 011.232 0l6.5 4.75A1 1 0 0117 9.25v6.5a1 1 0 01-1.384.926L10 15.382l-5.616 1.294A1 1 0 013 15.75v-6.5a1 1 0 01.384-.924l6.5-4.75z" clip-rule="evenodd" />
            </svg>
            @if (in_array($locationStatus, ['idle', 'pending'], true))
                <button
                    type="button"
                    x-on:click="$dispatch('request-directory-location')"
                    class="truncate font-semibold text-primary hover:underline"
                >
                    {{ $locationLabel }}
                </button>
            @elseif ($locationStatus === 'granted')
                <button
                    type="button"
                    wire:click="disableLocationSearch"
                    x-on:click="window.markDirectoryLocationSkipped?.()"
                    class="truncate font-semibold hover:underline"
                    title="Matikan pencarian berdasarkan lokasi"
                >
                    {{ $locationLabel }}
                </button>
            @else
                <span class="truncate">{{ $locationLabel }}</span>
            @endif
        </div>

        <div class="hidden min-w-0 lg:block">
            <span class="sr-only">Kategori</span>
            <x-customer.dropdown
                class="rounded-2xl bg-surface-muted"
                method="setCategoryFilter"
                :value="$selectedCategoryId"
                :label="$categoryLabel"
                :options="$categoryOptions"
            />
        </div>

        <button
            type="button"
            wire:click="applySearch"
            class="landing-btn-glow hidden h-12 rounded-2xl bg-primary px-4 text-sm font-bold text-white transition hover:bg-primary-dark lg:inline-flex lg:items-center lg:justify-center"
        >
            {{ $home['search_button_label'] }}
        </button>
        </div>
    </div>

    <div class="mt-3 flex flex-wrap gap-2 lg:hidden">
        <button
            type="button"
            @if ($locationStatus === 'granted')
                wire:click="disableLocationSearch"
                x-on:click="window.markDirectoryLocationSkipped?.()"
            @else
                x-on:click="$dispatch('request-directory-location')"
            @endif
            @class([
                'inline-flex items-center gap-2 rounded-full px-4 py-2 text-xs font-semibold ring-1 ring-[color:var(--border-subtle)]',
                'bg-emerald-500/10 text-emerald-700 dark:text-emerald-400' => $locationStatus === 'granted',
                'bg-primary text-white ring-primary' => in_array($locationStatus, ['idle', 'pending'], true),
                'bg-surface-raised text-body' => ! in_array($locationStatus, ['granted', 'idle', 'pending'], true),
            ])
        >
            {{ $locationStatus === 'granted' ? 'Matikan lokasi' : 'Lokasi Saya' }}
        </button>
        <div class="min-w-[9.5rem]">
            <x-customer.dropdown
                class="rounded-full bg-surface-raised ring-1 ring-[color:var(--border-subtle)]"
                size="compact"
                method="setCategoryFilter"
                :value="$selectedCategoryId"
                :label="$categoryLabel"
                :options="$categoryOptions"
            />
        </div>
        <button
            type="button"
            x-on:click="$dispatch('open-directory-filters')"
            class="inline-flex items-center gap-2 rounded-full bg-surface-raised px-4 py-2 text-xs font-semibold text-body ring-1 ring-[color:var(--border-subtle)]"
        >
            Filter
        </button>
        <button
            type="button"
            x-on:click="$dispatch('open-directory-map')"
            class="inline-flex items-center gap-2 rounded-full bg-surface-raised px-4 py-2 text-xs font-semibold text-primary ring-1 ring-[color:var(--border-subtle)] lg:hidden"
        >
            Lihat di Peta
        </button>
    </div>
</div>
