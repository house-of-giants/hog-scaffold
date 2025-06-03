<?php
/**
 * Team Profiles Block Registration
 *
 * @package HoG_Scaffold
 */

namespace HoGScaffold\Blocks\TeamProfilesBlock;

// Prevent direct access.
if (!defined('ABSPATH')) {
  exit;
}

/**
 * Register the Team Profiles block.
 */
function register()
{
  register_block_type(
    __DIR__ . '/block.json',
    array(
      'render_callback' => __NAMESPACE__ . '\render_team_profiles_block',
    )
  );
}

/**
 * Render the Team Profiles block.
 *
 * @param array $attributes Block attributes.
 * @return string Block HTML.
 */
function render_team_profiles_block($attributes)
{
  // Set default attributes.
  $attributes = wp_parse_args(
    $attributes,
    array(
      'members' => array(),
      'columns' => 3,
      'layout' => 'grid',
      'showBio' => true,
      'showSocialLinks' => true,
      'imageShape' => 'circle',
      'textAlignment' => 'center',
    )
  );

  // Generate block classes.
  $classes = array(
    'wp-block-team-profiles',
    'is-layout-' . esc_attr($attributes['layout']),
    'columns-' . esc_attr($attributes['columns']),
    'text-align-' . esc_attr($attributes['textAlignment']),
    'image-shape-' . esc_attr($attributes['imageShape']),
  );

  // Start output buffering.
  ob_start();

  // Include the markup template.
  include __DIR__ . '/markup.php';

  // Return the buffered content.
  return ob_get_clean();
}