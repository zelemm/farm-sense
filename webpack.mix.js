// const cssImport = require('postcss-import')
// const cssNesting = require('postcss-nesting')
const mix = require('laravel-mix')
const path = require('path')
// const purgecss = require('@fullhuman/postcss-purgecss')
// const tailwindcss = require('tailwindcss')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
// mix.babelConfig({
//     plugins: ['@babel/plugin-syntax-dynamic-import']
// })

mix.copyDirectory('resources/fonts', 'public/fonts');
mix.copyDirectory('resources/assets/img', 'public/img');

mix.js('resources/js/app.js', 'public/js').vue()
    .sass('resources/css/app.scss', 'public/css/app.css')
    // .options({
    //   postCss: [
    //     cssImport(),
    //     cssNesting(),
    //     tailwindcss('tailwind.config.js'),
    //     ...mix.inProduction() ? [
    //       purgecss({
    //         content: ['./resources/views/**/*.blade.php', './resources/js/**/*.vue'],
    //         defaultExtractor: content => content.match(/[\w-/:.]+(?<!:)/g) || [],
    //         whitelistPatternsChildren: [/nprogress/],
    //         safelist: [/^v-/]
    //       }),
    //     ] : [],
    //     require('precss')()
    //   ],
    // })
    .webpackConfig({
        output: { chunkFilename: 'js/[name].js?id=[chunkhash]' },
        resolve: {
            alias: {
                vue$: 'vue/dist/vue.runtime.esm.js',
                '@': path.resolve('resources/js'),
            },
        },
    })
    .version()
    .sourceMaps()

