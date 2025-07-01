<?php
/**
 * Custom Post Type Definitions
 *
 * This file contains all custom post type registrations for the theme
 * using the simplified registration functions.
 *
 * @package HoGScaffold\CPT
 * @since 1.0.0
 */

// Prevent direct access.
if (!defined('ABSPATH')) {
  exit;
}

/**
 * Initialize all custom post types
 *
 * This function is called during WordPress init and registers
 * all custom post types and their associated taxonomies.
 */
function hog_scaffold_init_custom_post_types()
{
  // Register Portfolio CPT
  hog_scaffold_register_portfolio_cpt();
}
add_action('init', 'hog_scaffold_init_custom_post_types');

/**
 * Register Portfolio Custom Post Type
 *
 * Comprehensive example of registering a custom post type with
 * meta fields, taxonomies, and admin customizations.
 *
 * @since 1.0.0
 */
function hog_scaffold_register_portfolio_cpt()
{
  // Register the main post type
  hog_scaffold_register_post_type('portfolio', array(
    'singular_name' => __('Portfolio Item', 'hog-scaffold'),
    'plural_name' => __('Portfolio Items', 'hog-scaffold'),
    'description' => __('Showcase your work and projects with detailed portfolio items.', 'hog-scaffold'),
    'menu_icon' => 'dashicons-portfolio',
    'menu_position' => 25,
    'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
    'has_archive' => true,
    'rewrite' => array('slug' => 'portfolio'),
  ));

  // Register associated taxonomies
  hog_scaffold_register_portfolio_taxonomies();

  // Register meta fields
  hog_scaffold_register_portfolio_meta_fields();

  // Add admin customizations
  hog_scaffold_setup_portfolio_admin();
}

/**
 * Register Portfolio Taxonomies
 *
 * Register all taxonomies associated with the portfolio post type.
 *
 * @since 1.0.0
 */
function hog_scaffold_register_portfolio_taxonomies()
{
  // Portfolio Categories (hierarchical)
  hog_scaffold_register_taxonomy('portfolio_category', 'portfolio', array(
    'singular_name' => __('Portfolio Category', 'hog-scaffold'),
    'plural_name' => __('Portfolio Categories', 'hog-scaffold'),
    'description' => __('Categorize portfolio items by type or industry', 'hog-scaffold'),
    'hierarchical' => true,
    'rewrite' => array('slug' => 'portfolio-category'),
  ));

  // Portfolio Tags (non-hierarchical)
  hog_scaffold_register_taxonomy('portfolio_tag', 'portfolio', array(
    'singular_name' => __('Portfolio Tag', 'hog-scaffold'),
    'plural_name' => __('Portfolio Tags', 'hog-scaffold'),
    'description' => __('Tag portfolio items with relevant keywords', 'hog-scaffold'),
    'hierarchical' => false,
    'rewrite' => array('slug' => 'portfolio-tag'),
  ));

  // Skills (non-hierarchical)
  hog_scaffold_register_taxonomy('portfolio_skill', 'portfolio', array(
    'singular_name' => __('Skill', 'hog-scaffold'),
    'plural_name' => __('Skills', 'hog-scaffold'),
    'description' => __('Skills and technologies demonstrated in this project', 'hog-scaffold'),
    'hierarchical' => false,
    'rewrite' => array('slug' => 'skill'),
  ));
}

/**
 * Register Portfolio Meta Fields
 *
 * Register all custom meta fields for portfolio items.
 *
 * @since 1.0.0
 */
function hog_scaffold_register_portfolio_meta_fields()
{
  // Client Name
  hog_scaffold_register_post_meta('portfolio', 'client_name', array(
    'type' => 'string',
    'description' => __('Client or company name for this project', 'hog-scaffold'),
    'single' => true,
    'show_in_rest' => true,
  ));

  // Project Date
  hog_scaffold_register_post_meta('portfolio', 'project_date', array(
    'type' => 'string',
    'description' => __('Project completion or launch date', 'hog-scaffold'),
    'single' => true,
    'show_in_rest' => true,
  ));

  // Project URL
  hog_scaffold_register_post_meta('portfolio', 'project_url', array(
    'type' => 'string',
    'description' => __('Live project URL (if applicable)', 'hog-scaffold'),
    'single' => true,
    'show_in_rest' => true,
    'sanitize_callback' => 'esc_url_raw',
  ));

  // Technologies Used
  hog_scaffold_register_post_meta('portfolio', 'technologies', array(
    'type' => 'string',
    'description' => __('Technologies and tools used (comma-separated)', 'hog-scaffold'),
    'single' => true,
    'show_in_rest' => true,
  ));

  // Project Status
  hog_scaffold_register_post_meta('portfolio', 'project_status', array(
    'type' => 'string',
    'description' => __('Current status of the project', 'hog-scaffold'),
    'single' => true,
    'show_in_rest' => true,
  ));

  // Featured Project
  hog_scaffold_register_post_meta('portfolio', 'featured_project', array(
    'type' => 'boolean',
    'description' => __('Mark as featured project', 'hog-scaffold'),
    'single' => true,
    'show_in_rest' => true,
    'sanitize_callback' => 'rest_sanitize_boolean',
    'default' => false,
  ));
}

