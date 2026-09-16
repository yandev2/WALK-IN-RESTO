<x-filament-widgets::widget>
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <!-- Card 1: Total Trafik -->
        <div class="vision-card p-4 sm:p-5 transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[11px] font-semibold tracking-wider text-slate-500 dark:text-gray-400 uppercase">
                        Total Trafik ({{ $period }} Hari)
                    </span>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                            {{ number_format($viewsGrowth['current']) }}
                        </span>
                        <span class="inline-flex items-center text-xs font-semibold {{ $viewsGrowth['is_positive'] ? 'text-[#01b574]' : 'text-[#e31a1a]' }}">
                            {{ $viewsGrowth['is_positive'] ? '+' : '-' }}{{ $viewsGrowth['percentage'] }}%
                        </span>
                    </div>
                </div>
                <div class="vision-icon-box vision-icon-box-blue">
                    <x-filament::icon icon="heroicon-o-eye" class="h-5 w-5 text-white" />
                </div>
            </div>
            <div class="mt-3 flex items-center text-xs text-slate-500 dark:text-gray-400 border-t border-slate-100 dark:border-white/5 pt-2.5">
                <span>Total akumulasi: <strong class="text-slate-800 dark:text-white">{{ number_format($totalViews) }}</strong> views</span>
            </div>
        </div>

        <!-- Card 2: Pembaca Unik -->
        <div class="vision-card p-4 sm:p-5 transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[11px] font-semibold tracking-wider text-slate-500 dark:text-gray-400 uppercase">
                        Pembaca Unik
                    </span>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                            {{ number_format($visitorsGrowth['current']) }}
                        </span>
                        <span class="inline-flex items-center text-xs font-semibold {{ $visitorsGrowth['is_positive'] ? 'text-[#01b574]' : 'text-[#e31a1a]' }}">
                            {{ $visitorsGrowth['is_positive'] ? '+' : '-' }}{{ $visitorsGrowth['percentage'] }}%
                        </span>
                    </div>
                </div>
                <div class="vision-icon-box vision-icon-box-green">
                    <x-filament::icon icon="heroicon-o-users" class="h-5 w-5 text-white" />
                </div>
            </div>
            <div class="mt-3 flex items-center text-xs text-slate-500 dark:text-gray-400 border-t border-slate-100 dark:border-white/5 pt-2.5">
                <span>Pengunjung unik teridentifikasi</span>
            </div>
        </div>

        <!-- Card 3: Interaksi & Suka -->
        <div class="vision-card p-4 sm:p-5 transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[11px] font-semibold tracking-wider text-slate-500 dark:text-gray-400 uppercase">
                        Interaksi & Likes
                    </span>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                            {{ number_format($progress['total_likes']) }}
                        </span>
                        <span class="inline-flex items-center text-xs font-semibold text-[#0284c7] dark:text-[#2cd9ff]">
                            {{ number_format($progress['total_comments']) }} Komentar
                        </span>
                    </div>
                </div>
                <div class="vision-icon-box vision-icon-box-purple">
                    <x-filament::icon icon="heroicon-o-heart" class="h-5 w-5 text-white" />
                </div>
            </div>
            <div class="mt-3 flex items-center text-xs text-slate-500 dark:text-gray-400 border-t border-slate-100 dark:border-white/5 pt-2.5">
                <span>Respon positif & komentar artikel</span>
            </div>
        </div>

        <!-- Card 4: Artikel & Target -->
        <div class="vision-card p-4 sm:p-5 transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[11px] font-semibold tracking-wider text-slate-500 dark:text-gray-400 uppercase">
                        Kesehatan Konten
                    </span>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                            {{ number_format($progress['total_published']) }}
                        </span>
                        <span class="inline-flex items-center text-xs font-semibold text-[#d97706] dark:text-[#ffb547]">
                            {{ $progress['published_this_month'] }}/{{ $progress['monthly_target'] }} Bulan Ini
                        </span>
                    </div>
                </div>
                <div class="vision-icon-box vision-icon-box-orange">
                    <x-filament::icon icon="heroicon-o-document-text" class="h-5 w-5 text-white" />
                </div>
            </div>
            <div class="mt-3 flex items-center text-xs text-slate-500 dark:text-gray-400 border-t border-slate-100 dark:border-white/5 pt-2.5">
                <span>Target bulanan: <strong class="text-slate-800 dark:text-white">{{ $progress['monthly_target_percent'] }}%</strong></span>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
