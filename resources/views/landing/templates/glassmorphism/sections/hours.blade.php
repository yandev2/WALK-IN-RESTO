@php
    use Illuminate\Support\Carbon;

    $hoursCopy = $layout->copyFor('hours');
    $hours = $outlet?->operatingHours?->sortBy('day_of_week') ?? collect();
    $dayNames = [
        0 => 'Minggu',
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
    ];
    $todayDay = now()->dayOfWeek;

    $formatTime = function ($time): string {
        if (blank($time)) {
            return '—';
        }
        return Carbon::parse($time)->format('H.i');
    };
@endphp

@if ($outlet)
    <section id="jam-buka" class="scroll-mt-24 py-8 sm:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="rounded-[2.8rem] bg-white/25 dark:bg-white/[0.06] backdrop-blur-2xl border border-white/50 dark:border-white/15 p-6 sm:p-10 shadow-[0_20px_70px_rgba(0,0,0,0.07),inset_0_1px_2px_rgba(255,255,255,0.7)] dark:shadow-[0_20px_70px_rgba(0,0,0,0.5),inset_0_1px_2px_rgba(255,255,255,0.15)]">
                
                {{-- Header --}}
                <div class="text-center max-w-xl mx-auto mb-8">
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-primary bg-primary/15 dark:bg-primary/20 px-4 py-1.5 rounded-full border border-primary/30 backdrop-blur-xl shadow-xs">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $hoursCopy['label'] ?? 'Waktu Layanan' }}</span>
                    </span>
                    <h2 class="mt-4 font-display text-2xl sm:text-3xl lg:text-4xl font-extrabold text-zinc-900 dark:text-white tracking-tight drop-shadow-2xs">
                        {{ $hoursCopy['title'] ?? 'Jam Operasional Restoran' }}
                    </h2>
                    <p class="mt-2 text-xs sm:text-sm text-zinc-700 dark:text-zinc-300">
                        {{ $hoursCopy['subtitle'] ?? 'Kami siap menyambut dan melayani Anda pada jam-jam berikut.' }}
                    </p>
                </div>

                {{-- Status Banner --}}
                <div class="mb-8 flex items-center justify-between rounded-2xl bg-white/40 dark:bg-white/10 backdrop-blur-xl border border-white/50 dark:border-white/15 p-4 shadow-md">
                    <div class="flex items-center gap-3">
                        <span class="relative flex h-3.5 w-3.5">
                            @if ($isOpenNow)
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-emerald-500"></span>
                            @else
                                <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-zinc-400"></span>
                            @endif
                        </span>
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-zinc-900 dark:text-white">
                                {{ $isOpenNow ? 'Restoran Buka Sekarang' : 'Restoran Sedang Tutup' }}
                            </span>
                            <span class="text-[11px] text-zinc-500 dark:text-zinc-400">
                                Waktu Lokal ({{ $restaurant->timezone ?? 'WIB' }})
                            </span>
                        </div>
                    </div>

                    @if ($whatsappUrl)
                        <a
                            href="{{ $whatsappUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-1.5 rounded-full bg-primary/15 hover:bg-primary/25 text-primary border border-primary/30 px-4 py-2 text-xs font-bold transition shadow-xs"
                        >
                            <span>Chat WA</span>
                        </a>
                    @endif
                </div>

                {{-- Day by Day Schedule List --}}
                <div class="divide-y divide-black/5 dark:divide-white/10">
                    @foreach ($dayNames as $dayIndex => $dayName)
                        @php
                            $dayHour = $hours->firstWhere('day_of_week', $dayIndex);
                            $isToday = $dayIndex === $todayDay;
                            $isDayClosed = $dayHour ? (bool) $dayHour->is_closed : false;
                        @endphp
                        <div class="py-3 sm:py-3.5 px-3 flex items-center justify-between rounded-xl transition-colors {{ $isToday ? 'bg-primary/15 border border-primary/30 font-bold shadow-xs' : '' }}">
                            <div class="flex items-center gap-2">
                                <span class="text-xs sm:text-sm text-zinc-900 dark:text-white">{{ $dayName }}</span>
                                @if ($isToday)
                                    <span class="rounded-full bg-primary text-white text-[10px] font-extrabold px-2 py-0.5 shadow-xs">Hari Ini</span>
                                @endif
                            </div>
                            <div class="text-xs sm:text-sm">
                                @if ($isDayClosed)
                                    <span class="text-rose-500 font-semibold">Tutup</span>
                                @elseif ($dayHour && $dayHour->open_time && $dayHour->close_time)
                                    <span class="text-zinc-700 dark:text-zinc-300">{{ $formatTime($dayHour->open_time) }} – {{ $formatTime($dayHour->close_time) }}</span>
                                @else
                                    <span class="text-zinc-400">—</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

        </div>
    </section>
@endif
