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

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/pages.js', 'public/js/pages.js')
    //  .sass('resources/scss/app.css', 'public/css')
    .postCss('resources/css/app.css', 'public/css', [
        require('@tailwindcss/postcss'),
        require("autoprefixer"),
    ])
    .vue()

/**
 *  
 */
loadAssets().forEach(({ module, type, file }) => {
    if (type === 'js') {
        mix.js(file, `public/js/core/${module.toLowerCase()}`);
    }

    if (type === 'scss') {
        mix.sass(file, `public/css/core/${module.toLowerCase()}`);
    }

    if (type === 'css') {
        mix.postCss(file, `public/css/core/${module.toLowerCase()}`);
    }
});

mix.version();
