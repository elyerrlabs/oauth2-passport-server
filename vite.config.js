// vite.config.js
import inertia from '@inertiajs/vite'
import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import fs from 'fs';
import tailwindcss from '@tailwindcss/vite'

function loadAliasCore() {
    const corePath = path.resolve(__dirname, 'core');
    const alias = {};

    if (fs.existsSync(corePath)) {
        fs.readdirSync(corePath).forEach((module) => {
            const jsPath = path.join(corePath, module, 'resources/js');

            if (fs.existsSync(jsPath)) {
                alias[`@${module.toLowerCase()}`] = jsPath;
            }
        });
    }

    return alias;
}

function loadEntries() {
    const entries = {
        app: path.resolve(__dirname, 'resources/js/app.js'),
        css: path.resolve(__dirname, 'resources/css/app.css'),
        pages: path.resolve(__dirname, 'resources/js/pages.js'),
    };

    const corePath = path.resolve(__dirname, 'core');

    if (!fs.existsSync(corePath)) {
        return entries;
    }

    fs.readdirSync(corePath).forEach((module) => {
        ['js', 'scss', 'css'].forEach((type) => {
            const dir = path.join(corePath, module, `resources/${type}`);

            if (!fs.existsSync(dir)) {
                return;
            }

            fs.readdirSync(dir)
                .filter(file => file.endsWith(`.${type}`))
                .forEach(file => {

                    const name = `${type}/core/${module.toLowerCase()}/${path.parse(file).name}`;
                    entries[name] = path.join(dir, file);
                });
        });
    });

    return entries;
}
export default defineConfig({
    server: {
        watch: {
            ignored: [
                '**/.junie/**',
                '**/.cursor/**',
                '**/.claude/**',
            ],
        },
    },
    publicDir: false,
    plugins: [
        laravel({
            input: Object.values(loadEntries()),
            refresh: true,
        }),
        tailwindcss(),
        vue(),
        inertia({
            ssr: false,
        })
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
            '@css': path.resolve(__dirname, 'resources/css'),
            ...loadAliasCore(),
        }
    },

    assetsInclude: ['**/*.txt'],

    /*build: {
        manifest: true,
        outDir: 'public/build',
        emptyOutDir: false,
        rollupOptions: {
            input: loadEntries(),
            output: {
                entryFileNames: 'js/[name].js',
                chunkFileNames: 'js/chunks/[name]-[hash].js',
                assetFileNames: (assetInfo) => {
                    if (assetInfo.names?.some(name => name.endsWith('.css'))) {
                        return 'css/[name][extname]';
                    }
                    if (assetInfo.names?.some(name => name.endsWith('.ttf'))) {
                        return 'fonts/[name][extname]';
                    }
                    return 'assets/[name][extname]';
                }
            }
        }
    }*/
});