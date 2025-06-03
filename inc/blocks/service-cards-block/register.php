<?php
/**
 * Service Cards Block Registration
 *
 * @package HoGScaffold\Blocks\ServiceCardsBlock
 */

namespace HoGScaffold\Blocks\ServiceCardsBlock;

/**
 * Register the service cards block
 */
function register()
{
  $n = function ($function) {
    return __NAMESPACE__ . "\\$function";
  };

  // Register the block.
  register_block_type_from_metadata(
    HOG_SCAFFOLD_BLOCK_DIR . '/service-cards-block', // this is the directory where the block.json is found.
    array(
      'render_callback' => $n('render_block_callback'),
    )
  );
}

/**
 * Render callback method for the service cards block
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
    'inc/blocks/service-cards-block/markup',
    null,
    array(
      'class_name' => 'wp-block-service-cards',
      'attributes' => $attributes,
      'content' => $content,
      'block' => $block,
    )
  );

  return ob_get_clean();
}