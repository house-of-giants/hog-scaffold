<?php
/**
 * Gutenberg Blocks setup
 *
 * @package HoGScaffold\Core
 */

namespace HoGScaffold\Blocks;

/**
 * Set up blocks
 *
 * @return void
 */
function setup()
{
  $n = function ($function) {
    return __NAMESPACE__ . "\\$function";
  };

  add_filter('block_categories_all', $n('blocks_categories'), 10, 2);
  add_action('init', $n('register_theme_blocks'));
}

/**
 * Register theme blocks
 *
 * @return void
 */
function register_theme_blocks()
{
  // Register each block directory - WordPress will automatically read block.json
  register_block_type(HOG_SCAFFOLD_BLOCK_DIR . 'example-block');

  // Add other blocks here as needed:
  // register_block_type(HOG_SCAFFOLD_BLOCK_DIR . 'cta-block');
  // register_block_type(HOG_SCAFFOLD_BLOCK_DIR . 'hero-block');
}

/**
 * Filters the registered block categories.
 *
 * @param array  $categories Registered categories.
 * @param object $post       The post object.
 *
 * @return array Filtered categories.
 */
function blocks_categories($categories, $post)
{
  return array_merge(
    $categories,
    array(
      array(
        'slug' => 'hog-scaffold-blocks',
        'title' => __('Custom Blocks', 'hog-scaffold'),
      ),
    )
  );
}
