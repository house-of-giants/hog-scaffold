<?php
/**
 * Simplified Custom Post Type Functions
 *
 * Clean, WordPress-native approach to custom post type registration
 * with sensible defaults and minimal abstraction.
 *
 * @package HoGScaffold\CPT
 * @since 1.0.0
 */

// Prevent direct access.
if (!defined('ABSPATH')) {
  exit;
}

/**
 * Register a custom post type with sensible defaults
 *
 * This function wraps WordPress's register_post_type() with defaults
 * optimized for modern WordPress development including block editor
 * support, REST API integration, and proper accessibility.
 *
 * @since 1.0.0
 *
 * @param string $post_type Post type key. Must not exceed 20 characters.
 * @param array  $args {
 *     Array of arguments for registering the post type.
 *
 *     @type string       $singular_name    Singular name for auto-generating labels.
 *     @type string       $plural_name      Plural name for auto-generating labels.
 *     @type array        $labels           Complete labels array (overrides auto-generation).
 *     @type string       $description      Description of the post type.
 *     @type bool         $public           Whether the post type is public. Default true.
 *     @type bool         $show_in_rest     Whether to include in REST API. Default true.
 *     @type array        $supports         Features the post type supports. Default ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'].
 *     @type bool         $has_archive      Whether to enable post type archives. Default true.
 *     @type bool         $show_in_nav_menus Whether to show in nav menus. Default true.
 *     @type string       $capability_type  Capability type for permissions. Default 'post'.
 *     @type bool         $hierarchical     Whether posts can have parents. Default false.
 *     @type int          $menu_position    Position in admin menu. Default 20.
 *     @type string       $menu_icon        Dashicon class or URL for menu icon.
 *     @type array|string $rewrite          Rewrite rules. Default ['slug' => $post_type].
 *     @type bool         $publicly_queryable Whether queries can be performed. Default true.
 *     @type bool         $show_ui          Whether to show admin UI. Default true.
 *     @type bool         $show_in_menu     Whether to show in admin menu. Default true.
 *     @type bool         $show_in_admin_bar Whether to show in admin bar. Default true.
 *     @type string       $rest_base        REST API base slug. Default $post_type.
 *     @type string       $rest_controller_class REST controller class. Default 'WP_REST_Posts_Controller'.
 * }
 * @return WP_Post_Type|WP_Error The registered post type object on success, WP_Error on failure.
 */
function hog_scaffold_register_post_type($post_type, $args = array())
{
  // Validate post type name
  if (empty($post_type) || strlen($post_type) > 20) {
    return new WP_Error(
      'invalid_post_type',
      __('Post type name must be between 1 and 20 characters.', 'hog-scaffold')
    );
  }

  // Sanitize post type name
  $post_type = sanitize_key($post_type);

  // Set up sensible defaults optimized for modern WordPress
  $defaults = array(
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'show_in_nav_menus' => true,
    'show_in_admin_bar' => true,
    'show_in_rest' => true,
    'rest_base' => $post_type,
    'rest_controller_class' => 'WP_REST_Posts_Controller',
    'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
    'has_archive' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_position' => 20,
    'query_var' => true,
    'rewrite' => array('slug' => $post_type),
    'can_export' => true,
    'delete_with_user' => false,
  );

  // Merge provided args with defaults
  $args = wp_parse_args($args, $defaults);

  // Auto-generate labels if not provided
  if (empty($args['labels'])) {
    $args['labels'] = hog_scaffold_generate_cpt_labels($post_type, $args);
  }

  // Set default menu icon if not provided
  if (empty($args['menu_icon'])) {
    $args['menu_icon'] = 'dashicons-admin-post';
  }

  // Ensure rewrite is properly formatted
  if (is_string($args['rewrite'])) {
    $args['rewrite'] = array('slug' => $args['rewrite']);
  } elseif (true === $args['rewrite']) {
    $args['rewrite'] = array('slug' => $post_type);
  }

  /**
   * Filter the arguments before registering a custom post type
   *
   * @since 1.0.0
   *
   * @param array  $args      Array of arguments for registering the post type.
   * @param string $post_type Post type key.
   */
  $args = apply_filters('hog_scaffold_register_post_type_args', $args, $post_type);

  // Register the post type
  $result = register_post_type($post_type, $args);

  /**
   * Fires after a custom post type is registered
   *
   * @since 1.0.0
   *
   * @param string                $post_type Post type key.
   * @param WP_Post_Type|WP_Error $result    The registered post type object or WP_Error.
   * @param array                 $args      Array of arguments used for registration.
   */
  do_action('hog_scaffold_post_type_registered', $post_type, $result, $args);

  return $result;
}

