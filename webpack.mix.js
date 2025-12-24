const mix = require('laravel-mix');
const path = require('path');
const fs = require('fs')
const MonacoWebpackPlugin = require('monaco-editor-webpack-plugin');

/**
 * Resolve components
 * @returns 
 */
function LoadAliasCore() {
    const corePath = path.resolve(__dirname, "core");
    const alias = {};

    if (fs.existsSync(corePath)) {
        const modules = fs.readdirSync(corePath);

        modules.forEach((module) => {
            const jsPath = path.join(corePath, module, "resources/js");

            if (fs.existsSync(jsPath)) {
                alias[`@${module}`.toLocaleLowerCase()] = jsPath;
            }
        });
    }
    return alias;
}

function loadAssets() {
    const corePath = path.resolve(__dirname, 'core');
    const assets = [];

    if (!fs.existsSync(corePath)) return assets;

    fs.readdirSync(corePath).forEach((module) => {
        ['js', 'scss', 'css'].forEach((type) => {
            const dir = path.join(corePath, module, `resources/${type}`);

            if (!fs.existsSync(dir)) return;

            fs.readdirSync(dir)
                .filter(file => file.match(new RegExp(`\\.(${type})$`)))
                .forEach(file => {
                    assets.push({
                        module,
                        type,
                        file: path.join(dir, file),
                    });
                });
        });
    });

    return assets;
}
mix.webpackConfig({
    resolve: {
        alias: {
            "@css": path.resolve(__dirname, "resources/css"),
            "@": path.resolve(__dirname, "resources/js"),
            ...LoadAliasCore(),
        },
    },
    stats: {
        children: false,
    },
    module: {
        rules: [
            {
                test: /\.ttf$/,
                type: 'asset/resource',
            },
            { test: /\.txt$/, use: 'raw-loader' }
        ],
    },
    plugins: [
        new MonacoWebpackPlugin({
            languages: ['json', 'css', 'html', 'typescript'],
        }),
    ],
})

mix.js('resources/js/app.js', 'js').version()
    .js('resources/js/pages.js', 'js/pages.js').version()
    //  .sass('resources/scss/app.css', 'css')
    .postCss('resources/css/app.css', 'css', [
        require('@tailwindcss/postcss'),
        require("autoprefixer"),
    ]).version()

/**
 *  
 */
loadAssets().forEach(({ module, type, file }) => {
    if (type === 'js') {
        mix.js(file, `js/core/${module.toLowerCase()}`).version();
    }

    if (type === 'scss') {
        mix.sass(file, `css/core/${module.toLowerCase()}`).version();
    }

    if (type === 'css') {
        mix.postCss(file, `css/core/${module.toLowerCase()}`).version();
    }
});
mix.vue({ version: 3 }) 