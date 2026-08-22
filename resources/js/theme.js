function applyTheme(isDark) {
    document.documentElement.classList.toggle('dark', isDark);
    document.documentElement.style.colorScheme = isDark ? 'dark' : 'light';
    syncThemeToggleIcons(isDark);
}

function syncThemeToggleIcons(isDark) {
    document.querySelectorAll('[data-theme-icon="light"]').forEach((el) => {
        el.hidden = isDark;
    });
    document.querySelectorAll('[data-theme-icon="dark"]').forEach((el) => {
        el.hidden = ! isDark;
    });
}

export function initTheme() {
    try {
        const stored = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const isDark = stored === 'dark' || (! stored && prefersDark);
        applyTheme(isDark);
    } catch {
        // ignore
    }
}

export function toggleTheme() {
    const isDark = ! document.documentElement.classList.contains('dark');
    applyTheme(isDark);
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
}

export function initReveal() {
    const reveals = document.querySelectorAll('.landing-reveal:not(.is-visible)');

    if (! reveals.length) {
        return;
    }

    if (! ('IntersectionObserver' in window)) {
        reveals.forEach((el) => el.classList.add('is-visible'));

        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.08, rootMargin: '0px 0px -32px 0px' },
    );

    reveals.forEach((el) => observer.observe(el));
}

export function bindThemeToggle() {
    document.querySelectorAll('[data-theme-toggle]').forEach((btn) => {
        if (btn.dataset.themeBound === '1') {
            return;
        }

        btn.dataset.themeBound = '1';
        btn.addEventListener('click', toggleTheme);
    });

    syncThemeToggleIcons(document.documentElement.classList.contains('dark'));
}
