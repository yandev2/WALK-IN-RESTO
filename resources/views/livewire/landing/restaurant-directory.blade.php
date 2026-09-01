<div
    class="directory-shell flex min-h-screen flex-col bg-surface-base"
    x-on:request-directory-location.window="window.requestDirectoryLocation?.()"
>
    <x-directory.header :home="$home" />

    <x-directory.hero :home="$home" />

    <div class="landing-container relative z-30 -mt-10 flex-1 pb-16">
        <x-directory.search-bar
            :categories="$this->categories"
            :category-ids="$categoryIds"
            :location-status="$locationStatus"
            :user-accuracy-m="$userAccuracyM"
            :home="$home"
        />

        <div class="mb-6">
            <x-directory.map-panel map-id="directory-map-desktop" :location-status="$locationStatus" />
        </div>
        <script type="application/json" id="directory-map-pins">@json($mapPins)</script>
        <script type="application/json" id="directory-user-location">@json($locationStatus === 'granted' ? ['lat' => $userLat, 'lng' => $userLng] : null)</script>

        <x-directory.recommended-slider :cards="$recommendedCards" />

        <div class="grid gap-6 lg:grid-cols-[16rem_minmax(0,1fr)]">
            <x-directory.filter-panel
                :categories="$this->categories"
                :facility-options="$facilityOptions"
                :sort="$sort"
                :location-status="$locationStatus"
                :max-distance-km="$maxDistanceKm"
            />

            <section aria-label="Daftar Restoran" class="min-w-0">
                <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-bold text-body">Restoran ditemukan</h2>
                        <p class="text-sm text-muted">{{ $totalCount }} restoran</p>
                    </div>

                    <div class="hidden items-center gap-1 rounded-xl bg-surface-muted p-1 ring-1 ring-[color:var(--border-subtle)] sm:flex">
                        <button
                            type="button"
                            wire:click="setViewMode('list')"
                            @class([
                                'rounded-lg px-3 py-1.5 text-xs font-semibold transition',
                                'bg-surface-raised text-body shadow-[var(--card-shadow)]' => $viewMode === 'list',
                                'text-muted' => $viewMode !== 'list',
                            ])
                        >
                            List
                        </button>
                        <button
                            type="button"
                            wire:click="setViewMode('grid')"
                            @class([
                                'rounded-lg px-3 py-1.5 text-xs font-semibold transition',
                                'bg-surface-raised text-body shadow-[var(--card-shadow)]' => $viewMode === 'grid',
                                'text-muted' => $viewMode !== 'grid',
                            ])
                        >
                            Grid
                        </button>
                    </div>
                </div>

                @if ($cards === [])
                    <div class="customer-card p-10 text-center ring-1 ring-[color:var(--border-subtle)]">
                        <p class="text-lg font-semibold text-body">Tidak ada restoran yang cocok.</p>
                        <p class="mt-2 text-sm text-muted">Coba ubah kata kunci atau filter.</p>
                    </div>
                @else
                    <div
                        wire:key="directory-results-{{ $viewMode }}-{{ $sort }}-{{ $maxDistanceKm }}"
                        @class([
                            'grid items-start gap-4',
                            'grid-cols-1' => $viewMode === 'list',
                            'grid-cols-1 sm:grid-cols-2' => $viewMode === 'grid',
                        ])
                    >
                        @foreach ($cards as $card)
                            <x-directory.restaurant-card :card="$card" :variant="$viewMode" wire:key="directory-card-{{ $card['slug'] }}" />
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $paginator->links('vendor.pagination.customer') }}
                    </div>
                @endif
            </section>
        </div>
    </div>

    <x-directory.footer :home="$home" />

    <x-directory.mobile-filter-sheet
        :categories="$this->categories"
        :facility-options="$facilityOptions"
        :sort="$sort"
        :location-status="$locationStatus"
        :max-distance-km="$maxDistanceKm"
    />

    <div
        x-data="{ open: false }"
        x-on:open-directory-map.window="open = true; $nextTick(() => window.refreshDirectoryMap?.('directory-map-mobile', true))"
        x-on:keydown.escape.window="open = false"
        x-cloak
    >
        <div x-show="open" x-transition.opacity class="fixed inset-0 z-50 bg-black/50 lg:hidden" x-on:click="open = false"></div>
        <div
            x-show="open"
            x-transition
            class="fixed inset-x-3 top-10 bottom-10 z-50 overflow-hidden rounded-[1.5rem] bg-surface-base shadow-[var(--card-shadow-hover)] lg:hidden"
        >
            <div class="flex items-center justify-between border-b border-border-subtle px-4 py-3">
                <h2 class="text-base font-bold text-body">Peta restoran</h2>
                <button type="button" x-on:click="open = false" class="rounded-full bg-surface-muted px-3 py-1 text-sm font-semibold">Tutup</button>
            </div>
            <x-directory.map-panel map-id="directory-map-mobile" mobile :location-status="$locationStatus" />
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.readDirectoryMapPayload = window.readDirectoryMapPayload || function () {
        const pinsHost = document.getElementById('directory-map-pins');
        const userHost = document.getElementById('directory-user-location');
        let pins = [];
        let userLocation = null;

        if (pinsHost) {
            try {
                pins = JSON.parse(pinsHost.textContent || '[]');
            } catch (error) {
                pins = [];
            }
        }

        if (userHost) {
            try {
                userLocation = JSON.parse(userHost.textContent || 'null');
            } catch (error) {
                userLocation = null;
            }
        }

        return { pins, userLocation };
    };

    window.initDirectoryMap = window.initDirectoryMap || function (mapId, pins, options = {}) {
        if (! window.L || ! document.getElementById(mapId)) {
            return;
        }

        const host = document.getElementById(mapId);
        const force = options.force === true;
        const userLocation = options.userLocation ?? null;

        if (host.dataset.initialized === '1' && ! force) {
            return;
        }

        if (host._leafletMap) {
            host._leafletMap.remove();
            host._leafletMap = null;
            host._userMarker = null;
        }

        const validPins = (pins || []).filter((pin) => pin.lat && pin.lng);
        const hasUser = userLocation && userLocation.lat && userLocation.lng;
        const fallbackCenter = [-6.2088, 106.8456];
        const center = hasUser
            ? [userLocation.lat, userLocation.lng]
            : validPins.length
                ? [
                    validPins.reduce((sum, pin) => sum + pin.lat, 0) / validPins.length,
                    validPins.reduce((sum, pin) => sum + pin.lng, 0) / validPins.length,
                ]
                : fallbackCenter;

        const map = L.map(mapId, { scrollWheelZoom: false }).setView(center, hasUser ? 14 : (validPins.length > 1 ? 12 : 14));
        host._leafletMap = map;
        host.dataset.initialized = '1';

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap',
        }).addTo(map);

        validPins.forEach((pin) => {
            L.marker([pin.lat, pin.lng])
                .addTo(map)
                .bindPopup(`<strong>${pin.name}</strong><br><a href="${pin.url}">Lihat restoran</a>`);
        });

        if (hasUser) {
            host._userMarker = L.circleMarker([userLocation.lat, userLocation.lng], {
                radius: 8,
                color: '#2563eb',
                weight: 2,
                fillColor: '#3b82f6',
                fillOpacity: 0.95,
            })
                .addTo(map)
                .bindPopup('Lokasi Anda');

            const bounds = L.latLngBounds([[userLocation.lat, userLocation.lng]]);
            validPins.forEach((pin) => bounds.extend([pin.lat, pin.lng]));

            if (bounds.isValid()) {
                map.fitBounds(bounds, { padding: [28, 28], maxZoom: 15 });
            }
        }

        setTimeout(() => map.invalidateSize(), 200);
    };

    window.refreshDirectoryMap = window.refreshDirectoryMap || function (mapId, force = false) {
        const payload = window.readDirectoryMapPayload();
        window.initDirectoryMap(mapId, payload.pins, {
            userLocation: payload.userLocation,
            force,
        });
    };

    window.directoryLocationSkipKey = window.directoryLocationSkipKey || 'directory-skip-location';

    window.markDirectoryLocationSkipped = window.markDirectoryLocationSkipped || function () {
        try {
            localStorage.setItem(window.directoryLocationSkipKey, '1');
        } catch (error) {}
    };

    window.clearDirectoryLocationSkip = window.clearDirectoryLocationSkip || function () {
        try {
            localStorage.removeItem(window.directoryLocationSkipKey);
        } catch (error) {}
    };

    window.requestDirectoryLocation = window.requestDirectoryLocation || function () {
        const component = @this;
        window.clearDirectoryLocationSkip();

        if (! window.isSecureContext) {
            component.reportLocationInsecure();
            return;
        }

        if (! navigator.geolocation) {
            component.reportLocationUnsupported();
            return;
        }

        component.beginLocationRequest();

        navigator.geolocation.getCurrentPosition(
            (position) => {
                component.setUserLocation(
                    position.coords.latitude,
                    position.coords.longitude,
                    position.coords.accuracy,
                );
            },
            (error) => {
                if (error.code === error.PERMISSION_DENIED) {
                    component.clearUserLocation('denied');
                    return;
                }

                if (error.code === error.TIMEOUT) {
                    component.reportLocationError();
                    return;
                }

                component.reportLocationError();
            },
            { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 },
        );
    };

    window.bootstrapDirectoryLocation = window.bootstrapDirectoryLocation || function () {
        try {
            if (localStorage.getItem(window.directoryLocationSkipKey) === '1') {
                return;
            }
        } catch (error) {}

        if (! window.isSecureContext) {
            @this.reportLocationInsecure();
            return;
        }

        if (! navigator.geolocation || ! navigator.permissions?.query) {
            return;
        }

        navigator.permissions.query({ name: 'geolocation' }).then((result) => {
            if (result.state === 'granted') {
                window.requestDirectoryLocation();
            }
        }).catch(() => {});
    };

    document.addEventListener('DOMContentLoaded', () => {
        window.refreshDirectoryMap('directory-map-desktop');
        window.bootstrapDirectoryLocation();
    });

    document.addEventListener('livewire:init', () => {
        Livewire.hook('morph.updated', ({ component }) => {
            if (component.name !== 'landing.restaurant-directory') {
                return;
            }

            window.refreshDirectoryMap('directory-map-desktop', true);
        });
    });
</script>
@endpush
