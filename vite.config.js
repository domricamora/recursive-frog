import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            // Self-hosted so the first paint is not blocked on a third-party
            // stylesheet, and so no visitor data reaches a font CDN.
            fonts: [
                bunny('Archivo', {
                    weights: [400, 500, 600],
                }),
                bunny('Newsreader', {
                    weights: [300, 400, 500],
                    styles: ['normal', 'italic'],
                }),
                bunny('Caveat', {
                    weights: [500],
                }),
                bunny('JetBrains Mono', {
                    weights: [400, 500],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
