import './bootstrap';
import { bindThemeToggle, initReveal, initTheme, toggleTheme } from './theme';

initTheme();

window.toggleTheme = toggleTheme;

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
