@props(['categories', 'categoryIds' => []])

@php
    $categoryIds = collect($categoryIds)->map(fn($id) => (int) $id)->values()->all();
    $selectedId = count($categoryIds) === 1 ? $categoryIds[0] : null;
@endphp

<section aria-label="Kategori Kuliner" class="directory-category-bar mb-8">
    <div class="mb-3.5 flex items-center justify-between gap-3">
        <div>
            <span class="text-[11px] font-bold uppercase tracking-wider text-primary">Kategori</span>
            <h2 class="text-lg font-bold text-body font-display sm:text-xl">Jelajahi Berdasarkan Kategori</h2>
        </div>
        @if (count($categoryIds) > 0)
            <button type="button" wire:click="setCategoryFilter(null)"
                class="inline-flex items-center gap-1 rounded-lg bg-primary/10 px-3 py-1 text-xs font-semibold text-primary hover:bg-primary/20 transition duration-200">
                Reset Filter
            </button>
        @endif
    </div>

    {{-- Text-Only Pills: Padat, Bersih, dan Responsif --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
        <button type="button" wire:click="setCategoryFilter(null)" @class([
            'shrink-0 rounded-lg px-4 py-2 text-xs font-semibold transition-all duration-200 cursor-pointer',
            'bg-primary text-white shadow-sm ring-1 ring-primary' => empty(
                $categoryIds
            ),
            'bg-surface-raised text-body hover:bg-surface-muted ring-1 ring-[color:var(--border-subtle)]' => !empty(
                $categoryIds
            ),
        ])>
            Semua
        </button>

        @foreach ($categories as $category)
            @php
                $isActive = $selectedId === $category->id;
            @endphp
            <button type="button" wire:click="setCategoryFilter({{ $category->id }})"
                wire:key="category-pill-{{ $category->id }}" @class([
                    'shrink-0 rounded-lg px-4 py-2 text-xs font-semibold transition-all duration-200 cursor-pointer',
                    'bg-primary text-white shadow-sm ring-1 ring-primary' => $isActive,
                    'bg-surface-raised text-body hover:bg-surface-muted ring-1 ring-[color:var(--border-subtle)]' => !$isActive,
                ])>
                {{ $category->name }}
            </button>
        @endforeach
    </div>
</section>
