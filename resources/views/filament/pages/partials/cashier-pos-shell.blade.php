@php
    $catalog = $catalog ?? [];
    $categories = $categories ?? [];
    $plainQtyByItem = $plainQtyByItem ?? [];
    $paymentMethod = $paymentMethod ?? 'cash';
    $preview = $preview ?? ['subtotal' => 0];
    $tables = $tables ?? [];
    $selectedTableId = (string) ($tableId ?? '');
    $selectedTableLabel = 'Pilih meja';
    foreach ($tables as $id => $code) {
        if ((string) $id === $selectedTableId) {
            $selectedTableLabel = (string) $code;
            break;
        }
    }
    $boot = [
        'plainQtyByItem' => $plainQtyByItem,
        'cartLines' => $cartLines ?? [],
        'preview' => $preview,
        'paymentMethod' => $paymentMethod,
        'catalog' => $catalog,
        'qrisImageUrl' => $qrisImageUrl ?? null,
        'hasFonnte' => (bool) ($hasFonnte ?? false),
        'sendReceipt' => (bool) ($sendReceipt ?? false),
        'customerWa' => (string) ($customerWa ?? ''),
        'customerName' => (string) ($customerName ?? ''),
        'tableId' => $selectedTableId,
        'tableLabel' => $selectedTableLabel,
        'cashReceived' => $cashReceived ?? null,
        'activeShift' => $activeShift ?? null,
        'canViewDrawerCash' => (bool) ($canViewDrawerCash ?? false),
        'pointsToRedeem' => (int) ($pointsToRedeem ?? 0),
        'loyaltySettings' => $loyaltySettings ?? [],
    ];
@endphp

