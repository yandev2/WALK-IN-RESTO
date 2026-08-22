<style>
    .kds-legend {
        border-radius: 0.875rem;
        border: 1px solid rgb(226 232 240);
        background: rgb(248 250 252);
        padding: 0.85rem 1.1rem;
        font-size: 0.8125rem;
        line-height: 1.5;
        color: rgb(71 85 105);
    }

    .dark .kds-legend {
        border-color: rgb(55 65 81);
        background: rgb(15 23 42 / 0.55);
        color: rgb(203 213 225);
    }

    .kds-legend__row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.55rem 1.1rem;
    }

    .kds-legend__label {
        font-weight: 700;
        color: rgb(51 65 85);
    }

    .dark .kds-legend__label {
        color: rgb(226 232 240);
    }

    .kds-legend__chip {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        white-space: nowrap;
        border-radius: 9999px;
        background: rgb(255 255 255);
        border: 1px solid rgb(226 232 240);
        padding: 0.2rem 0.6rem 0.2rem 0.4rem;
    }

    .dark .kds-legend__chip {
        background: rgb(30 41 59);
        border-color: rgb(71 85 105);
    }

    .kds-legend__dot {
        width: 0.625rem;
        height: 0.625rem;
        border-radius: 9999px;
        flex-shrink: 0;
    }

    .kds-legend__dot--green {
        background: rgb(34 197 94);
    }

    .kds-legend__dot--yellow {
        background: rgb(234 179 8);
    }

    .kds-legend__dot--red {
        background: rgb(239 68 68);
    }

    .kds-legend__note {
        margin-top: 0.45rem;
        color: rgb(100 116 139);
        font-size: 0.75rem;
    }

    .dark .kds-legend__note {
        color: rgb(148 163 184);
    }
</style>

<div class="kds-legend">
    <div class="kds-legend__row">
        <span class="kds-legend__label">Timer dari kasir terima</span>
        <span class="kds-legend__chip">
            <span class="kds-legend__dot kds-legend__dot--green" aria-hidden="true"></span>
            hijau &lt; 10 m
        </span>
        <span class="kds-legend__chip">
            <span class="kds-legend__dot kds-legend__dot--yellow" aria-hidden="true"></span>
            kuning 10–20 m
        </span>
        <span class="kds-legend__chip">
            <span class="kds-legend__dot kds-legend__dot--red" aria-hidden="true"></span>
            merah &gt; 20 m
        </span>
    </div>
    <p class="kds-legend__note">Hanya pesanan yang sudah dibayar.</p>
</div>
