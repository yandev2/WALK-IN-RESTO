@props([
    'home' => [],
])

@php
    $home = $home !== [] ? $home : \App\Models\PlatformSetting::homeViewData();
    $hasContact = filled($home['footer_email'] ?? null)
        || filled($home['footer_phone'] ?? null)
        || filled($home['footer_address'] ?? null);
    $hasSocial = filled($home['footer_instagram_url'] ?? null);
    $hasLegal = true; // Syarat & Ketentuan selalu ada sekarang
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

<footer role="contentinfo" class="directory-footer mt-auto border-t border-border-subtle/80 bg-surface-section">
    <div class="landing-container grid gap-10 py-12 sm:grid-cols-2 lg:grid-cols-12 lg:gap-x-16">
        <div class="min-w-0 sm:col-span-2 lg:col-span-6 lg:pr-4">
            <a href="{{ route('home') }}" class="group inline-flex min-w-0 items-center gap-2.5">
                @if (filled($home['logo_url'] ?? null))
                    <img
                        src="{{ $home['logo_url'] }}"
                        alt="{{ $home['site_name'] }}"
                        class="h-8 w-auto max-w-[180px] object-contain transition duration-200 group-hover:scale-105"
                    >
                @else
                    <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-primary to-primary-dark text-white shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M9.384 3.576a1 1 0 011.232 0l6.5 4.75A1 1 0 0117 9.25v6.5a1 1 0 01-1.384.926L10 15.382l-5.616 1.294A1 1 0 013 15.75v-6.5a1 1 0 01.384-.924l6.5-4.75z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <span class="truncate font-display text-base font-bold text-body group-hover:text-primary transition-colors">{{ $home['site_name'] }}</span>
                @endif
            </a>
            @if (filled($home['footer_about'] ?? null))
                <p class="mt-4 max-w-xl text-sm leading-relaxed text-muted">{{ $home['footer_about'] }}</p>
            @endif
        </div>

        <div @class($asideColClass)>
            <p class="text-xs font-bold uppercase tracking-wider text-primary">Jelajah</p>
            <ul class="mt-4 space-y-2.5 text-sm text-muted">
                <li>
                    <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
                </li>
                <li>
                    <a href="{{ route('blog.index', ['locale' => 'id']) }}" class="hover:text-primary transition-colors">Blog</a>
                </li>
                <li>
                    <a href="{{ route('page.about') }}" class="hover:text-primary transition-colors">Tentang kami</a>
                </li>
                @auth
                    @php
                        $authUser = auth()->user();
                        $panelUrl = $authUser instanceof \App\Models\User && $authUser->isSuperAdmin()
                            ? url('/founder')
                            : ($authUser?->hasRole('blogger') ? url('/blogger') : ($authUser?->restaurants()->first() ? url('/admin/'.$authUser->restaurants()->first()->slug) : url('/admin')));
                    @endphp
                    <li>
                        <a href="{{ $panelUrl }}" rel="nofollow" class="hover:text-primary transition-colors font-medium">Panel Admin</a>
                    </li>
                @else
                    <li>
                        <a href="{{ route('register.restaurant') }}" class="hover:text-primary transition-colors">{{ $home['cta_register_label'] }}</a>
                    </li>
                @endauth
            </ul>
        </div>

        @if ($hasContact)
            <div @class($asideColClass)>
                <p class="text-xs font-bold uppercase tracking-wider text-primary">Kontak</p>
                <ul class="mt-4 space-y-2.5 text-sm leading-relaxed text-muted">
                    @if (filled($home['footer_email'] ?? null))
                        <li>
                            <a href="mailto:{{ $home['footer_email'] }}" class="hover:text-primary transition-colors">{{ $home['footer_email'] }}</a>
                        </li>
                    @endif
                    @if (filled($home['footer_phone'] ?? null))
                        <li>
                            @if (filled($home['footer_whatsapp_url'] ?? null))
                                <a href="{{ $home['footer_whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="hover:text-primary transition-colors">
                                    {{ $home['footer_phone'] }}
                                </a>
                            @else
                                {{ $home['footer_phone'] }}
                            @endif
                        </li>
                    @endif
                    @if (filled($home['footer_address'] ?? null))
                        <li class="leading-snug">{{ $home['footer_address'] }}</li>
                    @endif
                </ul>
            </div>
        @endif

        @if ($hasSocial || $hasLegal)
            <div @class($asideColClass)>
                @if ($hasSocial)
                    <p class="text-xs font-bold uppercase tracking-wider text-primary">Ikuti kami</p>
                    <ul class="mt-4 space-y-2.5 text-sm text-muted">
                        <li>
                            <a href="{{ $home['footer_instagram_url'] }}" target="_blank" rel="noopener noreferrer" class="hover:text-primary transition-colors">
                                {{ $instagramLabel }}
                            </a>
                        </li>
                    </ul>
                @endif

                @if ($hasLegal)
                    <p @class(['text-xs font-bold uppercase tracking-wider text-primary', 'mt-6' => $hasSocial])>Kebijakan</p>
                    <ul class="mt-4 space-y-2.5 text-sm text-muted">
                        <li>
                            <a href="{{ filled($home['footer_terms_url'] ?? null) ? $home['footer_terms_url'] : route('page.terms') }}" class="hover:text-primary transition-colors">Syarat & ketentuan</a>
                        </li>
                        @if (filled($home['footer_privacy_url'] ?? null))
                            <li>
                                <a href="{{ $home['footer_privacy_url'] }}" target="_blank" rel="noopener noreferrer" class="hover:text-primary transition-colors">Kebijakan privasi</a>
                            </li>
                        @endif
                    </ul>
                @endif
            </div>
        @endif
    </div>

    <div class="border-t border-border-subtle/80">
        <p class="landing-container py-5 text-center text-xs text-muted">
            {{ $home['footer_copyright'] }}
        </p>
    </div>
</footer>
