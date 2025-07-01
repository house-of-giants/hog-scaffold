const path = require("path");

module.exports = ({ file, env }) => {
	const config = {
		plugins: {
			"postcss-import": {},
			"postcss-mixins": {},
			"postcss-nesting": {},
			"postcss-preset-env": {
				stage: 0,
				autoprefixer: {
					grid: false,
					// Suppress warnings for IE since we don't support it
					overrideBrowserslist: [
						"> 1%",
						"last 2 versions",
						"Firefox ESR",
						"not IE 11",
						"not IE_Mob 11",
					],
				},
			},
		},
	};

	config.plugins.cssnano =
		env === "production"
			? {
					preset: [
						"default",
						{
							autoprefixer: false,
							calc: {
								precision: 8,
							},
							convertValues: true,
							discardComments: {
								removeAll: true,
							},
							mergeLonghand: false,
							zindex: false,
						},
					],
				}
			: false;

	return config;
};
