<?php
/**
 * Custom Post Types setup
 *
 * @package HoGScaffold\CPT
 */

namespace HoGScaffold\CPT;

use HoGScaffold\Classes\CPT_Helper;
use HoGScaffold\Classes\CPT_Migration;

/**
 * Set up custom post types
 *
 * @return void
 */
function setup()
{
  $n = function ($function) {
    return __NAMESPACE__ . "\\$function";
  };

  // Initialize CPT framework.
  add_action('after_setup_theme', $n('init_cpt_framework'));

  // Add admin menu for CPT management.
  add_action('admin_menu', $n('add_cpt_admin_menu'));

  // Handle AJAX requests for migration.
  add_action('wp_ajax_cpt_migrate_posts', $n('handle_migration_ajax'));

  // Add REST API endpoints for CPT management.
  add_action('rest_api_init', $n('register_rest_endpoints'));
}

/**
 * Initialize CPT framework
 *
 * @return void
 */
function init_cpt_framework()
{
  // Include CPT definitions.
  require_once HOG_SCAFFOLD_INC . 'cpt/init.php';

  // Include migration examples and utilities.
  require_once HOG_SCAFFOLD_INC . 'cpt/migration-examples.php';
}

/**
 * Add CPT management admin menu
 *
 * @return void
 */
function add_cpt_admin_menu()
{
  add_management_page(
    __('CPT Management', 'hog-scaffold'),
    __('CPT Management', 'hog-scaffold'),
    'manage_options',
    'cpt-management',
    __NAMESPACE__ . '\\render_cpt_admin_page'
  );
}

/**
 * Render CPT management admin page
 *
 * @return void
 */
function render_cpt_admin_page()
{
  ?>
  <div class="wrap">
    <h1><?php esc_html_e('Custom Post Type Management', 'hog-scaffold'); ?></h1>

    <div class="cpt-management-tabs">
      <nav class="nav-tab-wrapper">
        <a href="#overview" class="nav-tab nav-tab-active"><?php esc_html_e('Overview', 'hog-scaffold'); ?></a>
        <a href="#migration" class="nav-tab"><?php esc_html_e('Migration', 'hog-scaffold'); ?></a>
        <a href="#documentation" class="nav-tab"><?php esc_html_e('Documentation', 'hog-scaffold'); ?></a>
      </nav>

      <div id="overview" class="tab-content">
        <h2><?php esc_html_e('Registered Custom Post Types', 'hog-scaffold'); ?></h2>
        <?php render_cpt_overview(); ?>
      </div>

      <div id="migration" class="tab-content" style="display: none;">
        <h2><?php esc_html_e('Migration Tools', 'hog-scaffold'); ?></h2>
        <?php render_migration_tools(); ?>
      </div>

      <div id="documentation" class="tab-content" style="display: none;">
        <h2><?php esc_html_e('Framework Documentation', 'hog-scaffold'); ?></h2>
        <?php render_documentation(); ?>
      </div>
    </div>
  </div>

  <style>
    .cpt-management-tabs .tab-content {
      margin-top: 20px;
    }

    .cpt-overview-table {
      width: 100%;
      border-collapse: collapse;
    }

    .cpt-overview-table th,
    .cpt-overview-table td {
      padding: 10px;
      border: 1px solid #ddd;
      text-align: left;
    }

    .cpt-overview-table th {
      background-color: #f9f9f9;
    }

    .migration-status {
      padding: 10px;
      margin: 10px 0;
      border-left: 4px solid #0073aa;
      background-color: #f7f7f7;
    }
  </style>

  <script>
    jQuery(document).ready(function ($) {
      $('.nav-tab').on('click', function (e) {
        e.preventDefault();
        var target = $(this).attr('href');

        $('.nav-tab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');

        $('.tab-content').hide();
        $(target).show();
      });
    });
  </script>
  <?php
}

/**
 * Render CPT overview
 *
 * @return void
 */
function render_cpt_overview()
{
  $post_types = get_post_types(array('public' => true, '_builtin' => false), 'objects');

  if (empty($post_types)) {
    echo '<p>' . esc_html__('No custom post types registered.', 'hog-scaffold') . '</p>';
    return;
  }

  echo '<table class="cpt-overview-table">';
  echo '<thead>';
  echo '<tr>';
  echo '<th>' . esc_html__('Post Type', 'hog-scaffold') . '</th>';
  echo '<th>' . esc_html__('Label', 'hog-scaffold') . '</th>';
  echo '<th>' . esc_html__('Posts Count', 'hog-scaffold') . '</th>';
  echo '<th>' . esc_html__('REST API', 'hog-scaffold') . '</th>';
  echo '<th>' . esc_html__('Block Editor', 'hog-scaffold') . '</th>';
  echo '</tr>';
  echo '</thead>';
  echo '<tbody>';

  foreach ($post_types as $post_type) {
    $count = wp_count_posts($post_type->name);
    $total_posts = $count->publish + $count->draft + $count->private;

    echo '<tr>';
    echo '<td><code>' . esc_html($post_type->name) . '</code></td>';
    echo '<td>' . esc_html($post_type->labels->name) . '</td>';
    echo '<td>' . esc_html($total_posts) . '</td>';
    echo '<td>' . ($post_type->show_in_rest ? '✅' : '❌') . '</td>';
    echo '<td>' . (post_type_supports($post_type->name, 'editor') ? '✅' : '❌') . '</td>';
    echo '</tr>';
  }

  echo '</tbody>';
  echo '</table>';
}

/**
 * Render migration tools
 *
 * @return void
 */