/**
 * Generate comprehensive labels for a custom post type
 *
 * Automatically creates all the labels needed for a custom post type
 * based on singular and plural names, with proper internationalization.
 *
 * @since 1.0.0
 *
 * @param string $post_type Post type key.
 * @param array  $args      Registration arguments that may contain singular_name and plural_name.
 * @return array Array of labels for the post type.
 */
function hog_scaffold_generate_cpt_labels($post_type, $args = array())
{
  // Determine singular and plural names
  $singular = $args['singular_name'] ?? ucfirst(str_replace(array('_', '-'), ' ', $post_type));
  $plural = $args['plural_name'] ?? $singular . 's';

  // Handle special pluralization cases
  $plural = hog_scaffold_smart_pluralize($singular, $plural);

  $singular_lower = strtolower($singular);
  $plural_lower = strtolower($plural);

  return array(
    'name' => $plural,
    'singular_name' => $singular,
    'menu_name' => $plural,
    'name_admin_bar' => $singular,
    'archives' => sprintf(_x('%s Archives', 'post type archives', 'hog-scaffold'), $singular),
    'attributes' => sprintf(_x('%s Attributes', 'post type attributes', 'hog-scaffold'), $singular),
    'parent_item_colon' => sprintf(_x('Parent %s:', 'post type parent item', 'hog-scaffold'), $singular),
    'all_items' => sprintf(_x('All %s', 'post type all items', 'hog-scaffold'), $plural),
    'add_new_item' => sprintf(_x('Add New %s', 'post type add new item', 'hog-scaffold'), $singular),
    'add_new' => _x('Add New', 'post type add new', 'hog-scaffold'),
    'new_item' => sprintf(_x('New %s', 'post type new item', 'hog-scaffold'), $singular),
    'edit_item' => sprintf(_x('Edit %s', 'post type edit item', 'hog-scaffold'), $singular),
    'update_item' => sprintf(_x('Update %s', 'post type update item', 'hog-scaffold'), $singular),
    'view_item' => sprintf(_x('View %s', 'post type view item', 'hog-scaffold'), $singular),
    'view_items' => sprintf(_x('View %s', 'post type view items', 'hog-scaffold'), $plural),
    'search_items' => sprintf(_x('Search %s', 'post type search items', 'hog-scaffold'), $plural),
    'not_found' => sprintf(_x('No %s found', 'post type not found', 'hog-scaffold'), $plural_lower),
    'not_found_in_trash' => sprintf(_x('No %s found in Trash', 'post type not found in trash', 'hog-scaffold'), $plural_lower),
    'featured_image' => _x('Featured Image', 'post type featured image', 'hog-scaffold'),
    'set_featured_image' => _x('Set featured image', 'post type set featured image', 'hog-scaffold'),
    'remove_featured_image' => _x('Remove featured image', 'post type remove featured image', 'hog-scaffold'),
    'use_featured_image' => _x('Use as featured image', 'post type use featured image', 'hog-scaffold'),
    'insert_into_item' => sprintf(_x('Insert into %s', 'post type insert into item', 'hog-scaffold'), $singular_lower),
    'uploaded_to_this_item' => sprintf(_x('Uploaded to this %s', 'post type uploaded to item', 'hog-scaffold'), $singular_lower),
    'items_list' => sprintf(_x('%s list', 'post type items list', 'hog-scaffold'), $plural),
    'items_list_navigation' => sprintf(_x('%s list navigation', 'post type items list navigation', 'hog-scaffold'), $plural),
    'filter_items_list' => sprintf(_x('Filter %s list', 'post type filter items list', 'hog-scaffold'), $plural_lower),
    'item_published' => sprintf(_x('%s published', 'post type item published', 'hog-scaffold'), $singular),
    'item_published_privately' => sprintf(_x('%s published privately', 'post type item published privately', 'hog-scaffold'), $singular),
    'item_reverted_to_draft' => sprintf(_x('%s reverted to draft', 'post type item reverted to draft', 'hog-scaffold'), $singular),
    'item_scheduled' => sprintf(_x('%s scheduled', 'post type item scheduled', 'hog-scaffold'), $singular),
    'item_updated' => sprintf(_x('%s updated', 'post type item updated', 'hog-scaffold'), $singular),
  );
}

