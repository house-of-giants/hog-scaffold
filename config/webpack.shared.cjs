/* global process, __dirname, __filename */

const path = require("path");
const CopyPlugin = require("copy-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const ESLintPlugin = require("eslint-webpack-plugin");
const StyleLintPlugin = require("stylelint-webpack-plugin");
const WebpackBar = require("webpackbar");
const DependencyExtractionWebpackPlugin = require("@wordpress/dependency-extraction-webpack-plugin");
const CleanExtractedDeps = require("./clean-extracted-deps.cjs");

// Config files.
const settings = require("./webpack.settings.cjs");

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
			const filepath = path.dirname(pathData.filename).split("/").slice(1);
			return `${filepath.join("/")} / [name].[hash][ext][query]`;
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
			// Modern JavaScript and JSX
			{
				test: /\.(js|jsx)$/,
				exclude: /(node_modules)/,
				use: [
					{
						loader: "babel-loader",
						options: {
							presets: [
								[
									"@babel/preset-env",
									{
										useBuiltIns: "usage",
										targets: "defaults",
										corejs: 3,
									},
								],
								[
									"@babel/preset-react",
									{
										runtime: "automatic", // Use new JSX transform
										development: process.env.NODE_ENV === "development",
									},
								],
							],
							plugins: [
								// Add support for WordPress specific features
								process.env.NODE_ENV === "development" && "react-refresh/babel",
							].filter(Boolean),
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
						loader: "css-loader",
						options: {
							importLoaders: 1,
							sourceMap: process.env.NODE_ENV === "development",
						},
					},
					{
						loader: "postcss-loader",
						options: {
							sourceMap: process.env.NODE_ENV === "development",
						},
					},
				],
			},

			// Block CSS files
			{
				test: /\.css$/i,
				include: path.resolve(process.cwd(), "inc/blocks"),
				use: [
					MiniCssExtractPlugin.loader,
					{
						loader: "css-loader",
						options: {
							importLoaders: 1,
							sourceMap: process.env.NODE_ENV === "development",
						},
					},
					{
						loader: "postcss-loader",
						options: {
							sourceMap: process.env.NODE_ENV === "development",
						},
					},
				],
			},

			// Images - Modern approach with asset modules
			{
				test: /\.(jpe?g|png|gif|webp)$/i,
				include: path.resolve(process.cwd(), settings.paths.src.images),
				type: "asset",
				parser: {
					dataUrlCondition: {
						maxSize: 8 * 1024, // 8kb - inline smaller images
					},
				},
				generator: {
					filename: "images/[name].[hash:8][ext]",
				},
			},

			// SVG files
			{
				test: /\.svg$/i,
				include: path.resolve(process.cwd(), settings.paths.src.images),
				type: "asset/resource",
				generator: {
					filename: "images/[name].[hash:8][ext]",
				},
			},

			// Fonts
			{
				test: /\.(woff|woff2|eot|ttf|otf)$/i,
				type: "asset/resource",
				generator: {
					filename: "fonts/[name].[hash:8][ext]",
				},
			},
		],
	},

	// Enhanced resolve configuration for modern JavaScript
	resolve: {
		extensions: [".js", ".jsx", ".json"],
		alias: {
			"@": path.resolve(process.cwd(), "assets"),
			"@js": path.resolve(process.cwd(), "assets/js"),
			"@css": path.resolve(process.cwd(), "assets/css"),
			"@images": path.resolve(process.cwd(), "assets/images"),
			"@blocks": path.resolve(process.cwd(), "inc/blocks"),
		},
		// Enable better module resolution
		symlinks: false,
		cacheWithContext: false,
	},

	plugins: [
		new ESLintPlugin({
			failOnError: false,
			fix: false,
			cache: true,
			cacheLocation: path.resolve(process.cwd(), "node_modules/.cache/eslint"),
			extensions: ["js", "jsx"], // Support JSX files
		}),

		// Extract CSS into individual files.
		new MiniCssExtractPlugin({
			filename: (options) => {
				return options.chunk.name.match(/-block$/)
					? settings.filename.blockCSS
					: settings.filename.css;
			},
			chunkFilename: "[id].[contenthash:8].css",
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
						ignore: ["**/.DS_Store", "**/Thumbs.db"],
					},
				},
			],
		}),

		// Lint CSS.
		new StyleLintPlugin({
			context: path.resolve(process.cwd(), settings.paths.src.css),
			files: "**/*.css",
			allowEmptyInput: true,
			configFile: path.join(path.dirname(__dirname), ".stylelintrc.json"),
			cache: true,
			cacheLocation: path.resolve(
				process.cwd(),
				"node_modules/.cache/stylelint"
			),
		}),

		// Fancy WebpackBar.
		new WebpackBar({
			name: "Theme Assets",
			color: "#2196F3",
		}),

		// WordPress dependency extraction with enhanced configuration
		new DependencyExtractionWebpackPlugin({
			injectPolyfill: true,
			combineAssets: true,
			// Enhanced externals for WordPress
			requestToExternal: (request) => {
				// Handle WordPress packages
				if (request.startsWith("@wordpress/")) {
					return [
						"wp",
						request.substring("@wordpress/".length).replace(/[/-]/g, ""),
					];
				}
				// Handle React
				if (request === "react") {
					return "React";
				}
				if (request === "react-dom") {
					return "ReactDOM";
				}
			},
			requestToHandle: (request) => {
				// Handle WordPress packages
				if (request.startsWith("@wordpress/")) {
					return "wp-" + request.substring("@wordpress/".length);
				}
				// Handle React
				if (request === "react") {
					return "react";
				}
				if (request === "react-dom") {
					return "react-dom";
				}
			},
		}),

		new CleanExtractedDeps(),
	],

	// Enhanced cache configuration for faster builds
	cache: {
		type: "filesystem",
		cacheDirectory: path.resolve(process.cwd(), "node_modules/.cache/webpack"),
		buildDependencies: {
			config: [__filename],
		},
		// Enhanced cache optimization
		compression: "gzip",
		hashAlgorithm: "xxhash64",
	},

	// Optimization settings for modern JavaScript
	optimization: {
		moduleIds: "deterministic",
		// Better tree shaking
		usedExports: true,
		sideEffects: false,
	},
};
