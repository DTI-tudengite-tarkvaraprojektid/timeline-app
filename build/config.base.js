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
                test: /\.s(a|c)ss$/,
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
                test: /\.css$/,
                use: [ 'css-loader' ]
            },
            {
                test: /\.svg$/,
                use: {
                    loader: 'html-loader',
                    options: {
                        minimize: true
                    }
                }
            },
            {
                test: /\.(jpe?g|png|gif)$/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            outputPath: (url, resourcePath, context) => {
                                if (/icon\.png|tile\.png|tile-wide\.png/.test(resourcePath)) {
                                    return url;
                                }
                                else {
                                    return `images/${url}`;
                                }
                            },
                            name: '[name].[ext]',
                        },
                    },
                    {
                      loader: 'image-webpack-loader',
                      options: {
                        disable: process.env.NODE_ENV !== 'production', // Disable during development
                        mozjpeg: {
                          progressive: true,
                          quality: 75
                        },
                      },
                    }
                ],
                exclude: /node_modules/,
            },
            {
                test: /\.(woff(2)?|ttf|eot)(\?[a-z0-9=.]+)?$/,
                loader: 'file-loader',
                options: {
                    outputPath: 'fonts',
                    name: '[name].[ext]',
                },
                exclude: /node_modules/,
            },
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