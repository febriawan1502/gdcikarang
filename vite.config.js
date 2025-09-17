import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { resolve } from 'path';

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
    resolve: {
        alias: {
            '~bootstrap': resolve(__dirname, 'node_modules/bootstrap'),
            '~@fortawesome': resolve(__dirname, 'node_modules/@fortawesome'),
            '~datatables.net': resolve(__dirname, 'node_modules/datatables.net'),
            '~sweetalert2': resolve(__dirname, 'node_modules/sweetalert2'),
            '~jquery': resolve(__dirname, 'node_modules/jquery'),
        }
    },
    build: {
        rollupOptions: {
            output: {
                manualChunks: undefined
            }
        },
        sourcemap: true,
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true
            }
        }
    },
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: 'localhost'
        },
        watch: {
            usePolling: true
        }
    },
    css: {
        preprocessorOptions: {
            scss: {
                additionalData: `
                    @import "~bootstrap/scss/functions";
                    @import "~bootstrap/scss/variables";
                    @import "~bootstrap/scss/mixins";
                `
            }
        },
        devSourcemap: true
    },
    optimizeDeps: {
        include: [
            'jquery',
            'bootstrap',
            '@popperjs/core',
            'datatables.net',
            'datatables.net-bs5',
            'sweetalert2'
        ]
    }
});