@php
    /** @var \App\Models\MenuItem $item */
    $photoUrl = \App\Support\CmsMedia::url($item->photo_path);
@endphp

<div class="menu-select-option">
    @if ($photoUrl)
        <img src="{{ $photoUrl }}" alt="" class="menu-select-option__thumb" loading="lazy" decoding="async" width="40" height="40">
    @else
        <div class="menu-select-option__thumb menu-select-option__placeholder">Menu</div>
    @endif

    <div class="menu-select-option__body">
        <div class="menu-select-option__name">
            {{ $item->name }}
            @if ($item->is_best_seller)
                <span class="menu-select-option__discount" style="background: rgba(245, 158, 11, 0.18); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.35); font-weight: 700; margin-left: 0.35rem">★ Best Seller</span>
            @endif
        </div>
        <div class="menu-select-option__meta">
            <span>{{ \App\Support\CmsMedia::formatIdr($item->effectivePrice()) }}</span>
            @if ($item->hasDiscount())
                <span class="menu-select-option__discount">-{{ $item->discount_percent }}%</span>
            @endif
        </div>
    </div>
</div>
