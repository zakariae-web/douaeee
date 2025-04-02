import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/ar.js'],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            // This ensures proper resolution of three.js modules
            'three': 'three/build/three.module.js',
        },
    },
});