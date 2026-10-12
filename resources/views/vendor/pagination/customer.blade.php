@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex flex-wrap items-center justify-center gap-1.5 sm:gap-2 max-w-full">
        @if ($paginator->onFirstPage())
            <span class="rounded-full px-2.5 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm text-muted/50 cursor-not-allowed">
                <span class="hidden sm:inline">Sebelumnya</span>
                <span class="sm:hidden">‹ Prev</span>
            </span>
        @else
            <button
                type="button"
                wire:click="previousPage('{{ $paginator->getPageName() }}')"
                wire:loading.attr="disabled"
                class="landing-interactive rounded-full border border-border-subtle bg-surface-raised px-2.5 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-semibold text-body transition hover:border-primary hover:text-primary"
            >
                <span class="hidden sm:inline">Sebelumnya</span>
                <span class="sm:hidden">‹ Prev</span>
            </button>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-1.5 sm:px-2 text-xs sm:text-sm text-muted">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="rounded-full bg-primary px-3 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-semibold text-white shadow-xs">{{ $page }}</span>
                    @else
                        <button
                            type="button"
                            wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                            class="landing-interactive rounded-full border border-border-subtle bg-surface-raised px-3 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-semibold text-body transition hover:border-primary hover:text-primary"
                        >
                            {{ $page }}
                        </button>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <button
                type="button"
                wire:click="nextPage('{{ $paginator->getPageName() }}')"
                wire:loading.attr="disabled"
                class="landing-interactive rounded-full border border-border-subtle bg-surface-raised px-2.5 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm font-semibold text-body transition hover:border-primary hover:text-primary"
            >
                <span class="hidden sm:inline">Berikutnya</span>
                <span class="sm:hidden">Next ›</span>
            </button>
        @else
            <span class="rounded-full px-2.5 py-1.5 sm:px-4 sm:py-2 text-xs sm:text-sm text-muted/50 cursor-not-allowed">
                <span class="hidden sm:inline">Berikutnya</span>
                <span class="sm:hidden">Next ›</span>
            </span>
        @endif
    </nav>
@endif
