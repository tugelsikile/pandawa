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

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');

//mix css
mix.styles([
    'vendor/fortawesome/font-awesome/css/font-awesome.min.css',
    'node_modules/sweetalert2/dist/sweetalert2.min.css'
],'public/pandawa/css/mix-all.css');

mix.scripts([
    'node_modules/sweetalert2/dist/sweetalert2.min.js',
],'public/pandawa/js/mix-all.js');

mix.copyDirectory('vendor/fortawesome/font-awesome/fonts', 'public/fonts');