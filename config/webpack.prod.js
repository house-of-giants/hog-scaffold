/* global module, require */

const { merge } = require('webpack-merge');
const TerserPlugin = require('terser-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');

const common = require('./webpack.shared.js');

module.exports = merge(common, {
	mode: 'production',

	optimization: {
		minimize: true,
		minimizer: [
			new TerserPlugin({
				parallel: true,
				terserOptions: {
					parse: {
						ecma: 8,
					},
					compress: {
						warnings: false,
						comparisons: false,
						inline: 2,
					},
					output: {
						comments: false,
					},
				},
			}),
		],
	},
	plugins: [
		// Clean the `dist` folder on build.
		new CleanWebpackPlugin(),
	],
});
