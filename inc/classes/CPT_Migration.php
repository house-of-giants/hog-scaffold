<?php
/**
 * CPT Migration Helper Class
 *
 * Provides utilities for migrating legacy custom post types to block-compatible versions.
 *
 * @package HoGScaffold\Classes
 */

namespace HoGScaffold\Classes;

/**
 * CPT Migration Class
 *
 * Helper class for converting legacy meta fields to blocks and updating CPT configurations.
 */
class CPT_Migration
{

  /**
   * Post type to migrate
   *
   * @var string
   */
  private $post_type;

  /**
   * Meta to block mapping
   *
   * @var array
   */
  private $meta_to_block_map;

  /**
   * Migration log
   *
   * @var array
   */
  private $migration_log = array();

  /**
   * Constructor
   *
   * @param string $post_type         Post type to migrate.
   * @param array  $meta_to_block_map Mapping of meta keys to block configurations.
   */
  public function __construct($post_type, $meta_to_block_map = array())
  {
    $this->post_type = $post_type;
    $this->meta_to_block_map = $meta_to_block_map;
  }

  /**
   * Migrate all posts of the specified type
   *
   * @param int $batch_size Number of posts to process per batch.
   * @return array Migration results.
   */
  public function migrate_all_posts($batch_size = 50)
  {
    $posts = get_posts(
      array(
        'post_type' => $this->post_type,
        'posts_per_page' => $batch_size,
        'post_status' => array('publish', 'draft', 'private'),
        'meta_query' => array(
          array(
            'key' => '_migrated_to_blocks',
            'compare' => 'NOT EXISTS',
          ),
        ),
      )
    );

    $results = array(
      'total_processed' => 0,
      'successful' => 0,
      'failed' => 0,
      'errors' => array(),
    );

    foreach ($posts as $post) {
      $result = $this->migrate_single_post($post->ID);
      $results['total_processed']++;

      if ($result['success']) {
        $results['successful']++;
      } else {
        $results['failed']++;
        $results['errors'][] = array(
          'post_id' => $post->ID,
          'error' => $result['error'],
        );
      }
    }

    return $results;
  }

  /**
   * Migrate a single post
   *
   * @param int $post_id Post ID to migrate.
   * @return array Migration result.
   */
  public function migrate_single_post($post_id)
  {
    $post = get_post($post_id);

    if (!$post || $post->post_type !== $this->post_type) {
      return array(
        'success' => false,
        'error' => 'Invalid post or post type mismatch',
      );
    }

    // Check if already migrated.
    if (get_post_meta($post_id, '_migrated_to_blocks', true)) {
      return array(
        'success' => false,
        'error' => 'Post already migrated',
      );
    }

    try {
      // Backup original content.
      $original_content = $post->post_content;
      update_post_meta($post_id, '_original_content_backup', $original_content);

      // Convert meta fields to blocks.
      $new_content = $this->convert_meta_to_blocks($post_id, $original_content);

      // Update post content.
      wp_update_post(
        array(
          'ID' => $post_id,
          'post_content' => $new_content,
        )
      );

      // Mark as migrated.
      update_post_meta($post_id, '_migrated_to_blocks', current_time('mysql'));

      $this->log_migration($post_id, 'success', 'Post migrated successfully');

      return array(
        'success' => true,
        'message' => 'Post migrated successfully',
      );

    } catch (Exception $e) {
      $this->log_migration($post_id, 'error', $e->getMessage());

      return array(
        'success' => false,
        'error' => $e->getMessage(),
      );
    }
  }

  /**
   * Convert meta fields to blocks
   *
   * @param int    $post_id         Post ID.
   * @param string $original_content Original post content.
   * @return string New content with blocks.
   */
  private function convert_meta_to_blocks($post_id, $original_content)
  {
    $blocks = array();

    // Add original content as first block if it exists.
    if (!empty(trim($original_content))) {
      $blocks[] = $original_content;
    }

    // Convert meta fields to blocks.
    foreach ($this->meta_to_block_map as $meta_key => $block_config) {
      $meta_value = get_post_meta($post_id, $meta_key, true);

      if (empty($meta_value)) {
        continue;
      }

      $block = $this->create_block_from_meta($meta_key, $meta_value, $block_config);
      if ($block) {
        $blocks[] = $block;
      }
    }

    return implode("\n\n", $blocks);
  }

