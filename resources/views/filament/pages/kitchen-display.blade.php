@php
    /** @var \Illuminate\Support\Collection<int, \App\Models\OrderItem>|\Illuminate\Contracts\Pagination\Paginator|null $records */
    /** @var \App\Filament\Pages\KitchenDisplay $this */

    use App\Models\OrderItem;
    use Illuminate\Contracts\Pagination\Paginator;
    use Illuminate\Support\Collection;

    $items = $records instanceof Paginator
        ? collect($records->items())
        : ($records instanceof Collection ? $records : collect($records ?? []));

    $isBatchTab = ($this->activeTab ?? null) === 'batch';

    $groups = $isBatchTab
        ? $items->groupBy(fn (OrderItem $item): string => $item->batchKey())
        : collect(['' => $items]);

    $statusLabel = static function (string $status): string {
        return match ($status) {
            'queued' => 'Antri',
            'preparing' => 'Dimasak',
            'ready' => 'Siap antar',
            'served' => 'Sudah diantar',
            default => $status,
        };
    };

    $advanceLabel = static function (OrderItem $item): string {
        return match ($item->kds_status) {
            'queued' => 'Mulai masak',
            'preparing' => 'Tandai siap',
            'ready' => 'Tandai diantar',
            default => 'Lanjut',
        };
    };

    $canAdvance = $this->canAdvance();
    $canMarkServed = $this->canMarkServed();
@endphp

