<?php
/**
 * Utility functions for the theme.
 *
 * This file is for custom helper functions.
 * These should not be confused with WordPress template
 * tags. Template tags typically use prefixing, as opposed
 * to Namespaces.
 *
 * @link https://developer.wordpress.org/themes/basics/template-tags/
 * @package HoGScaffold
 */

namespace HoGScaffold\Utility;

function get_dep_asset( $slug, $attribute = null ) {
	if ( ! file_exists( HOG_SCAFFOLD_PATH . 'dist/' . $slug . '.asset.php' ) ) {
		return null;
	}

	$asset = require HOG_SCAFFOLD_PATH . 'dist/' . $slug . '.asset.php';

	if ( ! empty( $attribute ) && isset ( $asset[$attribute] ) ) {
		return $asset[$attribute];
	}

	return $asset;
}
