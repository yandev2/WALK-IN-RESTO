@php
    $copy = $layout->copyFor('faq');
@endphp

@if ($restaurant->cmsFaqs->isNotEmpty())
    <section id="faq" class="scroll-mt-20 py-16 sm:py-24 bg-surface-section border-b border-border-subtle dark:border-zinc-800 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-2xl mx-auto mb-12 sm:mb-16">
                @if (filled($copy['label'] ?? null))
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-primary bg-linear-to-r from-primary/15 to-accent/15 px-3.5 py-1.5 rounded-full border border-primary/25 shadow-2xs">
                        {{ $copy['label'] }}
                    </span>
                @endif
                <h2 class="mt-4 font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-body tracking-tight">
                    {{ $copy['title'] ?? 'Frequently Asked Questions' }}
                </h2>
                <p class="mt-3 text-sm sm:text-base text-muted leading-relaxed">
                    Pertanyaan yang sering ditanyakan tamu seputar sistem walk-in dan layanan restoran kami.
                </p>
            </div>

            <div class="max-w-3xl mx-auto space-y-4">
                @foreach ($restaurant->cmsFaqs as $faq)
                    <details class="group rounded-3xl bg-surface-raised dark:bg-zinc-900 border border-border-subtle dark:border-zinc-800 p-5 sm:p-6 shadow-xs hover:shadow-lg hover:border-primary/50 hover:-translate-y-0.5 transition-all duration-300 open:border-primary/40 open:shadow-xl">
                        <summary class="flex cursor-pointer items-center justify-between gap-4 font-display font-bold text-base sm:text-lg text-body list-none select-none">
                            <span class="group-hover:text-primary transition-colors duration-200">{{ $faq->question }}</span>
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-linear-to-br from-primary/20 to-accent/20 text-primary text-base font-bold transition-all duration-300 group-hover:scale-110 group-open:rotate-45 group-open:bg-linear-to-br group-open:from-primary group-open:to-accent group-open:text-white">
                                +
                            </span>
                        </summary>
                        <div class="mt-4 pt-4 border-t border-border-subtle dark:border-zinc-800 text-sm sm:text-base text-muted leading-relaxed prose prose-sm dark:prose-invert max-w-none">
                            {!! $faq->answer_html !!}
                        </div>
                    </details>
                @endforeach
            </div>

        </div>
    </section>
@endif
