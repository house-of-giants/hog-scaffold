/* global process, module, require */

const path = require('path');
const CopyPlugin = require('copy-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const ESLintPlugin = require('eslint-webpack-plugin');
const StyleLintPlugin = require('stylelint-webpack-plugin');
const WebpackBar = require('webpackbar');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
const CleanExtractedDeps = require('./clean-extracted-deps.js');

// Config files.
const settings = require('./webpack.settings.js');

/**
 * Configure entries.
 */
const configureEntries = () => {
	const entries = {};

	for (const [key, value] of Object.entries(settings.entries)) {
		entries[key] = path.resolve(process.cwd(), value);
	}
	return entries;
};

module.exports = {
	entry: configureEntries(),
	output: {
		clean: true,
		path: path.resolve(process.cwd(), settings.paths.dist.base),
		filename: settings.filename.js,
	},

	// Console stats output.
	// @link https://webpack.js.org/configuration/stats/#stats
	stats: settings.stats,

	// Performance settings.
	performance: {
		maxAssetSize: settings.performance.maxAssetSize,
	},

	// Build rules to handle asset files.
	module: {
		rules: [
			// Scripts.
			{
				test: /\.js$/,
				exclude: /(node_modules)/,
				use: [
					{
						loader: 'babel-loader',
						options: {
							presets: [
								[
									'@babel/preset-env',
									{
										useBuiltIns: 'usage',
										targets: 'defaults',
										corejs: 3,
									},
								],
							],
							cacheDirectory: true,
						},
					},
				],
			},

			// Styles.
			{
				test: /\.css$/i,
				include: path.resolve(process.cwd(), settings.paths.src.css),
				use: [MiniCssExtractPlugin.loader, 'css-loader', 'postcss-loader'],
			},

			// Images
			{
				test: /\.(jpe?g|png|gif)$/i,
				include: path.resolve(process.cwd(), settings.paths.src.images),
				use: [
					{
						loader: 'file-loader',
						options: {
							name: '[name].[ext]',
							outputPath: `images/`,
							publicPath: `../images/`,
						},
					},
				],
			},
		],
	},

	plugins: [
		new ESLintPlugin({
			failOnError: false,
			fix: false,
		}),

		// Extract CSS into individual files.
		new MiniCssExtractPlugin({
			filename: (options) => {
				return options.chunk.name.match(/-block$/)
					? settings.filename.blockCSS
					: settings.filename.css;
			},
			chunkFilename: '[id].css',
		}),

		// Copy static assets to the `dist` folder.
		new CopyPlugin({
			patterns: [
				{
					from: settings.copyWebpackConfig.from,
					to: settings.copyWebpackConfig.to,
					context: path.resolve(process.cwd(), settings.paths.src.base),
					noErrorOnMissing: true,
				},
			],
		}),

		// Lint CSS.
		new StyleLintPlugin({
			context: path.resolve(process.cwd(), settings.paths.src.css),
			files: '**/*.css',
			allowEmptyInput: true,
			configFile: path.join(path.dirname(__dirname), '.stylelintrc.json'),
		}),

		// Fancy WebpackBar.
		new WebpackBar(),

		// During rebuilds, all webpack assets that are not used anymore
		// will be removed automatically.
		new CleanWebpackPlugin(),

		// dependecyExternals variable controls whether scripts' assets get
		// generated, and the default externals set.
		new DependencyExtractionWebpackPlugin({
			injectPolyfill: true,
			combineAssets: true,
		}),

		new CleanExtractedDeps(),
	],
};
