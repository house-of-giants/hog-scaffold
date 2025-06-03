<?php
/**
 * Cache Management Utility
 * 
 * A standalone script for managing various cache types in WordPress/WPEngine.
 * Can be run via WP-CLI or included in other scripts.
 * 
 * Usage:
 * - wp eval-file scripts/cache-management.php
 * - php scripts/cache-management.php (if WordPress constants are defined)
 * 
 * @package HoG_Scaffold
 */

// Check if we're in WordPress context
if (!defined('ABSPATH')) {
  // Try to load WordPress if running standalone
  $wp_config_path = dirname(dirname(__FILE__)) . '/wp-config.php';
  if (file_exists($wp_config_path)) {
    require_once $wp_config_path;
  } else {
    die("WordPress not found. Run this script via WP-CLI: wp eval-file scripts/cache-management.php\n");
  }
}

/**
 * Cache Management Class
 */
class Cache_Management_Utility
{

  /**
   * Available cache types
   */
  const CACHE_TYPES = [
    'object' => 'Object Cache (Redis/Memcached)',
    'page' => 'Page Cache',
    'transients' => 'WordPress Transients',
    'opcache' => 'OPcache (PHP)',
    'cdn' => 'CDN Cache',
  ];

  /**
   * Run cache management operations
   */
  public static function run()
  {
    if (defined('WP_CLI') && WP_CLI) {
      self::wp_cli_interface();
    } else {
      self::web_interface();
    }
  }

  /**
   * WP-CLI interface
   */
  private static function wp_cli_interface()
  {
    $args = $_SERVER['argv'] ?? [];
    $operation = $args[1] ?? 'status';

    switch ($operation) {
      case 'clear':
        $type = $args[2] ?? 'all';
        self::clear_cache($type);
        break;
      case 'warm':
        self::warm_cache();
        break;
      case 'status':
      default:
        self::show_cache_status();
        break;
    }
  }

  /**
   * Web interface (for admin pages)
   */
  private static function web_interface()
  {
    if (is_admin() && current_user_can('manage_options')) {
      $action = $_POST['cache_action'] ?? $_GET['cache_action'] ?? 'status';
      $type = $_POST['cache_type'] ?? $_GET['cache_type'] ?? 'all';

      switch ($action) {
        case 'clear':
          self::clear_cache($type);
          wp_redirect(admin_url('admin.php?page=cache-management&message=cleared'));
          exit;
        case 'warm':
          self::warm_cache();
          wp_redirect(admin_url('admin.php?page=cache-management&message=warmed'));
          exit;
        default:
          self::show_cache_status();
          break;
      }
    }
  }

  /**
   * Clear cache by type
   * 
   * @param string $type Cache type to clear
   */
  public static function clear_cache($type = 'all')
  {
    $cleared = [];
    $errors = [];

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

    if ($type === 'all' || $type === 'cdn') {
      if (self::clear_cdn_cache()) {
        $cleared[] = 'cdn';
      } else {
        $errors[] = 'cdn';
      }
    }

    // Output results
    if (!empty($cleared)) {
      self::log_message("✅ Cleared cache types: " . implode(', ', $cleared));
    }

    if (!empty($errors)) {
      self::log_message("❌ Failed to clear cache types: " . implode(', ', $errors));
    }

    // Fire action for other plugins/themes to hook into
    do_action('cache_management_cleared', $type, $cleared, $errors);
  }

