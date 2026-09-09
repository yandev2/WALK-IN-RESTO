<x-filament-widgets::widget class="fi-wi-welcome-banner w-full">
    @php
        $banner = $this->getBannerData();
        $theme = $banner['theme'];
    @endphp

    <div class="vision-card p-6 sm:p-7 relative overflow-hidden"
        style="--wb-primary: {{ $theme['primary'] }}; --wb-primary-dark: {{ $theme['primary_dark'] }}; --wb-accent: {{ $theme['accent'] }};"
        x-data="{
            time: '00:00:00',
            date: '',
            timer: null,
            timezone: @js($banner['timezone']),
            tick() {
                const now = new Date();
                const timeParts = new Intl.DateTimeFormat('en-GB', {
                    timeZone: this.timezone,
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false,
                }).formatToParts(now);
        
                const pick = (type) => timeParts.find(part => part.type === type)?.value ?? '00';
        
                this.time = `${pick('hour')}:${pick('minute')}:${pick('second')}`;
                this.date = now.toLocaleDateString('id-ID', {
                    timeZone: this.timezone,
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric',
                });
            },
            start() {
                this.tick();
                this.timer = setInterval(() => this.tick(), 1000);
            },
        }" x-init="start();
        return () => { timer && clearInterval(timer) };">
        {{-- Background Soft Glow Gradient Accents --}}
        <div
            class="absolute -top-24 -left-24 w-60 h-60 bg-sky-500/10 dark:bg-sky-500/15 rounded-full blur-3xl pointer-events-none">
        </div>
        <div
            class="absolute -bottom-24 -right-24 w-60 h-60 bg-cyan-500/10 dark:bg-cyan-500/15 rounded-full blur-3xl pointer-events-none">
        </div>

        {{-- Top Status Pills Row --}}
        <div
            class="relative z-10 flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-100 dark:border-white/5">
            <div class="flex items-center gap-2">
                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold tracking-wide bg-sky-500/10 dark:bg-sky-400/10 text-sky-600 dark:text-cyan-400 border border-sky-500/20">
                    <span class="w-2 h-2 rounded-full bg-sky-500 dark:bg-cyan-400 animate-pulse"></span>
                    OVERVIEW RESTORAN & AKTIVITAS
                </span>
            </div>

            <div class="flex items-center gap-3">
                @if ($banner['is_kds_active'])
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold tracking-wide bg-emerald-500/10 dark:bg-emerald-400/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 dark:bg-emerald-400 animate-pulse"></span>
                        Layanan Kasir & KDS Aktif
                    </span>
                @else
                    <a href="{{ $banner['billing_url'] ?? '#' }}"
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold tracking-wide bg-rose-500/15 text-rose-600 dark:text-rose-400 border border-rose-500/25 hover:bg-rose-500/25 transition-colors">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        Layanan Kasir & KDS Non-Aktif (Ada Tunggakan)
                    </a>
                @endif

                {{-- Live Clock Capsule --}}
                <div
                    class="hidden sm:flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300">
                    <x-filament::icon icon="heroicon-o-clock" class="h-3.5 w-3.5 text-sky-500 dark:text-cyan-400" />
                    <span class="sr-only">Waktu sekarang</span>
                    <span x-text="time" class="font-mono font-bold"></span>
                    <span class="text-slate-400">·</span>
                    <span x-text="date" class="text-[11px] text-slate-500 dark:text-slate-400"></span>
                    <span
                        class="text-[10px] uppercase font-bold text-sky-600 dark:text-cyan-400">{{ $banner['timezone_label'] }}</span>
                </div>
            </div>
        </div>

        {{-- Main Greeting & Bio Row --}}
        <div class="relative z-10 mt-5">
            <h2
                class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white flex items-center gap-2 flex-wrap">
                <span class="sr-only">Halo, {{ $banner['user_name'] }}</span>
                <span>{{ $banner['greeting'] }},</span>
                <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-sky-600 via-sky-500 to-cyan-500 dark:from-sky-400 dark:via-cyan-300 dark:to-cyan-400">
                    {{ $banner['user_name'] }}!
                </span>
                <span></span>
            </h2>

            <p class="mt-2 text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-relaxed max-w-4xl">
                <strong class="font-semibold text-slate-800 dark:text-white">{{ $banner['role_name'] }}.</strong>
                Selamat datang di pusat kendali restoran
                @if (filled($banner['restaurant_name']))
                    <span class="font-semibold text-sky-600 dark:text-cyan-300">{{ $banner['restaurant_name'] }}</span>.
                @endif
                Pantau interaksi pengunjung, tanggapi pesanan kasir, dan kelola operasional menu Anda secara
                terstruktur.
            </p>
        </div>

        {{-- Quick Actions Buttons Row --}}
        <div
            class="relative z-10 mt-6 pt-5 border-t border-slate-100 dark:border-white/5 flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2.5">
                {{-- Primary Action: Buat Pesanan Baru (Hanya tampil jika KDS aktif) --}}
                @if ($banner['is_kds_active'] && filled($banner['order_url']))
                    <a href="{{ $banner['order_url'] }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs sm:text-sm font-bold text-white bg-gradient-to-r from-sky-600 to-cyan-500 hover:from-sky-500 hover:to-cyan-400 shadow-md shadow-sky-500/25 transition-all duration-200 hover:-translate-y-0.5">
                        <x-filament::icon icon="heroicon-o-plus-circle" class="h-4 w-4" />
                        <span>+ Buat Pesanan Baru</span>
                    </a>
                @endif

                {{-- Action 2: Pesanan Masuk Pill (Hanya tampil jika KDS aktif) --}}
                @if ($banner['is_kds_active'] && filled($banner['order_url']))
                    <a href="{{ $banner['order_url'] }}"
                        class="vision-pill-card inline-flex items-center gap-2 px-3.5 py-2 text-xs sm:text-sm font-semibold hover:-translate-y-0.5">
                        <x-filament::icon icon="heroicon-o-inbox" class="h-4 w-4 text-slate-500 dark:text-slate-400" />
                        <span>Pesanan Masuk</span>
                        @if ($banner['pending_orders'] > 0)
                            <span
                                class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-500 text-white shadow-sm animate-pulse">
                                {{ $banner['pending_orders'] }} Baru
                            </span>
                        @else
                            <span
                                class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-200 dark:bg-white/10 text-slate-600 dark:text-slate-300">
                                0
                            </span>
                        @endif
                    </a>
                @endif

                {{-- Tombol bayar jika ada tagihan overdue / KDS non-aktif --}}
                @if (! $banner['is_kds_active'] && filled($banner['billing_url']))
                    <a href="{{ $banner['billing_url'] }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs sm:text-sm font-bold text-white bg-gradient-to-r from-rose-600 to-amber-500 hover:from-rose-500 hover:to-amber-400 shadow-md shadow-rose-500/25 transition-all duration-200 hover:-translate-y-0.5">
                        <x-filament::icon icon="heroicon-o-credit-card" class="h-4 w-4" />
                        <span>Bayar Tagihan Billing</span>
                    </a>
                @endif

                {{-- Action 3: Kelola Menu --}}
                @if (filled($banner['menu_url']))
                    <a href="{{ $banner['menu_url'] }}"
                        class="vision-pill-card inline-flex items-center gap-2 px-3.5 py-2 text-xs sm:text-sm font-semibold hover:-translate-y-0.5">
                        <x-filament::icon icon="heroicon-o-squares-2x2"
                            class="h-4 w-4 text-slate-500 dark:text-slate-400" />
                        <span>Kelola Menu</span>
                    </a>
                @endif
            </div>

            {{-- Right Aligned: Lihat Situs Publik --}}
            @if (filled($banner['public_url']))
                <a href="{{ $banner['public_url'] }}" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200 bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10 border border-slate-200 dark:border-white/10 transition-all duration-200 hover:-translate-y-0.5">
                    <x-filament::icon icon="heroicon-o-arrow-top-right-on-square"
                        class="h-4 w-4 text-sky-500 dark:text-cyan-400" />
                    <span>Lihat Situs Publik</span>
                </a>
            @endif
        </div>
    </div>
</x-filament-widgets::widget>
