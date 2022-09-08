<?php
/**
 * Gutenberg Blocks setup
 *
 * @package HoGScaffold\Core
 */

namespace HoGScaffold\Blocks;

use HoGScaffold\Blocks\Example;

/**
 * Set up blocks
 *
 * @return void
 */
function setup() {
	$n = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	add_action( 'enqueue_block_editor_assets', $n( 'blocks_editor_scripts' ) );

	add_filter( 'block_categories', $n( 'blocks_categories' ), 10, 2 );

	add_action( 'init', $n( 'register_theme_blocks' ) );

	add_action( 'init', $n( 'block_patterns_and_categories' ) );
}

/**
 * Add in blocks that are registered in this theme
 *
 * @return void
 */
function register_theme_blocks() {
	// Filter the plugins URL to allow us to have blocks in themes with linked assets. i.e editorScripts
	add_filter( 'plugins_url', __NAMESPACE__ . '\filter_plugins_url', 10, 2 );

	// Require custom blocks.
	require_once HOG_SCAFFOLD_BLOCK_DIR . '/example-block/register.php';

	// Call block register functions for each block.
	Example\register();

	// Remove the filter after we register the blocks
	remove_filter( 'plugins_url', __NAMESPACE__ . '\filter_plugins_url', 10, 2 );
}

/**
 * Filter the plugins_url to allow us to use assets from theme.
 *
 * @param string $url  The plugins url
 * @param string $path The path to the asset.
 *
 * @return string The overridden url to the block asset.
 */
function filter_plugins_url( $url, $path ) {
	$file = preg_replace( '/\.\.\//', '', $path );
	return trailingslashit( get_stylesheet_directory_uri() ) . $file;
}

/**
 * Enqueue editor-only JavaScript/CSS for blocks.
 *
 * @return void
 */
function blocks_editor_scripts() {

	wp_enqueue_script(
		'blocks-editor',
		HOG_SCAFFOLD_TEMPLATE_URL . '/dist/js/blocks-editor.js',
		[ 'wp-i18n', 'wp-element', 'wp-blocks', 'wp-components', 'wp-editor', 'wp-compose', 'wp-data' ],
		HOG_SCAFFOLD_VERSION,
		false
	);

	$localized_variables = array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
	);
	wp_localize_script( 'blocks-editor', 'localizedVariables', $localized_variables );

	wp_enqueue_style(
		'shared-style',
		HOG_SCAFFOLD_TEMPLATE_URL . '/dist/css/shared-style.css',
		[],
		HOG_SCAFFOLD_VERSION
	);

	if ( is_admin() ) {
		wp_enqueue_style(
			'admin-style',
			HOG_SCAFFOLD_TEMPLATE_URL . '/dist/css/admin-style.css',
			[],
			HOG_SCAFFOLD_VERSION
		);

		/*
		 * Import editor styles with .editor-styles-wrapper prefix
		 * See https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#enqueuing-the-editor-style
		 */
		add_theme_support( 'editor-styles' );
		add_editor_style( '/dist/css/editor-style.css' );
	}

}

/**
 * Filters the registered block categories.
 *
 * @param array  $categories Registered categories.
 * @param object $post       The post object.
 *
 * @return array Filtered categories.
 */
function blocks_categories( $categories, $post ) {
	if ( ! in_array( $post->post_type, array( 'post', 'page' ), true ) ) {
		return $categories;
	}

	return array_merge(
		$categories,
		array(
			array(
				'slug'  => 'hog-scaffold-blocks',
				'title' => __( 'Custom Blocks', 'hog' ),
			),
		)
	);
}

/**
 * Manage block patterns and block pattern categories
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-patterns/
 *
 * @return void
 */
function block_patterns_and_categories() {
	/*
		## Examples

		// Register block pattern
		register_block_pattern(
			'hog/block-pattern',
			array(
						'title'       => __( 'Two buttons', 'hog' ),
						'description' => _x( 'Two horizontal buttons, the left button is filled in, and the right button is outlined.', 'Block pattern description', 'wpdocs-my-plugin' ),
						'content'     => "<!-- wp:buttons {\"align\":\"center\"} -->\n<div class=\"wp-block-buttons aligncenter\"><!-- wp:button {\"backgroundColor\":\"very-dark-gray\",\"borderRadius\":0} -->\n<div class=\"wp-block-button\"><a class=\"wp-block-button__link has-background has-very-dark-gray-background-color no-border-radius\">" . esc_html__( 'Button One', 'wpdocs-my-plugin' ) . "</a></div>\n<!-- /wp:button -->\n\n<!-- wp:button {\"textColor\":\"very-dark-gray\",\"borderRadius\":0,\"className\":\"is-style-outline\"} -->\n<div class=\"wp-block-button is-style-outline\"><a class=\"wp-block-button__link has-text-color has-very-dark-gray-color no-border-radius\">" . esc_html__( 'Button Two', 'wpdocs-my-plugin' ) . "</a></div>\n<!-- /wp:button --></div>\n<!-- /wp:buttons -->",
				)
		);

		// Unregister a block pattern
		unregister_block_pattern( 'hog/block-pattern' );

		// Register a block pattern category
		register_block_pattern_category(
			'client-name',
				array( 'label' => __( 'Client Name', 'hog' ) )
		);

		// Unregister a block pattern category
		unregister_block_pattern('client-name')

	*/
}
