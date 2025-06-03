<?php
/**
 * CTA Block Registration
 *
 * @package HoGScaffold\Blocks\CtaBlock
 * @since 1.0.0
 */

namespace HoGScaffold\Blocks\CtaBlock;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the CTA block
 *
 * Registers the block using metadata from block.json for static rendering.
 * This function should be called during the 'init' action.
 *
 * @since 1.0.0
 * @return void
 */
function register() {
	// Ensure the block directory constant exists
	if ( ! defined( 'HOG_SCAFFOLD_BLOCK_DIR' ) ) {
		return;
	}

	$block_dir       = HOG_SCAFFOLD_BLOCK_DIR . '/cta-block';
	$block_json_file = $block_dir . '/block.json';

	// Ensure block.json exists before registration
	if ( ! file_exists( $block_json_file ) ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( 'CTA Block: block.json not found at ' . $block_json_file );
		}
		return;
	}

	// Register the block using static rendering (no render callback)
	// Content is rendered via save.js for optimal SEO performance
	register_block_type_from_metadata( $block_dir );
}