  /**
   * Create a block from meta field
   *
   * @param string $meta_key     Meta field key.
   * @param mixed  $meta_value   Meta field value.
   * @param array  $block_config Block configuration.
   * @return string|false Block content or false on failure.
   */
  private function create_block_from_meta($meta_key, $meta_value, $block_config)
  {
    $block_type = $block_config['block_type'] ?? 'core/paragraph';
    $attributes = $block_config['attributes'] ?? array();

    switch ($block_type) {
      case 'core/paragraph':
        return sprintf(
          '<!-- wp:paragraph --><p>%s</p><!-- /wp:paragraph -->',
          esc_html($meta_value)
        );

      case 'core/heading':
        $level = $attributes['level'] ?? 2;
        return sprintf(
          '<!-- wp:heading {"level":%d} --><h%d>%s</h%d><!-- /wp:heading -->',
          $level,
          $level,
          esc_html($meta_value),
          $level
        );

      case 'core/list':
        if (is_array($meta_value)) {
          $items = array_map(
            function ($item) {
              return sprintf('<li>%s</li>', esc_html($item));
            },
            $meta_value
          );
          $list_items = implode('', $items);
        } else {
          // Assume comma-separated values.
          $items = explode(',', $meta_value);
          $items = array_map('trim', $items);
          $items = array_map(
            function ($item) {
              return sprintf('<li>%s</li>', esc_html($item));
            },
            $items
          );
          $list_items = implode('', $items);
        }

        return sprintf(
          '<!-- wp:list --><ul>%s</ul><!-- /wp:list -->',
          $list_items
        );

      case 'core/image':
        if (is_numeric($meta_value)) {
          // Attachment ID.
          $image_url = wp_get_attachment_url($meta_value);
          $image_alt = get_post_meta($meta_value, '_wp_attachment_image_alt', true);

          return sprintf(
            '<!-- wp:image {"id":%d} --><figure class="wp-block-image"><img src="%s" alt="%s" class="wp-image-%d"/></figure><!-- /wp:image -->',
            $meta_value,
            esc_url($image_url),
            esc_attr($image_alt),
            $meta_value
          );
        }
        break;

      case 'custom':
        // Handle custom block types.
        if (isset($block_config['callback']) && is_callable($block_config['callback'])) {
          return call_user_func($block_config['callback'], $meta_key, $meta_value, $attributes);
        }
        break;
    }

    return false;
  }

  /**
   * Rollback migration for a post
   *
   * @param int $post_id Post ID to rollback.
   * @return array Rollback result.
   */
  public function rollback_post($post_id)
  {
    $original_content = get_post_meta($post_id, '_original_content_backup', true);

    if (empty($original_content)) {
      return array(
        'success' => false,
        'error' => 'No backup content found',
      );
    }

    // Restore original content.
    wp_update_post(
      array(
        'ID' => $post_id,
        'post_content' => $original_content,
      )
    );

    // Remove migration markers.
    delete_post_meta($post_id, '_migrated_to_blocks');
    delete_post_meta($post_id, '_original_content_backup');

    $this->log_migration($post_id, 'rollback', 'Post rolled back successfully');

    return array(
      'success' => true,
      'message' => 'Post rolled back successfully',
    );
  }

  /**
   * Get migration status for all posts
   *
   * @return array Migration status.
   */
  public function get_migration_status()
  {
    $total_posts = wp_count_posts($this->post_type);
    $migrated_posts = get_posts(
      array(
        'post_type' => $this->post_type,
        'posts_per_page' => -1,
        'post_status' => array('publish', 'draft', 'private'),
        'meta_query' => array(
          array(
            'key' => '_migrated_to_blocks',
            'compare' => 'EXISTS',
          ),
        ),
        'fields' => 'ids',
      )
    );

    return array(
      'total_posts' => $total_posts->publish + $total_posts->draft + $total_posts->private,
      'migrated_posts' => count($migrated_posts),
      'pending_posts' => ($total_posts->publish + $total_posts->draft + $total_posts->private) - count($migrated_posts),
    );
  }

  /**
   * Log migration activity
   *
   * @param int    $post_id Post ID.
   * @param string $type    Log type (success, error, rollback).
   * @param string $message Log message.
   * @return void
   */
  private function log_migration($post_id, $type, $message)
  {
    $this->migration_log[] = array(
      'post_id' => $post_id,
      'type' => $type,
      'message' => $message,
      'timestamp' => current_time('mysql'),
    );

    // Optionally log to WordPress error log.
    if (defined('WP_DEBUG') && WP_DEBUG) {
      error_log(sprintf('CPT Migration [%s] Post %d: %s', strtoupper($type), $post_id, $message));
    }
  }

  /**
   * Get migration log
   *
   * @return array Migration log entries.
   */
  public function get_migration_log()
  {
    return $this->migration_log;
  }

  /**
   * Clear migration log
   *
   * @return void
   */
  public function clear_migration_log()
  {
    $this->migration_log = array();
  }

  /**
   * Add meta to block mapping
   *
   * @param string $meta_key     Meta field key.
   * @param array  $block_config Block configuration.
   * @return void
   */
  public function add_meta_mapping($meta_key, $block_config)
  {
    $this->meta_to_block_map[$meta_key] = $block_config;
  }

  /**
   * Remove meta to block mapping
   *
   * @param string $meta_key Meta field key.
   * @return void
   */
  public function remove_meta_mapping($meta_key)
  {
    unset($this->meta_to_block_map[$meta_key]);
  }

  /**
   * Get all meta mappings
   *
   * @return array Meta to block mappings.
   */
  public function get_meta_mappings()
  {
    return $this->meta_to_block_map;
  }
}