<?php
/**
 * Gutenberg Blocks setup
 *
 * @package HoGScaffold\Core
 */

namespace HoGScaffold\Blocks;

// Require custom block classes.
require_once HOG_SCAFFOLD_INC . 'blocks/block-factory.php';

/**
 * Set up blocks
 *
 * @return void
 */
function setup() {
	$n = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	add_action( 'init', $n( 'init_blocks' ) );

	add_action( 'enqueue_block_editor_assets', $n( 'blocks_editor_scripts' ) );

	add_filter( 'block_categories', $n( 'blocks_categories' ), 10, 2 );
}

/**
 * Register blocks.
 *
 * @return void
 */
function init_blocks() {
	// BLOCK_NAME::get_new();
}

/**
 * Enqueue shared frontend and editor JavaScript for blocks.
 *
 * @return void
 */
function blocks_scripts() {

	wp_enqueue_script(
		'blocks',
		HOG_SCAFFOLD_TEMPLATE_URL . '/dist/js/blocks.min.js',
		[],
		HOG_SCAFFOLD_VERSION,
		true
	);
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
		'ajax_url'  => admin_url( 'admin-ajax.php' ),
	);
	wp_localize_script( 'blocks-editor', 'localizedVariables', $localized_variables );

	wp_enqueue_style(
		'editor-style',
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
				'slug'  => 'HoGScaffold-blocks',
				'title' => __( 'Custom Blocks', 'HoGScaffold' ),
			),
		)
	);
}
