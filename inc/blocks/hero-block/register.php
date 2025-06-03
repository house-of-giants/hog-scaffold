<?php
/**
 * Hero Block Registration
 *
 * @package HoGScaffold\Blocks\HeroBlock
 * @since 1.0.0
 */

namespace HoGScaffold\Blocks\HeroBlock;

// Prevent direct access
if (!defined('ABSPATH')) {
  exit;
}

/**
 * Register the hero block
 *
 * Registers the block using metadata from block.json and sets up server-side rendering.
 * This function should be called during the 'init' action.
 *
 * @since 1.0.0
 * @return void
 */
function register()
{
  // Ensure the block directory constant exists
  if (!defined('HOG_SCAFFOLD_BLOCK_DIR')) {
    return;
  }

  $block_dir = HOG_SCAFFOLD_BLOCK_DIR . '/hero-block';
  $block_json_file = $block_dir . '/block.json';

  // Ensure block.json exists before registration
  if (!file_exists($block_json_file)) {
    if (defined('WP_DEBUG') && WP_DEBUG) {
      error_log('Hero Block: block.json not found at ' . $block_json_file);
    }
    return;
  }

  // Register the block using metadata
  $result = register_block_type_from_metadata(
    $block_dir,
    array(
      'render_callback' => __NAMESPACE__ . '\\render_block_callback',
    )
  );

  // Log registration failure if in debug mode
  if (!$result && defined('WP_DEBUG') && WP_DEBUG) {
    error_log('Hero Block: Failed to register block from metadata');
  }
}

/**
 * Render callback method for the hero block
 *
 * @since 1.0.0
 * 
 * @param array    $attributes The block attributes
 * @param string   $content    Data returned from InnerBlocks.Content
 * @param WP_Block $block      Block information such as context
 *
 * @return string The rendered block markup
 */
function render_block_callback($attributes, $content, $block)
{
  // Validate inputs
  if (!is_array($attributes)) {
    $attributes = array();
  }

  if (!is_string($content)) {
    $content = '';
  }

  // Get the markup template path
  $template_path = __DIR__ . '/markup.php';

  // Ensure template file exists
  if (!file_exists($template_path)) {
    if (defined('WP_DEBUG') && WP_DEBUG) {
      error_log('Hero Block: Template file not found at ' . $template_path);
    }
    return '';
  }

  // Start output buffering
  ob_start();

  // Load template with error handling
  try {
    get_template_part(
      'inc/blocks/hero-block/markup',
      null,
      array(
        'class_name' => 'wp-block-hero',
        'attributes' => $attributes,
        'content' => $content,
        'block' => $block,
      )
    );
  } catch (Exception $e) {
    if (defined('WP_DEBUG') && WP_DEBUG) {
      error_log('Hero Block: Error rendering template - ' . $e->getMessage());
    }
    ob_end_clean();
    return '';
  }

  return ob_get_clean();
}