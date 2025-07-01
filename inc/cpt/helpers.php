<?php
/**
 * Custom Post Type Helper Functions
 *
 * Helper functions for common CPT UI tasks including meta boxes,
 * admin columns, and other utilities.
 *
 * @package HoGScaffold\CPT
 * @since 1.0.0
 */

// Prevent direct access.
if (!defined('ABSPATH')) {
  exit;
}

/**
 * Add a meta box to a custom post type
 *
 * Simplified wrapper around add_meta_box() that handles the
 * add_meta_boxes action hook automatically.
 *
 * @since 1.0.0
 *
 * @param string          $post_type    Post type to add the meta box to.
 * @param string          $meta_box_id  Unique ID for the meta box.
 * @param string          $title        Meta box title.
 * @param callable        $callback     Function to render the meta box content.
 * @param string          $context      Context where the box should appear. Default 'normal'.
 * @param string          $priority     Priority of the meta box. Default 'default'.
 * @param array|null      $callback_args Optional arguments to pass to the callback.
 */
function hog_scaffold_add_meta_box($post_type, $meta_box_id, $title, $callback, $context = 'normal', $priority = 'default', $callback_args = null)
{
  add_action('add_meta_boxes', function () use ($post_type, $meta_box_id, $title, $callback, $context, $priority, $callback_args) {
    add_meta_box($meta_box_id, $title, $callback, $post_type, $context, $priority, $callback_args);
  });
}

/**
 * Add custom admin columns to a post type
 *
 * Simplified way to add custom columns to the admin post list table.
 *
 * @since 1.0.0
 *
 * @param string   $post_type        Post type to add columns to.
 * @param callable $columns_callback Function that modifies the columns array.
 * @param callable $content_callback Function that renders the column content.
 * @param int      $priority         Priority for the filter hooks. Default 10.
 */
function hog_scaffold_add_admin_columns($post_type, $columns_callback, $content_callback, $priority = 10)
{
  add_filter("manage_{$post_type}_posts_columns", $columns_callback, $priority);
  add_action("manage_{$post_type}_posts_custom_column", $content_callback, $priority, 2);
}

/**
 * Make admin columns sortable
 *
 * Register columns as sortable in the admin post list table.
 *
 * @since 1.0.0
 *
 * @param string $post_type         Post type to make columns sortable for.
 * @param array  $sortable_columns  Array of column_key => meta_key pairs.
 * @param int    $priority          Priority for the filter hook. Default 10.
 */
function hog_scaffold_make_columns_sortable($post_type, $sortable_columns, $priority = 10)
{
  add_filter("manage_edit-{$post_type}_sortable_columns", function ($columns) use ($sortable_columns) {
    return array_merge($columns, $sortable_columns);
  }, $priority);

  // Handle the actual sorting in pre_get_posts
  add_action('pre_get_posts', function ($query) use ($post_type, $sortable_columns) {
    if (!is_admin() || !$query->is_main_query()) {
      return;
    }

    $orderby = $query->get('orderby');
    if (!$orderby || !isset($sortable_columns[$orderby])) {
      return;
    }

    $meta_key = $sortable_columns[$orderby];
    $query->set('meta_key', $meta_key);
    $query->set('orderby', 'meta_value');
  });
}

/**
 * Add a simple meta box with text input
 *
 * Convenience function for adding a simple text input meta box.
 *
 * @since 1.0.0
 *
 * @param string $post_type Post type to add the meta box to.
 * @param string $meta_key  Meta key for the field.
 * @param string $title     Meta box title.
 * @param array  $args {
 *     Optional arguments for the meta box.
 *
 *     @type string $input_type  Type of input field. Default 'text'.
 *     @type string $placeholder Placeholder text for the input.
 *     @type string $description Description text below the input.
 *     @type array  $input_attrs Additional input attributes.
 * }
 */
