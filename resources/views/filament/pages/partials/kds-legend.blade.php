<style>
    .kds-legend {
        border-radius: 0.75rem;
        border: 1px solid rgb(226 232 240);
        background: rgb(248 250 252);
        padding: 0.65rem 1rem;
        font-size: 0.8125rem;
        line-height: 1.45;
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
        gap: 0.45rem 1rem;
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
        gap: 0.35rem;
        white-space: nowrap;
    }

    .kds-legend__dot {
        width: 0.55rem;
        height: 0.55rem;
        border-radius: 9999px;
        flex-shrink: 0;
    }

    .kds-legend__dot--green { background: rgb(34 197 94); }
    .kds-legend__dot--yellow { background: rgb(234 179 8); }
    .kds-legend__dot--red { background: rgb(239 68 68); }

    .kds-row--green {
        box-shadow: inset 4px 0 0 rgb(34 197 94);
    }

    .kds-row--yellow {
        box-shadow: inset 4px 0 0 rgb(234 179 8);
    }

    .kds-row--red {
        box-shadow: inset 4px 0 0 rgb(239 68 68);
    }
</style>

<div class="kds-legend">
    <div class="kds-legend__row">
        <span class="kds-legend__label">Timer</span>
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
</div>
