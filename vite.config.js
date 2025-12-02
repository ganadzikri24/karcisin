import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss', // <--- Pastikan ini 'sass' dan 'scss'
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});