/**
 * Setup Portfolio Admin Customizations
 *
 * Add meta boxes, custom columns, and other admin interface enhancements.
 *
 * @since 1.0.0
 */
function hog_scaffold_setup_portfolio_admin()
{
  // Add simple meta boxes for most fields
  hog_scaffold_add_simple_meta_box('portfolio', 'client_name', __('Client Information', 'hog-scaffold'), array(
    'placeholder' => __('Enter client or company name', 'hog-scaffold'),
    'description' => __('The client or company this project was created for.', 'hog-scaffold'),
  ));

  hog_scaffold_add_simple_meta_box('portfolio', 'project_date', __('Project Date', 'hog-scaffold'), array(
    'input_type' => 'date',
    'description' => __('When was this project completed or launched?', 'hog-scaffold'),
  ));

  hog_scaffold_add_simple_meta_box('portfolio', 'project_url', __('Project URL', 'hog-scaffold'), array(
    'input_type' => 'url',
    'placeholder' => __('https://example.com', 'hog-scaffold'),
    'description' => __('Live URL where this project can be viewed (optional).', 'hog-scaffold'),
  ));

  hog_scaffold_add_simple_meta_box('portfolio', 'technologies', __('Technologies Used', 'hog-scaffold'), array(
    'input_type' => 'textarea',
    'placeholder' => __('WordPress, PHP, JavaScript, React, etc.', 'hog-scaffold'),
    'description' => __('List the technologies, tools, and frameworks used in this project.', 'hog-scaffold'),
  ));

  // Add custom status meta box (more complex)
  hog_scaffold_add_portfolio_status_meta_box();

  // Add featured meta box
  hog_scaffold_add_featured_meta_box('portfolio');

  // Add custom admin columns
  hog_scaffold_add_portfolio_admin_columns();

  // Add admin styles
  hog_scaffold_add_portfolio_admin_styles();
}

/**
 * Add Portfolio Status Meta Box
 *
 * Custom meta box for project status with dropdown selection.
 *
 * @since 1.0.0
 */
function hog_scaffold_add_portfolio_status_meta_box()
{
  hog_scaffold_add_meta_box(
    'portfolio',
    'project_status_meta_box',
    __('Project Status', 'hog-scaffold'),
    'hog_scaffold_render_project_status_meta_box',
    'side',
    'high'
  );

  // Handle saving
  add_action('save_post', 'hog_scaffold_save_project_status_meta');
}

/**
 * Render Project Status Meta Box
 *
 * @since 1.0.0
 * @param WP_Post $post Current post object.
 */
function hog_scaffold_render_project_status_meta_box($post)
{
  wp_nonce_field('save_project_status_meta', 'project_status_meta_nonce');

  $current_status = get_post_meta($post->ID, 'project_status', true);
  $statuses = hog_scaffold_get_portfolio_status_options();

  echo '<select name="project_status" id="project_status" style="width: 100%;">';
  echo '<option value="">' . esc_html__('Select Status', 'hog-scaffold') . '</option>';

  foreach ($statuses as $value => $label) {
    printf(
      '<option value="%s" %s>%s</option>',
      esc_attr($value),
      selected($current_status, $value, false),
      esc_html($label)
    );
  }

  echo '</select>';
  echo '<p class="description">' . esc_html__('Current status of this project.', 'hog-scaffold') . '</p>';
}

/**
 * Save Project Status Meta
 *
 * @since 1.0.0
 * @param int $post_id Post ID.
 */
function hog_scaffold_save_project_status_meta($post_id)
{
  if (
    !isset($_POST['project_status_meta_nonce']) ||
    !wp_verify_nonce($_POST['project_status_meta_nonce'], 'save_project_status_meta') ||
    !current_user_can('edit_post', $post_id)
  ) {
    return;
  }

  if (isset($_POST['project_status'])) {
    update_post_meta($post_id, 'project_status', sanitize_text_field($_POST['project_status']));
  } else {
    delete_post_meta($post_id, 'project_status');
  }
}

