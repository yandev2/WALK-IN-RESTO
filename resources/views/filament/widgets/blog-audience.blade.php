<x-filament-widgets::widget>
    <div class="vision-card p-5 sm:p-6 flex flex-col justify-between h-full">
        <!-- Header -->
        <div class="pb-3 border-b border-slate-100 dark:border-white/5">
            <h3 class="text-base font-bold text-slate-900 dark:text-white tracking-wide">
                Profil & Perangkat Pembaca
            </h3>
            <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">
                Distribusi platform & bahasa pengunjung ({{ $period }} hari)
            </p>
        </div>

        <!-- Sebaran Perangkat (Device Breakdown - Vision UI Style) -->
        <div class="space-y-4 my-2">
            <!-- Desktop -->
            <div>
                <div class="flex items-center justify-between text-xs">
                    <span class="flex items-center gap-1.5 font-medium text-slate-700 dark:text-gray-300">
                        <x-filament::icon icon="heroicon-o-computer-desktop" class="h-4 w-4 text-[#0075ff] dark:text-[#2cd9ff]" />
                        Desktop / Laptop
                    </span>
                    <span class="font-bold text-slate-900 dark:text-white">
                        {{ $devices['desktop_percent'] }}% <span class="text-xs font-normal text-slate-500 dark:text-gray-400">({{ number_format($devices['desktop']) }})</span>
                    </span>
                </div>
                <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-white/5">
                    <div class="h-full rounded-full bg-gradient-to-r from-[#0075ff] to-[#2cd9ff] shadow-[0_0_8px_rgba(44,217,255,0.4)]" style="width: {{ $devices['desktop_percent'] }}%"></div>
                </div>
            </div>

            <!-- Mobile -->
            <div>
                <div class="flex items-center justify-between text-xs">
                    <span class="flex items-center gap-1.5 font-medium text-slate-700 dark:text-gray-300">
                        <x-filament::icon icon="heroicon-o-device-phone-mobile" class="h-4 w-4 text-[#01b574]" />
                        Mobile / Smartphone
                    </span>
                    <span class="font-bold text-slate-900 dark:text-white">
                        {{ $devices['mobile_percent'] }}% <span class="text-xs font-normal text-slate-500 dark:text-gray-400">({{ number_format($devices['mobile']) }})</span>
                    </span>
                </div>
                <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-white/5">
                    <div class="h-full rounded-full bg-gradient-to-r from-[#01b574] to-[#05cd99] shadow-[0_0_8px_rgba(1,181,116,0.4)]" style="width: {{ $devices['mobile_percent'] }}%"></div>
                </div>
            </div>

            @if ($devices['tablet'] > 0)
                <!-- Tablet -->
                <div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="flex items-center gap-1.5 font-medium text-slate-700 dark:text-gray-300">
                            <x-filament::icon icon="heroicon-o-device-tablet" class="h-4 w-4 text-[#7551ff]" />
                            Tablet
                        </span>
                        <span class="font-bold text-slate-900 dark:text-white">
                            {{ $devices['tablet_percent'] }}% <span class="text-xs font-normal text-slate-500 dark:text-gray-400">({{ number_format($devices['tablet']) }})</span>
                        </span>
                    </div>
                    <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-white/5">
                        <div class="h-full rounded-full bg-gradient-to-r from-[#7551ff] to-[#3965ff] shadow-[0_0_8px_rgba(117,81,255,0.4)]" style="width: {{ $devices['tablet_percent'] }}%"></div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sebaran Bahasa (Locale) -->
        <div class="border-t border-slate-100 dark:border-white/5 pt-3">
            <span class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-gray-400">Preferensi Bahasa Konten</span>
            <div class="flex flex-wrap gap-2">
                @forelse ($locales as $loc)
                    <span class="vision-pill-card inline-flex items-center gap-1.5 px-2.5 py-1 text-xs">
                        <span class="font-bold uppercase text-[#0284c7] dark:text-[#2cd9ff]">{{ $loc['locale'] ?: 'ID' }}</span>
                        <span class="text-slate-500 dark:text-gray-400">· {{ number_format($loc['views']) }} views</span>
                    </span>
                @empty
                    <span class="text-xs text-slate-400 dark:text-gray-500">Semua Bahasa Indonesia (Default)</span>
                @endforelse
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
