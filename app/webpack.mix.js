const mix = require("laravel-mix");
const WebpackBarPlugin = require("webpackbar");

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
const CACHE_KEY_SUFFIX = "-1";

const barPlugin = new WebpackBarPlugin({
    name: "LIFF"
});

if (mix.inProduction()) {
    mix.version();
}

mix.js("resources/assets/js/app.js", "public/assets/js")
    .sourceMaps(false, "source-map")
    .extract(["vue"])
    .sass("resources/assets/sass/app.scss", "public/assets/css");

mix.webpackConfig({
    context: path.resolve(__dirname),
    plugins: [barPlugin],
    output: {
        chunkFilename: mix.inProduction() ? `[name].js` : "[name].dev.js"
    },
    watchOptions: {
        poll: 1000,
        ignored: "./node_modules/"
    }
});
