const mix = require('laravel-mix');

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
// mix.sass('resources/sass/app.scss','css/common.css')
mix.js('resources/js/app2.js', 'chromeExtention/scripts/main.js')
// mix.js('resources/js/popup.js', 'chromeExtention/scripts/popup.js')
// mix.js('resources/js/common.js', 'js/common.js')
// mix.js('resources/js/home/index/index.js', 'js/index.js')