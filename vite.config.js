import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import purgecss from 'vite-plugin-purgecss';

export default defineConfig({
    publicDir: 'public',

    assetsInclude: [
        '**/*.svg',
        '**/*.png',
        '**/*.jpg',
        '**/*.jpeg',
        '**/*.gif',
        '**/*.webp'
    ],

    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/website.css',
                'resources/js/website.js',
                'resources/css/login.css',
                'resources/js/login.js'
            ],
            refresh: true,
        }),
        vue(),
        purgecss({
            content: [
                './resources/**/*.vue',
                './resources/**/*.blade.php',
                './resources/**/*.js',
            ],
            safelist: [
                // Keep dynamic classes used by Bootstrap/Admin template
                /^modal/,
                /^btn/,
                /^nav/,
                /^dropdown/,
                /^alert/,
                /^badge/,
                /^table/,
                /^form/,
                /^card/,
                /^text-/,
                /^bg-/,
                /^border-/,
                /^d-/,
                /^p-/,
                /^m-/,
                /^w-/,
                /^h-/,
                /^fs-/,
                /^fw-/,
                /^rounded/,
                /^shadow/,
                /^col-/,
                /^row/,
                /^container/,
                /^fa-/,
                /^feather/,
                /^swal/,
            ]
        }),
    ],

    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },

    server: {
        host: 'localhost'
    }
});
