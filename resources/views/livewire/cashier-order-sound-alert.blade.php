<div
    wire:poll.5s="checkNewOrders"
    wire:ignore.self
    x-data="{
        muted: localStorage.getItem('resto_cashier_sound_muted') === '1',
        customAudioUrl: '{{ \App\Models\PlatformSetting::cashierSoundUrl() ?? '' }}',
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

            // Universal unlocker: any touch, click, or keypress anywhere on the screen primes the audio hardware
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
                    console.warn('Failed to load/decode custom cashier audio buffer:', err);
                });
        },

        toggleMute() {
            this.muted = !this.muted;
            localStorage.setItem('resto_cashier_sound_muted', this.muted ? '1' : '0');
            if (!this.muted) {
                this.playAlertSound();
            }
        },

        playAlertSound(soundUrl = null) {
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
                    console.warn('Web Audio buffer source error:', e);
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
                    audio.onerror = () => { this.synthesizeChime(); };
                    const promise = audio.play();
                    if (promise !== undefined) {
                        promise.then(() => {
                            this.audioUnlocked = true;
                            this.needsUnlockPrompt = false;
                        }).catch((err) => {
                            console.warn('Cashier HTML Audio play blocked or failed:', err);
                            if (err.name === 'NotAllowedError') {
                                this.needsUnlockPrompt = true;
                                this.audioUnlocked = false;
                            }
                            this.synthesizeChime();
                        });
                        return;
                    }
                } catch (e) {
                    this.synthesizeChime();
                    return;
                }
            }

            // 3. Procedural Synthesizer Chime fallback
            this.synthesizeChime();
        },

        handlePlaybackFinished() {
            clearTimeout(this.safetyTimer);
            this.isPlaying = false;
            if (this.pendingAlert) {
                this.pendingAlert = false;
                // Graceful 400ms pause before ringing next queued alert
                setTimeout(() => {
                    this.playAlertSound();
                }, 400);
            }
        },

        synthesizeChime() {
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

                // First tone: D5 (587.33 Hz)
                this.playTone(ctx, 587.33, now, 0.35, 0.45);

                // Second tone: A5 (880.00 Hz) slightly after
                this.playTone(ctx, 880.00, now + 0.16, 0.32, 0.55);

                setTimeout(() => {
                    this.handlePlaybackFinished();
                }, 750);
            } catch (e) {
                this.handlePlaybackFinished();
            }
        },

        playTone(ctx, freq, startTime, maxGain, duration) {
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();

            osc.type = 'sine';
            osc.frequency.setValueAtTime(freq, startTime);

            // Add slight harmonic overtone for chime richness
            const overtone = ctx.createOscillator();
            const overtoneGain = ctx.createGain();
            overtone.type = 'sine';
            overtone.frequency.setValueAtTime(freq * 2, startTime);
            overtoneGain.gain.setValueAtTime(0.001, startTime);
            overtoneGain.gain.exponentialRampToValueAtTime(maxGain * 0.25, startTime + 0.02);
            overtoneGain.gain.exponentialRampToValueAtTime(0.0001, startTime + duration * 0.7);

            gain.gain.setValueAtTime(0.001, startTime);
            gain.gain.exponentialRampToValueAtTime(maxGain, startTime + 0.02);
            gain.gain.exponentialRampToValueAtTime(0.0001, startTime + duration);

            osc.connect(gain);
            overtone.connect(overtoneGain);
            gain.connect(ctx.destination);
            overtoneGain.connect(ctx.destination);

            osc.start(startTime);
            overtone.start(startTime);
            osc.stop(startTime + duration);
            overtone.stop(startTime + duration);
        }
    }"
    x-on:cashier-order-sound.window="
        const targetUrl = $event.detail?.soundUrl || (Array.isArray($event.detail) ? $event.detail[0]?.soundUrl : null);
        playAlertSound(targetUrl);
    "
    class="pointer-events-auto"
>
    <!-- Floating Audio Unlock Banner: appears only if browser audio is locked after page load/refresh -->
    <div
        x-show="needsUnlockPrompt && !audioUnlocked && !muted"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        @click="unlockAudio()"
        class="fixed bottom-16 right-4 z-40 flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold rounded-xl bg-amber-500 text-white shadow-lg shadow-amber-500/25 cursor-pointer hover:bg-amber-600 transition select-none animate-pulse"
        title="Klik untuk mengizinkan pemutaran suara notifikasi otomatis"
    >
        <span>🔊</span>
        <span>Klik di mana saja untuk mengaktifkan suara pesanan</span>
    </div>

    <!-- Subtle Sound Status & Test Pill at Bottom-Right of Admin Panel -->
    <div
        class="fixed bottom-4 right-4 z-30 hidden sm:flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full shadow-sm border backdrop-blur transition select-none"
        :class="muted
            ? 'bg-amber-50/90 text-amber-800 border-amber-200 dark:bg-amber-950/80 dark:text-amber-300 dark:border-amber-800'
            : (audioUnlocked
                ? 'bg-white/90 text-gray-700 border-gray-200 dark:bg-gray-900/90 dark:text-gray-300 dark:border-gray-700'
                : 'bg-amber-50/90 text-amber-700 border-amber-300 dark:bg-amber-950/80 dark:text-amber-300 dark:border-amber-700')"
        style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);"
    >
        <button
            type="button"
            @click="toggleMute()"
            class="flex items-center gap-1 hover:opacity-80 transition focus:outline-none cursor-pointer"
            :title="muted ? 'Klik untuk mengaktifkan notifikasi suara kasir' : 'Klik untuk mematikan notifikasi suara kasir'"
        >
            <span x-text="muted ? '🔇' : (audioUnlocked ? '🔔' : '⚠️')"></span>
            <span x-text="muted ? 'Suara: Mati' : (audioUnlocked ? 'Suara Kasir: Aktif' : 'Klik untuk Aktifkan Suara')"></span>
        </button>

        <span class="text-gray-300 dark:text-gray-600">|</span>

        <button
            type="button"
            @click="playAlertSound()"
            class="underline hover:text-primary-600 dark:hover:text-primary-400 focus:outline-none cursor-pointer"
            title="Uji coba bunyikan notifikasi suara sekarang"
        >
            Tes
        </button>
    </div>
</div>
