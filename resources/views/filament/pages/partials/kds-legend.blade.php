<style>
    .kds-legend {
        border-radius: 0.75rem;
        border: 1px solid rgb(226 232 240);
        background: rgb(248 250 252);
        padding: 0.65rem 1rem;
        font-size: 0.8125rem;
        line-height: 1.45;
        color: rgb(71 85 105);
    }

    .dark .kds-legend {
        border-color: rgb(55 65 81);
        background: rgb(15 23 42 / 0.55);
        color: rgb(203 213 225);
    }

    .kds-legend__row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.45rem 1rem;
    }

    .kds-legend__label {
        font-weight: 700;
        color: rgb(51 65 85);
    }

    .dark .kds-legend__label {
        color: rgb(226 232 240);
    }

    .kds-legend__chip {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        white-space: nowrap;
    }

    .kds-legend__dot {
        width: 0.55rem;
        height: 0.55rem;
        border-radius: 9999px;
        flex-shrink: 0;
    }

    .kds-legend__dot--green { background: rgb(34 197 94); }
    .kds-legend__dot--yellow { background: rgb(234 179 8); }
    .kds-legend__dot--red { background: rgb(239 68 68); }

    .kds-row--green {
        box-shadow: inset 4px 0 0 rgb(34 197 94);
    }

    .kds-row--yellow {
        box-shadow: inset 4px 0 0 rgb(234 179 8);
    }

    .kds-row--red {
        box-shadow: inset 4px 0 0 rgb(239 68 68);
    }
</style>

<div class="kds-legend">
    <div class="kds-legend__row">
        <span class="kds-legend__label">Timer</span>
        <span class="kds-legend__chip">
            <span class="kds-legend__dot kds-legend__dot--green" aria-hidden="true"></span>
            hijau &lt; 10 m
        </span>
        <span class="kds-legend__chip">
            <span class="kds-legend__dot kds-legend__dot--yellow" aria-hidden="true"></span>
            kuning 10–20 m
        </span>
        <span class="kds-legend__chip">
            <span class="kds-legend__dot kds-legend__dot--red" aria-hidden="true"></span>
            merah &gt; 20 m
        </span>

        <div
            x-data="{
                muted: localStorage.getItem('resto_kitchen_sound_muted') === '1',
                customAudioUrl: '{{ \App\Models\PlatformSetting::kitchenSoundUrl() ?? '' }}',
                audioCtx: null,
                lastRungTime: 0,

                init() {
                    // Auto-unlock AudioContext on first user interaction on the KDS screen
                    const unlock = () => {
                        this.getAudioContext();
                        ['click', 'touchstart', 'keydown'].forEach(evt => window.removeEventListener(evt, unlock));
                    };
                    ['click', 'touchstart', 'keydown'].forEach(evt => window.addEventListener(evt, unlock, { passive: true }));
                },

                getAudioContext() {
                    if (!this.audioCtx) {
                        const AudioContextClass = window.AudioContext || window.webkitAudioContext;
                        if (AudioContextClass) {
                            this.audioCtx = new AudioContextClass();
                        }
                    }
                    if (this.audioCtx && this.audioCtx.state === 'suspended') {
                        this.audioCtx.resume();
                    }
                    return this.audioCtx;
                },

                toggle() {
                    this.muted = !this.muted;
                    localStorage.setItem('resto_kitchen_sound_muted', this.muted ? '1' : '0');
                    if (!this.muted) {
                        this.ringBell();
                    }
                },

                ringBell(soundUrl = null) {
                    if (this.muted) return;

                    // Debounce rapid/burst triggers within 1.5s
                    const now = Date.now();
                    if (now - this.lastRungTime < 1500) {
                        return;
                    }
                    this.lastRungTime = now;

                    const activeUrl = soundUrl || this.customAudioUrl;

                    // 1. If custom MP3/WAV file exists, prioritize it
                    if (activeUrl) {
                        try {
                            const audio = new Audio(activeUrl);
                            const promise = audio.play();
                            if (promise !== undefined) {
                                promise.catch((err) => {
                                    console.warn('Custom kitchen audio playback failed, falling back to synthesizer:', err);
                                    this.synthesizeServiceBell();
                                });
                                return;
                            }
                        } catch (e) {
                            console.warn('Custom kitchen audio error:', e);
                            this.synthesizeServiceBell();
                            return;
                        }
                    }

                    // 2. Synthesize distinct restaurant kitchen service bell (Web Audio API)
                    this.synthesizeServiceBell();
                },

                synthesizeServiceBell() {
                    try {
                        const ctx = this.getAudioContext();
                        if (!ctx) return;

                        const now = ctx.currentTime;

                        // Kitchen Service Bell Harmonic Frequencies (C5 + G5 + C6)
                        const harmonics = [
                            { freq: 523.25, gain: 0.15, duration: 0.8 },
                            { freq: 784.00, gain: 0.25, duration: 0.85 },
                            { freq: 1046.50, gain: 0.35, duration: 0.9 },
                        ];

                        harmonics.forEach(h => {
                            const osc = ctx.createOscillator();
                            const gain = ctx.createGain();

                            osc.type = 'sine';
                            osc.frequency.setValueAtTime(h.freq, now);

                            gain.gain.setValueAtTime(0.001, now);
                            gain.gain.exponentialRampToValueAtTime(h.gain, now + 0.015);
                            gain.gain.exponentialRampToValueAtTime(0.0001, now + h.duration);

                            osc.connect(gain);
                            gain.connect(ctx.destination);

                            osc.start(now);
                            osc.stop(now + h.duration);
                        });
                    } catch (e) {
                        console.error('KDS Synthesizer error:', e);
                    }
                }
            }"
            x-on:kds-beep.window="ringBell($event.detail?.soundUrl)"
            class="ml-auto inline-flex items-center gap-2 text-xs"
        >
            <button
                type="button"
                @click="toggle()"
                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md border font-medium transition select-none cursor-pointer"
                :class="muted
                    ? 'border-amber-300 bg-amber-50 text-amber-800 dark:border-amber-800 dark:bg-amber-950/40 dark:text-amber-300'
                    : 'border-emerald-300 bg-emerald-50 text-emerald-800 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300'"
                :title="muted ? 'Klik untuk mengaktifkan bel dapur' : 'Klik untuk mematikan bel dapur'"
            >
                <span x-text="muted ? '🔇 Bel Dapur Mati' : '🔔 Bel Dapur Aktif'"></span>
            </button>

            <button
                type="button"
                @click="ringBell()"
                class="px-2.5 py-1 rounded border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition font-medium cursor-pointer"
                title="Uji coba suara bel dapur"
            >
                Tes Bel
            </button>
        </div>
    </div>
</div>
