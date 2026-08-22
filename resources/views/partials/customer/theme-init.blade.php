<script>
    (function () {
        try {
            var stored = localStorage.getItem('theme');
            var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            var isDark = stored === 'dark' || (!stored && prefersDark);
            var root = document.documentElement;
            if (isDark) {
                root.classList.add('dark');
                root.style.colorScheme = 'dark';
            } else {
                root.style.colorScheme = 'light';
            }
        } catch (e) {}
    })();
</script>
