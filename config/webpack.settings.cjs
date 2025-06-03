module.exports = {
	entries: {
		// JS files.
		admin: "./assets/js/admin/admin.js",
		"blocks-editor": "./inc/blocks/blocks-editor.js",
		frontend: "./assets/js/frontend/frontend.js",
		shared: "./assets/js/shared/shared.js",

		// Layout block files
		"layout-container-editor": "./inc/blocks/layout/container/index.js",
		"layout-spacer-editor": "./inc/blocks/layout/spacer/index.js",
		"layout-divider-editor": "./inc/blocks/layout/divider/index.js",

		// Interactive block files
		"interactive-tabs-editor": "./inc/blocks/interactive-tabs-block/index.js",
		"interactive-tabs-view": "./inc/blocks/interactive-tabs-block/view.js",

		// Content block files
		"hero-block-editor": "./inc/blocks/hero-block/editor.js",
		"cta-block-editor": "./inc/blocks/cta-block/editor.js",
		"service-cards-block-editor": "./inc/blocks/service-cards-block/editor.js",
		"team-profiles-block-editor": "./inc/blocks/team-profiles-block/editor.js",
		"testimonials-block-editor": "./inc/blocks/testimonials-block/editor.js",
		"example-block-editor": "./inc/blocks/example-block/editor.js",

		// CSS files.
		"admin-style": "./assets/css/admin/admin-style.css",
		"editor-style": "./assets/css/editor-style.css",
		"shared-style": "./assets/css/shared/shared-style.css",
		style: "./assets/css/main.css",

		// Layout block CSS files
		"layout-container-style": "./inc/blocks/layout/container/style.css",
		"layout-container-editor-style": "./inc/blocks/layout/container/editor.css",
		"layout-spacer-style": "./inc/blocks/layout/spacer/style.css",
		"layout-divider-style": "./inc/blocks/layout/divider/style.css",

		// Interactive block CSS files
		"interactive-tabs-style": "./inc/blocks/interactive-tabs-block/style.css",
		"interactive-tabs-editor-style":
			"./inc/blocks/interactive-tabs-block/editor.css",

		// Content block CSS files
		"hero-block-style": "./inc/blocks/hero-block/style.css",
		"hero-block-editor-style": "./inc/blocks/hero-block/editor.css",
		"cta-block-style": "./inc/blocks/cta-block/style.css",
		"cta-block-editor-style": "./inc/blocks/cta-block/editor.css",
		"service-cards-block-style": "./inc/blocks/service-cards-block/style.css",
		"service-cards-block-editor-style":
			"./inc/blocks/service-cards-block/editor.css",
		"team-profiles-block-style": "./inc/blocks/team-profiles-block/style.css",
		"team-profiles-block-editor-style":
			"./inc/blocks/team-profiles-block/editor.css",
		"testimonials-block-style": "./inc/blocks/testimonials-block/style.css",
		"testimonials-block-editor-style":
			"./inc/blocks/testimonials-block/editor.css",
		"example-block-style": "./inc/blocks/example-block/style.css",
		"example-block-editor-style": "./inc/blocks/example-block/editor.css",
	},
	filename: {
		js: "js/[name].js",
		css: "css/[name].css",
		block: "blocks/[name]/editor.js",
		blockCSS: "blocks/[name]/editor.css",
	},
	paths: {
		src: {
			base: "./assets/",
			css: "./assets/css/",
			js: "./assets/js/",
			images: "./assets/images/",
		},
		dist: {
			base: "./dist/",
			clean: ["./images", "./css", "./js"],
		},
	},
	stats: {
		// Copied from `'minimal'`.
		all: false,
		errors: true,
		modules: true,
		warnings: true,
		// Our additional options.
		assets: true,
		errorDetails: true,
		excludeAssets: /\.(jpe?g|png|gif|svg|woff|woff2)$/i,
		moduleTrace: true,
		performance: true,
	},
	copyWebpackConfig: {
		from: "**/*.{jpg,jpeg,png,gif,svg,eot,ttf,woff,woff2}",
		to: "[path][name][ext]",
	},
	BrowserSyncConfig: {
		host: "localhost",
		port: 3000,
		proxy: "http://lancasterdemo.local/",
		open: false,
		files: [
			"**/*.php",
			"dist/js/**/*.js",
			"dist/css/**/*.css",
			"dist/svg/**/*.svg",
			"dist/images/**/*.{jpg,jpeg,png,gif}",
			"dist/fonts/**/*.{eot,ttf,woff,woff2,svg}",
		],
	},
	performance: {
		maxAssetSize: 100000,
	},
};
