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
// // mix.js('resources/js/popup.js', 'chromeExtention/scripts/popup.js')
// // mix.js('resources/js/common.js', 'js/common.js')
// mix.js('resources/js/home/index/index.js', 'js/index.js')
// mix.js('resources/js/home/columns/index.js', 'js/columns.js')
mix.js('resources/js/home/templates/index.js', 'js/templates.js')
mix.js('resources/js/home/template/index.js', 'js/template-products.js')
mix.js('resources/js/home/keys/index.js', 'js/keys.js')
mix.js('resources/js/home/product_columns/index.js', 'js/product-columns.js')
