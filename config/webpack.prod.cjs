/* global */

const { merge } = require("webpack-merge");
const TerserPlugin = require("terser-webpack-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const ImageMinimizerPlugin = require("image-minimizer-webpack-plugin");

const common = require("./webpack.shared.cjs");

module.exports = merge(common, {
	mode: "production",

	// Disable source maps in production for smaller bundle sizes
	devtool: false,

	optimization: {
		minimize: true,
		sideEffects: false, // Enable tree shaking

		// Code splitting configuration
		splitChunks: {
			chunks: "all",
			cacheGroups: {
				// Extract vendor libraries
				vendor: {
					test: /[\\/]node_modules[\\/]/,
					name: "vendors",
					chunks: "all",
					priority: 10,
				},
				// Extract common code
				common: {
					name: "common",
					minChunks: 2,
					chunks: "all",
					priority: 5,
					reuseExistingChunk: true,
				},
			},
		},

		minimizer: [
			// JavaScript optimization
			new TerserPlugin({
				parallel: true,
				extractComments: false,
				terserOptions: {
					parse: {
						ecma: 8,
					},
					compress: {
						warnings: false,
						comparisons: false,
						inline: 2,
						drop_console: true, // Remove console logs in production
						drop_debugger: true,
						pure_funcs: ["console.log"], // Remove specific functions
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
						"default",
						{
							discardComments: { removeAll: true },
							normalizeWhitespace: true,
							normalizeUnicode: true,
							minifyFontValues: true,
							minifySelectors: true,
						},
					],
				},
			}),

			// Image optimization
			new ImageMinimizerPlugin({
				minimizer: {
					implementation: ImageMinimizerPlugin.sharpMinify,
					options: {
						encodeOptions: {
							// JPEG options
							jpeg: {
								quality: 80,
								progressive: true,
							},
							// PNG options
							png: {
								quality: 80,
								progressive: true,
							},
							// WebP options
							webp: {
								quality: 80,
							},
						},
					},
				},
				generator: [
					{
						type: "asset",
						preset: "webp-custom-name",
						implementation: ImageMinimizerPlugin.sharpGenerate,
						options: {
							encodeOptions: {
								webp: {
									quality: 80,
								},
							},
						},
					},
				],
			}),
		],
	},

	// Additional production optimizations
	performance: {
		hints: "warning",
		maxEntrypointSize: 250000, // 250kb
		maxAssetSize: 250000, // 250kb
		assetFilter: function (assetFilename) {
			// Only consider JS and CSS files for performance hints
			return assetFilename.endsWith(".js") || assetFilename.endsWith(".css");
		},
	},
});
