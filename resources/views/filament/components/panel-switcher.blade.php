@php
    $user = auth()->user();
    $isOperator = $user instanceof \App\Models\User && $user->isPlatformOperator();
    $currentPanelId = filament()->getCurrentPanel()?->getId();
@endphp

@if ($isOperator && in_array($currentPanelId, ['blogger', 'founder'], true))
    @php
        $isBlogger = $currentPanelId === 'blogger';
        $targetPanelId = $isBlogger ? 'founder' : 'blogger';
        $targetUrl = filament()->getPanel($targetPanelId, isStrict: false)?->getUrl() ?? url("/{$targetPanelId}");

        $targetLabel = $isBlogger ? 'Panel Founder' : 'Panel Blog';
        $targetIcon = $isBlogger ? 'heroicon-m-building-office-2' : 'heroicon-m-newspaper';
        $tooltip = $isBlogger ? 'Beralih ke Panel Founder' : 'Beralih ke Panel Blog';

        $badgeTheme = $isBlogger
            ? 'bg-indigo-50 text-indigo-600 ring-1 ring-indigo-500/20 dark:bg-indigo-950/60 dark:text-indigo-300 dark:ring-indigo-400/30'
            : 'bg-orange-50 text-orange-600 ring-1 ring-orange-500/20 dark:bg-orange-950/60 dark:text-orange-300 dark:ring-orange-400/30';
    @endphp

    <div class="fi-panel-switcher flex items-center me-1">
        <a href="{{ $targetUrl }}" x-data="{}"
            x-tooltip="{
                content: @js($tooltip),
                theme: $store.theme
            }"
            aria-label="{{ $tooltip }}"
            class="group relative inline-flex items-center gap-x-2 rounded-lg border border-gray-200 bg-white/90 px-2.5 py-1.5 text-xs font-semibold text-gray-700 shadow-2xs backdrop-blur-md transition-all duration-200 hover:border-gray-300 hover:bg-gray-100/90 hover:text-gray-900 focus:outline-hidden focus:ring-2 focus:ring-primary-500/20 dark:border-white/10 dark:bg-white/5 dark:text-gray-200 dark:hover:border-white/20 dark:hover:bg-white/10 dark:hover:text-white">
            <!-- Target Icon Pill with dynamic tint -->
            <span
                class="flex h-5 w-5 shrink-0 items-center justify-center rounded-md {{ $badgeTheme }} transition-transform duration-200 group-hover:scale-110">
                <x-filament::icon :icon="$targetIcon" class="h-3.5 w-3.5" />
            </span>

            <!-- Target Label (Responsive) -->
            <span class="hidden sm:inline-block font-medium tracking-tight whitespace-nowrap">
                {{ $targetLabel }}
            </span>

            <!-- Direction Indicator with Micro-Animation -->

        </a>
    </div>
@endif
