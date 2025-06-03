<?php
/**
 * CTA Block Registration
 *
 * @package HoGScaffold
 */

namespace HoGScaffold\Blocks\CtaBlock;

/**
 * Registers the CTA block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function register_cta_block()
{
  register_block_type(
    __DIR__,
    array(
      'render_callback' => __NAMESPACE__ . '\render_cta_block',
    )
  );
}

/**
 * Renders the CTA block on the front end.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block default content.
 * @param WP_Block $block      Block instance.
 *
 * @return string Returns the rendered CTA block.
 */
function render_cta_block($attributes, $content, $block)
{
  // Get the markup file path
  $markup_file = __DIR__ . '/markup.php';

  if (!file_exists($markup_file)) {
    return '';
  }

  // Start output buffering
  ob_start();

  // Include the markup file with access to $attributes
  include $markup_file;

  // Return the rendered content
  return ob_get_clean();
}