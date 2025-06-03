<?php
/**
 * CPT Migration Examples and Utilities
 *
 * Practical examples of how to use the CPT_Migration class for common scenarios.
 *
 * @package HoGScaffold\CPT
 */

// Prevent direct access.
if (!defined('ABSPATH')) {
  exit;
}

use HoGScaffold\Classes\CPT_Migration;

/**
 * Example: Migrate a legacy "Projects" CPT to the new Portfolio CPT
 *
 * This example shows how to migrate from an old custom post type structure
 * to the new block-compatible Portfolio CPT.
 */
function hog_scaffold_migrate_legacy_projects_example()
{
  // Define the mapping from old meta fields to blocks
  $meta_to_block_mapping = array(
    // Simple text fields become paragraphs
    'project_description' => array(
      'block_type' => 'core/paragraph',
      'attributes' => array(),
    ),

    // Project features become a list
    'project_features' => array(
      'block_type' => 'core/list',
      'attributes' => array(),
    ),

    // Project gallery images
    'project_gallery' => array(
      'block_type' => 'core/gallery',
      'attributes' => array(
        'linkTo' => 'media',
      ),
    ),

    // Client testimonial becomes a quote
    'client_testimonial' => array(
      'block_type' => 'core/quote',
      'attributes' => array(),
    ),

    // Custom block for project details
    'project_details' => array(
      'block_type' => 'custom',
      'callback' => 'hog_scaffold_create_project_details_block',
    ),
  );

  // Create migration instance
  $migration = new CPT_Migration('legacy_projects', $meta_to_block_mapping);

  // Run migration in batches
  $results = $migration->migrate_all_posts(25);

  return $results;
}

/**
 * Custom callback for creating project details block
 *
 * @param string $meta_key   Meta field key.
 * @param mixed  $meta_value Meta field value.
 * @param array  $attributes Block attributes.
 * @return string Block content.
 */
function hog_scaffold_create_project_details_block($meta_key, $meta_value, $attributes)
{
  if (is_array($meta_value)) {
    $details = '';
    foreach ($meta_value as $key => $value) {
      $details .= sprintf('<li><strong>%s:</strong> %s</li>', esc_html(ucfirst($key)), esc_html($value));
    }

    return sprintf(
      '<!-- wp:heading {"level":3} --><h3>%s</h3><!-- /wp:heading --><!-- wp:list --><ul>%s</ul><!-- /wp:list -->',
      __('Project Details', 'hog-scaffold'),
      $details
    );
  }

  return sprintf(
    '<!-- wp:paragraph --><p>%s</p><!-- /wp:paragraph -->',
    esc_html($meta_value)
  );
}

/**
 * Migration utility: Convert ACF fields to blocks
 *
 * Example for migrating Advanced Custom Fields to block content.
 */
function hog_scaffold_migrate_acf_to_blocks_example()
{
  $acf_mapping = array(
    // ACF text field to paragraph
    'acf_project_overview' => array(
      'block_type' => 'core/paragraph',
      'attributes' => array(),
    ),

    // ACF image field to image block
    'acf_hero_image' => array(
      'block_type' => 'core/image',
      'attributes' => array(
        'align' => 'center',
      ),
    ),

    // ACF repeater field to columns
    'acf_project_stats' => array(
      'block_type' => 'custom',
      'callback' => 'hog_scaffold_create_stats_columns',
    ),

    // ACF WYSIWYG field (already in blocks format)
    'acf_project_content' => array(
      'block_type' => 'custom',
      'callback' => 'hog_scaffold_preserve_existing_blocks',
    ),
  );

  $migration = new CPT_Migration('portfolio', $acf_mapping);
  return $migration->migrate_all_posts(20);
}

/**
 * Create stats columns from ACF repeater data
 *
 * @param string $meta_key   Meta field key.
 * @param mixed  $meta_value Meta field value.
 * @param array  $attributes Block attributes.
 * @return string Block content.
 */
