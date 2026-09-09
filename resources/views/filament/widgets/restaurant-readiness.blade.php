<x-filament-widgets::widget class="h-full">
    @php
        $data = $this->getReadinessData();
    @endphp

    <div class="vision-card h-full flex flex-col justify-between p-5 sm:p-6">
        <div>
            {{-- Header --}}
            <div class="pb-3 border-b border-slate-100 dark:border-white/5">
                <h3 class="text-base font-bold text-slate-900 dark:text-white tracking-wide">
                    Kesiapan Restoran Hari Ini
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    Kesiapan menu, meja & profil operasional Anda
                </p>
            </div>

            {{-- Radial Gauge Center --}}
            <div
                class="relative mt-3 flex flex-col items-center justify-center"
                x-data="visionGaugeChart({ value: @js($data['percentage']), label: 'Kesiapan Resto' })"
                x-init="init()"
                x-destroy="destroy()"
                wire:ignore
            >
                <div x-ref="chart" class="w-full flex justify-center"></div>

                {{-- Gauge 0% and 100% boundary labels --}}
                <div class="w-full px-10 -mt-7 flex items-center justify-between text-[11px] font-semibold text-slate-400 dark:text-slate-500">
                    <span>0%</span>
                    <span class="text-xs font-bold text-slate-800 dark:text-white">
                        {{ $data['status_title'] }}
                    </span>
                    <span>100%</span>
                </div>
            </div>

            {{-- 3 Mini Pill Cards --}}
            <div class="grid grid-cols-3 gap-2.5 mt-5">
                <a
                    href="{{ $data['menu_url'] ?? '#' }}"
                    class="vision-pill-card p-3 flex flex-col items-center justify-center text-center group hover:-translate-y-0.5 transition-transform"
                >
                    <span class="text-lg font-extrabold text-slate-900 dark:text-white group-hover:text-sky-500 transition-colors">
                        {{ $data['active_menu_count'] }}
                    </span>
                    <span class="text-[11px] font-medium text-slate-500 dark:text-slate-400 mt-0.5">
                        Menu Aktif
                    </span>
                </a>

                <a
                    href="{{ $data['tables_url'] ?? '#' }}"
                    class="vision-pill-card p-3 flex flex-col items-center justify-center text-center group hover:-translate-y-0.5 transition-transform"
                >
                    <span class="text-lg font-extrabold text-slate-900 dark:text-white group-hover:text-emerald-500 transition-colors">
                        {{ $data['ready_tables_count'] }}
                    </span>
                    <span class="text-[11px] font-medium text-slate-500 dark:text-slate-400 mt-0.5">
                        Meja Siap
                    </span>
                </a>

                <div class="vision-pill-card p-3 flex flex-col items-center justify-center text-center">
                    <span class="text-lg font-extrabold text-amber-500">
                        {{ $data['reviews_count'] > 0 ? $data['avg_rating'] . ' ★' : 'Baru' }}
                    </span>
                    <span class="text-[11px] font-medium text-slate-500 dark:text-slate-400 mt-0.5">
                        Rating Ulasan
                    </span>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="mt-5 pt-3 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
            <span class="inline-flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400 font-semibold">
                <x-filament::icon icon="heroicon-o-check-circle" class="h-4 w-4" />
                <span>Status Operasional Prima</span>
            </span>
            <span class="font-bold text-slate-700 dark:text-slate-300">
                {{ $data['active_menu_count'] }} Menu Siap Jual
            </span>
        </div>
    </div>
</x-filament-widgets::widget>
