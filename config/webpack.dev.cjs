/* global module, require */

const { merge } = require('webpack-merge');

// Config files.
const common = require('./webpack.shared.cjs');
const settings = require('./webpack.settings.cjs');

module.exports = merge(common, {
	mode: 'development',
	devtool: 'inline-cheap-module-source-map',
	plugins: [
		// BrowserSync disabled due to security vulnerabilities in browser-sync-webpack-plugin
		// For live reloading, consider using webpack-dev-server or manual browser refresh
	],
});
