import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    // THIS IS THE WSL NETWORK BRIDGE
    server: {
        host: '0.0.0.0', // Tells Linux to open the door to Windows
        hmr: {
            host: 'localhost', // Tells Firefox exactly where to connect for live updates
        },
        watch: {
            usePolling: true, // Forces Vite to notice when you save a file in VS Code
        }
    },
});