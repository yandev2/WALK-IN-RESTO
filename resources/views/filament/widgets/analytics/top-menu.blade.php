<x-filament-widgets::widget
    :attributes="
        (new \Illuminate\View\ComponentAttributeBag)
            ->merge([
                'wire:poll.' . $this->getPollingInterval() => $this->getPollingInterval() ? true : null,
            ], escape: false)
            ->class(['fi-wi-analytics-top-menu'])
    "
>
    @php
        $theme = $this->analyticsTheme();
        $rows = $this->getMenuRows();
    @endphp

    <div
        class="ad-dashboard"
        style="--ad-primary: {{ $theme['primary'] }}; --ad-primary-dark: {{ $theme['primary_dark'] }}; --ad-accent: {{ $theme['accent'] }}; --ad-ink: {{ $theme['ink'] }};"
    >
        @include('filament.widgets.analytics._styles')

        <div class="ad-card">
            @include('filament.widgets.analytics._section-header', [
                'title' => 'Menu terlaris ('.$this->analyticsRangeLabel().')',
                'subtitle' => 'Diurutkan berdasarkan jumlah porsi terjual.',
            ])

            @if ($rows === [])
                <div class="ad-empty">
                    <p class="ad-empty__title">Belum ada penjualan</p>
                    <p class="ad-empty__desc">Item terlaris muncul setelah ada order lunas.</p>
                </div>
            @else
                <div class="ad-table-wrap">
                    <table class="ad-table">
                        <thead>
                            <tr>
                                <th style="width: 3rem;">#</th>
                                <th>Menu</th>
                                <th style="text-align: right;">Qty</th>
                                <th style="text-align: right;">Omzet item</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr>
                                    <td>
                                        <span @class(['ad-rank', 'ad-rank--top' => $row['rank'] <= 3])>
                                            {{ $row['rank'] }}
                                        </span>
                                    </td>
                                    <td style="font-weight: 600;">{{ $row['name'] }}</td>
                                    <td style="text-align: right;">
                                        <span class="ad-qty-badge">{{ $row['total_qty'] }}</span>
                                    </td>
                                    <td style="text-align: right; font-variant-numeric: tabular-nums; font-weight: 600;">
                                        {{ $row['revenue_formatted'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-filament-widgets::widget>
