<?php
/**
 * Core setup, site hooks and filters.
 *
 * @package HoGScaffold\Core
 */

namespace HoGScaffold\Core;

use HoGScaffold\Utility;

/**
 * Set up theme defaults and register supported WordPress features.
 *
 * @return void
 */
function setup()
{
  $n = function ($function) {
    return __NAMESPACE__ . "\\$function";
  };

  add_action('after_setup_theme', $n('i18n'));
  add_action('after_setup_theme', $n('theme_setup'));
  add_action('wp_enqueue_scripts', $n('scripts'));
  add_action('wp_enqueue_scripts', $n('styles'));
  add_action('wp_head', $n('js_detection'), 0);
  add_action('wp_head', $n('add_manifest'), 10);

  add_filter('script_loader_tag', $n('script_loader_tag'), 10, 2);

  // Add FSE compatibility improvements
  add_action('init', $n('register_block_patterns'));
  add_action('init', $n('register_template_part_support'));
  add_action('init', $n('improve_block_validation'));
  add_action('switch_theme', $n('clear_block_validation_cache'));
  add_action('switch_theme', $n('force_block_template_validation'));
  add_action('after_switch_theme', $n('force_block_template_validation'));
  add_action('admin_notices', $n('fse_troubleshooting_notice'));
  add_filter('render_block', $n('fix_block_validation'), 10, 2);
  add_filter('render_block', $n('debug_block_validation'), 5, 2);
  add_filter('block_editor_rest_api_preload_paths', $n('clear_block_validation_cache'));
  add_filter('get_template_part', $n('validate_template_content'));
}

/**
 * Makes Theme available for translation.
 *
 * Translations can be added to the /languages directory.
 * If you're building a theme based on "HoG-scaffold", change the
 * filename of '/languages/HoGScaffold.pot' to the name of your project.
 *
 * @return void
 */