<style>
    /* Spacing between Filament table frame and cards */
    .fi-ta-content:has(.kds-board),
    .fi-ta-ctn:has(.kds-board) {
        padding: 0 !important;
    }

    .kds-board {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
        padding: 1.25rem 1.25rem 0.75rem;
        box-sizing: border-box;
    }

    .kds-batch-group + .kds-batch-group {
        margin-top: 0.25rem;
    }

    .kds-batch-title {
        margin-bottom: 0.75rem;
        font-size: 0.8125rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        color: rgb(100 116 139);
    }

    .dark .kds-batch-title {
        color: rgb(148 163 184);
    }

    .kds-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(17.5rem, 1fr));
        gap: 1rem;
    }

    .kds-card {
        display: flex;
        flex-direction: column;
        min-height: 13rem;
        border-radius: 0.875rem;
        border: 1px solid rgb(226 232 240);
        background: rgb(255 255 255);
        box-shadow: 0 4px 16px -6px rgb(15 23 42 / 0.12);
        overflow: hidden;
        transition: box-shadow 160ms ease;
    }

    .kds-card:hover {
        box-shadow: 0 10px 28px -10px rgb(15 23 42 / 0.18);
    }

    .dark .kds-card {
        border-color: rgb(51 65 85);
        background: rgb(15 23 42);
        box-shadow: 0 6px 20px -8px rgb(0 0 0 / 0.45);
    }

    .kds-card--green {
        border-left: 6px solid rgb(34 197 94);
    }

    .kds-card--yellow {
        border-left: 6px solid rgb(234 179 8);
    }

    .kds-card--red {
        border-left: 6px solid rgb(239 68 68);
    }

    .kds-card--served {
        opacity: 0.8;
    }

    .kds-card__inner {
        display: flex;
        flex: 1;
        flex-direction: column;
        gap: 0;
        padding: 1.1rem 1.15rem 1rem;
    }

    .kds-card__head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.75rem;
        padding-bottom: 0.85rem;
        border-bottom: 1px solid rgb(241 245 249);
    }

    .dark .kds-card__head {
        border-bottom-color: rgb(51 65 85);
    }

    .kds-card__meta {
        display: flex;
        flex-direction: column;
        gap: 0.15rem;
        min-width: 0;
    }

    .kds-card__table {
        font-size: 1.125rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        color: rgb(15 23 42);
        line-height: 1.2;
    }

    .dark .kds-card__table {
        color: rgb(248 250 252);
    }

    .kds-card__order {
        font-size: 0.8125rem;
        font-weight: 500;
        color: rgb(148 163 184);
    }

    .kds-card__timer {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2.75rem;
        height: 2.75rem;
        padding: 0 0.45rem;
        border-radius: 9999px;
        font-size: 0.8125rem;
        font-weight: 800;
        font-variant-numeric: tabular-nums;
        white-space: nowrap;
        line-height: 1;
        flex-shrink: 0;
    }

    .kds-card__timer--green {
        background: rgb(220 252 231);
        color: rgb(21 128 61);
    }

    .kds-card__timer--yellow {
        background: rgb(254 249 195);
        color: rgb(161 98 7);
    }

    .kds-card__timer--red {
        background: rgb(254 226 226);
        color: rgb(185 28 28);
    }

    .dark .kds-card__timer--green {
        background: rgb(21 128 61 / 0.22);
        color: rgb(134 239 172);
    }

    .dark .kds-card__timer--yellow {
        background: rgb(161 98 7 / 0.25);
        color: rgb(253 224 71);
    }

    .dark .kds-card__timer--red {
        background: rgb(185 28 28 / 0.25);
        color: rgb(252 165 165);
    }

    .kds-card__body {
        display: flex;
        flex: 1;
        flex-direction: column;
        gap: 0.65rem;
        padding: 1rem 0 1.1rem;
    }

    .kds-card__line {
        display: flex;
        align-items: flex-start;
        gap: 0.55rem;
        margin: 0;
        font-size: 0.975rem;
        line-height: 1.35;
        color: rgb(30 41 59);
    }

    .dark .kds-card__line {
        color: rgb(226 232 240);
    }

    .kds-card__qty {
        font-weight: 800;
        color: rgb(15 23 42);
        flex-shrink: 0;
    }

    .dark .kds-card__qty {
        color: rgb(248 250 252);
    }

    .kds-card__name {
        font-weight: 600;
        min-width: 0;
    }

    .kds-card__extras {
        margin: 0.15rem 0 0;
        font-size: 0.8125rem;
        font-weight: 500;
        line-height: 1.4;
        color: rgb(100 116 139);
    }

    .dark .kds-card__extras {
        color: rgb(148 163 184);
    }

    .kds-card__notes {
        margin: 0;
        border-radius: 0.55rem;
        background: rgb(255 251 235);
        border: 1px solid rgb(253 230 138);
        padding: 0.45rem 0.6rem;
        font-size: 0.75rem;
        line-height: 1.4;
        color: rgb(146 64 14);
    }

    .dark .kds-card__notes {
        background: rgb(120 53 15 / 0.25);
        border-color: rgb(180 83 9 / 0.45);
        color: rgb(253 186 116);
    }

    .kds-card__footer {
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
        margin-top: auto;
    }

    .kds-card__status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 0.55rem 0.75rem;
        border-radius: 0.65rem;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        line-height: 1;
        text-align: center;
    }

    .kds-card__status--queued {
        background: rgb(241 245 249);
        color: rgb(71 85 105);
    }

    .kds-card__status--preparing {
        background: rgb(254 249 195);
        color: rgb(161 98 7);
    }

    .kds-card__status--ready {
        background: rgb(220 252 231);
        color: rgb(21 128 61);
    }

    .kds-card__status--served {
        background: rgb(219 234 254);
        color: rgb(29 78 216);
    }

    .dark .kds-card__status--queued {
        background: rgb(51 65 85);
        color: rgb(203 213 225);
    }

    .dark .kds-card__status--preparing {
        background: rgb(161 98 7 / 0.25);
        color: rgb(253 224 71);
    }

    .dark .kds-card__status--ready {
        background: rgb(21 128 61 / 0.25);
        color: rgb(134 239 172);
    }

    .dark .kds-card__status--served {
        background: rgb(29 78 216 / 0.25);
        color: rgb(147 197 253);
    }

    .kds-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        border-radius: 0.65rem;
        border: 1px solid transparent;
        padding: 0.7rem 0.9rem;
        font-size: 0.875rem;
        font-weight: 700;
        line-height: 1;
        cursor: pointer;
        transition: filter 140ms ease;
    }

    .kds-btn:hover {
        filter: brightness(0.96);
    }

    .kds-btn:disabled {
        opacity: 0.55;
        cursor: wait;
    }

    .kds-btn--primary {
        background: rgb(37 99 235);
        color: rgb(255 255 255);
    }

    .kds-btn--success {
        background: rgb(22 163 74);
        color: rgb(255 255 255);
    }

    .kds-btn--ghost {
        border-color: rgb(203 213 225);
        background: rgb(248 250 252);
        color: rgb(51 65 85);
    }

    .dark .kds-btn--ghost {
        border-color: rgb(71 85 105);
        background: rgb(30 41 59);
        color: rgb(226 232 240);
    }

    .kds-empty {
        border-radius: 1rem;
        border: 1px dashed rgb(203 213 225);
        padding: 2.75rem 1.5rem;
        text-align: center;
        background: rgb(248 250 252 / 0.6);
    }

    .dark .kds-empty {
        border-color: rgb(71 85 105);
        background: rgb(15 23 42 / 0.4);
    }

    .kds-empty__title {
        font-size: 1rem;
        font-weight: 700;
        color: rgb(15 23 42);
    }

    .dark .kds-empty__title {
        color: rgb(248 250 252);
    }

    .kds-empty__desc {
        margin-top: 0.35rem;
        font-size: 0.875rem;
        color: rgb(100 116 139);
    }
