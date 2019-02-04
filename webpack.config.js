const Encore = require('@symfony/webpack-encore');

Encore
// directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableVersioning(Encore.isProduction())
    .enableSourceMaps(!Encore.isProduction())
    .addEntry('app', './assets/js/app.js')
    .addLoader({
            test: /\.js$/,
            loader: 'babel-loader',
            query: {
                    presets: [
                        [
                            "@babel/preset-env",
                            {
                                "useBuiltIns": "entry"
                            }
                        ]
                    ]
            }
    })
    .enableSassLoader((options) => {
            options.outputStyle = 'compressed';
    })
    .disableSingleRuntimeChunk();

module.exports = Encore.getWebpackConfig();