const mix = require('laravel-mix');
// require('laravel-mix-polyfill');
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
  .sass('resources/sass/app.scss', 'public/css')
  .version()
  .webpackConfig({
    output: {
      publicPath: "http://192.168.99.100:80/" // (ホストOSのhostsで割り当てたドメイン):8080 でもＯＫ
    },
    devServer: {
      disableHostCheck: true,
      contentBase: path.join(__dirname, "public"),
      publicPath: '/',
      host: '0.0.0.0',
      port: 80,
      proxy: {
        '/': {
          target: 'http://nginx'
        }
      }
    },
    watchOptions: {
      poll: true,
      ignored: /node_modules/
    }
  })
  /*
  .polyfill({
    enabled: true,
    useBuiltIns: "usage",
    targets: { "ie": 11 }
  });
  */

