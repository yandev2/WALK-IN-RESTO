import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/filament/admin/theme.css',
                'resources/css/auth-glass.css',
            ],
            refresh: [
                'resources/views/**',
                'resources/css/**',
                'resources/js/**',
                'routes/**',
                'app/Livewire/**',
                'app/Filament/**',
                'app/View/**',
            ],
        }),
        tailwindcss(),
    ],
    server: {
        host: 'localhost',
        cors: true,
        watch: {
            ignored: [
                '**/graphify-out/**',
                '**/storage/**',
                '**/*.tmp',
                '**/Cetak biru blogger/**',
            ],
        },
    },
});
