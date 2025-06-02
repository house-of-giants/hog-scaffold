/* global module, require */

const { merge } = require('webpack-merge');
const TerserPlugin = require('terser-webpack-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');

const common = require('./webpack.shared.cjs');

module.exports = merge(common, {
	mode: 'production',

	// Disable source maps in production for smaller bundle sizes
	devtool: false,

	optimization: {
		minimize: true,
		minimizer: [
			// JavaScript optimization
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
					mangle: {
						safari10: true,
					},
					output: {
						ecma: 5,
						comments: false,
						ascii_only: true,
					},
				},
			}),

			// CSS optimization
			new CssMinimizerPlugin({
				minimizerOptions: {
					preset: [
						'default',
						{
							discardComments: { removeAll: true },
						},
					],
				},
			}),
		],
	},

	// Additional production optimizations
	performance: {
		hints: 'warning',
		maxEntrypointSize: 512000,
		maxAssetSize: 512000,
	},
});
