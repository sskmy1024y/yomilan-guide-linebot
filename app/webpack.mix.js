const mix = require('laravel-mix')
const WebpackBarPlugin = require('webpackbar')
const VueLoaderPlugin = require('vue-loader/lib/plugin')

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

const barPlugin = new WebpackBarPlugin({
  name: 'LIFF'
})

mix
  .ts('resources/assets/ts/app.ts', 'public/assets/js')
  .sourceMaps(false, 'source-map')
  .extract(['vue'])
  .sass('resources/assets/sass/app.scss', 'public/assets/css')

mix.webpackConfig({
  context: path.resolve(__dirname),
  plugins: [barPlugin],
  output: {
    chunkFilename: mix.inProduction() ? `[name].js` : '[name].dev.js'
  },
  watchOptions: {
    poll: 1000,
    ignored: './node_modules/'
  }
})

if (mix.inProduction()) {
  mix.version()
} else {
  mix.browserSync({
    proxy: 'localhost:3000',
    files: ['./resources/views/**/*.blade.php', './public/css/*.css', './public/js/*.js']
  })
}
