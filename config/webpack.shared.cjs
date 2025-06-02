/* global process, module, require */

const path = require('path');
const CopyPlugin = require('copy-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const ESLintPlugin = require('eslint-webpack-plugin');
const StyleLintPlugin = require('stylelint-webpack-plugin');
const WebpackBar = require('webpackbar');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
const CleanExtractedDeps = require('./clean-extracted-deps.cjs');

// Config files.
const settings = require('./webpack.settings.cjs');

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
		// Add asset module filename for better organization
		assetModuleFilename: (pathData) => {
			const filepath = path.dirname(pathData.filename).split('/').slice(1);
			return `${filepath.join('/')} / [name].[hash][ext][query]`;
		},
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
							cacheCompression: false, // Faster builds
						},
					},
				],
			},

			// Styles.
			{
				test: /\.css$/i,
				include: path.resolve(process.cwd(), settings.paths.src.css),
				use: [
					MiniCssExtractPlugin.loader,
					{
						loader: 'css-loader',
						options: {
							importLoaders: 1,
							sourceMap: process.env.NODE_ENV === 'development',
						},
					},
					{
						loader: 'postcss-loader',
						options: {
							sourceMap: process.env.NODE_ENV === 'development',
						},
					},
				],
			},

			// Images - Modern approach with asset modules
			{
				test: /\.(jpe?g|png|gif|webp)$/i,
				include: path.resolve(process.cwd(), settings.paths.src.images),
				type: 'asset',
				parser: {
					dataUrlCondition: {
						maxSize: 8 * 1024, // 8kb - inline smaller images
					},
				},
				generator: {
					filename: 'images/[name].[hash:8][ext]',
				},
			},

			// SVG files
			{
				test: /\.svg$/i,
				include: path.resolve(process.cwd(), settings.paths.src.images),
				type: 'asset/resource',
				generator: {
					filename: 'images/[name].[hash:8][ext]',
				},
			},

			// Fonts
			{
				test: /\.(woff|woff2|eot|ttf|otf)$/i,
				type: 'asset/resource',
				generator: {
					filename: 'fonts/[name].[hash:8][ext]',
				},
			},
		],
	},

	// Resolve configuration
	resolve: {
		extensions: ['.js', '.jsx', '.json'],
		alias: {
			'@': path.resolve(process.cwd(), 'assets'),
			'@js': path.resolve(process.cwd(), 'assets/js'),
			'@css': path.resolve(process.cwd(), 'assets/css'),
			'@images': path.resolve(process.cwd(), 'assets/images'),
		},
	},

	plugins: [
		new ESLintPlugin({
			failOnError: false,
			fix: false,
			cache: true,
			cacheLocation: path.resolve(process.cwd(), 'node_modules/.cache/eslint'),
		}),

		// Extract CSS into individual files.
		new MiniCssExtractPlugin({
			filename: (options) => {
				return options.chunk.name.match(/-block$/)
					? settings.filename.blockCSS
					: settings.filename.css;
			},
			chunkFilename: '[id].[contenthash:8].css',
		}),

		// Copy static assets to the `dist` folder.
		new CopyPlugin({
			patterns: [
				{
					from: settings.copyWebpackConfig.from,
					to: settings.copyWebpackConfig.to,
					context: path.resolve(process.cwd(), settings.paths.src.base),
					noErrorOnMissing: true,
					// Add globOptions to exclude certain files
					globOptions: {
						ignore: ['**/.DS_Store', '**/Thumbs.db'],
					},
				},
			],
		}),

		// Lint CSS.
		new StyleLintPlugin({
			context: path.resolve(process.cwd(), settings.paths.src.css),
			files: '**/*.css',
			allowEmptyInput: true,
			configFile: path.join(path.dirname(__dirname), '.stylelintrc.json'),
			cache: true,
			cacheLocation: path.resolve(
				process.cwd(),
				'node_modules/.cache/stylelint',
			),
		}),

		// Fancy WebpackBar.
		new WebpackBar({
			name: 'Theme Assets',
			color: '#2196F3',
		}),

		// dependecyExternals variable controls whether scripts' assets get
		// generated, and the default externals set.
		new DependencyExtractionWebpackPlugin({
			injectPolyfill: true,
			combineAssets: true,
		}),

		new CleanExtractedDeps(),
	],

	// Cache configuration for faster builds
	cache: {
		type: 'filesystem',
		cacheDirectory: path.resolve(process.cwd(), 'node_modules/.cache/webpack'),
		buildDependencies: {
			config: [__filename],
		},
	},
};