/**
 * Add Portfolio Admin Columns
 *
 * @since 1.0.0
 */
function hog_scaffold_add_portfolio_admin_columns()
{
  hog_scaffold_add_admin_columns(
    'portfolio',
    function ($columns) {
      $new_columns = array();
      foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ('title' === $key) {
          $new_columns['client'] = __('Client', 'hog-scaffold');
          $new_columns['project_date'] = __('Project Date', 'hog-scaffold');
          $new_columns['project_status'] = __('Status', 'hog-scaffold');
          $new_columns['featured'] = __('Featured', 'hog-scaffold');
        }
      }
      return $new_columns;
    },
    function ($column, $post_id) {
      switch ($column) {
        case 'client':
          echo get_post_meta($post_id, 'client_name', true) ?: '—';
          break;
        case 'project_date':
          $date = get_post_meta($post_id, 'project_date', true);
          echo $date ? esc_html(date_i18n(get_option('date_format'), strtotime($date))) : '—';
          break;
        case 'project_status':
          echo hog_scaffold_get_portfolio_status_badge($post_id);
          break;
        case 'featured':
          echo get_post_meta($post_id, 'featured_project', true) ? '⭐' : '—';
          break;
      }
    }
  );

  hog_scaffold_make_columns_sortable('portfolio', array(
    'client' => 'client_name',
    'project_date' => 'project_date',
    'project_status' => 'project_status',
    'featured' => 'featured_project',
  ));
}

/**
 * Add Portfolio Admin Styles
 *
 * @since 1.0.0
 */
function hog_scaffold_add_portfolio_admin_styles()
{
  add_action('admin_head', function () {
    global $post_type;
    if ('portfolio' !== $post_type) {
      return;
    }
    ?>
    <style>
      .status-badge {
        padding: 2px 8px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: 500;
        text-transform: uppercase;
        color: white;
      }

      .status-completed {
        background-color: #46b450;
      }

      .status-in-progress {
        background-color: #00a0d2;
      }

      .status-on-hold {
        background-color: #ffb900;
      }

      .status-cancelled {
        background-color: #dc3232;
      }

      .column-featured {
        width: 80px;
        text-align: center;
      }

      .column-project_date {
        width: 120px;
      }

      .column-project_status {
        width: 100px;
      }

      .column-client {
        width: 150px;
      }
    </style>
    <?php
  });
}

/**
 * Portfolio Helper Functions
 */

/**
 * Get portfolio status options
 *
 * @since 1.0.0
 * @return array Status options.
 */
function hog_scaffold_get_portfolio_status_options()
{
  return array(
    'completed' => __('Completed', 'hog-scaffold'),
    'in-progress' => __('In Progress', 'hog-scaffold'),
    'on-hold' => __('On Hold', 'hog-scaffold'),
    'cancelled' => __('Cancelled', 'hog-scaffold'),
  );
}

/**
 * Get featured portfolio items
 *
 * @since 1.0.0
 * @param int $limit Number of items to retrieve. Default 5.
 * @return WP_Query Query object containing featured portfolio items.
 */
function hog_scaffold_get_featured_portfolio($limit = 5)
{
  return new WP_Query(array(
    'post_type' => 'portfolio',
    'posts_per_page' => $limit,
    'post_status' => 'publish',
    'meta_query' => array(
      array(
        'key' => 'featured_project',
        'value' => true,
        'compare' => '=',
      ),
    ),
    'orderby' => 'date',
    'order' => 'DESC',
  ));
}

/**
 * Get portfolio items by category
 *
 * @since 1.0.0
 * @param string $category_slug Category slug to filter by.
 * @param int    $limit         Number of items to retrieve. Default 10.
 * @return WP_Query Query object containing portfolio items.
 */
function hog_scaffold_get_portfolio_by_category($category_slug, $limit = 10)
{
  return hog_scaffold_get_posts_by_taxonomy('portfolio', 'portfolio_category', $category_slug, array(
    'posts_per_page' => $limit,
    'orderby' => 'date',
    'order' => 'DESC',
  ));
}

/**
 * Get portfolio project status badge HTML
 *
 * @since 1.0.0
 * @param int $post_id Portfolio post ID.
 * @return string HTML for status badge.
 */
function hog_scaffold_get_portfolio_status_badge($post_id)
{
  $status = get_post_meta($post_id, 'project_status', true);

  if (!$status) {
    return '—';
  }

  $statuses = hog_scaffold_get_portfolio_status_options();
  $label = $statuses[$status] ?? ucfirst($status);

  return sprintf(
    '<span class="status-badge status-%s">%s</span>',
    esc_attr($status),
    esc_html($label)
  );
}