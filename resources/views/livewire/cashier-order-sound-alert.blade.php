<div
    wire:poll.8s="checkNewOrders"
    x-data="{
        muted: localStorage.getItem('resto_cashier_sound_muted') === '1',
        customAudioUrl: '{{ \App\Models\PlatformSetting::cashierSoundUrl() ?? '' }}',
        audioCtx: null,

        init() {
            // Auto-unlock AudioContext on first user interaction in this session
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

        lastPlayedTime: 0,

        toggleMute() {
            this.muted = !this.muted;
            localStorage.setItem('resto_cashier_sound_muted', this.muted ? '1' : '0');
            if (!this.muted) {
                this.playAlertSound();
            }
        },

        playAlertSound(soundUrl = null) {
            if (this.muted) return;

            // Debounce so rapid/burst triggers within 1.5s play a clean single chime
            const now = Date.now();
            if (now - this.lastPlayedTime < 1500) {
                return;
            }
            this.lastPlayedTime = now;

            const activeUrl = soundUrl || this.customAudioUrl;

            // 1. If custom audio file exists, prioritize it
            if (activeUrl) {
                try {
                    const audio = new Audio(activeUrl);
                    const promise = audio.play();
                    if (promise !== undefined) {
                        promise.catch((err) => {
                            console.warn('Custom cashier audio playback failed, falling back to synthesizer:', err);
                            this.synthesizeChime();
                        });
                        return;
                    }
                } catch (e) {
                    console.warn('Custom cashier audio error:', e);
                    this.synthesizeChime();
                    return;
                }
            }

            // 2. Synthesize a warm, melodious two-tone restaurant cashier chime (Web Audio API)
            this.synthesizeChime();
        },

        synthesizeChime() {
            try {
                const ctx = this.getAudioContext();
                if (!ctx) return;

                const now = ctx.currentTime;

                // First tone: D5 (587.33 Hz)
                this.playTone(ctx, 587.33, now, 0.35, 0.45);

                // Second tone: A5 (880.00 Hz) slightly after
                this.playTone(ctx, 880.00, now + 0.16, 0.32, 0.55);
            } catch (e) {}
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
    x-on:cashier-order-sound.window="playAlertSound($event.detail?.soundUrl)"
    class="pointer-events-auto"
>
    <!-- Subtle Sound Status & Test Pill at Bottom-Right of Admin Panel -->
    <div
        class="fixed bottom-4 right-4 z-30 hidden sm:flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full shadow-sm border backdrop-blur transition select-none"
        :class="muted
            ? 'bg-amber-50/90 text-amber-800 border-amber-200 dark:bg-amber-950/80 dark:text-amber-300 dark:border-amber-800'
            : 'bg-white/90 text-gray-700 border-gray-200 dark:bg-gray-900/90 dark:text-gray-300 dark:border-gray-700'"
        style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);"
    >
        <button
            type="button"
            @click="toggleMute()"
            class="flex items-center gap-1 hover:opacity-80 transition focus:outline-none"
            :title="muted ? 'Klik untuk mengaktifkan notifikasi suara kasir' : 'Klik untuk mematikan notifikasi suara kasir'"
        >
            <span x-text="muted ? '🔇' : '🔔'"></span>
            <span x-text="muted ? 'Suara: Mati' : 'Suara Kasir: Aktif'"></span>
        </button>

        <span class="text-gray-300 dark:text-gray-600">|</span>

        <button
            type="button"
            @click="playAlertSound()"
            class="underline hover:text-primary-600 dark:hover:text-primary-400 focus:outline-none"
            title="Uji coba bunyikan notifikasi suara sekarang"
        >
            Tes
        </button>
    </div>
</div>