function i18n()
{
  load_theme_textdomain('HoG-scaffold', HOG_SCAFFOLD_PATH . '/languages');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function theme_setup()
{
  add_theme_support('automatic-feed-links');
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support(
    'html5',
    array(
      'search-form',
      'gallery',
      'navigation-widgets',
    )
  );

  // Add comprehensive block theme support
  add_theme_support('block-templates');
  add_theme_support('block-template-parts');
  add_theme_support('editor-styles');
  add_theme_support('wp-block-styles');
  add_theme_support('align-wide');
  add_theme_support('responsive-embeds');
  add_theme_support('custom-units');
  add_theme_support('custom-spacing');
  add_theme_support('link-color');
  add_theme_support('border');

  // Add support for block editor features
  add_theme_support('editor-color-palette');
  add_theme_support('editor-gradient-presets');
  add_theme_support('editor-font-sizes');

  // Disable custom colors, gradients, and font sizes if using theme.json
  add_theme_support('disable-custom-colors');
  add_theme_support('disable-custom-font-sizes');
  add_theme_support('disable-custom-gradients');

  // Add support for full and wide aligned images
  add_theme_support('align-wide');

  // Register navigation menus
  register_nav_menus(
    array(
      'primary' => esc_html__('Primary Menu', 'hog-scaffold'),
      'footer' => esc_html__('Footer Menu', 'hog-scaffold'),
    )
  );
}

/**
 * Enqueue scripts for front-end.
 *
 * @return void
 */
function scripts()
{

  wp_enqueue_script(
    'frontend',
    HOG_SCAFFOLD_TEMPLATE_URL . '/dist/js/frontend.js',
    Utility\get_dep_asset('frontend', 'dependencies'),
    Utility\get_dep_asset('frontend', 'version'),
    true
  );

  // Add defer attribute for non-critical JavaScript
  wp_script_add_data('frontend', 'script_execution', 'defer');

  // Enqueue accessibility enhancements
  wp_enqueue_script(
    'accessibility',
    HOG_SCAFFOLD_TEMPLATE_URL . '/assets/js/accessibility.js',
    array(),
    HOG_SCAFFOLD_VERSION,
    true
  );

  // Add defer attribute for accessibility script
  wp_script_add_data('accessibility', 'script_execution', 'defer');

  // Enqueue lazy loading script for enhanced browser support
  wp_enqueue_script(
    'lazy-loading',
    HOG_SCAFFOLD_TEMPLATE_URL . '/assets/js/frontend/lazy-loading.js',
    array(),
    HOG_SCAFFOLD_VERSION,
    true
  );

  // Add defer attribute for lazy loading script
  wp_script_add_data('lazy-loading', 'script_execution', 'defer');
}

/**
 * Enqueue styles for front-end.
 *
 * @return void
 */
function styles()
{

  wp_enqueue_style(
    'styles',
    HOG_SCAFFOLD_TEMPLATE_URL . '/dist/css/style.css',
    array(),
    Utility\get_dep_asset('styles', 'version'),
  );
}

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @return void
 */
function js_detection()
{

  echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}

/**
 * Add async/defer attributes to enqueued scripts that have the specified script_execution flag.
 *
 * @link https://core.trac.wordpress.org/ticket/12009
 * @param string $tag    The script tag.
 * @param string $handle The script handle.
 * @return string
 */
function script_loader_tag($tag, $handle)
{
  $script_execution = wp_scripts()->get_data($handle, 'script_execution');

  if (!$script_execution) {
    return $tag;
  }

  if ('async' !== $script_execution && 'defer' !== $script_execution) {
    return $tag;
  }

  // Abort adding async/defer for scripts that have this script as a dependency. _doing_it_wrong()?
  foreach (wp_scripts()->registered as $script) {
    if (in_array($handle, $script->deps, true)) {
      return $tag;
    }
  }

  // Add the attribute if it hasn't already been added.
  if (!preg_match(":\s$script_execution(=|>|\s):", $tag)) {
    $tag = preg_replace(':(?=></script>):', " $script_execution", $tag, 1);
  }

  return $tag;
}

/**
 * Appends a link tag used to add a manifest.json to the head
 *
 * @return void
 */
function add_manifest()
{
  echo "<link rel='manifest' href='" . esc_url(HOG_SCAFFOLD_TEMPLATE_URL . '/manifest.json') . "' />";
}

/**
 * Register block patterns for the theme
 *
 * @return void
 */
function register_block_patterns()
{
  // Remove default WordPress patterns that might conflict
  if (function_exists('unregister_block_pattern')) {
    unregister_block_pattern('core/query-standard-posts');
    unregister_block_pattern('core/query-medium-posts');
    unregister_block_pattern('core/query-small-posts');
  }
}

/**
 * Fix block validation issues by cleaning up block content
 *
 * @param string $block_content The block content.
 * @param array  $block         The block data.
 * @return string The modified block content.
 */
function fix_block_validation($block_content, $block)
{
  // Skip if we don't have block data
  if (!isset($block['blockName'])) {
    return $block_content;
  }

  // Handle template part blocks specifically
  if ('core/template-part' === $block['blockName']) {
    // Ensure template parts have proper theme attribute
    if (isset($block['attrs']['slug']) && empty($block['attrs']['theme'])) {
      $block['attrs']['theme'] = get_stylesheet();
    }
  }

  // Handle navigation blocks
  if ('core/navigation' === $block['blockName']) {
    // Remove problematic ref attributes that might reference non-existent menus
    if (isset($block['attrs']['ref'])) {
      unset($block['attrs']['ref']);
    }
  }

  // Handle group blocks that might have validation issues
  if ('core/group' === $block['blockName']) {
    // Ensure group blocks have proper layout structure
    if (!isset($block['attrs']['layout'])) {
      $block['attrs']['layout'] = array('type' => 'default');
    }
  }

  return $block_content;
}

/**
 * Register additional template part support
 *
 * @return void
 */
function register_template_part_support()
{
  // Ensure template parts directory exists
  $template_parts_dir = get_template_directory() . '/parts';
  if (!is_dir($template_parts_dir)) {
    wp_mkdir_p($template_parts_dir);
  }

  // Register template part areas
  if (function_exists('register_template_part_area')) {
    register_template_part_area(array(
      'area' => 'header',
      'area_tag' => 'header',
      'label' => __('Header', 'hog-scaffold'),
      'description' => __('The header template part', 'hog-scaffold'),
    ));

    register_template_part_area(array(
      'area' => 'footer',
      'area_tag' => 'footer',
      'label' => __('Footer', 'hog-scaffold'),
      'description' => __('The footer template part', 'hog-scaffold'),
    ));
  }
}

/**
 * Additional block validation improvements
 *
 * @return void
 */
function improve_block_validation()
{
  // Remove problematic core patterns that might conflict
  remove_theme_support('core-block-patterns');

  // Re-add specific core patterns we want to keep
  add_theme_support('core-block-patterns');
}

/**
 * Filter to ensure proper block validation on the frontend
 *
 * @param string $content The template content.
 * @return string Modified content.
 */
function validate_template_content($content)
{
  // Ensure template parts have proper theme attributes
  $content = preg_replace(
    '/<!-- wp:template-part \{([^}]*)"slug":"([^"]*)"([^}]*)\} \/-->/',
    '<!-- wp:template-part {"slug":"$2","theme":"' . get_stylesheet() . '"$3} /-->',
    $content
  );

  // Remove any problematic ref attributes from navigation blocks
  $content = preg_replace(
    '/<!-- wp:navigation \{([^}]*)"ref":[^,}]*,?([^}]*)\} -->/',
    '<!-- wp:navigation {$1$2} -->',
    $content
  );

  return $content;
}

/**
 * Clear WordPress block validation cache
 *
 * @return void
 */
function clear_block_validation_cache()
{
  // Clear any WordPress caches that might be causing validation issues
  if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
  }

  // Clear theme transients
  delete_transient('theme_template_' . get_stylesheet());
  delete_transient('theme_template_part_' . get_stylesheet());
}

