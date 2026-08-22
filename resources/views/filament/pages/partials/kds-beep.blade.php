<div
    wire:ignore
    x-data="{
        beep() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'square';
                osc.frequency.value = 880;
                gain.gain.value = 0.05;
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.18);
            } catch (e) {}
        }
    }"
    x-on:kds-beep.window="beep()"
></div>
