<x-filament-widgets::widget
    :attributes="
        (new \Illuminate\View\ComponentAttributeBag)
            ->merge([
                'wire:poll.' . $this->getPollingInterval() => $this->getPollingInterval() ? true : null,
            ], escape: false)
            ->class(['fi-wi-analytics-top-menu', 'h-full'])
    "
>
    @php
        $rows = $this->getMenuRows();
    @endphp

    <div class="vision-card h-full flex flex-col justify-between p-5 sm:p-6">
        <div>
            {{-- Header --}}
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-white/5">
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white tracking-wide">
                        Menu terlaris ({{ $this->analyticsRangeLabel() }})
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Diurutkan berdasarkan jumlah porsi terjual
                    </p>
                </div>
                <div class="vision-icon-box vision-icon-box-purple shadow-sm">
                    <x-filament::icon icon="heroicon-o-fire" class="h-5 w-5 text-white" />
                </div>
            </div>

            {{-- Table or Empty State --}}
            @if ($rows === [])
                <div class="py-8 flex flex-col items-center justify-center text-center">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-400 mb-2">
                        <x-filament::icon icon="heroicon-o-cake" class="h-6 w-6 text-slate-400" />
                    </div>
                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300">
                        Belum ada penjualan
                    </p>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                        Item terlaris muncul setelah ada order lunas.
                    </p>
                </div>
            @else
                <div class="mt-3 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 dark:border-white/5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                <th class="pb-2.5 w-12 text-center">#</th>
                                <th class="pb-2.5">Menu</th>
                                <th class="pb-2.5 text-right">Porsi</th>
                                <th class="pb-2.5 text-right">Total Omzet</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @foreach ($rows as $row)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.02] transition-colors">
                                    <td class="py-3 text-center">
                                        @if ($row['rank'] <= 3)
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-black text-white bg-gradient-to-br from-sky-500 to-cyan-400 shadow-sm shadow-sky-500/30">
                                                {{ $row['rank'] }}
                                            </span>
                                        @else
                                            <span class="text-xs font-semibold text-slate-400">
                                                {{ $row['rank'] }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 font-bold text-slate-900 dark:text-white">
                                        {{ $row['name'] }}
                                    </td>
                                    <td class="py-3 text-right">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-extrabold bg-sky-500/10 text-sky-600 dark:text-cyan-400 border border-sky-500/20">
                                            {{ $row['total_qty'] }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-right font-mono font-bold text-slate-800 dark:text-slate-200">
                                        {{ $row['revenue_formatted'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-white/5 text-right">
            <a
                href="{{ \App\Filament\Resources\MenuItems\MenuItemResource::getUrl() }}"
                class="text-xs font-semibold text-sky-600 dark:text-sky-400 hover:text-sky-500 dark:hover:text-cyan-300 transition-colors inline-flex items-center gap-1"
            >
                Kelola Semua Menu →
            </a>
        </div>
    </div>
</x-filament-widgets::widget>
