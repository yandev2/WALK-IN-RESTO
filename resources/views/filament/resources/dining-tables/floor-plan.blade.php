@php
    /** @var \Illuminate\Support\Collection<int, \App\Models\DiningTable> $records */
@endphp

<style>
    .floor-plan-root {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .floor-plan-banner {
        border-radius: 0.5rem;
        border: 1px solid rgb(253 186 116);
        background: rgb(255 247 237);
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        color: rgb(154 52 18);
    }

    .dark .floor-plan-banner {
        border-color: rgb(234 88 12 / 0.3);
        background: rgb(234 88 12 / 0.1);
        color: rgb(254 215 170);
    }

    .floor-plan-canvas {
        position: relative;
        min-height: 32rem;
        max-height: 85vh;
        overflow: auto;
        border-radius: 0.75rem;
        border: 1px solid rgb(229 231 235);
        background-color: rgb(248 250 252);
        background-image: radial-gradient(circle, rgb(148 163 184 / 0.35) 1px, transparent 1px);
        background-size: 24px 24px;
    }

    .dark .floor-plan-canvas {
        border-color: rgb(55 65 81);
        background-color: rgb(17 24 39 / 0.4);
    }

    .floor-plan-card {
        position: absolute;
        width: 9rem;
        user-select: none;
        border-radius: 0.75rem;
        border-width: 2px;
        border-style: solid;
        padding: 0.75rem;
        box-shadow: 0 1px 2px rgb(0 0 0 / 0.05);
        transition: box-shadow 150ms ease, transform 150ms ease;
    }

    .floor-plan-card:hover {
        box-shadow: 0 4px 12px rgb(0 0 0 / 0.08);
    }

    .floor-plan-card.is-editing {
        cursor: grab;
        outline: 2px solid rgb(251 191 36 / 0.6);
        outline-offset: 2px;
    }

    .floor-plan-card.is-dragging {
        cursor: grabbing;
        z-index: 20;
        transform: scale(1.02);
        box-shadow: 0 10px 24px rgb(0 0 0 / 0.12);
    }

    .floor-plan-card.is-view {
        cursor: pointer;
    }

    .floor-plan-card--success {
        border-color: rgb(34 197 94 / 0.45);
        background: rgb(240 253 244);
        color: rgb(21 128 61);
    }

    .floor-plan-card--danger {
        border-color: rgb(239 68 68 / 0.45);
        background: rgb(254 242 242);
        color: rgb(185 28 28);
    }

    .floor-plan-card--warning {
        border-color: rgb(245 158 11 / 0.45);
        background: rgb(255 251 235);
        color: rgb(180 83 9);
    }

    .floor-plan-card--info {
        border-color: rgb(59 130 246 / 0.45);
        background: rgb(239 246 255);
        color: rgb(29 78 216);
    }

    .floor-plan-card--gray {
        border-color: rgb(156 163 175 / 0.45);
        background: rgb(249 250 251);
        color: rgb(55 65 81);
    }

    .dark .floor-plan-card--success {
        border-color: rgb(34 197 94 / 0.35);
        background: rgb(34 197 94 / 0.1);
        color: rgb(134 239 172);
    }

    .dark .floor-plan-card--danger {
        border-color: rgb(239 68 68 / 0.35);
        background: rgb(239 68 68 / 0.1);
        color: rgb(252 165 165);
    }

    .dark .floor-plan-card--warning {
        border-color: rgb(245 158 11 / 0.35);
        background: rgb(245 158 11 / 0.1);
        color: rgb(252 211 77);
    }

    .dark .floor-plan-card--info {
        border-color: rgb(59 130 246 / 0.35);
        background: rgb(59 130 246 / 0.1);
        color: rgb(147 197 253);
    }

    .dark .floor-plan-card--gray {
        border-color: rgb(156 163 175 / 0.35);
        background: rgb(156 163 175 / 0.1);
        color: rgb(209 213 219);
    }

    .floor-plan-card__header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.5rem;
    }

    .floor-plan-card__code {
        font-size: 1.125rem;
        font-weight: 600;
        line-height: 1;
    }

    .floor-plan-card__capacity {
        margin-top: 0.25rem;
        font-size: 0.75rem;
        opacity: 0.8;
    }

    .floor-plan-card__badge {
        display: inline-flex;
        margin-top: 0.75rem;
        border-radius: 0.375rem;
        padding: 0.125rem 0.5rem;
        font-size: 0.75rem;
        font-weight: 500;
        box-shadow: inset 0 0 0 1px rgb(0 0 0 / 0.08);
    }

    .floor-plan-card__area {
        margin-top: 0.5rem;
        font-size: 0.75rem;
        opacity: 0.7;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .floor-plan-empty {
        border-radius: 0.75rem;
        border: 1px dashed rgb(209 213 219);
        padding: 4rem 1.5rem;
        text-align: center;
    }
</style>

<div
    x-data="{
        draggingId: null,
        offsetX: 0,
        offsetY: 0,
        positions: {},
        startDrag(event, tableId, currentX, currentY) {
            if (! $wire.isEditingLayout) {
                return;
            }

            event.preventDefault();

            const canvas = this.$refs.canvas;
            const card = event.currentTarget;

            if (! canvas || ! card) {
                return;
            }

            const cardRect = card.getBoundingClientRect();

            this.draggingId = tableId;
            this.offsetX = event.clientX - cardRect.left;
            this.offsetY = event.clientY - cardRect.top;

            if (! this.positions[tableId]) {
                this.positions[tableId] = { x: currentX, y: currentY };
            }
        },
        onDrag(event) {
            if (this.draggingId === null) {
                return;
            }

            const canvas = this.$refs.canvas;
            const card = this.$refs['card-' + this.draggingId];

            if (! canvas || ! card) {
                return;
            }

            const canvasRect = canvas.getBoundingClientRect();
            const cardWidthPct = (card.offsetWidth / canvasRect.width) * 100;
            const cardHeightPct = (card.offsetHeight / canvasRect.height) * 100;

            let x = ((event.clientX - canvasRect.left - this.offsetX) / canvasRect.width) * 100;
            let y = ((event.clientY - canvasRect.top - this.offsetY) / canvasRect.height) * 100;

            x = Math.max(0, Math.min(100 - cardWidthPct, x));
            y = Math.max(0, Math.min(100 - cardHeightPct, y));

            this.positions[this.draggingId] = {
                x: Math.round(x * 100) / 100,
                y: Math.round(y * 100) / 100,
            };
        },
        endDrag() {
            if (this.draggingId === null) {
                return;
            }

            const tableId = this.draggingId;
            const position = this.positions[tableId];

            this.draggingId = null;

            if (! position) {
                return;
            }

            $wire.saveTablePosition(tableId, position.x, position.y);
        },
        cardPosition(tableId, fallbackX, fallbackY) {
            if (this.positions[tableId]) {
                return this.positions[tableId];
            }

            return { x: fallbackX, y: fallbackY };
        },
        cardStyle(tableId, fallbackX, fallbackY) {
            const pos = this.cardPosition(tableId, fallbackX, fallbackY);

            return `left:${pos.x}%;top:${pos.y}%;`;
        },
        cardClick(event, tableId) {
            if ($wire.isEditingLayout) {
                return;
            }

            if (event.target.closest('[data-floor-plan-menu]')) {
                return;
            }

            $wire.mountTableAction('edit', tableId);
        },
    }"
    x-on:mousemove.window="onDrag($event)"
    x-on:mouseup.window="endDrag()"
    class="floor-plan-root"
