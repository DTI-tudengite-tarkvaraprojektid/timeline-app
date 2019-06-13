const path = require('path')
const { SRC, DIST, ASSETS } = require('./paths')
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const webpack = require('webpack');

module.exports = {
    entry: {
        app: path.resolve(SRC, 'js', 'app.js')
    },
    output: {
        path: DIST,
        filename: '[name].js',
        publicPath: ASSETS
    },
    module: {
        rules: [
            {
                test: /\.m?js$/,
                exclude: /(bower_components)/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env']
                    }
                }
            },
            {
                test: /\.scss$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    "css-loader",
                    {
                        loader: "postcss-loader",
                        options: {
                            plugins: function () {
                                return [
                                    require("autoprefixer")
                                ];
                            }
                        }
                    },
                    "sass-loader"
                ]
            }, 
            {
                test: /\.svg$/,
                use: {
                    loader: 'html-loader',
                    options: {
                        minimize: true
                    }
                }
            }
        ]
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: "[name].css",
            chunkFilename: "[id].css"
        }),
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery'
        })
    ]
}