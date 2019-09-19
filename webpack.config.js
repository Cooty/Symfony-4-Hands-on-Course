const Encore = require('@symfony/webpack-encore');

Encore
// directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableVersioning()
    .enableSourceMaps(!Encore.isProduction())
    //TODO: Add minification to JS output
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
            //TODO: find out why it's not compressing
            options.outputStyle = 'compressed';
    })
    .enableSingleRuntimeChunk();

const config = Encore.getWebpackConfig();
config.watchOptions = {
    poll: true
};

module.exports = config;