function render_migration_tools()
{
  ?>
  <div class="migration-tools">
    <p>
      <?php esc_html_e('Use these tools to migrate legacy custom post types to block-compatible versions.', 'hog-scaffold'); ?>
    </p>

    <div class="migration-status">
      <h3><?php esc_html_e('Migration Status', 'hog-scaffold'); ?></h3>
      <p><?php esc_html_e('Migration tools will be available when legacy CPTs are detected.', 'hog-scaffold'); ?></p>
    </div>

    <h3><?php esc_html_e('Example Migration Usage', 'hog-scaffold'); ?></h3>
    <pre><code>
  // Example: Migrate a legacy 'product' CPT
  $migration = new CPT_Migration( 'product', array(
    'product_price' => array(
      'block_type' => 'core/paragraph',
      'attributes' => array()
    ),
    'product_features' => array(
      'block_type' => 'core/list',
      'attributes' => array()
    ),
    'product_image' => array(
      'block_type' => 'core/image',
      'attributes' => array()
    )
  ) );

  // Migrate all posts
  $results = $migration->migrate_all_posts();
      </code></pre>
  </div>
  <?php
}

/**
 * Render documentation
 *
 * @return void
 */
function render_documentation()
{
  ?>
  <div class="cpt-documentation">
    <h3><?php esc_html_e('Quick Start Guide', 'hog-scaffold'); ?></h3>

    <h4><?php esc_html_e('1. Creating a Basic CPT', 'hog-scaffold'); ?></h4>
    <pre><code>
  use HoGScaffold\Classes\CPT_Helper;

  $portfolio = new CPT_Helper(
    'portfolio',           // Post type key
    'Portfolio Item',      // Singular name
    'Portfolio Items',     // Plural name
    'portfolio',          // URL slug
    array(                // Additional options
      'menu_icon' => 'dashicons-portfolio',
      'supports'  => array( 'title', 'editor', 'thumbnail' )
    )
  );
      </code></pre>

    <h4><?php esc_html_e('2. Adding Meta Fields', 'hog-scaffold'); ?></h4>
    <pre><code>
  $portfolio
    ->add_meta_field( 'client_name', array(
      'type'        => 'string',
      'description' => 'Client name for this project'
    ), 'text' )
    ->add_meta_field( 'project_date', array(
      'type'        => 'string',
      'description' => 'Project completion date'
    ), 'date' )
    ->add_meta_field( 'technologies', array(
      'type'        => 'string',
      'description' => 'Technologies used (comma-separated)'
    ), 'textarea' );
      </code></pre>

    <h4><?php esc_html_e('3. Adding Taxonomies', 'hog-scaffold'); ?></h4>
    <pre><code>
  $portfolio->add_taxonomy(
    'portfolio_category',     // Taxonomy key
    'Portfolio Category',     // Singular name
    'Portfolio Categories',   // Plural name
    'portfolio-category'      // URL slug
  );
      </code></pre>

    <h4><?php esc_html_e('4. Setting Block Templates', 'hog-scaffold'); ?></h4>
    <pre><code>
  $portfolio->set_block_template( array(
    array( 'core/heading', array( 'level' => 2 ) ),
    array( 'core/paragraph', array( 'placeholder' => 'Project description...' ) ),
    array( 'core/gallery' ),
    array( 'core/columns', array(), array(
      array( 'core/column', array(), array(
        array( 'core/heading', array( 'level' => 3, 'content' => 'Project Details' ) ),
        array( 'core/list' )
      ) ),
      array( 'core/column', array(), array(
        array( 'core/heading', array( 'level' => 3, 'content' => 'Technologies' ) ),
        array( 'core/paragraph' )
      ) )
    ) )
  ) );
      </code></pre>

    <h4><?php esc_html_e('5. Adding Admin Metaboxes', 'hog-scaffold'); ?></h4>
    <pre><code>
  $portfolio->add_admin_metabox();
      </code></pre>

    <p><strong><?php esc_html_e('Note:', 'hog-scaffold'); ?></strong>
      <?php esc_html_e('All CPT definitions should be placed in the inc/cpt/init.php file.', 'hog-scaffold'); ?></p>
  </div>
  <?php
}

/**
 * Handle migration AJAX requests
 *
 * @return void
 */
function handle_migration_ajax()
{
  check_ajax_referer('cpt_migration_nonce', 'nonce');

  if (!current_user_can('manage_options')) {
    wp_die(__('Insufficient permissions', 'hog-scaffold'));
  }

  $post_type = sanitize_text_field($_POST['post_type'] ?? '');
  $action_type = sanitize_text_field($_POST['action_type'] ?? '');

  // Handle different migration actions here.
  wp_send_json_success(array('message' => 'Migration functionality ready for implementation'));
}

/**
 * Register REST API endpoints
 *
 * @return void
 */
function register_rest_endpoints()
{
  register_rest_route(
    'hog-scaffold/v1',
    '/cpt/(?P<post_type>[a-zA-Z0-9_-]+)/migration-status',
    array(
      'methods' => 'GET',
      'callback' => __NAMESPACE__ . '\\get_migration_status_rest',
      'permission_callback' => function () {
        return current_user_can('manage_options');
      },
    )
  );
}

/**
 * Get migration status via REST API
 *
 * @param \WP_REST_Request $request Request object.
 * @return \WP_REST_Response
 */
function get_migration_status_rest($request)
{
  $post_type = $request->get_param('post_type');

  if (!post_type_exists($post_type)) {
    return new \WP_Error('invalid_post_type', 'Post type does not exist', array('status' => 404));
  }

  $migration = new CPT_Migration($post_type);
  $status = $migration->get_migration_status();

  return rest_ensure_response($status);
}