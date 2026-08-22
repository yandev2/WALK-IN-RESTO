@php
    use App\Support\CmsMedia;
    $logoUrl = filled($guestRestaurant?->logo_path) ? CmsMedia::url($guestRestaurant->logo_path) : null;
@endphp

@if ($guestRestaurant)
    <header class="sticky top-0 z-50 border-b border-transparent bg-surface-base/95 shadow-[0_1px_0_0_color-mix(in_srgb,var(--brand-primary)_8%,transparent)] backdrop-blur">
        <div class="mx-auto flex max-w-md items-center justify-between gap-2 px-5 py-3">
            <div class="flex min-w-0 items-center gap-2">
                @if ($logoUrl)
                    <img src="{{ $logoUrl }}" alt="" class="h-9 w-9 rounded-full object-cover ring-2 ring-primary/20">
                @else
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary text-xs font-bold text-white">
                        {{ mb_substr($guestRestaurant->name, 0, 1) }}
                    </span>
                @endif
                <div class="min-w-0">
                    <p class="truncate text-sm font-bold text-body">{{ $guestRestaurant->name }}</p>
                    @if ($guestVisit?->diningTable)
                        <p class="text-xs text-muted">Meja {{ $guestVisit->diningTable->code }}</p>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if ($guestVisit?->join_pin)
                    <span class="customer-pill bg-surface-muted text-muted">PIN {{ $guestVisit->join_pin }}</span>
                @endif
                <x-customer.theme-toggle />
            </div>
        </div>
    </header>
@endif
