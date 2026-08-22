@php
    $copy = $layout->copyFor('hours');
@endphp

<section id="jam" class="scroll-mt-20 bg-zinc-950 text-white">
    <div class="landing-container landing-section">
        <div class="grid gap-10 md:grid-cols-2 md:gap-14">
            <div class="landing-reveal">
                @if (filled($copy['label'] ?? null))
                    <p class="font-script text-2xl text-accent md:text-3xl">{{ $copy['label'] }}</p>
                @endif
                <h2 class="mt-2 font-display text-3xl font-bold text-white md:text-4xl">
                    @if (filled($copy['highlight'] ?? null))
                        {!! str_replace($copy['highlight'], '<span class="text-accent">'.$copy['highlight'].'</span>', e($copy['title'] ?? '')) !!}
                    @else
                        {{ $copy['title'] ?? '' }}
                    @endif
                </h2>
                <p class="mt-4 max-w-md text-base leading-relaxed text-white/70">
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
                </p>
                @if ($outlet?->phone)
                    <a href="{{ $whatsappUrl }}" class="landing-interactive mt-8 inline-flex rounded-full border border-white/25 bg-white/5 px-5 py-2.5 text-sm font-semibold text-white hover:bg-white/10">
                        WhatsApp {{ $outlet->phone }}
                    </a>
                @endif
            </div>
            <ul class="landing-reveal divide-y divide-white/10 overflow-hidden rounded-3xl border border-white/10 bg-white/5">
                @forelse ($outlet?->operatingHours ?? [] as $hours)
                    <li @class([
                        'flex items-center justify-between px-5 py-3.5 text-sm',
                        'bg-white/10' => $todayHours && $hours->day_of_week === $todayHours->day_of_week,
                    ])>
                        <span class="font-medium">{{ $dayNames[$hours->day_of_week] ?? 'Hari '.$hours->day_of_week }}</span>
                        <span class="tabular-nums text-white/80">
                            @if ($hours->is_closed)
                                Tutup
                            @else
                                {{ $formatTime($hours->opens_at) }} – {{ $formatTime($hours->closes_at) }}
                            @endif
                        </span>
                    </li>
                @empty
                    <li class="px-5 py-6 text-sm text-white/60">Jam operasional belum diatur.</li>
                @endforelse
            </ul>
        </div>
    </div>
</section>
