<?php
/**
 * WP Theme constants and setup functions
 *
 * @package ChalatLaw
 */

// Useful global constants.
define( 'Chalat_LAW_VERSION', '0.1.0' );
define( 'Chalat_LAW_TEMPLATE_URL', get_template_directory_uri() );
define( 'Chalat_LAW_PATH', get_template_directory() . '/' );
define( 'Chalat_LAW_INC', Chalat_LAW_PATH . 'includes/' );

require_once Chalat_LAW_INC . 'core.php';
require_once Chalat_LAW_INC . 'overrides.php';
require_once Chalat_LAW_INC . 'template-tags.php';
require_once Chalat_LAW_INC . 'utility.php';

// Run the setup functions.
ChalatLaw\Core\setup();

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
