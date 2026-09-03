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
        'cashReceived' => $cashReceived ?? null,
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
            cashRaw: '',
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
                this.cashRaw = next.cashReceived == null ? '' : String(next.cashReceived);
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
                this.editor = {
                    type: 'new',
                    index: null,
                    menu_item_id: item.id,
                    name: item.name,
                    modifiers: item.modifiers || [],
                    modifier_ids: [],
                    notes: '',
                };
            },
            openLine(line) {
                const item = this.catalogById[line.menu_item_id] ||
                    this.catalog.find((row) => Number(row.id) === Number(line.menu_item_id));
                this.editor = {
                    type: 'edit',
                    index: line.index,
                    menu_item_id: line.menu_item_id,
                    name: line.name,
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
})()" @keydown.escape.window="$store.cashierPos.editor = null">
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
            <div class="cashier-pos-head">
                <h2 class="cashier-pos-title">Semua menu</h2>
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
                                @if ($item['has_modifiers'] ?? false)
                                    <button type="button" class="cashier-pos-add"
                                        @click="openExtra({{ (int) $item['id'] }})">
                                        Pilih extra
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
                tableId: {{ \Illuminate\Support\Js::from($selectedTableId) }},
                tableLabel: {{ \Illuminate\Support\Js::from($selectedTableLabel) }},
                pickTable(id, label) {
                    this.tableId = id;
                    this.tableLabel = label;
                    this.tableOpen = false;
                    this.$wire.setPosField('table_id', id);
                },
                onWa(value) {
                    const pos = Alpine.store('cashierPos');
                    pos.customerWa = value;
                    if (!value) {
                        pos.sendReceipt = false;
                    }
                    this.$wire.setPosField('customer_wa', value);
                },
            }" @click.outside="tableOpen = false">
                <h2 class="cashier-pos-title mb-3.5">Ringkasan pesanan</h2>
                <div class="cashier-pos-field">
                    <span class="cashier-pos-label" id="cashier-pos-table-label">Meja</span>
                    <div class="cashier-pos-combobox">
                        <button type="button" id="cashier-pos-table" class="cashier-pos-select" aria-haspopup="listbox"
                            aria-labelledby="cashier-pos-table-label" :aria-expanded="tableOpen"
                            @click="tableOpen = ! tableOpen">
                            <span x-text="tableLabel"></span>
                        </button>
                        <div class="cashier-pos-combobox__menu" x-show="tableOpen" x-cloak role="listbox"
                            aria-labelledby="cashier-pos-table-label">
                            <button type="button" class="cashier-pos-combobox__option"
                                :class="tableId === '' && 'is-active'" role="option"
                                @click="pickTable('', 'Pilih meja')">Pilih meja</button>
                            @foreach ($tables as $id => $code)
                                <button type="button" class="cashier-pos-combobox__option"
                                    :class="String(tableId) === '{{ $id }}' && 'is-active'" role="option"
                                    @click="pickTable('{{ $id }}', {{ \Illuminate\Support\Js::from((string) $code) }})">{{ $code }}</button>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="cashier-pos-field">
                    <label class="cashier-pos-label" for="cashier-pos-name">Nama tamu</label>
                    <input id="cashier-pos-name" type="text" class="cashier-pos-input"
                        value="{{ $customerName }}" maxlength="120"
                        x-on:change="$wire.setPosField('customer_name', $event.target.value)">
                </div>
                <div class="cashier-pos-field">
                    <label class="cashier-pos-label" for="cashier-pos-wa">WhatsApp tamu</label>
                    <input id="cashier-pos-wa" type="text" class="cashier-pos-input" value="{{ $customerWa }}"
                        maxlength="20" placeholder="08xxxxxxxxxx" x-on:change="onWa($event.target.value)">
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
                            <p class="cashier-pos-cart-item__name"><span x-text="line.name"></span> <span
                                    x-text="'x' + line.qty"></span></p>
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
                    <span x-text="$store.cashierPos.editor.type === 'edit' ? 'Ubah extra' : 'Pilih extra'"></span>
                    <span x-text="$store.cashierPos.editor.name"></span>
                </h3>
                <div class="cashier-pos-modal__options">
                    <p class="cashier-pos-empty" x-show="$store.cashierPos.editor.modifiers.length === 0">Tidak ada
                        extra untuk menu ini.</p>
                    <template x-for="mod in $store.cashierPos.editor.modifiers" :key="mod.id">
                        <label class="cashier-pos-modal__option"
                            :class="$store.cashierPos.isModOn(mod.id) && 'is-checked'">
                            <input type="checkbox" :checked="$store.cashierPos.isModOn(mod.id)"
                                @change="$store.cashierPos.toggleMod(mod.id)">
                            <span x-text="mod.label"></span>
                        </label>
                    </template>
                </div>
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
