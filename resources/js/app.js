import './bootstrap';
import { bindThemeToggle, initReveal, initTheme, toggleTheme } from './theme';

initTheme();

window.toggleTheme = toggleTheme;

window.imagePreview = window.imagePreview || function (images) {
    return {
        images: Array.isArray(images) ? images : [],
        index: 0,
        previewOpen: false,

        get current() {
            return this.images[this.index] || { src: '', caption: '' };
        },

        init() {
            this.$watch('previewOpen', (open) => {
                document.body.classList.toggle('overflow-hidden', Boolean(open));
            });
        },

        openPreview(i) {
            const next = Number(i);

            if (! Number.isFinite(next) || next < 0 || next >= this.images.length) {
                return;
            }

            this.index = next;
            this.previewOpen = true;
        },

        closePreview() {
            this.previewOpen = false;
        },

        previewNext() {
            if (this.images.length < 2) {
                return;
            }

            this.index = (this.index + 1) % this.images.length;
        },

        previewPrev() {
            if (this.images.length < 2) {
                return;
            }

            this.index = (this.index - 1 + this.images.length) % this.images.length;
        },
    };
};

document.addEventListener('DOMContentLoaded', () => {
    initTheme();
    bindThemeToggle();
    initReveal();
});

document.addEventListener('livewire:navigated', () => {
    initTheme();
    bindThemeToggle();
    initReveal();
});
