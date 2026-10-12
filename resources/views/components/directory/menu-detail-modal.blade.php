<div
    x-data="{
        activeMenu: null,
        activePhotoIndex: 0,
        openMenuModal(item) {
            this.activeMenu = item;
            this.activePhotoIndex = 0;
        },
        closeMenuModal() {
            this.activeMenu = null;
        },
        menuPhotos() {
            if (! this.activeMenu) return [];
            if (Array.isArray(this.activeMenu.photos) && this.activeMenu.photos.length > 0) {
                return this.activeMenu.photos;
            }
            return this.activeMenu.photo_url ? [this.activeMenu.photo_url] : [];
        },
        prevPhoto() {
            const p = this.menuPhotos();
            if (p.length > 1) {
                this.activePhotoIndex = (this.activePhotoIndex - 1 + p.length) % p.length;
            }
        },
        nextPhoto() {
            const p = this.menuPhotos();
            if (p.length > 1) {
                this.activePhotoIndex = (this.activePhotoIndex + 1) % p.length;
            }
        }
    }"
    @open-menu-modal.window="openMenuModal($event.detail)"
>
    <template x-teleport="body">
        <div
            x-show="activeMenu !== null"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center bg-black/75 p-0 sm:p-4 backdrop-blur-xs"
            role="dialog"
            aria-modal="true"
            :aria-label="activeMenu ? activeMenu.name : 'Detail Menu'"
            @click.self="closeMenuModal()"
            @keydown.escape.window="activeMenu && closeMenuModal()"
        >
            <div
                x-show="activeMenu !== null"
                x-transition:enter="transition ease-out duration-250"
                x-transition:enter-start="opacity-0 translate-y-8 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-8 sm:scale-95"
                class="relative flex w-full min-h-[60vh] max-h-[85vh] sm:min-h-0 sm:max-h-[82vh] sm:max-w-lg flex-col overflow-hidden rounded-t-[1.75rem] sm:rounded-[1.75rem] bg-surface-raised ring-1 ring-[color:var(--border-subtle)] shadow-2xl"
            >
                {{-- Tombol Close / Cancel di Pojok Kanan Atas Modal --}}
                <button
                    type="button"
                    @click="closeMenuModal()"
                    class="absolute right-3.5 top-3.5 sm:right-4 sm:top-4 z-30 inline-flex h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-full bg-black/60 text-white backdrop-blur-md hover:bg-black/80 transition shadow-md cursor-pointer"
                    aria-label="Tutup modal"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <template x-if="activeMenu">
                    <div class="flex flex-col flex-1 min-h-0 overflow-hidden">
                        {{-- Wrapper Image dengan Gap / Padding dari Card Modal --}}
                        <div class="p-3.5 sm:p-4 pb-1 sm:pb-1 shrink-0">
                            {{-- Container Image Preview dengan Sudut Membulat --}}
                            <div class="relative h-44 sm:h-52 w-full overflow-hidden rounded-2xl bg-surface-muted select-none shadow-xs ring-1 ring-border-subtle/60">
                                {{-- Foto Utama Aktif --}}
                                <template x-if="menuPhotos().length > 0">
                                    <img
                                        :src="menuPhotos()[activePhotoIndex] || activeMenu.photo_url"
                                        :alt="activeMenu.name"
                                        class="h-full w-full object-cover transition duration-300"
                                    >
                                </template>

                                <template x-if="menuPhotos().length === 0">
                                    <div class="flex h-full w-full items-center justify-center bg-surface-muted text-muted/30">
                                        <svg class="h-12 w-12" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                </template>

                                {{-- Navigasi Foto Prev / Next jika ada lebih dari 1 foto --}}
                                <template x-if="menuPhotos().length > 1">
                                    <div class="absolute inset-x-2 top-1/2 -translate-y-1/2 flex items-center justify-between pointer-events-none z-10">
                                        <button
                                            type="button"
                                            @click.stop="prevPhoto()"
                                            class="pointer-events-auto inline-flex h-8 w-8 items-center justify-center rounded-full bg-black/60 text-white backdrop-blur-md hover:bg-black/80 transition shadow-sm cursor-pointer"
                                            aria-label="Foto sebelumnya"
                                        >
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                            </svg>
                                        </button>
                                        <button
                                            type="button"
                                            @click.stop="nextPhoto()"
                                            class="pointer-events-auto inline-flex h-8 w-8 items-center justify-center rounded-full bg-black/60 text-white backdrop-blur-md hover:bg-black/80 transition shadow-sm cursor-pointer"
                                            aria-label="Foto berikutnya"
                                        >
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </button>
                                    </div>
                                </template>

                                {{-- Badges di Atas Foto --}}
                                <div class="absolute top-2.5 left-2.5 flex flex-col gap-1 items-start z-10">
                                    <template x-if="activeMenu.is_best_seller">
                                        <span class="rounded-lg bg-amber-500/95 backdrop-blur-xs px-2.5 py-0.5 text-[11px] font-bold text-white shadow-xs">
                                            ★ Best Seller
                                        </span>
                                    </template>
                                    <template x-if="!activeMenu.hide_price && activeMenu.discount_percent && Number(activeMenu.discount_percent) > 0">
                                        <span class="rounded-lg bg-rose-500/95 backdrop-blur-xs px-2.5 py-0.5 text-[11px] font-bold text-white shadow-xs" x-text="'-' + activeMenu.discount_percent + '%'"></span>
                                    </template>
                                </div>

                                {{-- Dots Indicator di Bawah Foto Utama --}}
                                <template x-if="menuPhotos().length > 1">
                                    <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex items-center gap-1.5 z-10 pointer-events-auto">
                                        <template x-for="(dot, dIdx) in menuPhotos()" :key="dIdx">
                                            <button
                                                type="button"
                                                @click.stop="activePhotoIndex = dIdx"
                                                class="h-1.5 rounded-full transition-all duration-200 cursor-pointer"
                                                :class="activePhotoIndex === dIdx ? 'w-4 bg-white shadow-xs' : 'w-1.5 bg-white/50 hover:bg-white/80'"
                                                :aria-label="'Foto ke-' + (dIdx + 1)"
                                            ></button>
                                        </template>
                                    </div>
                                </template>

                                {{-- Distance & Photo Count Badge --}}
                                <div class="absolute bottom-2 inset-x-2.5 flex items-center justify-between pointer-events-none z-10">
                                    <div>
                                        <template x-if="activeMenu.distance_label">
                                            <span class="rounded-md bg-black/60 backdrop-blur-md px-2 py-0.5 text-[10px] font-semibold text-white shadow-xs" x-text="'📍 ' + activeMenu.distance_label"></span>
                                        </template>
                                    </div>
                                    <template x-if="menuPhotos().length > 1">
                                        <span class="rounded-md bg-black/60 backdrop-blur-md px-2 py-0.5 text-[10px] font-semibold text-white shadow-xs" x-text="(activePhotoIndex + 1) + ' / ' + menuPhotos().length"></span>
                                    </template>
                                </div>
                            </div>

                            {{-- Strip Galeri Thumbnail Semua Foto (jika > 1 foto) --}}
                            <template x-if="menuPhotos().length > 1">
                                <div class="mt-2 flex items-center gap-2 overflow-x-auto no-scrollbar py-0.5">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-muted shrink-0" x-text="'Foto (' + menuPhotos().length + '):'"></span>
                                    <template x-for="(photoUrl, pIdx) in menuPhotos()" :key="pIdx">
                                        <button
                                            type="button"
                                            @click="activePhotoIndex = pIdx"
                                            class="relative shrink-0 h-10 w-10 sm:h-11 sm:w-11 rounded-xl overflow-hidden border-2 transition cursor-pointer"
                                            :class="activePhotoIndex === pIdx ? 'border-primary ring-2 ring-primary/30 shadow-xs scale-105' : 'border-transparent opacity-60 hover:opacity-100'"
                                            :aria-label="'Pilih foto ' + (pIdx + 1)"
                                        >
                                            <img :src="photoUrl" :alt="activeMenu.name" class="h-full w-full object-cover">
                                        </button>
                                    </template>
                                </div>
                            </template>
                        </div>

                        {{-- Konten Informasi Menu --}}
                        <div class="flex-1 overflow-y-auto px-4 sm:px-5 py-2.5 sm:py-3 space-y-2.5 sm:space-y-3">
                            <div>
                                {{-- Info Restoran & Kategori --}}
                                <div class="flex flex-wrap items-center gap-1.5 text-xs text-muted mb-0.5">
                                    <span class="font-semibold text-body truncate max-w-[220px]" x-text="activeMenu.restaurant_name"></span>
                                    <template x-if="activeMenu.restaurant_categories">
                                        <span class="text-muted/70 truncate" x-text="'· ' + activeMenu.restaurant_categories"></span>
                                    </template>
                                </div>

                                {{-- Nama Menu --}}
                                <h2 class="font-display text-base sm:text-lg font-bold text-body leading-snug" x-text="activeMenu.name"></h2>
                            </div>

                            {{-- Harga Menu --}}
                            <div>
                                <template x-if="activeMenu.hide_price">
                                    <div class="inline-flex items-center gap-1.5 rounded-lg bg-primary/10 px-3 py-1 text-xs font-semibold text-primary">
                                        <span>Harga: Kunjungi restoran</span>
                                    </div>
                                </template>

                                <template x-if="!activeMenu.hide_price">
                                    <div class="flex items-baseline gap-2">
                                        <template x-if="activeMenu.has_discount">
                                            <div class="flex items-baseline gap-2">
                                                <span class="text-xs sm:text-sm text-muted line-through" x-text="activeMenu.price_formatted"></span>
                                                <span class="text-base sm:text-lg font-bold text-primary font-display" x-text="activeMenu.effective_price_formatted"></span>
                                            </div>
                                        </template>
                                        <template x-if="!activeMenu.has_discount">
                                            <span class="text-base sm:text-lg font-bold text-primary font-display" x-text="activeMenu.price_formatted"></span>
                                        </template>
                                    </div>
                                </template>
                            </div>

                            {{-- Deskripsi Lengkap --}}
                            <div class="border-t border-border-subtle/70 pt-2.5">
                                <h3 class="text-[11px] font-bold uppercase tracking-wider text-muted mb-1">Deskripsi Menu</h3>
                                <div class="text-xs sm:text-sm text-body leading-relaxed">
                                    <p x-show="activeMenu.description && activeMenu.description.trim() !== ''" class="whitespace-pre-line" x-text="activeMenu.description"></p>
                                    <p x-show="!activeMenu.description || activeMenu.description.trim() === ''" class="italic text-muted">Tidak ada deskripsi detail untuk menu ini.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Button CTA: Mengarah ke Restoran --}}
                        <div class="border-t border-border-subtle bg-surface-muted/30 p-3 sm:p-3.5 shrink-0">
                            <template x-if="activeMenu.landing_url">
                                <a
                                    :href="activeMenu.landing_url"
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-5 py-2.5 sm:py-3 text-xs sm:text-sm font-bold text-white shadow-md hover:bg-primary-dark transition cursor-pointer"
                                >
                                    <span x-text="activeMenu.cta_label || 'Kunjungi Restoran'"></span>
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </a>
                            </template>
                            <template x-if="!activeMenu.landing_url">
                                <button
                                    type="button"
                                    @click="closeMenuModal()"
                                    class="inline-flex w-full items-center justify-center rounded-xl bg-surface-muted px-5 py-2.5 text-xs font-bold text-body hover:bg-surface-base transition cursor-pointer"
                                >
                                    Tutup
                                </button>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </template>
</div>