function hog_scaffold_create_stats_columns($meta_key, $meta_value, $attributes)
{
  if (!is_array($meta_value) || empty($meta_value)) {
    return '';
  }

  $columns = '';
  foreach ($meta_value as $stat) {
    $columns .= sprintf(
      '<!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":4,"textAlign":"center"} --><h4 class="has-text-align-center">%s</h4><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">%s</p><!-- /wp:paragraph --></div><!-- /wp:column -->',
      esc_html($stat['number'] ?? ''),
      esc_html($stat['label'] ?? '')
    );
  }

  return sprintf(
    '<!-- wp:columns --><div class="wp-block-columns">%s</div><!-- /wp:columns -->',
    $columns
  );
}

/**
 * Preserve existing block content
 *
 * @param string $meta_key   Meta field key.
 * @param mixed  $meta_value Meta field value.
 * @param array  $attributes Block attributes.
 * @return string Block content.
 */
function hog_scaffold_preserve_existing_blocks($meta_key, $meta_value, $attributes)
{
  // If content already contains block markup, return as-is
  if (strpos($meta_value, '<!-- wp:') !== false) {
    return $meta_value;
  }

  // Otherwise, wrap in paragraph block
  return sprintf(
    '<!-- wp:paragraph --><p>%s</p><!-- /wp:paragraph -->',
    wp_kses_post($meta_value)
  );
}

/**
 * Batch migration utility with progress tracking
 *
 * @param string $post_type Post type to migrate.
 * @param array  $mapping   Meta to block mapping.
 * @param int    $batch_size Batch size for processing.
 * @return array Migration results with progress.
 */
function hog_scaffold_batch_migrate_with_progress($post_type, $mapping, $batch_size = 25)
{
  $migration = new CPT_Migration($post_type, $mapping);

  // Get total count for progress tracking
  $total_posts = wp_count_posts($post_type);
  $total_count = $total_posts->publish + $total_posts->draft + $total_posts->private;

  $overall_results = array(
    'total_posts' => $total_count,
    'batches_run' => 0,
    'total_processed' => 0,
    'total_successful' => 0,
    'total_failed' => 0,
    'errors' => array(),
    'start_time' => current_time('mysql'),
  );

  // Process in batches until no more posts to migrate
  do {
    $batch_results = $migration->migrate_all_posts($batch_size);

    $overall_results['batches_run']++;
    $overall_results['total_processed'] += $batch_results['total_processed'];
    $overall_results['total_successful'] += $batch_results['successful'];
    $overall_results['total_failed'] += $batch_results['failed'];
    $overall_results['errors'] = array_merge($overall_results['errors'], $batch_results['errors']);

    // Break if no posts were processed (all done)
    if ($batch_results['total_processed'] === 0) {
      break;
    }

    // Optional: Add delay between batches to prevent timeouts
    if ($batch_results['total_processed'] === $batch_size) {
      sleep(1);
    }

  } while ($batch_results['total_processed'] > 0);

  $overall_results['end_time'] = current_time('mysql');
  $overall_results['duration'] = strtotime($overall_results['end_time']) - strtotime($overall_results['start_time']);

  return $overall_results;
}

/**
 * Migration rollback utility
 *
 * @param string $post_type Post type to rollback.
 * @param array  $post_ids  Specific post IDs to rollback (optional).
 * @return array Rollback results.
 */
function hog_scaffold_rollback_migration($post_type, $post_ids = array())
{
  $migration = new CPT_Migration($post_type);

  if (empty($post_ids)) {
    // Get all migrated posts
    $migrated_posts = get_posts(array(
      'post_type' => $post_type,
      'posts_per_page' => -1,
      'post_status' => array('publish', 'draft', 'private'),
      'meta_query' => array(
        array(
          'key' => '_migrated_to_blocks',
          'compare' => 'EXISTS',
        ),
      ),
      'fields' => 'ids',
    ));
    $post_ids = $migrated_posts;
  }

  $results = array(
    'total_posts' => count($post_ids),
    'successful' => 0,
    'failed' => 0,
    'errors' => array(),
  );

  foreach ($post_ids as $post_id) {
    $result = $migration->rollback_post($post_id);

    if ($result['success']) {
      $results['successful']++;
    } else {
      $results['failed']++;
      $results['errors'][] = array(
        'post_id' => $post_id,
        'error' => $result['error'],
      );
    }
  }

  return $results;
}

