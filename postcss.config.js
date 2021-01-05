/**
 * Exports the PostCSS configuration.
 *
 * @return {string} PostCSS options.
 */
module.exports = ( { options, env } ) => ( { /* eslint-disable-line */
	plugins: {
		'postcss-import': {},
		'postcss-preset-env': {
			stage: 0,
			autoprefixer: {
				grid: true,
			},
		},
		'postcss-nesting': {},
		// Minify style on production using cssano.
		cssnano:
			env === 'production'
				? {
						preset: [
							'default',
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
				: false,
	},
});
