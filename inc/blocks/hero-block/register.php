<?php
/**
 * Hero Block Registration
 *
 * @package HoGScaffold\Blocks\HeroBlock
 */

namespace HoGScaffold\Blocks\HeroBlock;

/**
 * Register the hero block
 */
function register()
{
  $n = function ($function) {
    return __NAMESPACE__ . "\\$function";
  };

  // Register the block.
  register_block_type_from_metadata(
    HOG_SCAFFOLD_BLOCK_DIR . '/hero-block', // this is the directory where the block.json is found.
    array(
      'render_callback' => $n('render_block_callback'),
    )
  );
}

/**
 * Render callback method for the hero block
 *
 * @param array  $attributes The blocks attributes
 * @param string $content    Data returned from InnerBlocks.Content
 * @param array  $block      Block information such as context.
 *
 * @return string The rendered block markup.
 */
function render_block_callback($attributes, $content, $block)
{
  ob_start();
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

  return ob_get_clean();
}