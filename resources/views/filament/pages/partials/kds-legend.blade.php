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

<div class="kds-legend" wire:poll.5s="pollAlerts">
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
            wire:ignore.self
            x-data="{
                muted: localStorage.getItem('resto_kitchen_sound_muted') === '1',
                customAudioUrl: '{{ \App\Models\PlatformSetting::kitchenSoundUrl() ?? '' }}',
                audioCtx: null,
                audioBuffer: null,
                audioUnlocked: false,
                needsUnlockPrompt: false,
                isPlaying: false,
                pendingAlert: false,
                safetyTimer: null,

                init() {
                    this.getAudioContext();
                    this.loadAudioBuffer();

                    if (this.audioCtx && this.audioCtx.state === 'running') {
                        this.audioUnlocked = true;
                    } else {
                        this.needsUnlockPrompt = true;
                    }

                    // Auto-unlock Audio on first user interaction on the KDS screen
                    const unlockHandler = () => {
                        this.unlockAudio();
                        ['click', 'touchstart', 'keydown'].forEach(evt => window.removeEventListener(evt, unlockHandler));
                    };
                    ['click', 'touchstart', 'keydown'].forEach(evt => window.addEventListener(evt, unlockHandler, { passive: true, once: true }));
                },

                getAudioContext() {
                    if (!this.audioCtx) {
                        const AudioContextClass = window.AudioContext || window.webkitAudioContext;
                        if (AudioContextClass) {
                            this.audioCtx = new AudioContextClass();
                        }
                    }
                    return this.audioCtx;
                },

                unlockAudio() {
                    const ctx = this.getAudioContext();
                    if (ctx && ctx.state === 'suspended') {
                        ctx.resume().then(() => {
                            this.audioUnlocked = true;
                            this.needsUnlockPrompt = false;
                        }).catch(() => {});
                    }

                    try {
                        // Prime HTMLMediaElement with silent 1-sample audio so browsers authorize subsequent background plays
                        const silentAudio = new Audio('data:audio/wav;base64,UklGRigAAABXQVZFZm10IBIAAAABAAEARKwAAIhYAQACABAAAABkYXRhAgAAAAEA');
                        silentAudio.volume = 0.01;
                        const p = silentAudio.play();
                        if (p !== undefined) {
                            p.then(() => {
                                this.audioUnlocked = true;
                                this.needsUnlockPrompt = false;
                            }).catch(() => {});
                        }
                    } catch (e) {}

                    this.audioUnlocked = true;
                    this.needsUnlockPrompt = false;

                    if (!this.audioBuffer) {
                        this.loadAudioBuffer();
                    }
                },

                loadAudioBuffer(url = null) {
                    const targetUrl = url || this.customAudioUrl;
                    if (!targetUrl) return;

                    const ctx = this.getAudioContext();
                    if (!ctx) return;

                    fetch(targetUrl)
                        .then(res => {
                            if (!res.ok) throw new Error('Network response not ok: ' + res.status);
                            return res.arrayBuffer();
                        })
                        .then(buf => ctx.decodeAudioData(buf))
                        .then(decoded => {
                            this.audioBuffer = decoded;
                        })
                        .catch(err => {
                            console.warn('Failed to load/decode custom kitchen audio buffer:', err);
                        });
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

                    // Anti-Collision & Rush-Hour Queue Management
                    if (this.isPlaying) {
                        this.pendingAlert = true;
                        return;
                    }
                    this.isPlaying = true;

                    // Safety timeout: Ensure isPlaying never permanently locks queue even if tab is throttled
                    clearTimeout(this.safetyTimer);
                    this.safetyTimer = setTimeout(() => {
                        this.handlePlaybackFinished();
                    }, 4000);

                    const ctx = this.getAudioContext();
                    if (ctx && ctx.state === 'suspended') {
                        ctx.resume().catch(() => {});
                    }

                    // 1. Prioritize decoded RAM Web Audio Buffer ONLY if AudioContext is actively running
                    if (ctx && ctx.state === 'running' && this.audioBuffer) {
                        try {
                            const source = ctx.createBufferSource();
                            source.buffer = this.audioBuffer;
                            source.connect(ctx.destination);
                            source.onended = () => {
                                this.handlePlaybackFinished();
                            };
                            source.start(0);
                            return;
                        } catch (e) {
                            console.warn('Web Audio buffer source error in KDS:', e);
                        }
                    }

                    // 2. Primary fallback: HTMLMediaElement new Audio (works in background tabs where AudioContext is suspended)
                    const targetUrl = (typeof soundUrl === 'string' && soundUrl.length > 0)
                        ? soundUrl
                        : (this.customAudioUrl || '');

                    if (targetUrl) {
                        try {
                            const audio = new Audio(targetUrl);
                            audio.volume = 1.0;
                            audio.onended = () => { this.handlePlaybackFinished(); };
                            audio.onerror = () => { this.synthesizeServiceBell(); };
                            const promise = audio.play();
                            if (promise !== undefined) {
                                promise.then(() => {
                                    this.audioUnlocked = true;
                                    this.needsUnlockPrompt = false;
                                }).catch((err) => {
                                    console.warn('KDS HTML Audio play blocked or failed:', err);
                                    if (err.name === 'NotAllowedError') {
                                        this.needsUnlockPrompt = true;
                                        this.audioUnlocked = false;
                                    }
                                    this.synthesizeServiceBell();
                                });
                                return;
                            }
                        } catch (e) {
                            this.synthesizeServiceBell();
                            return;
                        }
                    }

                    // 3. Procedural Synthesizer Service Bell fallback
                    this.synthesizeServiceBell();
                },

                handlePlaybackFinished() {
                    clearTimeout(this.safetyTimer);
                    this.isPlaying = false;
                    if (this.pendingAlert) {
                        this.pendingAlert = false;
                        // Graceful 400ms pause before ringing next queued alert
                        setTimeout(() => {
                            this.ringBell();
                        }, 400);
                    }
                },

                synthesizeServiceBell() {
                    try {
                        const ctx = this.getAudioContext();
                        if (!ctx) {
                            this.handlePlaybackFinished();
                            return;
                        }

                        if (ctx.state === 'suspended') {
                            ctx.resume().catch(() => {});
                        }

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

                        setTimeout(() => {
                            this.handlePlaybackFinished();
                        }, 950);
                    } catch (e) {
                        console.error('KDS Synthesizer error:', e);
                        this.handlePlaybackFinished();
                    }
                }
            }"
            x-on:kds-beep.window="
                const targetUrl = $event.detail?.soundUrl || (Array.isArray($event.detail) ? $event.detail[0]?.soundUrl : null);
                ringBell(targetUrl);
            "
            class="ml-auto inline-flex items-center gap-2 text-xs"
        >
            <!-- Floating Audio Unlock Prompt for KDS screen if audio is locked -->
            <div
                x-show="needsUnlockPrompt && !audioUnlocked && !muted"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                @click="unlockAudio()"
                class="fixed top-16 right-6 z-50 flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold rounded-xl bg-amber-500 text-white shadow-xl shadow-amber-500/30 cursor-pointer hover:bg-amber-600 transition select-none animate-pulse"
                title="Sentuh layar sekali untuk mengizinkan bel pesanan otomatis berbunyi"
            >
                <span class="text-base">🔊</span>
                <span>Sentuh layar sekali untuk mengaktifkan bel dapur</span>
            </div>

            <button
                type="button"
                @click="toggle()"
                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md border font-medium transition select-none cursor-pointer"
                :class="muted
                    ? 'border-amber-300 bg-amber-50 text-amber-800 dark:border-amber-800 dark:bg-amber-950/40 dark:text-amber-300'
                    : (audioUnlocked
                        ? 'border-emerald-300 bg-emerald-50 text-emerald-800 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300'
                        : 'border-amber-300 bg-amber-50 text-amber-800 dark:border-amber-800 dark:bg-amber-950/40 dark:text-amber-300')"
                :title="muted ? 'Klik untuk mengaktifkan bel dapur' : 'Klik untuk mematikan bel dapur'"
            >
                <span x-text="muted ? '🔇 Bel Dapur Mati' : (audioUnlocked ? '🔔 Bel Dapur Aktif' : '⚠️ Sentuh untuk Aktifkan Bel')"></span>
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
