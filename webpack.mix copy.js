const mix = require('laravel-mix');
const exec = require('child_process').exec;
require('dotenv').config();

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

const glob = require('glob')
const path = require('path')

/*
 |--------------------------------------------------------------------------
 | Vendor assets
 |--------------------------------------------------------------------------
 */

function mixAssetsDir(query, cb) {
  (glob.sync('resources/' + query) || []).forEach(f => {
    f = f.replace(/[\\\/]+/g, '/');
    cb(f, f.replace('resources', 'public'));
  });
}

const sassOptions = {
  precision: 5
};

// plugins Core stylesheets
mixAssetsDir('sass/plugins/**/!(_)*.scss', (src, dest) => mix.sass(src, dest.replace(/(\\|\/)sass(\\|\/)/, '$1css$2').replace(/\.scss$/, '.css'), sassOptions));

// themes Core stylesheets
mixAssetsDir('sass/themes/**/!(_)*.scss', (src, dest) => mix.sass(src, dest.replace(/(\\|\/)sass(\\|\/)/, '$1css$2').replace(/\.scss$/, '.css'), sassOptions));

// pages Core stylesheets
mixAssetsDir('sass/pages/**/!(_)*.scss', (src, dest) => mix.sass(src, dest.replace(/(\\|\/)sass(\\|\/)/, '$1css$2').replace(/\.scss$/, '.css'), sassOptions));

// Core stylesheets
mixAssetsDir('sass/core/**/!(_)*.scss', (src, dest) => mix.sass(src, dest.replace(/(\\|\/)sass(\\|\/)/, '$1css$2').replace(/\.scss$/, '.css'), sassOptions));

// script js
mixAssetsDir('js/scripts/**/*.js', (src, dest) => mix.scripts(src, dest));

/*
 |--------------------------------------------------------------------------
 | Application assets
 |--------------------------------------------------------------------------
 */

mixAssetsDir('vendors/js/**/*.js', (src, dest) => mix.scripts(src, dest));
mixAssetsDir('vendors/css/**/*.css', (src, dest) => mix.copy(src, dest));
mixAssetsDir('vendors/css/editors/quill/fonts/', (src, dest) => mix.copy(src, dest));
mix.copyDirectory('resources/images', 'public/images');
mix.copyDirectory('resources/fonts', 'public/fonts');

mix.copy('node_modules/sweetalert2/dist/sweetalert2.min.js', 'public/js/scripts');
mix.copy('node_modules/sweetalert2/dist/sweetalert2.min.css', 'public/css');
mix.copy('node_modules/select2/dist/css/select2.min.css', 'public/css');
mix.copy('node_modules/animate.css/animate.min.css', 'public/css');
mix.copy('node_modules/select2/dist/js/select2.full.min.js', 'public/js/scripts');
mix.copy('node_modules/jquery-mask-plugin/dist/jquery.mask.min.js', 'public/js/scripts');
mix.copy('node_modules/apexcharts/dist/apexcharts.min.js', 'public/vendors/js/charts');
//mix.copy('node_modules/apexcharts/dist/apexcharts.css', 'public/vendors/css/charts');
mix.copy('node_modules/quill/dist/quill.bubble.css', 'public/css');
mix.copy('node_modules/quill/dist/quill.snow.css', 'public/css');


mix.js('resources/js/core/app-menu.js', 'public/js/core')
  .js('resources/js/core/app.js', 'public/js/core')
  .js('resources/js/user/funnel.js', 'public/js/user')
  .js('resources/js/user/marketingAction.js', 'public/js/user')
  .js('resources/js/user/funnelShow.js', 'public/js/user')
  .js('resources/js/user/funnelOrgChart.js', 'public/js/user')
  .js('node_modules/insert-text-at-cursor', 'public/js')
  .sass('resources/sass/bootstrap.scss', 'public/css')
  .sass('resources/sass/bootstrap-extended.scss', 'public/css')
  .sass('resources/sass/colors.scss', 'public/css')
  .sass('resources/sass/components.scss', 'public/css')
  .sass('resources/sass/custom-rtl.scss', 'public/css')
  .sass('resources/sass/custom-laravel.scss', 'public/css')
  .sass('resources/sass/organogram.scss', 'public/css')
  .sass('resources/sass/custom.scss', 'public/css');

/* mix.then(() => {
  if (process.env.MIX_CONTENT_DIRECTION === "rtl") {
    let command = `node ${path.resolve('node_modules/rtlcss/bin/rtlcss.js')} -d -e ".css" ./public/css/ ./public/css/`;
    exec(command, function (err, stdout, stderr) {
      if (err !== null) {
        console.log(err);
      }
    });
    // exec('./node_modules/rtlcss/bin/rtlcss.js -d -e ".css" ./public/css/ ./public/css/');
  }
}); */

mix.version();
