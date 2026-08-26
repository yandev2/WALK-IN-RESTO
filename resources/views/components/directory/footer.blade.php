@props([
    'home' => [],
])

@php
    $home = $home !== [] ? $home : \App\Models\PlatformSetting::homeViewData();
    $hasContact = filled($home['footer_email'] ?? null)
        || filled($home['footer_phone'] ?? null)
        || filled($home['footer_address'] ?? null);
    $hasSocial = filled($home['footer_instagram_url'] ?? null);
    $hasLegal = filled($home['footer_privacy_url'] ?? null) || filled($home['footer_terms_url'] ?? null);
    $instagramLabel = filled($home['footer_instagram'] ?? null) && ! str_starts_with((string) $home['footer_instagram'], 'http')
        ? '@'.ltrim((string) $home['footer_instagram'], '@/')
        : 'Instagram';
    $asideCount = 1 + (int) $hasContact + (int) ($hasSocial || $hasLegal);
    $asideColClass = [
        'lg:col-span-6' => $asideCount <= 1,
        'lg:col-span-3' => $asideCount === 2,
        'lg:col-span-2' => $asideCount >= 3,
    ];
@endphp

<footer class="directory-footer mt-auto border-t border-border-subtle bg-surface-muted/70">
    <div class="landing-container grid gap-10 py-12 sm:grid-cols-2 lg:grid-cols-12 lg:gap-x-16">
        <div class="min-w-0 sm:col-span-2 lg:col-span-6 lg:pr-4">
            <a href="{{ route('home') }}" class="inline-flex min-w-0 items-center gap-2.5">
                @if (filled($home['logo_url'] ?? null))
                    <img
                        src="{{ $home['logo_url'] }}"
                        alt="{{ $home['site_name'] }}"
                        class="h-8 w-auto max-w-[180px] object-contain"
                    >
                @else
                    <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-primary text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M9.384 3.576a1 1 0 011.232 0l6.5 4.75A1 1 0 0117 9.25v6.5a1 1 0 01-1.384.926L10 15.382l-5.616 1.294A1 1 0 013 15.75v-6.5a1 1 0 01.384-.924l6.5-4.75z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <span class="truncate font-display text-base font-bold text-body">{{ $home['site_name'] }}</span>
                @endif
            </a>
            @if (filled($home['footer_about'] ?? null))
                <p class="mt-4 max-w-xl text-sm leading-relaxed text-muted">{{ $home['footer_about'] }}</p>
            @endif
        </div>

        <div @class($asideColClass)>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-primary">Jelajah</p>
            <ul class="mt-4 space-y-2.5 text-sm text-muted">
                <li>
                    <a href="{{ route('home') }}" class="hover:text-body">Beranda</a>
                </li>
                <li>
                    <a href="{{ route('register.restaurant') }}" class="hover:text-body">{{ $home['cta_register_label'] }}</a>
                </li>
            </ul>
        </div>

        @if ($hasContact)
            <div @class($asideColClass)>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-primary">Kontak</p>
                <ul class="mt-4 space-y-2.5 text-sm leading-relaxed text-muted">
                    @if (filled($home['footer_email'] ?? null))
                        <li>
                            <a href="mailto:{{ $home['footer_email'] }}" class="hover:text-body">{{ $home['footer_email'] }}</a>
                        </li>
                    @endif
                    @if (filled($home['footer_phone'] ?? null))
                        <li>
                            @if (filled($home['footer_whatsapp_url'] ?? null))
                                <a href="{{ $home['footer_whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="hover:text-body">
                                    {{ $home['footer_phone'] }}
                                </a>
                            @else
                                {{ $home['footer_phone'] }}
                            @endif
                        </li>
                    @endif
                    @if (filled($home['footer_address'] ?? null))
                        <li>{{ $home['footer_address'] }}</li>
                    @endif
                </ul>
            </div>
        @endif

        @if ($hasSocial || $hasLegal)
            <div @class($asideColClass)>
                @if ($hasSocial)
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-primary">Ikuti kami</p>
                    <ul class="mt-4 space-y-2.5 text-sm text-muted">
                        <li>
                            <a href="{{ $home['footer_instagram_url'] }}" target="_blank" rel="noopener noreferrer" class="hover:text-body">
                                {{ $instagramLabel }}
                            </a>
                        </li>
                    </ul>
                @endif

                @if ($hasLegal)
                    <p @class(['text-xs font-semibold uppercase tracking-[0.18em] text-primary', 'mt-8' => $hasSocial])>Kebijakan</p>
                    <ul class="mt-4 space-y-2.5 text-sm text-muted">
                        @if (filled($home['footer_privacy_url'] ?? null))
                            <li>
                                <a href="{{ $home['footer_privacy_url'] }}" target="_blank" rel="noopener noreferrer" class="hover:text-body">Kebijakan privasi</a>
                            </li>
                        @endif
                        @if (filled($home['footer_terms_url'] ?? null))
                            <li>
                                <a href="{{ $home['footer_terms_url'] }}" target="_blank" rel="noopener noreferrer" class="hover:text-body">Syarat & ketentuan</a>
                            </li>
                        @endif
                    </ul>
                @endif
            </div>
        @endif
    </div>

    <div class="border-t border-border-subtle">
        <p class="landing-container py-5 text-center text-xs text-muted">
            {{ $home['footer_copyright'] }}
        </p>
    </div>
</footer>
