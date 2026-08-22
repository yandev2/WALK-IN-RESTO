@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center justify-center gap-2">
        @if ($paginator->onFirstPage())
            <span class="rounded-full px-4 py-2 text-sm text-muted/50">Sebelumnya</span>
        @else
            <button
                type="button"
                wire:click="previousPage('{{ $paginator->getPageName() }}')"
                wire:loading.attr="disabled"
                class="landing-interactive rounded-full border border-border-subtle bg-surface-raised px-4 py-2 text-sm font-semibold text-body transition hover:border-primary hover:text-primary"
            >
                Sebelumnya
            </button>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-2 text-sm text-muted">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="rounded-full bg-primary px-4 py-2 text-sm font-semibold text-white">{{ $page }}</span>
                    @else
                        <button
                            type="button"
                            wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                            class="landing-interactive rounded-full border border-border-subtle bg-surface-raised px-4 py-2 text-sm font-semibold text-body transition hover:border-primary hover:text-primary"
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
                class="landing-interactive rounded-full border border-border-subtle bg-surface-raised px-4 py-2 text-sm font-semibold text-body transition hover:border-primary hover:text-primary"
            >
                Berikutnya
            </button>
        @else
            <span class="rounded-full px-4 py-2 text-sm text-muted/50">Berikutnya</span>
        @endif
    </nav>
@endif