/**
 * Smart pluralization helper
 *
 * Handles common English pluralization rules when auto-generating labels.
 *
 * @since 1.0.0
 *
 * @param string $singular Singular form.
 * @param string $plural   Provided plural form (used as fallback).
 * @return string Properly pluralized form.
 */
function hog_scaffold_smart_pluralize($singular, $plural)
{
  // If plural was explicitly provided, use it
  if ($plural !== $singular . 's') {
    return $plural;
  }

  $singular_lower = strtolower($singular);

  // Common irregular plurals
  $irregulars = array(
    'child' => 'children',
    'person' => 'people',
    'man' => 'men',
    'woman' => 'women',
    'tooth' => 'teeth',
    'foot' => 'feet',
    'mouse' => 'mice',
    'goose' => 'geese',
  );

  if (isset($irregulars[$singular_lower])) {
    return ucfirst($irregulars[$singular_lower]);
  }

  // Words ending in -y (preceded by consonant)
  if (preg_match('/[bcdfghjklmnpqrstvwxz]y$/i', $singular)) {
    return substr($singular, 0, -1) . 'ies';
  }

  // Words ending in -s, -ss, -sh, -ch, -x, -z
  if (preg_match('/(s|ss|sh|ch|x|z)$/i', $singular)) {
    return $singular . 'es';
  }

  // Words ending in -f or -fe
  if (preg_match('/f(e)?$/i', $singular)) {
    return preg_replace('/f(e)?$/i', 'ves', $singular);
  }

  // Default: add 's'
  return $singular . 's';
}

/**
 * Register post meta with sensible defaults
 *
 * Wrapper around register_post_meta() with defaults optimized for
 * block editor and REST API integration.
 *
 * @since 1.0.0
 *
 * @param string $post_type Post type to register meta for.
 * @param string $meta_key  Meta key to register.
 * @param array  $args {
 *     Array of arguments for registering the meta field.
 *
 *     @type string   $type              Data type. Default 'string'.
 *     @type string   $description       Description of the meta field.
 *     @type bool     $single            Whether the field should be single-valued. Default true.
 *     @type bool     $show_in_rest      Whether to include in REST API. Default true.
 *     @type callable $sanitize_callback Sanitization callback. Default 'sanitize_text_field'.
 *     @type callable $auth_callback     Authorization callback.
 *     @type mixed    $default           Default value.
 * }
 * @return bool True if the meta key was successfully registered, false otherwise.
 */
function hog_scaffold_register_post_meta($post_type, $meta_key, $args = array())
{
  $defaults = array(
    'type' => 'string',
    'single' => true,
    'show_in_rest' => true,
    'sanitize_callback' => 'sanitize_text_field',
  );

  $args = wp_parse_args($args, $defaults);

  /**
   * Filter the arguments before registering post meta
   *
   * @since 1.0.0
   *
   * @param array  $args      Array of arguments for registering the meta field.
   * @param string $meta_key  Meta key being registered.
   * @param string $post_type Post type the meta is being registered for.
   */
  $args = apply_filters('hog_scaffold_register_post_meta_args', $args, $meta_key, $post_type);

  return register_post_meta($post_type, $meta_key, $args);
}

/**
 * Register a taxonomy with sensible defaults
 *
 * Wrapper around register_taxonomy() with defaults optimized for
 * modern WordPress development.
 *
 * @since 1.0.0
 *
 * @param string          $taxonomy   Taxonomy key. Must not exceed 32 characters.
 * @param array|string    $post_types Post type(s) to register the taxonomy for.
 * @param array           $args {
 *     Array of arguments for registering the taxonomy.
 *
 *     @type string $singular_name    Singular name for auto-generating labels.
 *     @type string $plural_name      Plural name for auto-generating labels.
 *     @type array  $labels           Complete labels array (overrides auto-generation).
 *     @type string $description      Description of the taxonomy.
 *     @type bool   $hierarchical     Whether the taxonomy is hierarchical. Default true.
 *     @type bool   $public           Whether the taxonomy is public. Default true.
 *     @type bool   $show_in_rest     Whether to include in REST API. Default true.
 *     @type bool   $show_admin_column Whether to show in admin columns. Default true.
 *     @type bool   $show_in_nav_menus Whether to show in nav menus. Default true.
 *     @type bool   $show_tagcloud    Whether to show in tag cloud. Default true.
 *     @type bool   $show_in_quick_edit Whether to show in quick edit. Default true.
 *     @type array  $rewrite          Rewrite rules. Default ['slug' => $taxonomy].
 *     @type string $rest_base        REST API base slug. Default $taxonomy.
 * }
 * @return WP_Taxonomy|WP_Error The registered taxonomy object on success, WP_Error on failure.
 */