>
    @if ($records->isEmpty())
        <div class="floor-plan-empty">
            <p style="font-size: 0.875rem; font-weight: 500;">Belum ada meja di area ini.</p>
            <p style="margin-top: 0.25rem; font-size: 0.875rem; opacity: 0.7;">Tambah meja baru lalu atur posisinya di denah.</p>
        </div>
    @else
        @php
            $canvasMinHeightRem = \App\Support\TableFloorPlan::canvasMinHeightRem($records);
        @endphp

        <div
            x-show="$wire.isEditingLayout"
            x-cloak
            class="floor-plan-banner"
        >
            Mode edit denah aktif. Geser kartu meja lalu lepas untuk menyimpan posisi.
        </div>

        <div
            x-ref="canvas"
            class="floor-plan-canvas"
            style="min-height: {{ $canvasMinHeightRem }}rem;"
        >
            @foreach ($records as $index => $record)
                @php
                    $position = $record->displayPosition($index);
                    $status = $record->floorStatusMeta();
                @endphp

                <div
                    x-ref="card-{{ $record->id }}"
                    wire:key="floor-table-{{ $record->id }}"
                    x-bind:style="cardStyle({{ $record->id }}, {{ $position['x'] }}, {{ $position['y'] }})"
                    x-on:mousedown="startDrag($event, {{ $record->id }}, {{ $position['x'] }}, {{ $position['y'] }})"
                    x-on:click="cardClick($event, {{ $record->id }})"
                    x-bind:class="{
                        'is-editing': $wire.isEditingLayout,
                        'is-view': ! $wire.isEditingLayout,
                        'is-dragging': draggingId === {{ $record->id }},
                    }"
                    class="floor-plan-card floor-plan-card--{{ $status['color'] }}"
                    style="left: {{ $position['x'] }}%; top: {{ $position['y'] }}%;"
                >
                    <div class="floor-plan-card__header">
                        <div style="min-width: 0;">
                            <p class="floor-plan-card__code">{{ $record->code }}</p>
                            <p class="floor-plan-card__capacity">{{ $record->capacity }} org</p>
                        </div>

                        <div data-floor-plan-menu x-on:click.stop>
                            <x-filament::dropdown placement="bottom-end">
                                <x-slot name="trigger">
                                    <button
                                        type="button"
                                        style="border-radius: 0.375rem; padding: 0.25rem; opacity: 0.7;"
                                        aria-label="Aksi meja {{ $record->code }}"
                                    >
                                        <x-filament::icon icon="heroicon-m-ellipsis-vertical" class="h-4 w-4" />
                                    </button>
                                </x-slot>

                                <x-filament::dropdown.list>
                                    <x-filament::dropdown.list.item
                                        icon="heroicon-m-pencil-square"
                                        wire:click="mountTableAction('edit', '{{ $record->id }}')"
                                    >
                                        Edit
                                    </x-filament::dropdown.list.item>

                                    <x-filament::dropdown.list.item
                                        tag="a"
                                        href="{{ \App\Support\TableQrToken::url($record) }}"
                                        target="_blank"
                                        icon="heroicon-m-qr-code"
                                    >
                                        Buka pemesanan
                                    </x-filament::dropdown.list.item>

                                    <x-filament::dropdown.list.item
                                        icon="heroicon-m-clipboard-document"
                                        x-on:click="if (window.navigator?.clipboard?.writeText) { window.navigator.clipboard.writeText('{{ addslashes(\App\Support\TableQrToken::url($record)) }}').catch(() => {}); }"
                                        wire:click="mountTableAction('copyGuestOrder', '{{ $record->id }}')"
                                    >
                                        Salin tautan QR
                                    </x-filament::dropdown.list.item>

                                    @if ($record->needs_cleaning)
                                        <x-filament::dropdown.list.item
                                            icon="heroicon-m-check"
                                            wire:click="mountTableAction('markReady', '{{ $record->id }}')"
                                        >
                                            Meja siap
                                        </x-filament::dropdown.list.item>
                                    @endif

                                    @if (filled($record->open_visit_id))
                                        <x-filament::dropdown.list.item
                                            icon="heroicon-m-key"
                                            wire:click="mountTableAction('resetPin', '{{ $record->id }}')"
                                        >
                                            Reset PIN
                                        </x-filament::dropdown.list.item>

                                        <x-filament::dropdown.list.item
                                            icon="heroicon-m-arrows-right-left"
                                            wire:click="mountTableAction('moveVisit', '{{ $record->id }}')"
                                        >
                                            Pindah meja
                                        </x-filament::dropdown.list.item>
                                    @endif

                                    <x-filament::dropdown.list.item
                                        icon="heroicon-m-arrow-path"
                                        wire:click="mountTableAction('regenerateQr', '{{ $record->id }}')"
                                    >
                                        Regenerate QR
                                    </x-filament::dropdown.list.item>

                                    <x-filament::dropdown.list.item
                                        icon="heroicon-m-arrow-down-tray"
                                        wire:click="mountTableAction('downloadQr', '{{ $record->id }}')"
                                    >
                                        Unduh QR PNG
                                    </x-filament::dropdown.list.item>

                                    <x-filament::dropdown.list.item
                                        icon="heroicon-m-document"
                                        wire:click="mountTableAction('downloadQrPdf', '{{ $record->id }}')"
                                    >
                                        Unduh QR PDF
                                    </x-filament::dropdown.list.item>

                                    @if (! filled($record->open_visit_id))
                                        <x-filament::dropdown.list.item
                                            color="danger"
                                            icon="heroicon-m-trash"
                                            wire:click="mountTableAction('delete', '{{ $record->id }}')"
                                        >
                                            Hapus
                                        </x-filament::dropdown.list.item>
                                    @endif
                                </x-filament::dropdown.list>
                            </x-filament::dropdown>
                        </div>
                    </div>

                    <span class="floor-plan-card__badge">{{ $status['label'] }}</span>

                    @if (filled($record->area))
                        <p class="floor-plan-card__area">{{ $record->area }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
