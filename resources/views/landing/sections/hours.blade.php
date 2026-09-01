@php
    $copy = $layout->copyFor('hours');
@endphp

<section id="jam" class="scroll-mt-20 bg-zinc-950 text-white">
    <div class="landing-container landing-section">
        <div class="grid gap-10 md:grid-cols-2 md:gap-14 items-center">
            <div class="landing-reveal">
                @if (filled($copy['label'] ?? null))
                    <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3.5 py-1 text-sm backdrop-blur-md border border-white/15 mb-3">
                        <span class="h-2 w-2 rounded-full bg-accent animate-pulse"></span>
                        <span class="font-script text-xl text-accent md:text-2xl">{{ $copy['label'] }}</span>
                    </div>
                @endif
                <h2 class="mt-1 font-display text-3xl font-bold text-white md:text-4xl">
                    @if (filled($copy['highlight'] ?? null))
                        {!! str_replace($copy['highlight'], '<span class="text-accent">'.$copy['highlight'].'</span>', e($copy['title'] ?? '')) !!}
                    @else
                        {{ $copy['title'] ?? '' }}
                    @endif
                </h2>
                <div class="mt-4 inline-flex items-center gap-2 rounded-2xl bg-white/5 border border-white/10 px-4 py-3 text-sm text-white/80">
                    <span @class([
                        'h-2.5 w-2.5 rounded-full',
                        'bg-emerald-400 animate-pulse' => $isOpenNow && $outlet?->is_open !== false,
                        'bg-rose-400' => ! $isOpenNow || $outlet?->is_open === false,
                    ])></span>
                    <span>
                        @if ($outlet?->is_open === false)
                            Restoran sedang ditutup oleh kasir. Jam di bawah tetap sebagai acuan.
                        @elseif ($isOpenNow)
                            Sekarang buka
                            @if ($todayHours)
                                sampai {{ $formatTime($todayHours->closes_at) }}.
                            @endif
                        @else
                            Sekarang tutup. Datang sesuai jam operasional.
                        @endif
                    </span>
                </div>
                @if ($outlet?->phone)
                    <div class="mt-6">
                        <a href="{{ $whatsappUrl }}" class="landing-interactive inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-5 py-2.5 text-sm font-semibold text-white hover:bg-white/20 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                            </svg>
                            WhatsApp {{ $outlet->phone }}
                        </a>
                    </div>
                @endif
            </div>
            <ul class="landing-reveal divide-y divide-white/10 overflow-hidden rounded-3xl border border-white/15 bg-white/5 shadow-lg backdrop-blur-sm">
                @forelse ($outlet?->operatingHours ?? [] as $hours)
                    <li @class([
                        'flex items-center justify-between px-6 py-4 text-sm transition',
                        'bg-white/15 font-semibold text-white' => $todayHours && $hours->day_of_week === $todayHours->day_of_week,
                        'text-white/80' => ! ($todayHours && $hours->day_of_week === $todayHours->day_of_week),
                    ])>
                        <span class="flex items-center gap-2">
                            @if ($todayHours && $hours->day_of_week === $todayHours->day_of_week)
                                <span class="h-1.5 w-1.5 rounded-full bg-accent animate-pulse"></span>
                            @endif
                            {{ $dayNames[$hours->day_of_week] ?? 'Hari '.$hours->day_of_week }}
                        </span>
                        <span class="tabular-nums">
                            @if ($hours->is_closed)
                                <span class="text-white/40">Tutup</span>
                            @else
                                {{ $formatTime($hours->opens_at) }} – {{ $formatTime($hours->closes_at) }}
                            @endif
                        </span>
                    </li>
                @empty
                    <li class="px-6 py-8 text-sm text-white/60 text-center">Jam operasional belum diatur.</li>
                @endforelse
            </ul>
        </div>
    </div>
</section>
