import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/frontend.css',
                'resources/css/admin.css',
                'resources/css/admin/tinymce-enhanced.css',
                'resources/js/app.js',
                'resources/js/admin/login.js',
                'resources/js/admin/admin.js',
                'resources/js/admin/course.js',
                'resources/js/admin/tinymce-enhanced.js',
                'resources/js/admin/image-handler.js',

                'resources/js/frontend/course.js',
                'resources/js/frontend/frontend.js',
                'resources/js/frontend/player.js',

            ],
            refresh: true,
        }),
    ],
});