  /**
   * Clear object cache
   */
  private static function clear_object_cache()
  {
    try {
      wp_cache_flush();

      // WPEngine specific object cache clearing
      if (class_exists('WpeCommon')) {
        if (method_exists('WpeCommon', 'purge_memcached')) {
          WpeCommon::purge_memcached();
        }
      }

      return true;
    } catch (Exception $e) {
      self::log_message("Object cache error: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Clear page cache
   */
  private static function clear_page_cache()
  {
    try {
      // WPEngine page cache
      if (class_exists('WpeCommon')) {
        if (method_exists('WpeCommon', 'clear_maxcdn_cache')) {
          WpeCommon::clear_maxcdn_cache();
        }
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

      return true;
    } catch (Exception $e) {
      self::log_message("Page cache error: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Clear WordPress transients
   */
  private static function clear_transients()
  {
    global $wpdb;

    try {
      // Clear all transients
      $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%'");
      $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_site_transient_%'");

      return true;
    } catch (Exception $e) {
      self::log_message("Transients error: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Clear OPcache
   */
  private static function clear_opcache()
  {
    try {
      if (function_exists('opcache_reset')) {
        opcache_reset();
        return true;
      }
      return false;
    } catch (Exception $e) {
      self::log_message("OPcache error: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Clear CDN cache
   */
  private static function clear_cdn_cache()
  {
    // This is environment-specific and should be implemented based on your CDN
    // Examples: CloudFlare, MaxCDN, CloudFront, etc.

    $cdn_cleared = false;

    // CloudFlare example (requires API credentials)
    if (defined('CLOUDFLARE_API_KEY') && defined('CLOUDFLARE_ZONE_ID')) {
      $cdn_cleared = self::clear_cloudflare_cache();
    }

    // Allow other plugins to handle CDN clearing
    $cdn_cleared = apply_filters('cache_management_clear_cdn', $cdn_cleared);

    return $cdn_cleared;
  }

  /**
   * Example CloudFlare cache clearing
   */
  private static function clear_cloudflare_cache()
  {
    // This is a placeholder - implement based on your CloudFlare setup
    return false;
  }

  /**
   * Warm up cache
   */
  public static function warm_cache()
  {
    $urls = self::get_urls_to_warm();
    $warmed = 0;
    $errors = 0;

    foreach ($urls as $url) {
      if (self::warm_url($url)) {
        $warmed++;
      } else {
        $errors++;
      }
    }

    self::log_message("🔥 Cache warmup complete: {$warmed} URLs warmed, {$errors} errors");

    // Fire action
    do_action('cache_management_warmed', $urls, $warmed, $errors);
  }

  /**
   * Get URLs to warm up
   */
  private static function get_urls_to_warm()
  {
    $urls = [
      home_url('/'),
    ];

    // Add recent posts
    $recent_posts = get_posts([
      'numberposts' => 10,
      'post_status' => 'publish'
    ]);

    foreach ($recent_posts as $post) {
      $urls[] = get_permalink($post->ID);
    }

    // Add category pages
    $categories = get_categories(['number' => 5]);
    foreach ($categories as $category) {
      $urls[] = get_category_link($category->term_id);
    }

    // Allow filtering
    return apply_filters('cache_management_warmup_urls', $urls);
  }

  /**
   * Warm up a specific URL
   */
  private static function warm_url($url)
  {
    try {
      $response = wp_remote_get($url, [
        'timeout' => 30,
        'user-agent' => 'Cache Warmup Bot'
      ]);

      return !is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200;
    } catch (Exception $e) {
      return false;
    }
  }

  /**
   * Show cache status
   */
  public static function show_cache_status()
  {
    $status = [];

    // Object cache status
    $status['object'] = self::get_object_cache_status();

    // Page cache status
    $status['page'] = self::get_page_cache_status();

    // OPcache status
    $status['opcache'] = self::get_opcache_status();

    // Output status
    self::log_message("📊 Cache Status Report:");
    foreach ($status as $type => $info) {
      $emoji = $info['enabled'] ? '✅' : '❌';
      self::log_message("{$emoji} {$info['name']}: {$info['status']}");
    }

    return $status;
  }

  /**
   * Get object cache status
   */
  private static function get_object_cache_status()
  {
    return [
      'name' => 'Object Cache',
      'enabled' => wp_using_ext_object_cache(),
      'status' => wp_using_ext_object_cache() ? 'Active' : 'Not Active'
    ];
  }

  /**
   * Get page cache status
   */
  private static function get_page_cache_status()
  {
    $active_plugins = [];

    if (class_exists('WpeCommon')) {
      $active_plugins[] = 'WPEngine';
    }
    if (function_exists('wp_cache_clear_cache')) {
      $active_plugins[] = 'WP Super Cache';
    }
    if (function_exists('w3tc_flush_all')) {
      $active_plugins[] = 'W3 Total Cache';
    }
    if (function_exists('rocket_clean_domain')) {
      $active_plugins[] = 'WP Rocket';
    }

    return [
      'name' => 'Page Cache',
      'enabled' => !empty($active_plugins),
      'status' => !empty($active_plugins) ? implode(', ', $active_plugins) : 'No page cache detected'
    ];
  }

  /**
   * Get OPcache status
   */
  private static function get_opcache_status()
  {
    $enabled = function_exists('opcache_get_status') && opcache_get_status();

    return [
      'name' => 'OPcache',
      'enabled' => $enabled,
      'status' => $enabled ? 'Enabled' : 'Disabled'
    ];
  }

  /**
   * Log message
   */
  private static function log_message($message)
  {
    if (defined('WP_CLI') && WP_CLI) {
      WP_CLI::log($message);
    } else {
      error_log($message);
      if (is_admin()) {
        echo "<p>" . esc_html($message) . "</p>";
      }
    }
  }
}

// Run if called directly
if (!defined('WP_CLI') || !WP_CLI) {
  Cache_Management_Utility::run();
}