function hog_scaffold_register_taxonomy($taxonomy, $post_types, $args = array())
{
  // Validate taxonomy name
  if (empty($taxonomy) || strlen($taxonomy) > 32) {
    return new WP_Error(
      'invalid_taxonomy',
      __('Taxonomy name must be between 1 and 32 characters.', 'hog-scaffold')
    );
  }

  // Sanitize taxonomy name
  $taxonomy = sanitize_key($taxonomy);

  // Set up sensible defaults
  $defaults = array(
    'hierarchical' => true,
    'public' => true,
    'show_ui' => true,
    'show_admin_column' => true,
    'show_in_nav_menus' => true,
    'show_tagcloud' => true,
    'show_in_quick_edit' => true,
    'show_in_rest' => true,
    'rest_base' => $taxonomy,
    'rewrite' => array('slug' => $taxonomy),
  );

  // Merge provided args with defaults
  $args = wp_parse_args($args, $defaults);

  // Auto-generate labels if not provided
  if (empty($args['labels'])) {
    $args['labels'] = hog_scaffold_generate_taxonomy_labels($taxonomy, $args);
  }

  // Ensure rewrite is properly formatted
  if (is_string($args['rewrite'])) {
    $args['rewrite'] = array('slug' => $args['rewrite']);
  } elseif (true === $args['rewrite']) {
    $args['rewrite'] = array('slug' => $taxonomy);
  }

  /**
   * Filter the arguments before registering a taxonomy
   *
   * @since 1.0.0
   *
   * @param array        $args       Array of arguments for registering the taxonomy.
   * @param string       $taxonomy   Taxonomy key.
   * @param array|string $post_types Post type(s) the taxonomy is being registered for.
   */
  $args = apply_filters('hog_scaffold_register_taxonomy_args', $args, $taxonomy, $post_types);

  // Register the taxonomy
  $result = register_taxonomy($taxonomy, $post_types, $args);

  /**
   * Fires after a taxonomy is registered
   *
   * @since 1.0.0
   *
   * @param string                  $taxonomy   Taxonomy key.
   * @param WP_Taxonomy|WP_Error    $result     The registered taxonomy object or WP_Error.
   * @param array|string            $post_types Post type(s) the taxonomy was registered for.
   * @param array                   $args       Array of arguments used for registration.
   */
  do_action('hog_scaffold_taxonomy_registered', $taxonomy, $result, $post_types, $args);

  return $result;
}

/**
 * Generate comprehensive labels for a taxonomy
 *
 * Automatically creates all the labels needed for a taxonomy
 * based on singular and plural names, with proper internationalization.
 *
 * @since 1.0.0
 *
 * @param string $taxonomy Taxonomy key.
 * @param array  $args     Registration arguments that may contain singular_name and plural_name.
 * @return array Array of labels for the taxonomy.
 */