<div class="cashier-pos-app" x-data="(() => {
    const boot = {{ \Illuminate\Support\Js::from($boot) }};
    if (!Alpine.store('cashierPos')) {
        Alpine.store('cashierPos', {
            catalog: [],
            catalogById: {},
            paymentMethod: 'cash',
            plainQtyByItem: {},
            cartLines: [],
            preview: { subtotal: 0 },
            qrisImageUrl: null,
            hasFonnte: false,
            sendReceipt: false,
            customerWa: '',
            customerName: '',
            tableId: '',
            tableLabel: 'Pilih meja',
            cashRaw: '',
            activeShift: null,
            canViewDrawerCash: false,
            pointsToRedeem: 0,
            loyaltySettings: {},
            customerInfo: null,
            editor: null,
            indexCatalog() {
                const map = {};
                for (const item of this.catalog) {
                    map[item.id] = item;
                }
                this.catalogById = map;
            },
            hydrate(next) {
                next = next || {};
                this.catalog = next.catalog || [];
                this.indexCatalog();
                this.paymentMethod = next.paymentMethod || 'cash';
                this.plainQtyByItem = next.plainQtyByItem || {};
                this.cartLines = next.cartLines || [];
                this.preview = next.preview || { subtotal: 0 };
                this.qrisImageUrl = next.qrisImageUrl || null;
                this.hasFonnte = !!next.hasFonnte;
                this.sendReceipt = !!next.sendReceipt;
                this.customerWa = next.customerWa || '';
                this.customerName = next.customerName || '';
                this.tableId = next.tableId || '';
                this.tableLabel = next.tableLabel || 'Pilih meja';
                this.cashRaw = next.cashReceived == null ? '' : String(next.cashReceived);
                this.activeShift = next.activeShift || null;
                this.canViewDrawerCash = !!next.canViewDrawerCash;
                this.pointsToRedeem = next.pointsToRedeem || 0;
                this.loyaltySettings = next.loyaltySettings || {};
                this.customerInfo = null;
                this.editor = null;
            },
            reset() {
                this.tableId = '';
                this.tableLabel = 'Pilih meja';
                this.customerName = '';
                this.customerWa = '';
                this.sendReceipt = false;
                this.paymentMethod = 'cash';
                this.cashRaw = '';
                this.pointsToRedeem = 0;
                this.customerInfo = null;
                this.plainQtyByItem = {};
                this.cartLines = [];
                this.preview = { subtotal: 0 };
                this.editor = null;
            },
            cashParsed() {
                const digits = String(this.cashRaw).replace(/\D+/g, '');

                return digits === '' ? null : Number(digits);
            },
            cashChange() {
                const received = this.cashParsed();

                return received === null ? 0 : received - Number(this.preview.grand_payable || 0);
            },
            cashShort() {
                return this.cashParsed() !== null && this.cashChange() < 0;
            },
            cashFormat(amount) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.trunc(amount));
            },
            apply(state) {
                if (!state) {
                    return;
                }
                this.plainQtyByItem = state.plainQtyByItem || {};
                this.cartLines = state.cartLines || [];
                this.preview = state.preview || this.preview;
                if (state.paymentMethod) {
                    this.paymentMethod = state.paymentMethod;
                }
            },
            qtyOf(id) {
                return Number(this.plainQtyByItem[id] ?? this.plainQtyByItem[String(id)] ?? 0);
            },
            openExtra(id) {
                const item = this.catalogById[id] || this.catalog.find((row) => Number(row.id) === Number(id));
                if (!item) {
                    return;
                }
                const defaultVariant = (item.variants && item.variants.length > 0) ? item.variants[0].id : null;
                this.editor = {
                    type: 'new',
                    index: null,
                    menu_item_id: item.id,
                    name: item.name,
                    variants: item.variants || [],
                    variant_id: defaultVariant,
                    modifiers: item.modifiers || [],
                    modifier_ids: [],
                    notes: '',
                };
            },
            openLine(line) {
                const item = this.catalogById[line.menu_item_id] ||
                    this.catalog.find((row) => Number(row.id) === Number(line.menu_item_id));
                const itemVariants = item ? (item.variants || []) : [];
                const defaultVariant = (itemVariants.length > 0) ? itemVariants[0].id : null;
                this.editor = {
                    type: 'edit',
                    index: line.index,
                    menu_item_id: line.menu_item_id,
                    name: line.name,
                    variants: itemVariants,
                    variant_id: line.variant_id ?? defaultVariant,
                    modifiers: item ? (item.modifiers || []) : [],
                    modifier_ids: (line.modifier_ids || []).map(Number),
                    notes: line.notes || '',
                };
            },
            isModOn(id) {
                return (this.editor?.modifier_ids || []).map(Number).includes(Number(id));
            },
            toggleMod(id) {
                if (!this.editor) {
                    return;
                }
                const value = Number(id);
                const ids = (this.editor.modifier_ids || []).map(Number);
                this.editor.modifier_ids = ids.includes(value) ?
                    ids.filter((entry) => entry !== value) :
                    ids.concat(value);
            },
            closeEditor() {
                this.editor = null;
            },
        });
    }
    Alpine.store('cashierPos').hydrate(boot);
    return {};
})()" @cashier-reset-form.window="$store.cashierPos.reset()" @keydown.escape.window="$store.cashierPos.editor = null">
    <div class="cashier-pos">
        <section class="cashier-pos-catalog" wire:ignore x-data="{
            query: '',
            category: 'all',
            itemVisible(name, categoryId) {
                const q = this.query.trim().toLowerCase();
                if (q && !String(name).toLowerCase().includes(q)) {
                    return false;
                }
                if (this.category === 'all') {
                    return true;
                }
                if (this.category === 'lainnya') {
                    return !categoryId;
                }
                return String(categoryId) === String(this.category);
            },
            async addPlain(id) {
                Alpine.store('cashierPos').apply(await this.$wire.addPosItem(id));
            },
            openExtra(id) {
                Alpine.store('cashierPos').openExtra(id);
            },
            async bumpItem(id, delta) {
                Alpine.store('cashierPos').apply(await this.$wire.changePosItemQty(id, delta));
            },
        }">
            <div class="cashier-pos-head flex flex-wrap items-center justify-between gap-2.5 pb-2 mb-3 border-b border-gray-100 dark:border-gray-800"
                @open-cashier-shift-modal.window="openModal = true"
                @open-cashier-close-modal.window="closeModal = true"
                x-data="{
                    openModal: false,
                    movementModal: false,
                    closeModal: false,
                    summaryModal: false,
                    closedSummary: null,
                    startingCashRaw: '100000',
                    startingCashNotes: '',
                    moveType: 'cash_out',
                    moveAmountRaw: '',
                    moveCategory: 'operasional',
                    moveNotes: '',
                    actualCashRaw: '',
                    diffReason: '',
                    closeNotes: '',
                    isSubmitting: false,
                    setStarting(val) {
                        this.startingCashRaw = String(val);
                    },
                    setMovementAmount(val) {
                        this.moveAmountRaw = String(val);
                    },
                    async submitOpen() {
                        if (!this.startingCashRaw) return;
                        this.isSubmitting = true;
                        try {
                            const res = await this.$wire.openShift(this.startingCashRaw, this.startingCashNotes);
                            if (res && res.activeShift) {
                                Alpine.store('cashierPos').activeShift = res.activeShift;
                                this.openModal = false;
                                this.startingCashNotes = '';
                            }
                        } finally {
                            this.isSubmitting = false;
                        }
                    },
                    async submitMovement() {
                        if (!this.moveAmountRaw) return;
                        this.isSubmitting = true;
                        try {
                            const res = await this.$wire.recordCashMovement(this.moveType, this.moveAmountRaw, this.moveCategory, this.moveNotes);
                            if (res && res.activeShift) {
                                Alpine.store('cashierPos').activeShift = res.activeShift;
                                this.movementModal = false;
                                this.moveAmountRaw = '';
                                this.moveNotes = '';
                            }
                        } finally {
                            this.isSubmitting = false;
                        }
                    },
                    async submitClose() {
                        if (this.actualCashRaw === '') return;
                        this.isSubmitting = true;
                        try {
                            const res = await this.$wire.closeShift(this.actualCashRaw, this.diffReason, this.closeNotes);
                            if (res && res.closedShift) {
                                this.closedSummary = res.closedShift;
                                Alpine.store('cashierPos').activeShift = null;
                                this.closeModal = false;
                                this.summaryModal = true;
                                this.actualCashRaw = '';
                                this.diffReason = '';
                                this.closeNotes = '';
                            }
                        } finally {
                            this.isSubmitting = false;
                        }
                    }
                }">
                <div class="flex items-center gap-2 shrink-0">
                    <h2 class="cashier-pos-title">Semua menu</h2>
                </div>

                {{-- Shift Control Bar --}}
                <div class="flex items-center gap-2 shrink-0">
                    {{-- When Shift is Active --}}
                    <template x-if="$store.cashierPos.activeShift">
                        <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                            <div class="inline-flex items-center gap-1.5 rounded-md bg-emerald-50/90 dark:bg-emerald-950/40 border border-emerald-300 dark:border-emerald-800/80 px-2.5 py-1 text-xs text-emerald-950 dark:text-emerald-100 shadow-xs">
                                <span class="relative flex h-2 w-2 shrink-0">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-sm bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-sm h-2 w-2 bg-emerald-500"></span>
                                </span>
                                <span class="font-bold text-emerald-800 dark:text-emerald-200 whitespace-nowrap" x-text="$store.cashierPos.activeShift.user_name"></span>
                                <span class="text-emerald-300 dark:text-emerald-700">|</span>
                                <span class="text-emerald-700 dark:text-emerald-300 whitespace-nowrap">Modal: <strong x-text="$store.cashierPos.cashFormat($store.cashierPos.activeShift.starting_cash)"></strong></span>
                                <template x-if="$store.cashierPos.canViewDrawerCash && $store.cashierPos.activeShift.expected_cash !== null">
                                    <span class="inline-flex items-center gap-1 whitespace-nowrap">
                                        <span class="text-emerald-300 dark:text-emerald-700">|</span>
                                        <span class="text-emerald-800 dark:text-emerald-200">Laci: <strong class="text-emerald-600 dark:text-emerald-400 font-bold" x-text="$store.cashierPos.cashFormat($store.cashierPos.activeShift.expected_cash)"></strong></span>
                                    </span>
                                </template>
                            </div>

                            <button type="button" @click="movementModal = true"
                                class="inline-flex items-center gap-1 rounded-md bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700 px-2.5 py-1 text-xs font-semibold text-gray-700 dark:text-gray-200 shadow-xs transition whitespace-nowrap cursor-pointer"
                                title="Catat Kas Masuk / Kas Keluar">
                                <svg class="w-3.5 h-3.5 text-gray-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/></svg>
                                <span>+/- Kas</span>
                            </button>
                        </div>
                    </template>

                    {{-- When Shift is NOT Active --}}
                    <template x-if="!$store.cashierPos.activeShift">
                        <div class="flex items-center gap-2 shrink-0">
                            <button type="button" @click="openModal = true"
                                class="inline-flex items-center gap-2 rounded-md bg-amber-50 hover:bg-amber-100 dark:bg-amber-950/40 dark:hover:bg-amber-900/50 border border-amber-300 dark:border-amber-700/80 px-2.5 py-1 text-xs font-semibold text-amber-800 dark:text-amber-300 whitespace-nowrap shadow-xs transition cursor-pointer"
                                title="Klik untuk membuka shift kasir">
                                <span class="h-2 w-2 rounded-sm bg-amber-500 shrink-0"></span>
                                <span>Shift Belum Dibuka</span>
                            </button>
                        </div>
                    </template>
                </div>

                {{-- Modals --}}
                {{-- 1. Modal Buka Shift --}}
                <div class="cashier-pos-modal" x-show="openModal" x-cloak role="dialog" aria-modal="true">
                    <div class="cashier-pos-modal__backdrop" @click="openModal = false"></div>
                    <div class="cashier-pos-modal__panel" @click.stop>
                        <div class="flex items-center gap-2 text-base font-bold text-gray-900 dark:text-white mb-2">
                            <span class="p-1.5 rounded-lg bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <span class="text-base font-bold text-gray-900 dark:text-white leading-tight">Buka Shift Kasir Baru</span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                            Masukkan modal awal (float cash) uang kembalian yang ada di laci kas saat ini.
                        </p>

                        <label class="cashier-pos-label font-semibold" for="shift-starting-cash">Modal Awal Kasir (Rp)</label>
                        <input type="text" id="shift-starting-cash" class="cashier-pos-search w-full mb-2 font-bold text-base"
                            x-model="startingCashRaw" placeholder="Contoh: 100000" autocomplete="off">

                        <div class="flex flex-wrap gap-1.5 mb-3">
                            <button type="button" class="cashier-pos-chip text-xs py-1" @click="setStarting(50000)">50 Rb</button>
                            <button type="button" class="cashier-pos-chip text-xs py-1" @click="setStarting(100000)">100 Rb</button>
                            <button type="button" class="cashier-pos-chip text-xs py-1" @click="setStarting(200000)">200 Rb</button>
                            <button type="button" class="cashier-pos-chip text-xs py-1" @click="setStarting(500000)">500 Rb</button>
                            <button type="button" class="cashier-pos-chip text-xs py-1" @click="setStarting(0)">Rp 0</button>
                        </div>

                        <label class="cashier-pos-label font-semibold" for="shift-notes">Catatan Shift (Opsional)</label>
                        <input type="text" id="shift-notes" class="cashier-pos-search w-full mb-4 text-xs"
                            x-model="startingCashNotes" placeholder="Misal: Uang receh 2 ribuan 20 lembar">

                        <div class="cashier-pos-editor-actions flex justify-end gap-2">
                            <button type="button" class="cashier-pos-muted px-4 py-2 text-xs rounded-xl" @click="openModal = false">Batal</button>
                            <button type="button" class="cashier-pos-add cashier-pos-modal__save px-5 py-2 text-xs font-bold rounded-xl"
                                :disabled="isSubmitting" @click="submitOpen()">
                                <span x-show="!isSubmitting">Mulai Shift Kasir</span>
                                <span x-show="isSubmitting" x-cloak>Menyimpan...</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- 2. Modal Kas Masuk / Keluar --}}
                <div class="cashier-pos-modal" x-show="movementModal" x-cloak role="dialog" aria-modal="true">
                    <div class="cashier-pos-modal__backdrop" @click="movementModal = false"></div>
                    <div class="cashier-pos-modal__panel" @click.stop>
                        <div class="flex items-center gap-2 text-base font-bold text-gray-900 dark:text-white mb-2">
                            <span class="p-1.5 rounded-lg bg-indigo-100 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/></svg>
                            </span>
                            <span class="text-base font-bold text-gray-900 dark:text-white leading-tight">Catat Kas Masuk / Keluar Laci</span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                            Catat arus kas kecil (petty cash) seperti belanja darurat, beli es batu, atau tambah modal.
                        </p>

                        <div class="grid grid-cols-2 gap-2 mb-3">
                            <button type="button" class="py-2 px-3 rounded-xl border text-xs font-bold transition flex items-center justify-center gap-1.5"
                                :class="moveType === 'cash_out' ? 'bg-rose-50 dark:bg-rose-950/50 border-rose-400 text-rose-700 dark:text-rose-300 shadow-sm' : 'bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300'"
                                @click="moveType = 'cash_out'">
                                <span class="text-sm font-bold text-rose-500">-</span> Kas Keluar (Pengeluaran)
                            </button>
                            <button type="button" class="py-2 px-3 rounded-xl border text-xs font-bold transition flex items-center justify-center gap-1.5"
                                :class="moveType === 'cash_in' ? 'bg-emerald-50 dark:bg-emerald-950/50 border-emerald-400 text-emerald-700 dark:text-emerald-300 shadow-sm' : 'bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300'"
                                @click="moveType = 'cash_in'">
                                <span class="text-sm font-bold text-emerald-500">+</span> Kas Masuk (Pemasukan)
                            </button>
                        </div>

                        <label class="cashier-pos-label font-semibold">Nominal (Rp)</label>
                        <input type="text" class="cashier-pos-search w-full mb-2 font-bold text-base"
                            x-model="moveAmountRaw" placeholder="Contoh: 25000" autocomplete="off">

                        <div class="flex flex-wrap gap-1.5 mb-3">
                            <button type="button" class="cashier-pos-chip text-xs py-1" @click="setMovementAmount(10000)">10 Rb</button>
                            <button type="button" class="cashier-pos-chip text-xs py-1" @click="setMovementAmount(20000)">20 Rb</button>
                            <button type="button" class="cashier-pos-chip text-xs py-1" @click="setMovementAmount(50000)">50 Rb</button>
                            <button type="button" class="cashier-pos-chip text-xs py-1" @click="setMovementAmount(100000)">100 Rb</button>
                        </div>

                        <label class="cashier-pos-label font-semibold">Kategori</label>
                        <select class="cashier-pos-search w-full mb-3 text-xs" x-model="moveCategory">
                            <template x-if="moveType === 'cash_out'">
                                <optgroup label="Pengeluaran">
                                    <option value="operasional">Operasional (Belanja darurat/bahan)</option>
                                    <option value="es_batu_galon">Es Batu / Air Galon</option>
                                    <option value="kebersihan">Kebersihan & Plastik</option>
                                    <option value="kasbon">Kasbon / Konsumsi Karyawan</option>
                                    <option value="lainnya">Lainnya</option>
                                </optgroup>
                            </template>
                            <template x-if="moveType === 'cash_in'">
                                <optgroup label="Pemasukan">
                                    <option value="tambah_modal">Tambah Modal dari Owner/Brankas</option>
                                    <option value="tukar_receh">Tukar Uang Pecahan</option>
                                    <option value="lainnya">Lainnya</option>
                                </optgroup>
                            </template>
                        </select>

                        <label class="cashier-pos-label font-semibold">Keterangan / Catatan</label>
                        <input type="text" class="cashier-pos-search w-full mb-4 text-xs"
                            x-model="moveNotes" placeholder="Misal: Beli 2 bungkus es batu kristal">

                        <div class="cashier-pos-editor-actions flex justify-end gap-2">
                            <button type="button" class="cashier-pos-muted px-4 py-2 text-xs rounded-xl" @click="movementModal = false">Batal</button>
                            <button type="button" class="cashier-pos-add cashier-pos-modal__save px-5 py-2 text-xs font-bold rounded-xl"
                                :disabled="isSubmitting" @click="submitMovement()">
                                <span x-show="!isSubmitting">Simpan Mutasi</span>
                                <span x-show="isSubmitting" x-cloak>Menyimpan...</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- 3. Modal Tutup Shift (Blind Cash Count) --}}
                <div class="cashier-pos-modal" x-show="closeModal" x-cloak role="dialog" aria-modal="true">
                    <div class="cashier-pos-modal__backdrop" @click="closeModal = false"></div>
                    <div class="cashier-pos-modal__panel" @click.stop>
                        <div class="flex items-center gap-2 text-base font-bold text-gray-900 dark:text-white mb-3">
                            <span class="p-1.5 rounded-lg bg-amber-100 dark:bg-amber-950 text-amber-600 dark:text-amber-400 shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                            </span>
                            <span class="text-base font-bold text-gray-900 dark:text-white leading-tight">Tutup Shift Kasir & Rekonsiliasi</span>
                        </div>

                        <div class="rounded-xl bg-amber-50/90 dark:bg-amber-950/40 border border-amber-200/80 dark:border-amber-800/60 p-3 mb-3 text-xs text-amber-800 dark:text-amber-200 leading-relaxed">
                            <strong>🔒 Blind Cash Count:</strong> Hitung seluruh uang fisik tunai yang ada di laci kas saat ini. Sistem akan mencocokkan dengan catatan transaksi secara otomatis.
                        </div>

                        <label class="cashier-pos-label font-semibold" for="close-shift-actual-cash">Total Uang Fisik di Laci Kas (Rp)</label>
                        <input type="text" id="close-shift-actual-cash" class="cashier-pos-search w-full font-bold text-lg text-emerald-600 dark:text-emerald-400"
                            x-model="actualCashRaw" placeholder="Contoh: 750000" autocomplete="off">
                        <template x-if="actualCashRaw">
                            <div class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 mt-1 mb-2">
                                Terbaca: <span x-text="$store.cashierPos.cashFormat(actualCashRaw)"></span>
                            </div>
                        </template>
                        <template x-if="!actualCashRaw">
                            <div class="mb-2"></div>
                        </template>

                        <label class="cashier-pos-label font-semibold" for="close-shift-diff-reason">Catatan / Alasan Selisih (Opsional)</label>
                        <input type="text" id="close-shift-diff-reason" class="cashier-pos-search w-full mb-4 text-xs"
                            x-model="diffReason" placeholder="Isi jika ada selisih kas fisik vs sistem">

                        <div class="cashier-pos-editor-actions flex justify-end gap-2">
                            <button type="button" class="cashier-pos-muted px-4 py-2 text-xs rounded-xl" @click="closeModal = false">Batal</button>
                            <button type="button" class="cashier-pos-add bg-rose-600 hover:bg-rose-700 text-white px-5 py-2 text-xs font-bold rounded-xl shadow transition"
                                :disabled="isSubmitting || actualCashRaw === ''" @click="submitClose()">
                                <span x-show="!isSubmitting">Hitung & Tutup Shift</span>
                                <span x-show="isSubmitting" x-cloak>Memproses...</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- 4. Modal Hasil Tutup Shift & Cetak Struk --}}
                <div class="cashier-pos-modal" x-show="summaryModal" x-cloak role="dialog" aria-modal="true">
                    <div class="cashier-pos-modal__backdrop" @click="summaryModal = false"></div>
                    <template x-if="closedSummary">
                        <div class="cashier-pos-modal__panel" @click.stop>
                            <div class="flex items-center gap-2 text-base font-bold text-gray-900 dark:text-white mb-3">
                                <span class="p-1.5 rounded-lg bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </span>
                                <span class="text-base font-bold text-gray-900 dark:text-white leading-tight">Shift #<span x-text="closedSummary.id"></span> Berhasil Ditutup</span>
                            </div>

                            <div class="divide-y divide-gray-100 dark:divide-gray-800 text-xs my-3 bg-gray-50 dark:bg-gray-900/60 rounded-xl p-3 border border-gray-200 dark:border-gray-800">
                                <div class="flex justify-between py-1.5">
                                    <span class="text-gray-500">Modal Awal</span>
                                    <span class="font-bold" x-text="$store.cashierPos.cashFormat(closedSummary.starting_cash)"></span>
                                </div>
                                <div class="flex justify-between py-1.5">
                                    <span class="text-gray-500">(+) Penjualan Tunai</span>
                                    <span class="font-bold" x-text="$store.cashierPos.cashFormat(closedSummary.cash_sales)"></span>
                                </div>
                                <div class="flex justify-between py-1.5">
                                    <span class="text-gray-500">(+) Kas Masuk</span>
                                    <span class="font-bold" x-text="$store.cashierPos.cashFormat(closedSummary.cash_in)"></span>
                                </div>
                                <div class="flex justify-between py-1.5">
                                    <span class="text-gray-500">(-) Kas Keluar</span>
                                    <span class="font-bold" x-text="$store.cashierPos.cashFormat(closedSummary.cash_out)"></span>
                                </div>
                                <div class="flex justify-between py-1.5 border-t border-gray-200 dark:border-gray-700">
                                    <span class="font-semibold text-gray-700 dark:text-gray-300">Total Kas Sistem</span>
                                    <span class="font-bold text-gray-900 dark:text-white" x-text="$store.cashierPos.cashFormat(closedSummary.expected_cash)"></span>
                                </div>
                                <div class="flex justify-between py-1.5">
                                    <span class="font-semibold text-gray-700 dark:text-gray-300">Uang Fisik Kasir</span>
                                    <span class="font-bold text-gray-900 dark:text-white" x-text="$store.cashierPos.cashFormat(closedSummary.actual_cash)"></span>
                                </div>
                                <div class="flex justify-between py-2 border-t border-gray-200 dark:border-gray-700">
                                    <span class="font-bold text-gray-800 dark:text-gray-200">Selisih Kas</span>
                                    <span class="font-black text-sm"
                                        :class="closedSummary.difference === 0 ? 'text-emerald-600 dark:text-emerald-400' : (closedSummary.difference < 0 ? 'text-rose-600 dark:text-rose-400' : 'text-blue-600 dark:text-blue-400')"
                                        x-text="closedSummary.difference === 0 ? 'PAS (Rp 0)' : (closedSummary.difference < 0 ? 'MINUS ' + $store.cashierPos.cashFormat(Math.abs(closedSummary.difference)) : 'LEBIH +' + $store.cashierPos.cashFormat(closedSummary.difference))">
                                    </span>
                                </div>
                            </div>

                            <div class="cashier-pos-editor-actions flex justify-between gap-2 mt-4">
                                <a :href="closedSummary.print_url" target="_blank"
                                    class="inline-flex items-center gap-1.5 rounded-xl bg-gray-900 hover:bg-black text-white px-4 py-2 text-xs font-bold shadow transition">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.04-.37-2.126-.37-3.239A8.962 8.962 0 0112 1.62c4.97 0 9 4.03 9 9 0 1.113-.13 2.199-.37 3.239M6.72 13.829A8.992 8.992 0 0012 17.25c2.052 0 3.935-.688 5.438-1.844M6.72 13.829l-3.37 3.37m13.79-3.37l3.37 3.37"/></svg>
                                    <span>Cetak Struk Shift (PDF)</span>
                                </a>
                                <button type="button" class="cashier-pos-muted px-4 py-2 text-xs rounded-xl" @click="summaryModal = false">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="cashier-pos-search-row">
                <input type="search" class="cashier-pos-search" placeholder="Cari menu" x-model="query"
                    autocomplete="off">
                <button type="button" class="cashier-pos-search-reset" x-show="query.length" x-cloak
                    @click="query = ''" aria-label="Hapus pencarian">Reset</button>
            </div>

            <div class="cashier-pos-chips" role="tablist" aria-label="Kategori menu">
                <button type="button" class="cashier-pos-chip" :class="category === 'all' && 'is-active'"
                    @click="category = 'all'">Semua</button>
                @foreach ($categories as $category)
                    <button type="button" class="cashier-pos-chip"
                        :class="String(category) === '{{ $category['id'] }}' && 'is-active'"
                        @click="category = '{{ $category['id'] }}'">{{ $category['name'] }}</button>
                @endforeach
                @if ($hasUncategorized)
                    <button type="button" class="cashier-pos-chip" :class="category === 'lainnya' && 'is-active'"
                        @click="category = 'lainnya'">Lainnya</button>
                @endif
            </div>

            @if ($catalog === [])
                <p class="cashier-pos-empty">Tidak ada menu yang cocok.</p>
            @else
                <div class="cashier-pos-grid">
                    @foreach ($catalog as $item)
                        <article class="cashier-pos-card"
                            x-show="itemVisible({{ \Illuminate\Support\Js::from($item['name']) }}, {{ \Illuminate\Support\Js::from($item['category_id']) }})"
                            :class="$store.cashierPos.qtyOf({{ (int) $item['id'] }}) > 0 && 'is-active'">
                            <div class="cashier-pos-card__photo relative overflow-hidden">
                                @if (filled($item['photo_url'] ?? null))
                                    <img src="{{ $item['photo_url'] }}" alt="" loading="lazy" decoding="async"
                                        width="240" height="160">
                                @else
                                    <span>Menu</span>
                                @endif
                                @if (!empty($item['is_best_seller']))
                                    <span
                                        class="pointer-events-none absolute bottom-2 left-2 z-10 inline-flex items-center gap-1 rounded-full bg-amber-500 px-2 py-0.5 text-[10px] font-extrabold text-white shadow-sm">
                                        <svg class="h-2.5 w-2.5 text-white fill-current" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <span class="text-white">Best Seller</span>
                                    </span>
                                @endif
                            </div>
                            <div class="cashier-pos-card__body">
                                <div class="cashier-pos-card__name">{{ $item['name'] }}</div>
                                <div class="cashier-pos-card__price">
                                    <span>{{ \App\Support\CmsMedia::formatIdr((int) $item['effective_price']) }}</span>
                                    @if ((int) ($item['discount_percent'] ?? 0) > 0)
                                        <span
                                            class="cashier-pos-card__was">{{ \App\Support\CmsMedia::formatIdr((int) $item['price']) }}</span>
                                        <span
                                            class="cashier-pos-card__discount">-{{ (int) $item['discount_percent'] }}%</span>
                                    @endif
                                </div>
                                @if (!empty($item['has_variants']) || !empty($item['has_modifiers']))
                                    <button type="button" class="cashier-pos-add"
                                        @click="openExtra({{ (int) $item['id'] }})">
                                        @if (!empty($item['has_variants']) && !empty($item['has_modifiers']))
                                            Pilih opsi
                                        @elseif (!empty($item['has_variants']))
                                            Pilih varian
                                        @else
                                            Pilih extra
                                        @endif
                                    </button>
                                @else
                                    <template x-if="$store.cashierPos.qtyOf({{ (int) $item['id'] }}) > 0">
                                        <div class="cashier-pos-stepper">
                                            <button type="button" @click="bumpItem({{ (int) $item['id'] }}, -1)"
                                                aria-label="Kurangi">−</button>
                                            <span x-text="$store.cashierPos.qtyOf({{ (int) $item['id'] }})"></span>
                                            <button type="button" @click="bumpItem({{ (int) $item['id'] }}, 1)"
                                                aria-label="Tambah">+</button>
                                        </div>
                                    </template>
                                    <template x-if="$store.cashierPos.qtyOf({{ (int) $item['id'] }}) < 1">
                                        <button type="button" class="cashier-pos-add"
                                            @click="addPlain({{ (int) $item['id'] }})">Tambah</button>
                                    </template>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>

        <aside class="cashier-pos-summary">
            <div class="cashier-pos-section" x-data="{
                tableOpen: false,
                pickTable(id, label) {
                    const pos = Alpine.store('cashierPos');
                    pos.tableId = id;
                    pos.tableLabel = label;
                    this.tableOpen = false;
                    this.$wire.setPosField('table_id', id);
                },
                async onWa(value) {
                    const pos = Alpine.store('cashierPos');
                    pos.customerWa = value;
                    if (!value) {
                        pos.sendReceipt = false;
                        pos.customerInfo = null;
                        if (pos.pointsToRedeem > 0) {
                            pos.pointsToRedeem = 0;
                            pos.apply(await this.$wire.setPosPoints(0));
                        }
                    } else if (value.replace(/\D/g, '').length >= 9) {
                        const info = await this.$wire.checkCustomerPoints(value);
                        if (info && info.found) {
                            pos.customerInfo = info;
                            if (!pos.customerName && info.name) {
                                pos.customerName = info.name;
                                this.$wire.setPosField('customer_name', info.name);
                            }
                        } else {
                            pos.customerInfo = null;
                        }
                    } else {
                        pos.customerInfo = null;
                    }
                    this.$wire.setPosField('customer_wa', value);
                },
                onName(value) {
                    Alpine.store('cashierPos').customerName = value;
                    this.$wire.setPosField('customer_name', value);
                },
            }" @click.outside="tableOpen = false">
                <h2 class="cashier-pos-title mb-3.5">Ringkasan pesanan</h2>
                <div class="cashier-pos-field">
                    <span class="cashier-pos-label" id="cashier-pos-table-label">Meja</span>
                    <div class="cashier-pos-combobox">
                        <button type="button" id="cashier-pos-table" class="cashier-pos-select" aria-haspopup="listbox"
                            aria-labelledby="cashier-pos-table-label" :aria-expanded="tableOpen"
                            @click="tableOpen = ! tableOpen">
                            <span x-text="$store.cashierPos.tableLabel"></span>
                        </button>
                        <div class="cashier-pos-combobox__menu" x-show="tableOpen" x-cloak role="listbox"
                            aria-labelledby="cashier-pos-table-label">
                            <button type="button" class="cashier-pos-combobox__option"
                                :class="$store.cashierPos.tableId === '' && 'is-active'" role="option"
                                @click="pickTable('', 'Pilih meja')">Pilih meja</button>
                            @foreach ($tables as $id => $code)
                                <button type="button" class="cashier-pos-combobox__option"
                                    :class="String($store.cashierPos.tableId) === '{{ $id }}' && 'is-active'" role="option"
                                    @click="pickTable('{{ $id }}', {{ \Illuminate\Support\Js::from((string) $code) }})">{{ $code }}</button>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="cashier-pos-field">
                    <label class="cashier-pos-label" for="cashier-pos-name">Nama tamu</label>
                    <input id="cashier-pos-name" type="text" class="cashier-pos-input"
                        x-model="$store.cashierPos.customerName" maxlength="120"
                        x-on:input="onName($event.target.value)">
                </div>
                <div class="cashier-pos-field">
                    <label class="cashier-pos-label" for="cashier-pos-wa">WhatsApp tamu</label>
                    <input id="cashier-pos-wa" type="text" class="cashier-pos-input"
                        x-model="$store.cashierPos.customerWa"
                        maxlength="20" placeholder="08xxxxxxxxxx" x-on:input="onWa($event.target.value)">
                </div>

                {{-- Member Loyalty Point Redemption Card --}}
                <div x-show="$store.cashierPos.customerInfo && $store.cashierPos.customerInfo.loyalty_enabled && $store.cashierPos.customerInfo.points >= $store.cashierPos.customerInfo.min_points"
                    x-cloak
                    class="mt-2.5 p-3 rounded-xl border border-emerald-500/20 bg-emerald-500/5 dark:bg-emerald-500/10 space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-emerald-700 dark:text-emerald-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z"/></svg>
                            <span x-text="'Member: ' + ($store.cashierPos.customerInfo ? $store.cashierPos.customerInfo.tier : '')"></span>
                        </span>
                        <span class="text-[11px] font-semibold text-slate-600 dark:text-slate-300"
                            x-text="'Saldo: ' + ($store.cashierPos.customerInfo ? $store.cashierPos.customerInfo.points : 0) + ' Poin'"></span>
                    </div>

                    <div class="text-[11px] text-slate-500 dark:text-slate-400 leading-tight">
                        1 Poin = <span x-text="$store.cashierPos.cashFormat($store.cashierPos.customerInfo ? $store.cashierPos.customerInfo.rate : 1000)"></span>. Tukar poin:
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="number" min="0"
                            :max="$store.cashierPos.customerInfo ? $store.cashierPos.customerInfo.points : 0"
                            class="cashier-pos-input text-xs py-1 px-2.5 w-24 tabular-nums text-center"
                            placeholder="0"
                            x-model.number="$store.cashierPos.pointsToRedeem"
                            @change="async () => {
                                const pts = Math.max(0, Number($store.cashierPos.pointsToRedeem || 0));
                                $store.cashierPos.pointsToRedeem = pts;
                                const state = await $wire.setPosPoints(pts);
                                $store.cashierPos.apply(state);
                            }">
                        <button type="button"
                            class="text-[11px] font-bold text-emerald-600 hover:text-emerald-500 dark:text-emerald-400 underline underline-offset-2"
                            @click="async () => {
                                if (!$store.cashierPos.customerInfo) return;
                                const maxPts = $store.cashierPos.customerInfo.points;
                                $store.cashierPos.pointsToRedeem = maxPts;
                                const state = await $wire.setPosPoints(maxPts);
                                $store.cashierPos.apply(state);
                            }">
                            Maksimal
                        </button>
                        <button type="button"
                            x-show="$store.cashierPos.pointsToRedeem > 0"
                            class="text-[11px] font-medium text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"
                            @click="async () => {
                                $store.cashierPos.pointsToRedeem = 0;
                                const state = await $wire.setPosPoints(0);
                                $store.cashierPos.apply(state);
                            }">
                            Batal
                        </button>
                    </div>
                </div>

                <label class="cashier-pos-check" style="margin-top: 0.7rem" x-show="$store.cashierPos.hasFonnte"
                    x-cloak>
                    <input type="checkbox" x-model="$store.cashierPos.sendReceipt"
                        :disabled="!$store.cashierPos.customerWa"
                        @change="$wire.setPosField('send_receipt', $store.cashierPos.sendReceipt)">
                    Kirim struk WhatsApp
                </label>
            </div>

            <div class="cashier-pos-section" x-data="{
                openLine(line) {
                        Alpine.store('cashierPos').openLine(line);
                    },
                    async bumpLine(index, delta) {
                        Alpine.store('cashierPos').apply(await this.$wire.changePosQty(index, delta));
                    },
            }">
                <template x-if="$store.cashierPos.cartLines.length === 0">
                    <p class="cashier-pos-empty">Belum ada item. Pilih menu di kiri.</p>
                </template>
                <template x-for="line in $store.cashierPos.cartLines" :key="line.index">
                    <div class="cashier-pos-cart-item">
                        <template x-if="line.photo_url">
                            <img :src="line.photo_url" alt="">
                        </template>
                        <template x-if="! line.photo_url">
                            <div class="cashier-pos-cart-item__ph">Menu</div>
                        </template>
                        <div>
                            <p class="cashier-pos-cart-item__name">
                                <span x-text="line.name"></span>
                                <span x-show="line.variant_name" class="font-medium text-primary-600 dark:text-primary-400" x-text="' (' + line.variant_name + ')'"></span>
                                <span x-text="' x' + line.qty"></span>
                            </p>
                            <p class="cashier-pos-cart-item__meta" x-show="line.extras.length"
                                x-text="line.extras.join(', ')"></p>
                            <p class="cashier-pos-cart-item__meta" x-show="line.notes" x-text="line.notes"></p>
                            <button type="button" class="cashier-pos-link" @click="openLine(line)">Opsi</button>
                        </div>
                        <div>
                            <p class="cashier-pos-cart-item__name" style="text-align: right"
                                x-text="line.total_label"></p>
                            <div class="cashier-pos-stepper" style="margin-top: 0.35rem; min-width: 6.5rem">
                                <button type="button" @click="bumpLine(line.index, -1)">−</button>
                                <span x-text="line.qty"></span>
                                <button type="button" @click="bumpLine(line.index, 1)">+</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="cashier-pos-section" x-data="{
                setPayment(method) {
                    Alpine.store('cashierPos').paymentMethod = method;
                    this.$wire.setPosPaymentMethod(method);
                },
            }">
                <p class="cashier-pos-label">Metode bayar</p>
                <div class="cashier-pos-pay">
                    <button type="button" :class="$store.cashierPos.paymentMethod === 'cash' && 'is-active'"
                        @click="setPayment('cash')">Tunai</button>
                    <button type="button" :class="$store.cashierPos.paymentMethod === 'qris' && 'is-active'"
                        @click="setPayment('qris')">QRIS</button>
                </div>
            </div>

            <div x-data="{
                cashExact() {
                    const pos = Alpine.store('cashierPos');
                    pos.cashRaw = String(pos.preview.grand_payable || '');
                    this.$wire.setPosField('cash_received', pos.cashRaw);
                },
            }">
                <div class="space-y-4">
                    <div class="flex items-center justify-between gap-3">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            <span x-show="! $store.cashierPos.preview.subtotal">Belum ada item — tambahkan menu untuk
                                melihat perkiraan total.</span>
                            <span x-show="$store.cashierPos.preview.subtotal"
                                x-text="($store.cashierPos.preview.line_count || 0) + ' baris · ' + ($store.cashierPos.preview.total_qty || 0) + ' porsi'"></span>
                        </div>
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold"
                            :class="$store.cashierPos.paymentMethod === 'qris' ?
                                'bg-info-50 text-info-700 ring-1 ring-info-600/10 dark:bg-info-400/10 dark:text-info-400 dark:ring-info-400/20' :
                                'bg-success-50 text-success-700 ring-1 ring-success-600/10 dark:bg-success-400/10 dark:text-success-400 dark:ring-success-400/20'"
                            x-text="$store.cashierPos.paymentMethod === 'qris' ? 'QRIS' : 'Tunai'"></span>
                    </div>

                    <div x-show="$store.cashierPos.preview.subtotal" x-cloak>
                        <dl class="space-y-1.5 text-xs">
                            <div class="flex items-baseline justify-between gap-3">
                                <dt class="text-gray-500 dark:text-gray-400">Subtotal</dt>
                                <dd class="font-medium tabular-nums text-gray-950 dark:text-white"
                                    x-text="$store.cashierPos.preview.subtotal_label"></dd>
                            </div>
                            <div class="flex items-baseline justify-between gap-3 text-emerald-600 dark:text-emerald-400 font-medium"
                                x-show="$store.cashierPos.preview.discount_amount > 0">
                                <dt class="flex items-center gap-1">
                                    <span>Diskon Poin</span>
                                    <span class="text-[10px] bg-emerald-500/10 px-1.5 py-0.5 rounded-full"
                                        x-text="'(' + ($store.cashierPos.preview.points_redeemed || 0) + ' Poin)'"></span>
                                </dt>
                                <dd class="tabular-nums font-bold"
                                    x-text="'-' + $store.cashierPos.preview.discount_label"></dd>
                            </div>
                            <div class="flex items-baseline justify-between gap-3"
                                x-show="$store.cashierPos.preview.service_amount > 0">
                                <dt class="text-gray-500 dark:text-gray-400">Service (<span
                                        x-text="$store.cashierPos.preview.service_pct_label"></span>%)</dt>
                                <dd class="font-medium tabular-nums text-gray-950 dark:text-white"
                                    x-text="$store.cashierPos.preview.service_label"></dd>
                            </div>
                            <div class="flex items-baseline justify-between gap-3"
                                x-show="$store.cashierPos.preview.pb1_amount > 0">
                                <dt class="text-gray-500 dark:text-gray-400">PB1 (<span
                                        x-text="$store.cashierPos.preview.pb1_pct_label"></span>%)</dt>
                                <dd class="font-medium tabular-nums text-gray-950 dark:text-white"
                                    x-text="$store.cashierPos.preview.pb1_label"></dd>
                            </div>
                        </dl>

                        <div class="mt-4 rounded-lg bg-gray-50 p-3 text-center ring-1 ring-gray-950/5 dark:bg-white/5 dark:ring-white/10"
                            x-show="$store.cashierPos.paymentMethod === 'qris'">
                            <p
                                class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Scan QRIS outlet</p>
                            <template x-if="$store.cashierPos.qrisImageUrl">
                                <img :src="$store.cashierPos.qrisImageUrl" alt="QRIS outlet"
                                    class="mx-auto block w-full max-w-[11rem] rounded-lg bg-white ring-1 ring-gray-950/5">
                            </template>
                            <p class="text-sm text-gray-500 dark:text-gray-400"
                                x-show="! $store.cashierPos.qrisImageUrl">
                                QRIS outlet belum diunggah. Atur di menu Outlet agar kasir bisa scan di sini.
                            </p>
                        </div>

                        <div
                            class="mt-3.5 flex items-baseline justify-between gap-3 rounded-lg bg-gray-50 px-3 py-2.5 ring-1 ring-gray-950/5 dark:bg-white/5 dark:ring-white/10">
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-200">Total bayar</span>
                            <span class="text-lg font-bold tabular-nums tracking-tight text-gray-950 dark:text-white"
                                x-text="$store.cashierPos.preview.grand_label"></span>
                        </div>

                        <p class="mt-4 rounded-lg bg-info-50 px-3 py-2 text-xs leading-5 text-info-700 dark:bg-info-400/10 dark:text-info-400"
                            x-show="$store.cashierPos.paymentMethod === 'qris'">
                            QRIS: kode unik 1–999 ditambahkan saat pembayaran diproses.
                        </p>

                        <div class="space-y-2 border-t border-gray-200 pt-4 dark:border-white/10"
                            x-show="$store.cashierPos.paymentMethod === 'cash'" x-cloak>
                            <div class="flex items-center justify-between gap-2">
                                <label for="cashier-pos-cash-received"
                                    class="text-sm font-medium text-gray-950 dark:text-white">Uang diterima</label>
                                <button type="button"
                                    class="text-sm font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400"
                                    @click="cashExact()">Uang pas</button>
                            </div>
                            <div
                                class="flex overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-gray-950/10 dark:bg-white/5 dark:ring-white/20 focus-within:ring-2 focus-within:ring-primary-500">
                                <span class="flex items-center px-3 text-sm text-gray-500 dark:text-gray-400">Rp</span>
                                <input id="cashier-pos-cash-received" type="text" inputmode="numeric"
                                    autocomplete="off" placeholder="0"
                                    class="min-w-0 flex-1 border-none bg-transparent py-2 pe-3 text-sm text-gray-950 outline-none ring-0 placeholder:text-gray-400 dark:text-white"
                                    x-model="$store.cashierPos.cashRaw"
                                    @input="$wire.setPosField('cash_received', $store.cashierPos.cashRaw === '' ? null : $store.cashierPos.cashRaw)">
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Isi nominal uang dari tamu. Kembalian
                                dihitung otomatis.</p>

                            <div x-show="$store.cashierPos.cashParsed() !== null && $store.cashierPos.cashShort()"
                                class="flex items-baseline justify-between gap-3 text-sm">
                                <span class="text-danger-600 dark:text-danger-400">Kembalian</span>
                                <span class="font-bold tabular-nums text-danger-600 dark:text-danger-400"
                                    x-text="'Kurang ' + $store.cashierPos.cashFormat(Math.abs($store.cashierPos.cashChange()))"></span>
                            </div>
                            <div x-show="$store.cashierPos.cashParsed() !== null && ! $store.cashierPos.cashShort()"
                                class="flex items-baseline justify-between gap-3 text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Kembalian</span>
                                <span class="font-bold tabular-nums text-success-600 dark:text-success-400"
                                    x-text="$store.cashierPos.cashFormat($store.cashierPos.cashChange())"></span>
                            </div>
                        </div>


                    </div>
                </div>
                <button type="button" class="cashier-pos-submit" wire:click="create">Buat pesanan</button>
            </div>
        </aside>
    </div>

    <div class="cashier-pos-modal" x-data="{
        async saveEditor() {
            const pos = Alpine.store('cashierPos');
            if (!pos.editor) {
                return;
            }
            pos.apply(await this.$wire.commitPosEditor(
                pos.editor.type,
                pos.editor.menu_item_id,
                pos.editor.index,
                pos.editor.variant_id ?? null,
                pos.editor.modifier_ids || [],
                pos.editor.notes || null,
            ));
            pos.editor = null;
        },
    }" x-show="$store.cashierPos.editor" x-cloak wire:ignore
        role="dialog" aria-modal="true" aria-labelledby="cashier-pos-modal-title">
        <button type="button" class="cashier-pos-modal__backdrop" @click="$store.cashierPos.closeEditor()"
            aria-label="Tutup"></button>
        <template x-if="$store.cashierPos.editor">
            <div class="cashier-pos-modal__panel" @click.stop>
                <h3 id="cashier-pos-modal-title">
                    <span x-text="$store.cashierPos.editor.type === 'edit' ? 'Ubah opsi' : 'Pilih opsi'"></span>:
                    <span x-text="$store.cashierPos.editor.name"></span>
                </h3>

                {{-- Pilihan Varian (Wajib jika ada) --}}
                <template x-if="$store.cashierPos.editor.variants && $store.cashierPos.editor.variants.length > 0">
                    <div style="margin-bottom: 0.9rem">
                        <p class="cashier-pos-label" style="margin-bottom: 0.4rem">Varian <span class="text-xs text-primary-600 dark:text-primary-400 font-normal">· Pilih salah satu</span></p>
                        <div class="cashier-pos-modal__options" style="margin-bottom: 0">
                            <template x-for="v in $store.cashierPos.editor.variants" :key="v.id">
                                <label class="cashier-pos-modal__option"
                                    :class="Number($store.cashierPos.editor.variant_id) === Number(v.id) && 'is-checked'">
                                    <input type="radio" name="pos_variant_choice" :value="v.id"
                                        :checked="Number($store.cashierPos.editor.variant_id) === Number(v.id)"
                                        @change="$store.cashierPos.editor.variant_id = Number(v.id)">
                                    <span x-text="v.label"></span>
                                </label>
                            </template>
                        </div>
                    </div>
                </template>

                {{-- Pilihan Extra / Modifier (Opsional) --}}
                <template x-if="$store.cashierPos.editor.modifiers && $store.cashierPos.editor.modifiers.length > 0">
                    <div style="margin-bottom: 0.9rem">
                        <p class="cashier-pos-label" style="margin-bottom: 0.4rem">Extra / Tambahan <span class="text-xs text-gray-500 font-normal">· Opsional</span></p>
                        <div class="cashier-pos-modal__options" style="margin-bottom: 0">
                            <template x-for="mod in $store.cashierPos.editor.modifiers" :key="mod.id">
                                <label class="cashier-pos-modal__option"
                                    :class="$store.cashierPos.isModOn(mod.id) && 'is-checked'">
                                    <input type="checkbox" :checked="$store.cashierPos.isModOn(mod.id)"
                                        @change="$store.cashierPos.toggleMod(mod.id)">
                                    <span x-text="mod.label"></span>
                                </label>
                            </template>
                        </div>
                    </div>
                </template>

                <template x-if="(!$store.cashierPos.editor.variants || $store.cashierPos.editor.variants.length === 0) && (!$store.cashierPos.editor.modifiers || $store.cashierPos.editor.modifiers.length === 0)">
                    <p class="cashier-pos-empty">Tidak ada opsi tambahan untuk menu ini.</p>
                </template>

                <label class="cashier-pos-label" for="cashier-pos-notes">Catatan</label>
                <textarea id="cashier-pos-notes" class="cashier-pos-textarea" rows="2"
                    x-model="$store.cashierPos.editor.notes" placeholder="Opsional"></textarea>
                <div class="cashier-pos-editor-actions">
                    <button type="button" class="cashier-pos-muted"
                        @click="$store.cashierPos.closeEditor()">Batal</button>
                    <button type="button" class="cashier-pos-add cashier-pos-modal__save"
                        @click="saveEditor()">Simpan</button>
                </div>
            </div>
        </template>
    </div>


</div>
