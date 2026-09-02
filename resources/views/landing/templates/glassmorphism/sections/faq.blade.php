@php
    $faqCopy = $layout->copyFor('faq');
    $faqs = $restaurant->cmsFaqs ?? collect();
@endphp

@if ($faqs->isNotEmpty())
    <section id="faq" class="scroll-mt-24 py-8 sm:py-12 relative">
        
        {{-- Ambient Light Orb --}}
        <div class="pointer-events-none absolute -left-20 top-1/3 h-80 w-80 rounded-full bg-primary/20 blur-3xl -z-10"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Section Header --}}
            <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-14">
                <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-primary bg-primary/15 dark:bg-primary/20 px-4 py-1.5 rounded-full border border-primary/30 backdrop-blur-xl shadow-xs">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ $faqCopy['label'] ?? 'Paling Sering Ditanyakan' }}</span>
                </span>
                <h2 class="mt-4 font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-zinc-900 dark:text-white tracking-tight drop-shadow-2xs">
                    {{ $faqCopy['title'] ?? 'Pertanyaan yang sering diajukan' }}
                </h2>
                <p class="mt-3 text-sm sm:text-base text-zinc-700 dark:text-zinc-300 leading-relaxed">
                    {{ $faqCopy['subtitle'] ?? 'Temukan jawaban cepat seputar pemesanan, reservasi, dan layanan kami.' }}
                </p>
            </div>

            {{-- Glass Accordion List --}}
            <div class="space-y-4" x-data="{ active: null }">
                @foreach ($faqs as $index => $faq)
                    <div class="overflow-hidden rounded-[2.2rem] bg-white/25 dark:bg-white/[0.06] backdrop-blur-2xl border border-white/50 dark:border-white/15 shadow-[0_8px_30px_rgba(0,0,0,0.05),inset_0_1px_1px_rgba(255,255,255,0.7)] dark:shadow-[0_8px_30px_rgba(0,0,0,0.3),inset_0_1px_1px_rgba(255,255,255,0.15)] transition-all">
                        <button
                            type="button"
                            class="w-full px-6 py-5 sm:px-8 text-left flex items-center justify-between gap-4 font-display font-bold text-base sm:text-lg text-zinc-900 dark:text-white hover:text-primary transition-colors"
                            @click="active = (active === {{ $index }} ? null : {{ $index }})"
                        >
                            <span>{{ $faq->question }}</span>
                            <span
                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white/50 dark:bg-white/10 border border-white/50 dark:border-white/20 text-primary transition-transform duration-300 shadow-sm"
                                :class="active === {{ $index }} ? 'rotate-180 bg-primary text-white' : ''"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </span>
                        </button>
                        
                        <div
                            x-show="active === {{ $index }}"
                            x-collapse
                            x-cloak
                        >
                            <div class="px-6 pb-6 sm:px-8 sm:pb-8 pt-0 border-t border-black/5 dark:border-white/10 text-xs sm:text-sm text-zinc-700 dark:text-zinc-300 leading-relaxed prose prose-zinc dark:prose-invert max-w-none">
                                {!! $faq->answer_html !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>
@endif
