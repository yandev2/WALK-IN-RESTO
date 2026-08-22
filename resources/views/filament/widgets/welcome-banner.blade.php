<x-filament-widgets::widget class="fi-wi-welcome-banner">
    @php
        $banner = $this->getBannerData();
        $theme = $banner['theme'];
    @endphp

    <div
        class="welcome-banner"
        style="--wb-primary: {{ $theme['primary'] }}; --wb-primary-dark: {{ $theme['primary_dark'] }}; --wb-accent: {{ $theme['accent'] }};"
        x-data="{
            time: '00:00:00',
            date: '',
            timer: null,
            timezone: @js($banner['timezone']),
            tick() {
                const now = new Date();
                const timeParts = new Intl.DateTimeFormat('en-GB', {
                    timeZone: this.timezone,
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false,
                }).formatToParts(now);

                const pick = (type) => timeParts.find(part => part.type === type)?.value ?? '00';

                this.time = `${pick('hour')}:${pick('minute')}:${pick('second')}`;
                this.date = now.toLocaleDateString('id-ID', {
                    timeZone: this.timezone,
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric',
                });
            },
            start() {
                this.tick();
                this.timer = setInterval(() => this.tick(), 1000);
            },
        }"
        x-init="
            start();
            return () => { timer && clearInterval(timer) };
        "
    >
        <style>
            .welcome-banner {
                --wb-surface: #ffffff;
                --wb-muted: #f8fafc;
                --wb-border: #e2e8f0;
                --wb-text: #0f172a;
                --wb-text-muted: #64748b;
                position: relative;
                overflow: hidden;
                border-radius: 1.25rem;
                border: 1px solid var(--wb-border);
                background: var(--wb-surface);
                box-shadow: 0 10px 30px -18px rgb(15 23 42 / 0.28);
            }

            .dark .welcome-banner {
                --wb-surface: #111827;
                --wb-muted: #1f2937;
                --wb-border: #374151;
                --wb-text: #f8fafc;
                --wb-text-muted: #94a3b8;
                box-shadow: 0 10px 30px -18px rgb(0 0 0 / 0.55);
            }

            .welcome-banner__accent {
                position: absolute;
                inset: 0 auto 0 0;
                width: 0.35rem;
                background: linear-gradient(180deg, var(--wb-primary-dark), var(--wb-primary), var(--wb-accent));
            }

            .welcome-banner__inner {
                position: relative;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1.25rem;
                padding: 1.35rem 1.5rem 1.35rem 1.75rem;
            }

            .welcome-banner__profile {
                display: flex;
                align-items: center;
                gap: 1rem;
                min-width: 0;
                flex: 1;
            }

            .welcome-banner__avatar {
                width: 3.75rem;
                height: 3.75rem;
                border-radius: 9999px;
                overflow: hidden;
                flex-shrink: 0;
                border: 3px solid color-mix(in srgb, var(--wb-primary) 35%, white);
                background: color-mix(in srgb, var(--wb-primary) 12%, white);
                box-shadow: 0 8px 20px -12px color-mix(in srgb, var(--wb-primary) 55%, rgb(15 23 42));
            }

            .dark .welcome-banner__avatar {
                border-color: color-mix(in srgb, var(--wb-primary) 45%, #111827);
                background: color-mix(in srgb, var(--wb-primary) 18%, #1f2937);
            }

            .welcome-banner__avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .welcome-banner__avatar-initials {
                display: grid;
                place-items: center;
                width: 100%;
                height: 100%;
                font-size: 1rem;
                font-weight: 700;
                letter-spacing: 0.04em;
                color: var(--wb-primary-dark);
            }

            .dark .welcome-banner__avatar-initials {
                color: var(--wb-accent);
            }

            .welcome-banner__eyebrow {
                margin: 0;
                font-size: 0.8125rem;
                font-weight: 600;
                color: var(--wb-text-muted);
            }

            .welcome-banner__title {
                margin: 0.25rem 0 0;
                font-size: clamp(1.25rem, 2.2vw, 1.65rem);
                font-weight: 700;
                line-height: 1.2;
                letter-spacing: -0.03em;
                color: var(--wb-text);
            }

            .welcome-banner__subtitle {
                margin: 0.35rem 0 0;
                font-size: 0.875rem;
                color: var(--wb-text-muted);
            }

            .welcome-banner__clock {
                display: none;
                text-align: right;
                padding: 0.85rem 1rem;
                border-radius: 1rem;
                background: var(--wb-muted);
                border: 1px solid var(--wb-border);
                min-width: 11rem;
            }

            .welcome-banner__clock-label {
                margin: 0;
                font-size: 0.6875rem;
                font-weight: 600;
                letter-spacing: 0.06em;
                text-transform: uppercase;
                color: var(--wb-text-muted);
            }

            .welcome-banner__clock-face {
                margin-top: 0.35rem;
                font-size: 1.65rem;
                font-weight: 700;
                letter-spacing: -0.04em;
                font-variant-numeric: tabular-nums;
                color: var(--wb-text);
            }

            .welcome-banner__clock-sep,
            .welcome-banner__clock-hours,
            .welcome-banner__clock-minutes,
            .welcome-banner__clock-seconds {
                display: none;
            }

            .welcome-banner__date {
                margin: 0.35rem 0 0;
                font-size: 0.75rem;
                line-height: 1.35;
                color: var(--wb-text-muted);
            }

            .welcome-banner__timezone {
                margin: 0.2rem 0 0;
                font-size: 0.6875rem;
                font-weight: 600;
                letter-spacing: 0.04em;
                color: var(--wb-primary-dark);
            }

            @keyframes wb-blink {
                50% { opacity: 0.25; }
            }

            @media (min-width: 640px) {
                .welcome-banner__clock {
                    display: block;
                }
            }

            @media (max-width: 639px) {
                .welcome-banner__inner {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .welcome-banner__clock {
                    display: block;
                    width: 100%;
                    text-align: left;
                }

                .welcome-banner__clock-face {
                    justify-content: flex-start;
                }
            }
        </style>

        <div class="welcome-banner__accent" aria-hidden="true"></div>

        <div class="welcome-banner__inner">
            <div class="welcome-banner__profile">
                <div class="welcome-banner__avatar" aria-hidden="true">
                    @if (filled($banner['avatar_url']))
                        <img src="{{ $banner['avatar_url'] }}" alt="">
                    @else
                        <span class="welcome-banner__avatar-initials">{{ $banner['user_initials'] }}</span>
                    @endif
                </div>

                <div>
                    <p class="welcome-banner__eyebrow">{{ $banner['greeting'] }}</p>
                    <h2 class="welcome-banner__title">Halo, {{ $banner['user_name'] }}</h2>
                    <p class="welcome-banner__subtitle">
                        Cek aktivitas dan omzet di dashboard ini
                        @if (filled($banner['restaurant_name']))
                            · {{ $banner['restaurant_name'] }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="welcome-banner__clock">
                <p class="welcome-banner__clock-label">Waktu sekarang</p>
                <div class="welcome-banner__clock-face" aria-live="polite" aria-atomic="true">
                    <span x-text="time"></span>
                </div>
                <p class="welcome-banner__date" x-text="date"></p>
                <p class="welcome-banner__timezone">{{ $banner['timezone_label'] }}</p>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