function hog_scaffold_add_simple_meta_box($post_type, $meta_key, $title, $args = array())
{
  $defaults = array(
    'input_type' => 'text',
    'placeholder' => '',
    'description' => '',
    'input_attrs' => array(),
  );

  $args = wp_parse_args($args, $defaults);

  // Register the meta field
  hog_scaffold_register_post_meta($post_type, $meta_key);

  // Add the meta box
  hog_scaffold_add_meta_box(
    $post_type,
    $meta_key . '_meta_box',
    $title,
    function ($post) use ($meta_key, $args) {
      // Add nonce field for security
      wp_nonce_field("save_{$meta_key}_meta", "{$meta_key}_meta_nonce");

      // Get current value
      $value = get_post_meta($post->ID, $meta_key, true);

      // Prepare input attributes
      $input_attrs = wp_parse_args($args['input_attrs'], array(
        'type' => $args['input_type'],
        'id' => $meta_key,
        'name' => $meta_key,
        'value' => esc_attr($value),
        'placeholder' => esc_attr($args['placeholder']),
        'style' => 'width: 100%;',
      ));

      // Generate attributes string
      $attr_string = '';
      foreach ($input_attrs as $attr => $attr_value) {
        $attr_string .= sprintf(' %s="%s"', esc_attr($attr), esc_attr($attr_value));
      }

      // Render the input
      if ('textarea' === $args['input_type']) {
        printf('<textarea%s>%s</textarea>', $attr_string, esc_textarea($value));
      } else {
        printf('<input%s />', $attr_string);
      }

      // Add description if provided
      if (!empty($args['description'])) {
        printf('<p class="description">%s</p>', esc_html($args['description']));
      }
    }
  );

  // Handle saving the meta field
  add_action('save_post', function ($post_id) use ($meta_key) {
    // Verify nonce
    if (
      !isset($_POST["{$meta_key}_meta_nonce"]) ||
      !wp_verify_nonce($_POST["{$meta_key}_meta_nonce"], "save_{$meta_key}_meta")
    ) {
      return;
    }

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
      return;
    }

    // Save the meta field
    if (isset($_POST[$meta_key])) {
      $value = sanitize_text_field($_POST[$meta_key]);
      update_post_meta($post_id, $meta_key, $value);
    } else {
      delete_post_meta($post_id, $meta_key);
    }
  });
}

/**
 * Get posts by taxonomy term
 *
 * Helper function to retrieve posts filtered by taxonomy term.
 *
 * @since 1.0.0
 *
 * @param string $post_type Post type to query.
 * @param string $taxonomy  Taxonomy to filter by.
 * @param string $term      Term slug or ID.
 * @param array  $args      Additional WP_Query arguments.
 * @return WP_Query Query object.
 */
function hog_scaffold_get_posts_by_taxonomy($post_type, $taxonomy, $term, $args = array())
{
  $defaults = array(
    'post_type' => $post_type,
    'posts_per_page' => 10,
    'post_status' => 'publish',
    'tax_query' => array(
      array(
        'taxonomy' => $taxonomy,
        'field' => is_numeric($term) ? 'term_id' : 'slug',
        'terms' => $term,
      ),
    ),
  );

  $args = wp_parse_args($args, $defaults);

  return new WP_Query($args);
}

/**
 * Add featured meta box for featured content
 *
 * Convenience function for adding a "featured" checkbox meta box.
 *
 * @since 1.0.0
 *
 * @param string $post_type Post type to add the meta box to.
 * @param string $title     Meta box title. Default 'Featured Content'.
 */
function hog_scaffold_add_featured_meta_box($post_type, $title = null)
{
  if (null === $title) {
    $title = __('Featured Content', 'hog-scaffold');
  }

  // Register the meta field
  hog_scaffold_register_post_meta($post_type, 'featured', array(
    'type' => 'boolean',
    'sanitize_callback' => 'rest_sanitize_boolean',
    'show_in_rest' => true,
  ));

  // Add the meta box
  hog_scaffold_add_meta_box(
    $post_type,
    'featured_meta_box',
    $title,
    function ($post) {
      // Add nonce field for security
      wp_nonce_field('save_featured_meta', 'featured_meta_nonce');

      // Get current value
      $featured = get_post_meta($post->ID, 'featured', true);

      printf(
        '<label><input type="checkbox" name="featured" value="1" %s /> %s</label>',
        checked($featured, true, false),
        esc_html__('Mark this item as featured', 'hog-scaffold')
      );
    },
    'side',
    'high'
  );

  // Handle saving the meta field
  add_action('save_post', function ($post_id) {
    // Verify nonce
    if (
      !isset($_POST['featured_meta_nonce']) ||
      !wp_verify_nonce($_POST['featured_meta_nonce'], 'save_featured_meta')
    ) {
      return;
    }

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
      return;
    }

    // Save the featured status
    $featured = isset($_POST['featured']) ? true : false;
    update_post_meta($post_id, 'featured', $featured);
  });
}