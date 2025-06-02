/**
 * Removes wp-polyfill from CSS assets extracted via @wordpress/dependency-extraction-webpack-plugin
 */

const { Compilation } = require('webpack');
const { RawSource } = require('webpack-sources');

class CleanExtractedDeps {
	constructor(options) {
		this.options = options;
	}

	apply(compiler) {
		compiler.hooks.compilation.tap('CleanExtractedDeps', (compilation) => {
			compilation.hooks.processAssets.tap(
				{
					name: 'CleanExtractedDeps',
					stage: Compilation.PROCESS_ASSETS_STAGE_OPTIMIZE,
				},
				(assets) => {
					for (const [
						entrypointName,
						entrypoint,
					] of compilation.entrypoints.entries()) {
						let compilationAssetMatch = false;
						let entryPointPath = false;

						Object.keys(assets).forEach((assetName) => {
							if (assetName.match(new RegExp(`${entrypointName}.asset.php$`))) {
								compilationAssetMatch = assetName;
							}
							if (assetName.match(new RegExp(`${entrypointName}.css$`))) {
								entryPointPath = assetName;
							}
						});

						if (
							entrypoint.origins[0].request.match(/\.css$/) &&
							entryPointPath &&
							compilationAssetMatch
						) {
							const source = assets[compilationAssetMatch].source();

							compilation.deleteAsset(compilationAssetMatch);

							compilation.emitAsset(
								entryPointPath.replace('.css', '.asset.php'),
								new RawSource(
									source.replace(/('|")wp-polyfill('|")[\s]*,?/, ''),
								),
							);
						}
					}
				},
			);
		});
	}
}

module.exports = CleanExtractedDeps;
