<x-filament-widgets::widget :attributes="new \Illuminate\View\ComponentAttributeBag()
    ->merge(
        [
            'wire:poll.' . $this->getPollingInterval() => $this->getPollingInterval() ? true : null,
        ],
        escape: false,
    )
    ->class(['fi-wi-analytics-kpi', 'w-full'])">
    @php
        $visionData = $this->getVisionKpiData();
        $cards = $visionData['cards'];
        $pills = $visionData['pills'];
    @endphp

    <div class="space-y-3 w-full">
        {{-- Row 1: Vision UI Main Stat Cards (2 Columns Grid) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 2xl:grid-cols-4 gap-4 sm:gap-5">
            @foreach ($cards as $card)
                <div
                    class="vision-card p-4 sm:p-5 flex flex-col justify-between h-full group hover:-translate-y-1 transition-all duration-300">
                    <div>
                        {{-- Top Header Row --}}
                        <div class="flex items-center justify-between gap-2">
                            <div class="min-w-0 flex-1">
                                <p
                                    class="text-[11px] font-bold tracking-wider uppercase text-slate-500 dark:text-slate-400">
                                    {{ $card['title'] }}
                                </p>
                                <span class="sr-only">{{ $card['label'] }}</span>
                            </div>
                            <div class="vision-icon-box {{ $card['icon_class'] }} shadow-md">
                                <x-filament::icon :icon="$card['icon']" class="h-5 w-5 text-white" />
                            </div>
                        </div>

                        {{-- Value & Badge Stack --}}
                        <div class="mt-3">
                            <div class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white cursor-default"
                                @if (filled($card['raw_value'] ?? null)) title="{{ $card['raw_value'] }}" @endif>
                                {{ $card['value'] }}
                            </div>
                            @if (filled($card['badge']))
                                @php
                                    $badgeClasses = match ($card['badge_tone']) {
                                        'up',
                                        'success'
                                            => 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border-emerald-500/20',
                                        'down',
                                        'danger'
                                            => 'bg-rose-500/15 text-rose-600 dark:text-rose-400 border-rose-500/20',
                                        'warning'
                                            => 'bg-amber-500/15 text-amber-600 dark:text-amber-400 border-amber-500/20',
                                        'info' => 'bg-cyan-500/15 text-cyan-600 dark:text-cyan-400 border-cyan-500/20',
                                        default
                                            => 'bg-slate-500/10 text-slate-600 dark:text-slate-300 border-slate-500/20',
                                    };
                                @endphp
                                <div class="mt-2.5">
                                    <span
                                        class="inline-flex items-center text-[11px] font-bold px-2.5 py-0.5 rounded-full border {{ $badgeClasses }}">
                                        {{ $card['badge'] }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Footer Row --}}
                    <div
                        class="mt-4 pt-3 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span>{{ $card['footer_text'] }}</span>
                        @if (filled($card['action_url']))
                            <a href="{{ $card['action_url'] }}"
                                class="inline-flex items-center gap-1 font-semibold text-sky-600 dark:text-sky-400 hover:text-sky-500 dark:hover:text-cyan-300 transition-colors shrink-0 ml-2">
                                {{ $card['action_label'] }}
                            </a>
                        @elseif (filled($card['action_label']))
                            <span class="font-semibold text-slate-400 dark:text-slate-500 shrink-0 ml-2">
                                {{ $card['action_label'] }}
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Sub-Row: Operational Breakdown Pills --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
            @foreach ($pills as $pill)
                <div class="vision-pill-card px-3.5 py-2 flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2 text-slate-600 dark:text-slate-300 font-medium">
                        <x-filament::icon :icon="$pill['icon']" class="h-4 w-4 text-sky-500 dark:text-cyan-400" />
                        <span>{{ $pill['label'] }}</span>
                    </div>
                    <span class="font-bold text-slate-800 dark:text-white">
                        {{ $pill['value'] }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</x-filament-widgets::widget>
