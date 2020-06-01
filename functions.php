<?php
/**
 * WP Theme constants and setup functions
 *
 * @package HoGScaffold
 */

// Useful global constants.
define( 'HOG_SCAFFOLD_VERSION', '0.1.0' );
define( 'HOG_SCAFFOLD_TEMPLATE_URL', get_template_directory_uri() );
define( 'HOG_SCAFFOLD_PATH', get_template_directory() . '/' );
define( 'HOG_SCAFFOLD_INC', HOG_SCAFFOLD_PATH . 'includes/' );

require_once HOG_SCAFFOLD_INC . 'core.php';
require_once HOG_SCAFFOLD_INC . 'overrides.php';
require_once HOG_SCAFFOLD_INC . 'template-tags.php';
require_once HOG_SCAFFOLD_INC . 'utility.php';
require_once HOG_SCAFFOLD_INC . 'blocks.php';

// Run the setup functions.
HoGScaffold\Core\setup();
HoGScaffold\Blocks\setup();

// Require Composer autoloader if it exists.
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once 'vendor/autoload.php';
}

if ( ! function_exists( 'wp_body_open' ) ) {

	/**
	 * Shim for the the new wp_body_open() function that was added in 5.2
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}