</style>

<div class="kds-board">
    @if ($items->isEmpty())
        <div class="kds-empty">
            <p class="kds-empty__title">Antrian kosong</p>
            <p class="kds-empty__desc">Item masuk setelah kasir menerima pembayaran.</p>
        </div>
    @else
        @foreach ($groups as $batchKey => $groupItems)
            @if ($isBatchTab)
                @php
                    /** @var OrderItem $firstItem */
                    $firstItem = $groupItems->first();
                    $batchQty = (int) $groupItems->sum('qty');
                @endphp
                <div class="kds-batch-group">
                    <div class="kds-batch-title">
                        {{ $firstItem->displayName() }} · {{ $batchQty }} porsi
                    </div>
            @endif

            <div class="kds-grid">
                @foreach ($groupItems as $record)
                    @continue(! $record instanceof OrderItem)
                    @php
                        $timerBand = $record->timerBand();
                        $tableCode = $record->order?->visit?->diningTable?->code;
                        $showAdvance = in_array($record->kds_status, ['queued', 'preparing', 'ready'], true)
                            && ($record->kds_status === 'ready' ? $canMarkServed : $canAdvance);
                        $showRevert = $record->canRevertServed() && $canAdvance;
                        $extras = collect([
                            filled($record->variant_name_snapshot) ? $record->variant_name_snapshot : null,
                            $record->modifiers->pluck('name_snapshot')->filter()->implode(', ') ?: null,
                        ])->filter()->implode(' · ');
                    @endphp

                    <article
                        wire:key="kds-item-{{ $record->id }}"
                        @class([
                            'kds-card',
                            'kds-card--'.$timerBand,
                            'kds-card--served' => $record->kds_status === 'served',
                        ])
                    >
                        <div class="kds-card__inner">
                            <div class="kds-card__head">
                                <div class="kds-card__meta">
                                    <span class="kds-card__table">Meja {{ $tableCode ?: '—' }}</span>
                                    <span class="kds-card__order">Pesanan #{{ $record->order?->number }}</span>
                                </div>
                                <span class="kds-card__timer kds-card__timer--{{ $timerBand }}">
                                    {{ $record->elapsedMinutes() }}m
                                </span>
                            </div>

                            <div class="kds-card__body">
                                <p class="kds-card__line">
                                    <span class="kds-card__qty">{{ $record->qty }}x</span>
                                    <span class="kds-card__name">
                                        {{ $record->name_snapshot }}
                                        @if (filled($extras))
                                            <span class="kds-card__extras">{{ $extras }}</span>
                                        @endif
                                    </span>
                                </p>
                                @if (filled($record->notes))
                                    <p class="kds-card__notes">Catatan: {{ $record->notes }}</p>
                                @endif
                            </div>

                            <div class="kds-card__footer">
                                @if ($showAdvance)
                                    <button
                                        type="button"
                                        class="kds-btn {{ $record->kds_status === 'ready' ? 'kds-btn--success' : 'kds-btn--primary' }}"
                                        wire:click="mountTableAction('advance', '{{ $record->getKey() }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="mountTableAction"
                                    >
                                        {{ $advanceLabel($record) }}
                                    </button>
                                @elseif (! $showRevert)
                                    <span class="kds-card__status kds-card__status--{{ $record->kds_status }}">
                                        {{ $statusLabel($record->kds_status) }}
                                    </span>
                                @endif

                                @if ($showRevert)
                                    <button
                                        type="button"
                                        class="kds-btn kds-btn--ghost"
                                        wire:click="mountTableAction('revertServed', '{{ $record->getKey() }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="mountTableAction"
                                    >
                                        Kembali ke siap
                                    </button>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            @if ($isBatchTab)
                </div>
            @endif
        @endforeach
    @endif
</div>
