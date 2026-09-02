@php
    $copy = $layout->copyFor('hours');
@endphp

<section id="jam" class="scroll-mt-20 py-16 sm:py-24 bg-surface-section border-b border-border-subtle dark:border-zinc-800 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-12 lg:gap-16">
            
            {{-- Left Column: Info & Status --}}
            <div class="lg:col-span-5">
                @if (filled($copy['label'] ?? null))
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-primary bg-primary/10 px-3.5 py-1.5 rounded-full border border-primary/20 shadow-2xs">
                        {{ $copy['label'] }}
                    </span>
                @endif

                <h2 class="mt-4 font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-body tracking-tight leading-tight">
                    {{ $copy['title'] ?? 'Jam Operasional' }}
                </h2>

                <div class="mt-6 flex items-center gap-3 rounded-2xl bg-surface-raised dark:bg-zinc-900 p-4 border border-border-subtle dark:border-zinc-800 shadow-xs">
                    <span @class([
                        'h-3 w-3 rounded-full shrink-0',
                        'bg-emerald-500 animate-pulse' => $isOpenNow && $outlet?->is_open !== false,
                        'bg-rose-500' => ! $isOpenNow || $outlet?->is_open === false,
                    ])></span>
                    <span class="text-sm text-body font-medium">
                        @if ($outlet?->is_open === false)
                            Restoran sedang ditutup sementara oleh kasir.
                        @elseif ($isOpenNow)
                            Sekarang <strong class="text-emerald-600 dark:text-emerald-400">Buka</strong>
                            @if ($todayHours)
                                sampai {{ $formatTime($todayHours->closes_at) }}.
                            @endif
                        @else
                            Sekarang <strong class="text-rose-600 dark:text-rose-400">Tutup</strong>. Datang sesuai jadwal operasional kami.
                        @endif
                    </span>
                </div>

                @if ($whatsappUrl)
                    <div class="mt-6">
                        <a
                            href="{{ $whatsappUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-2.5 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 text-sm font-bold shadow-md transition-all hover:scale-103"
                        >
                            <span>WhatsApp Outlet ({{ $outlet->phone }})</span>
                        </a>
                    </div>
                @endif
            </div>

            {{-- Right Column: Weekly Schedule Card --}}
            <div class="lg:col-span-7">
                <div class="overflow-hidden rounded-3xl bg-surface-raised dark:bg-zinc-900 border border-border-subtle dark:border-zinc-800 shadow-xl divide-y divide-border-subtle dark:divide-zinc-800">
                    @forelse ($outlet?->operatingHours ?? [] as $hours)
                        @php
                            $isToday = $todayHours && $hours->day_of_week === $todayHours->day_of_week;
                        @endphp
                        <div @class([
                            'flex items-center justify-between px-6 py-4 text-sm transition',
                            'bg-primary/10 font-bold text-primary dark:text-primary' => $isToday,
                            'text-body' => ! $isToday,
                        ])>
                            <span class="flex items-center gap-2">
                                @if ($isToday)
                                    <span class="h-2 w-2 rounded-full bg-primary animate-ping"></span>
                                @endif
                                <span>{{ $dayNames[$hours->day_of_week] ?? $hours->day_name }}</span>
                                @if ($isToday)
                                    <span class="rounded-md bg-primary text-white text-[10px] font-extrabold px-2 py-0.5 uppercase tracking-wider">
                                        Hari Ini
                                    </span>
                                @endif
                            </span>

                            <span class="font-medium">
                                @if ($hours->is_closed)
                                    <span class="text-rose-500 font-semibold">Tutup</span>
                                @else
                                    {{ $formatTime($hours->opens_at) }} – {{ $formatTime($hours->closes_at) }}
                                @endif
                            </span>
                        </div>
                    @empty
                        <div class="p-6 text-center text-sm text-muted">
                            Jadwal operasional belum diatur.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</section>