/**
 * Debug block validation issues when WP_DEBUG is enabled
 *
 * @param string $block_content The block content.
 * @param array  $block         The block data.
 * @return string The block content.
 */
function debug_block_validation($block_content, $block)
{
  // Only run in debug mode
  if (!defined('WP_DEBUG') || !WP_DEBUG) {
    return $block_content;
  }

  // Log blocks that might have validation issues
  if (
    isset($block['blockName']) && (
      strpos($block['blockName'], 'template-part') !== false ||
      strpos($block['blockName'], 'navigation') !== false ||
      strpos($block['blockName'], 'group') !== false
    )
  ) {
    error_log('Block Debug - ' . $block['blockName'] . ': ' . print_r($block['attrs'] ?? [], true));
  }

  return $block_content;
}

/**
 * Add admin notice for FSE troubleshooting
 *
 * @return void
 */
function fse_troubleshooting_notice()
{
  // Only show to administrators
  if (!current_user_can('manage_options')) {
    return;
  }

  // Only show on editor pages
  $screen = get_current_screen();
  if (!$screen || strpos($screen->id, 'site-editor') === false) {
    return;
  }

  echo '<div class="notice notice-info">';
  echo '<p><strong>FSE Theme Debug:</strong> If you see block validation errors, try these steps:</p>';
  echo '<ol>';
  echo '<li>Clear any caching plugins</li>';
  echo '<li>Refresh the editor page</li>';
  echo '<li>Click "Attempt recovery" on each invalid block</li>';
  echo '<li>Create navigation menus in Appearance > Menus</li>';
  echo '</ol>';
  echo '</div>';
}

/**
 * Force block template validation on theme activation
 *
 * @return void
 */
function force_block_template_validation()
{
  // Clear any existing template caches
  wp_cache_delete('theme_template_' . get_stylesheet(), 'theme');
  wp_cache_delete('theme_template_part_' . get_stylesheet(), 'theme');

  // Delete any problematic transients
  $transients_to_delete = [
    'theme_template_' . get_stylesheet(),
    'theme_template_part_' . get_stylesheet(),
    '_site_transient_theme_roots',
    '_transient_theme_roots',
  ];

  foreach ($transients_to_delete as $transient) {
    delete_transient($transient);
    delete_site_transient($transient);
  }

  // Force WordPress to re-scan block templates
  if (function_exists('_prime_post_caches')) {
    _prime_post_caches([], false, false);
  }
}
