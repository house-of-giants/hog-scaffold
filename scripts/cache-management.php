<?php
/**
 * Simple Cache Management Utility
 *
 * A lightweight cache management script for WordPress themes.
 * Supports common caching plugins and hosting providers.
 *
 * Usage:
 * - wp eval-file scripts/cache-management.php clear [type]
 * - wp eval-file scripts/cache-management.php status
 *
 * @package Your_Theme_Name
 */

// Ensure WordPress context
if (!defined('ABSPATH')) {
  $wp_config_path = dirname(__DIR__) . '/wp-config.php';
  if (file_exists($wp_config_path)) {
    require_once $wp_config_path;
  } else {
    die("WordPress not found. Run via WP-CLI: wp eval-file scripts/cache-management.php\n");
  }
}

/**
 * Simple Cache Management Class
 */
class Simple_Cache_Management
{

  /**
   * Available cache types
   */
  const CACHE_TYPES = array(
    'object' => 'Object Cache',
    'page' => 'Page Cache',
    'transients' => 'WordPress Transients',
    'opcache' => 'PHP OPcache',
  );

  /**
   * Initialize cache management
   */
  public static function init()
  {
    $args = $_SERVER['argv'] ?? array();
    $action = $args[1] ?? 'status';
    $type = $args[2] ?? 'all';

    switch ($action) {
      case 'clear':
        self::clear_cache($type);
        break;
      case 'status':
      default:
        self::show_status();
        break;
    }
  }

  /**
   * Clear cache by type
   *
   * @param string $type Cache type to clear ('all', 'object', 'page', 'transients', 'opcache')
   */
  public static function clear_cache($type = 'all')
  {
    $cleared = array();
    $errors = array();

    if ($type === 'all' || $type === 'object') {
      if (self::clear_object_cache()) {
        $cleared[] = 'object';
      } else {
        $errors[] = 'object';
      }
    }

    if ($type === 'all' || $type === 'page') {
      if (self::clear_page_cache()) {
        $cleared[] = 'page';
      } else {
        $errors[] = 'page';
      }
    }

    if ($type === 'all' || $type === 'transients') {
      if (self::clear_transients()) {
        $cleared[] = 'transients';
      } else {
        $errors[] = 'transients';
      }
    }

    if ($type === 'all' || $type === 'opcache') {
      if (self::clear_opcache()) {
        $cleared[] = 'opcache';
      } else {
        $errors[] = 'opcache';
      }
    }

    // Output results
    if (!empty($cleared)) {
      self::log('✅ Cleared: ' . implode(', ', $cleared));
    }

    if (!empty($errors)) {
      self::log('❌ Errors: ' . implode(', ', $errors));
    }

    // Hook for additional functionality
    do_action('simple_cache_cleared', $type, $cleared, $errors);
  }

  /**
   * Clear object cache
   *
   * @return bool Success status
   */
  private static function clear_object_cache()
  {
    try {
      wp_cache_flush();

      // WP Engine specific
      if (class_exists('WpeCommon') && method_exists('WpeCommon', 'purge_memcached')) {
        WpeCommon::purge_memcached();
      }

      return true;
    } catch (Exception $e) {
      self::log('Object cache error: ' . $e->getMessage());
      return false;
    }
  }

  /**
   * Clear page cache
   *
   * @return bool Success status
   */
  private static function clear_page_cache()
  {
    try {
      // WP Engine
      if (class_exists('WpeCommon') && method_exists('WpeCommon', 'clear_maxcdn_cache')) {
        WpeCommon::clear_maxcdn_cache();
      }

      // WP Super Cache
      if (function_exists('wp_cache_clear_cache')) {
        wp_cache_clear_cache();
      }

      // W3 Total Cache
      if (function_exists('w3tc_flush_all')) {
        w3tc_flush_all();
      }

      // WP Rocket
      if (function_exists('rocket_clean_domain')) {
        rocket_clean_domain();
      }

      // LiteSpeed Cache
      if (class_exists('LiteSpeed_Cache_API') && method_exists('LiteSpeed_Cache_API', 'purge_all')) {
        LiteSpeed_Cache_API::purge_all();
      }

      return true;
    } catch (Exception $e) {
      self::log('Page cache error: ' . $e->getMessage());
      return false;
    }
  }

  /**
   * Clear WordPress transients
   *
   * @return bool Success status
   */
  private static function clear_transients()
  {
    global $wpdb;

    try {
      $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%'");
      $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_site_transient_%'");
      return true;
    } catch (Exception $e) {
      self::log('Transients error: ' . $e->getMessage());
      return false;
    }
  }

  /**
   * Clear PHP OPcache
   *
   * @return bool Success status
   */
  private static function clear_opcache()
  {
    try {
      if (function_exists('opcache_reset')) {
        return opcache_reset();
      }
      return false;
    } catch (Exception $e) {
      self::log('OPcache error: ' . $e->getMessage());
      return false;
    }
  }

  /**
   * Show cache status
   */
  public static function show_status()
  {
    self::log('=== Cache Status ===');

    foreach (self::CACHE_TYPES as $key => $label) {
      $status = self::get_cache_status($key);
      self::log("{$label}: {$status}");
    }
  }

  /**
   * Get cache status for a specific type
   *
   * @param string $type Cache type
   * @return string Status description
   */
  private static function get_cache_status($type)
  {
    switch ($type) {
      case 'object':
        return wp_using_ext_object_cache() ? '✅ Active' : '❌ Not Active';
      case 'page':
        if (class_exists('WpeCommon')) {
          return '✅ WP Engine Cache';
        } elseif (function_exists('wp_cache_clear_cache')) {
          return '✅ WP Super Cache';
        } elseif (function_exists('w3tc_flush_all')) {
          return '✅ W3 Total Cache';
        } elseif (function_exists('rocket_clean_domain')) {
          return '✅ WP Rocket';
        } elseif (class_exists('LiteSpeed_Cache_API')) {
          return '✅ LiteSpeed Cache';
        }
        return '❌ No page cache detected';
      case 'opcache':
        return function_exists('opcache_get_status') && opcache_get_status() ? '✅ Active' : '❌ Not Active';
      case 'transients':
        global $wpdb;
        $count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->options} WHERE option_name LIKE '_transient_%'");
        return $count ? "✅ {$count} transients" : '✅ No transients';
      default:
        return '❓ Unknown';
    }
  }

  /**
   * Simple logging function
   *
   * @param string $message Message to log
   */
  private static function log($message)
  {
    if (defined('WP_CLI') && WP_CLI) {
      WP_CLI::line($message);
    } else {
      echo esc_html($message) . "\n";
    }
  }
}

// Run if called directly
if (!defined('WP_CLI') || (defined('WP_CLI') && WP_CLI)) {
  Simple_Cache_Management::init();
}
