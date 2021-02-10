/* global module, require */

const { merge } = require( 'webpack-merge' );
const TerserPlugin = require( 'terser-webpack-plugin' );
const { CleanWebpackPlugin } = require('clean-webpack-plugin');

const common = require( './webpack.shared.js' );

module.exports = merge( common, {
	mode: 'production',

	optimization: {
		minimizer: [
			new TerserPlugin( {
				cache: true,
				parallel: true,
				sourceMap: false,
				terserOptions: {
					parse: {
						ecma: 8
					},
					compress: {
						ecma: 5,
						warnings: false,
						comparisons: false,
						inline: 2
					},
					output: {
						ecma: 5,
						comments: false
					},
					ie8: false
				}
			} )
		],
	},
	plugins: [
		// Clean the `dist` folder on build.
		new CleanWebpackPlugin(),
	]
} );