function hog_scaffold_generate_taxonomy_labels($taxonomy, $args = array())
{
  // Determine singular and plural names
  $singular = $args['singular_name'] ?? ucfirst(str_replace(array('_', '-'), ' ', $taxonomy));
  $plural = $args['plural_name'] ?? hog_scaffold_smart_pluralize($singular, $singular . 's');

  $singular_lower = strtolower($singular);
  $plural_lower = strtolower($plural);

  // Check if this is hierarchical to adjust some labels
  $hierarchical = $args['hierarchical'] ?? true;

  if ($hierarchical) {
    return array(
      'name' => $plural,
      'singular_name' => $singular,
      'menu_name' => $plural,
      'all_items' => sprintf(_x('All %s', 'taxonomy all items', 'hog-scaffold'), $plural),
      'edit_item' => sprintf(_x('Edit %s', 'taxonomy edit item', 'hog-scaffold'), $singular),
      'view_item' => sprintf(_x('View %s', 'taxonomy view item', 'hog-scaffold'), $singular),
      'update_item' => sprintf(_x('Update %s', 'taxonomy update item', 'hog-scaffold'), $singular),
      'add_new_item' => sprintf(_x('Add New %s', 'taxonomy add new item', 'hog-scaffold'), $singular),
      'new_item_name' => sprintf(_x('New %s Name', 'taxonomy new item name', 'hog-scaffold'), $singular),
      'parent_item' => sprintf(_x('Parent %s', 'taxonomy parent item', 'hog-scaffold'), $singular),
      'parent_item_colon' => sprintf(_x('Parent %s:', 'taxonomy parent item colon', 'hog-scaffold'), $singular),
      'search_items' => sprintf(_x('Search %s', 'taxonomy search items', 'hog-scaffold'), $plural),
      'popular_items' => sprintf(_x('Popular %s', 'taxonomy popular items', 'hog-scaffold'), $plural),
      'separate_items_with_commas' => sprintf(_x('Separate %s with commas', 'taxonomy separate with commas', 'hog-scaffold'), $plural_lower),
      'add_or_remove_items' => sprintf(_x('Add or remove %s', 'taxonomy add or remove', 'hog-scaffold'), $plural_lower),
      'choose_from_most_used' => sprintf(_x('Choose from the most used %s', 'taxonomy choose most used', 'hog-scaffold'), $plural_lower),
      'not_found' => sprintf(_x('No %s found', 'taxonomy not found', 'hog-scaffold'), $plural_lower),
      'no_terms' => sprintf(_x('No %s', 'taxonomy no terms', 'hog-scaffold'), $plural_lower),
      'items_list_navigation' => sprintf(_x('%s list navigation', 'taxonomy list navigation', 'hog-scaffold'), $plural),
      'items_list' => sprintf(_x('%s list', 'taxonomy items list', 'hog-scaffold'), $plural),
      'back_to_items' => sprintf(_x('&larr; Back to %s', 'taxonomy back to items', 'hog-scaffold'), $plural),
    );
  } else {
    // Non-hierarchical (tags-style) labels
    return array(
      'name' => $plural,
      'singular_name' => $singular,
      'menu_name' => $plural,
      'all_items' => sprintf(_x('All %s', 'taxonomy all items', 'hog-scaffold'), $plural),
      'edit_item' => sprintf(_x('Edit %s', 'taxonomy edit item', 'hog-scaffold'), $singular),
      'view_item' => sprintf(_x('View %s', 'taxonomy view item', 'hog-scaffold'), $singular),
      'update_item' => sprintf(_x('Update %s', 'taxonomy update item', 'hog-scaffold'), $singular),
      'add_new_item' => sprintf(_x('Add New %s', 'taxonomy add new item', 'hog-scaffold'), $singular),
      'new_item_name' => sprintf(_x('New %s Name', 'taxonomy new item name', 'hog-scaffold'), $singular),
      'search_items' => sprintf(_x('Search %s', 'taxonomy search items', 'hog-scaffold'), $plural),
      'popular_items' => sprintf(_x('Popular %s', 'taxonomy popular items', 'hog-scaffold'), $plural),
      'separate_items_with_commas' => sprintf(_x('Separate %s with commas', 'taxonomy separate with commas', 'hog-scaffold'), $plural_lower),
      'add_or_remove_items' => sprintf(_x('Add or remove %s', 'taxonomy add or remove', 'hog-scaffold'), $plural_lower),
      'choose_from_most_used' => sprintf(_x('Choose from the most used %s', 'taxonomy choose most used', 'hog-scaffold'), $plural_lower),
      'not_found' => sprintf(_x('No %s found', 'taxonomy not found', 'hog-scaffold'), $plural_lower),
      'no_terms' => sprintf(_x('No %s', 'taxonomy no terms', 'hog-scaffold'), $plural_lower),
      'items_list_navigation' => sprintf(_x('%s list navigation', 'taxonomy list navigation', 'hog-scaffold'), $plural),
      'items_list' => sprintf(_x('%s list', 'taxonomy items list', 'hog-scaffold'), $plural),
      'back_to_items' => sprintf(_x('&larr; Back to %s', 'taxonomy back to items', 'hog-scaffold'), $plural),
    );
  }
}