/**
 * Migration validation utility
 *
 * Check if migration was successful by comparing content.
 *
 * @param string $post_type Post type to validate.
 * @return array Validation results.
 */
function hog_scaffold_validate_migration($post_type)
{
  $migrated_posts = get_posts(array(
    'post_type' => $post_type,
    'posts_per_page' => -1,
    'post_status' => array('publish', 'draft', 'private'),
    'meta_query' => array(
      array(
        'key' => '_migrated_to_blocks',
        'compare' => 'EXISTS',
      ),
    ),
  ));

  $validation_results = array(
    'total_migrated' => count($migrated_posts),
    'valid_migrations' => 0,
    'invalid_migrations' => 0,
    'issues' => array(),
  );

  foreach ($migrated_posts as $post) {
    $current_content = $post->post_content;
    $backup_content = get_post_meta($post->ID, '_original_content_backup', true);

    // Check if post has block content
    $has_blocks = strpos($current_content, '<!-- wp:') !== false;

    // Check if backup exists
    $has_backup = !empty($backup_content);

    if ($has_blocks && $has_backup) {
      $validation_results['valid_migrations']++;
    } else {
      $validation_results['invalid_migrations']++;
      $validation_results['issues'][] = array(
        'post_id' => $post->ID,
        'post_title' => $post->post_title,
        'has_blocks' => $has_blocks,
        'has_backup' => $has_backup,
      );
    }
  }

  return $validation_results;
}

/**
 * WP-CLI command for running migrations
 *
 * Example usage: wp hog-scaffold migrate-cpt portfolio --batch-size=25
 */
if (defined('WP_CLI') && WP_CLI) {
  /**
   * Migrate custom post type to block format.
   *
   * ## OPTIONS
   *
   * <post_type>
   * : The post type to migrate.
   *
   * [--batch-size=<size>]
   * : Number of posts to process per batch.
   * ---
   * default: 25
   * ---
   *
   * [--dry-run]
   * : Show what would be migrated without making changes.
   *
   * ## EXAMPLES
   *
   *     wp hog-scaffold migrate-cpt portfolio --batch-size=50
   *     wp hog-scaffold migrate-cpt projects --dry-run
   */
  WP_CLI::add_command('hog-scaffold migrate-cpt', function ($args, $assoc_args) {
    $post_type = $args[0];
    $batch_size = $assoc_args['batch-size'] ?? 25;
    $dry_run = isset($assoc_args['dry-run']);

    if (!post_type_exists($post_type)) {
      WP_CLI::error("Post type '{$post_type}' does not exist.");
    }

    if ($dry_run) {
      $migration = new CPT_Migration($post_type);
      $status = $migration->get_migration_status();

      WP_CLI::line("Migration Status for '{$post_type}':");
      WP_CLI::line("Total posts: {$status['total_posts']}");
      WP_CLI::line("Already migrated: {$status['migrated_posts']}");
      WP_CLI::line("Pending migration: {$status['pending_posts']}");

      return;
    }

    WP_CLI::line("Starting migration for post type: {$post_type}");

    // Use default mapping - in real scenarios, this would be customized
    $results = hog_scaffold_batch_migrate_with_progress($post_type, array(), $batch_size);

    WP_CLI::success("Migration completed!");
    WP_CLI::line("Total processed: {$results['total_processed']}");
    WP_CLI::line("Successful: {$results['total_successful']}");
    WP_CLI::line("Failed: {$results['total_failed']}");
    WP_CLI::line("Duration: {$results['duration']} seconds");

    if (!empty($results['errors'])) {
      WP_CLI::warning("Some posts failed to migrate. Check the errors:");
      foreach ($results['errors'] as $error) {
        WP_CLI::line("Post {$error['post_id']}: {$error['error']}");
      }
    }
  });
}