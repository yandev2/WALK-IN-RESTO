@php
    $copy = $layout->copyFor('faq');
@endphp

<section id="faq" class="scroll-mt-20 bg-surface-base">
    <div class="landing-container landing-section">
        <div class="landing-reveal mx-auto max-w-2xl text-center">
            <x-customer.section-heading
                :label="$copy['label'] ?? null"
                :title="$copy['title'] ?? 'FAQ'"
                :highlight="$copy['highlight'] ?? null"
                class="text-center md:text-center"
            />
        </div>
        <div class="mx-auto mt-10 max-w-3xl space-y-3">
            @foreach ($restaurant->cmsFaqs as $faq)
                <details class="landing-reveal customer-card group landing-card-hover p-5 sm:px-6">
                    <summary class="cursor-pointer list-none font-semibold text-body marker:content-none">
                        <span class="flex items-center justify-between gap-4 text-left">
                            {{ $faq->question }}
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary/10 text-lg leading-none text-primary transition duration-300 group-open:rotate-45 group-open:bg-primary group-open:text-white">+</span>
                        </span>
                    </summary>
                    <div class="landing-faq-content">
                        <div>
                            <div class="landing-prose mt-4 text-sm leading-relaxed text-muted sm:text-base">
                                {!! $faq->answer_html !!}
                            </div>
                        </div>
                    </div>
                </details>
            @endforeach
        </div>
    </div>
</section>
