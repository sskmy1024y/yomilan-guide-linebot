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

const barPlugin = new WebpackBarPlugin({
    name: "LIFF"
});

const production = process.env.NODE_ENV === "production";

mix.js("resources/assets/js/app.js", "public/js").sass(
    "resources/assets/sass/app.scss",
    "public/css"
);

mix.webpackConfig({
    context: path.resolve(__dirname),
    output: {
        filename: production
            ? `[name].bundle.[chunkhash]${CACHE_KEY_SUFFIX}.js`
            : "[name].bundle.[hash].dev.js",
        chunkFilename: production
            ? `[name].chunk.[chunkhash]${CACHE_KEY_SUFFIX}.js`
            : "[name].chunk.[hash].dev.js"
    },
    devtool: "source-map",
    plugins: [barPlugin],
    watchOptions: {
        poll: 1000,
        ignored: "./node_modules/"
    }
});
