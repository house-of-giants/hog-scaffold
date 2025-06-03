<?php
/**
 * WPEngine Configuration
 * 
 * WPEngine-specific settings for cache headers, object caching, and CDN configuration.
 * 
 * @package HoG_Scaffold
 */

// Prevent direct access
if (!defined('ABSPATH')) {
  exit;
}

/**
 * WPEngine Cache Configuration
 */
class WPEngine_Config
{

  /**
   * Initialize WPEngine configurations
   */
  public static function init()
  {
    add_action('init', [__CLASS__, 'setup_cache_headers']);
    add_action('wp_enqueue_scripts', [__CLASS__, 'setup_cdn_assets']);
    add_action('admin_init', [__CLASS__, 'setup_object_cache']);
    add_filter('wp_headers', [__CLASS__, 'add_security_headers']);
  }

  /**
   * Set up cache headers for different content types
   */
  public static function setup_cache_headers()
  {
    // Don't cache admin pages
    if (is_admin()) {
      header('Cache-Control: no-cache, must-revalidate, max-age=0');
      return;
    }

    // Set cache headers based on content type
    if (is_front_page()) {
      // Cache front page for 1 hour
      header('Cache-Control: public, max-age=3600');
    } elseif (is_singular()) {
      // Cache posts/pages for 24 hours
      header('Cache-Control: public, max-age=86400');
    } elseif (is_archive() || is_category() || is_tag()) {
      // Cache archive pages for 6 hours
      header('Cache-Control: public, max-age=21600');
    } else {
      // Default cache for 1 hour
      header('Cache-Control: public, max-age=3600');
    }

    // Add ETags for better caching
    if (!headers_sent()) {
      $etag = md5(get_the_modified_time('U') . get_permalink());
      header("ETag: \"$etag\"");

      // Check if client has cached version
      if (
        isset($_SERVER['HTTP_IF_NONE_MATCH']) &&
        trim($_SERVER['HTTP_IF_NONE_MATCH'], '"') === $etag
      ) {
        header('HTTP/1.1 304 Not Modified');
        exit;
      }
    }
  }

  /**
   * Configure CDN for static assets
   */
  public static function setup_cdn_assets()
  {
    // Only apply in production
    if (defined('WP_DEBUG') && WP_DEBUG) {
      return;
    }

    // Check if CDN URL is defined
    if (!defined('CDN_URL') || !CDN_URL) {
      return;
    }

    // Replace asset URLs with CDN URLs
    add_filter('stylesheet_uri', [__CLASS__, 'cdn_url_filter']);
    add_filter('script_loader_src', [__CLASS__, 'cdn_url_filter']);
    add_filter('wp_get_attachment_url', [__CLASS__, 'cdn_url_filter']);
  }

  /**
   * Filter URLs to use CDN
   * 
   * @param string $url Original URL
   * @return string CDN URL or original URL
   */
  public static function cdn_url_filter($url)
  {
    if (!defined('CDN_URL') || !CDN_URL) {
      return $url;
    }

    $site_url = get_site_url();
    $cdn_url = rtrim(CDN_URL, '/');

    // Only replace URLs from this site
    if (strpos($url, $site_url) === 0) {
      $url = str_replace($site_url, $cdn_url, $url);
    }

    return $url;
  }

  /**
   * Configure object caching
   */
  public static function setup_object_cache()
  {
    // WPEngine has Redis object caching built-in
    // This function can be used for additional cache configurations

    // Set up cache groups that shouldn't be cached in Redis
    wp_cache_add_non_persistent_groups([
      'comment',
      'counts',
      'plugins'
    ]);

    // Increase cache timeouts for expensive queries
    add_filter('posts_clauses', [__CLASS__, 'extend_cache_timeout'], 10, 2);
  }

  /**
   * Extend cache timeout for expensive queries
   * 
   * @param array $clauses SQL clauses
   * @param WP_Query $query The WP_Query instance
   * @return array Modified clauses
   */
  public static function extend_cache_timeout($clauses, $query)
  {
    // For archive pages with many posts, extend cache timeout
    if ($query->is_archive() && !$query->is_admin()) {
      $query->query_vars['cache_timeout'] = HOUR_IN_SECONDS * 6;
    }

    return $clauses;
  }

  /**
   * Add security headers
   * 
   * @param array $headers Current headers
   * @return array Modified headers
   */
  public static function add_security_headers($headers)
  {
    // Add security headers for production
    if (!defined('WP_DEBUG') || !WP_DEBUG) {
      $headers['X-Frame-Options'] = 'SAMEORIGIN';
      $headers['X-Content-Type-Options'] = 'nosniff';
      $headers['X-XSS-Protection'] = '1; mode=block';
      $headers['Referrer-Policy'] = 'strict-origin-when-cross-origin';
    }

    return $headers;
  }

  /**
   * Clear WPEngine cache programmatically
   * 
   * @param string $type Cache type ('all', 'object', 'page')
   */
  public static function clear_cache($type = 'all')
  {
    // Clear object cache
    if ($type === 'all' || $type === 'object') {
      wp_cache_flush();
    }

    // Clear page cache (WPEngine specific)
    if (($type === 'all' || $type === 'page') && class_exists('WpeCommon')) {
      // WPEngine's page cache clearing
      if (method_exists('WpeCommon', 'purge_memcached')) {
        WpeCommon::purge_memcached();
      }
      if (method_exists('WpeCommon', 'clear_maxcdn_cache')) {
        WpeCommon::clear_maxcdn_cache();
      }
    }

    // Trigger action for custom cache clearing
    do_action('wpengine_clear_cache', $type);
  }

  /**
   * Add cache clearing admin bar menu
   */
  public static function add_cache_admin_bar()
  {
    if (!current_user_can('manage_options')) {
      return;
    }

    global $wp_admin_bar;

    $wp_admin_bar->add_menu([
      'id' => 'clear-cache',
      'title' => '🧹 Clear Cache',
      'href' => wp_nonce_url(admin_url('admin-post.php?action=clear_cache'), 'clear_cache'),
      'meta' => ['title' => 'Clear WPEngine Cache']
    ]);
  }

  /**
   * Handle cache clearing from admin bar
   */
  public static function handle_cache_clear()
  {
    if (
      !current_user_can('manage_options') ||
      !wp_verify_nonce($_GET['_wpnonce'], 'clear_cache')
    ) {
      wp_die('Unauthorized');
    }

    self::clear_cache('all');

    wp_redirect(wp_get_referer() ?: admin_url());
    exit;
  }
}

// Initialize WPEngine configurations
WPEngine_Config::init();

// Add admin bar cache clearing
if (is_admin_bar_showing()) {
  add_action('admin_bar_menu', ['WPEngine_Config', 'add_cache_admin_bar'], 100);
  add_action('admin_post_clear_cache', ['WPEngine_Config', 'handle_cache_clear']);
}

/**
 * Helper function to clear cache
 * 
 * @param string $type Cache type
 */
function hog_scaffold_clear_cache($type = 'all')
{
  WPEngine_Config::clear_cache($type);
}