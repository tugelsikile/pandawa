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

/*mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');*/

//mix css
mix.styles([
    'vendor/twitter/bootstrap/dist/css/bootstrap.min.css',
    'vendor/datatables/datatables/media/css/dataTables.bootstrap.css',
    'vendor/fortawesome/font-awesome/css/font-awesome.min.css',
    'node_modules/sweetalert2/dist/sweetalert2.min.css',
    'resources/sass/custom.scss'
],'public/pandawa/css/mix-all.css');
mix.styles([
    'vendor/twitter/bootstrap/dist/css/bootstrap.min.css',
    'vendor/fortawesome/font-awesome/css/font-awesome.min.css'
],'public/pandawa/css/guest.css');


mix.scripts([
    'vendor/components/jquery/jquery.min.js',
    'vendor/datatables/datatables/media/js/jquery.dataTables.min.js',
    'vendor/twitter/bootstrap/dist/js/bootstrap.min.js',
    'vendor/datatables/datatables/media/js/dataTables.bootstrap.js',
    'node_modules/sweetalert2/dist/sweetalert2.min.js',
],'public/pandawa/js/mix-all.js');
mix.scripts([
    'vendor/components/jquery/jquery.min.js',
    'vendor/twitter/bootstrap/dist/js/bootstrap.min.js',
],'public/pandawa/js/guest.js');


mix.copyDirectory('vendor/fortawesome/font-awesome/fonts', 'public/pandawa/fonts');
mix.copyDirectory('vendor/twitter/bootstrap/dist/fonts', 'public/pandawa/fonts');