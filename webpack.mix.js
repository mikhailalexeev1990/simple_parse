const mix = require('laravel-mix');

require('@ayctor/laravel-mix-svg-sprite');
require('dotenv').config();

mix.js('resources/js/main.js', 'public/js');
mix.sass('resources/sass/main.scss', 'public/css');

mix.version()
    .sourceMaps(false, 'source-map')
    .browserSync({
        proxy: `localhost:${process.env.PROJECT_PORT}`,
        port: process.env.BROWSER_SYNC_PORT
    });


mix.svgSprite('resources/svg/*.svg', {
    output: {
        filename: 'svg/sprite.svg',
        svg: {
            sizes: false,
        },
        chunk: {
            name: '/svg/sprite',
            keep: true
        },
        svgo: {
            plugins: [{
                removeEmptyAttrs: true,
                convertStyleToAttrs: true,
                cleanupListOfValues: true
            }],
        }
    },
    sprite: {
        prefix: false,
        generate: {
            use: true,
            title: false,
            view: '-svg',
            symbol: true
        },
    },
    styles: {
        format: 'fragment',
        filename: path.join(__dirname, 'resources/sass/mixins/_sprites.scss')
    }
});

/* vue.js */
mix.js('resources/js/vue/app.js', 'public/js/vue/app.js');
