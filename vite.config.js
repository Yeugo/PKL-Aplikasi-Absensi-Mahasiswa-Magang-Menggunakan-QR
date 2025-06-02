// vite.config.js
import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
    server: { // <<< Tambahkan bagian server ini
        host: '127.0.0.1', // Pastikan Vite mendengarkan di IP ini
        port: 5173, // Port default Vite, bisa diubah jika ada konflik
        hmr: {
            host: '127.0.0.1', // Penting: Pastikan ini cocok dengan IP Laravel
            port: 5173, // Port yang digunakan Vite untuk HMR
        },
        // Atau jika menggunakan 'localhost' di APP_URL:
        // hmr: {
        //     host: 'localhost',
        //     port: 5173,
        // },
    },
});
