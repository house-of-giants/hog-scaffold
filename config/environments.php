<?php
/**
 * Environment Configuration
 * 
 * Manages environment-specific settings for development, staging, and production.
 * 
 * @package HoG_Scaffold
 */

// Prevent direct access
if (!defined('ABSPATH')) {
  exit;
}

/**
 * Environment Configuration Class
 */
class Environment_Config
{

  /**
   * Current environment
   * @var string
   */
  private static $environment = null;

  /**
   * Environment configurations
   * @var array
   */
  private static $configs = [];

  /**
   * Initialize environment configuration
   */
  public static function init()
  {
    self::detect_environment();
    self::setup_configurations();
    self::apply_environment_config();
  }

  /**
   * Detect current environment
   */
  private static function detect_environment()
  {
    // Check for explicit environment setting
    if (defined('WP_ENVIRONMENT_TYPE')) {
      self::$environment = WP_ENVIRONMENT_TYPE;
      return;
    }

    // Check environment variable
    if (getenv('WPENGINE_ENV')) {
      self::$environment = getenv('WPENGINE_ENV');
      return;
    }

    // Detect by URL patterns
    $host = $_SERVER['HTTP_HOST'] ?? '';

    if (strpos($host, '.wpengine.com') !== false) {
      self::$environment = 'staging';
    } elseif (strpos($host, 'localhost') !== false || strpos($host, '.local') !== false) {
      self::$environment = 'development';
    } elseif (defined('WP_DEBUG') && WP_DEBUG) {
      self::$environment = 'development';
    } else {
      self::$environment = 'production';
    }
  }

  /**
   * Set up environment-specific configurations
   */
  private static function setup_configurations()
  {
    self::$configs = [
      'development' => [
        'debug' => true,
        'debug_log' => true,
        'debug_display' => true,
        'cache_enabled' => false,
        'minify_assets' => false,
        'cdn_enabled' => false,
        'error_reporting' => E_ALL,
        'log_errors' => true,
        'cache_timeout' => 0,
        'asset_version' => time(), // Always fresh assets in dev
      ],
      'staging' => [
        'debug' => true,
        'debug_log' => true,
        'debug_display' => false,
        'cache_enabled' => true,
        'minify_assets' => true,
        'cdn_enabled' => false,
        'error_reporting' => E_ALL & ~E_NOTICE,
        'log_errors' => true,
        'cache_timeout' => 300, // 5 minutes
        'asset_version' => defined('HOG_SCAFFOLD_VERSION') ? HOG_SCAFFOLD_VERSION : '1.0.0',
      ],
      'production' => [
        'debug' => false,
        'debug_log' => true,
        'debug_display' => false,
        'cache_enabled' => true,
        'minify_assets' => true,
        'cdn_enabled' => true,
        'error_reporting' => 0,
        'log_errors' => true,
        'cache_timeout' => 3600, // 1 hour
        'asset_version' => defined('HOG_SCAFFOLD_VERSION') ? HOG_SCAFFOLD_VERSION : '1.0.0',
      ]
    ];
  }

  /**
   * Apply environment-specific configuration
   */
  private static function apply_environment_config()
  {
    $config = self::get_config();

    // Set WordPress debug constants if not already defined
    if (!defined('WP_DEBUG')) {
      define('WP_DEBUG', $config['debug']);
    }
    if (!defined('WP_DEBUG_LOG')) {
      define('WP_DEBUG_LOG', $config['debug_log']);
    }
    if (!defined('WP_DEBUG_DISPLAY')) {
      define('WP_DEBUG_DISPLAY', $config['debug_display']);
    }

    // Set error reporting
    error_reporting($config['error_reporting']);
    ini_set('log_errors', $config['log_errors']);

    // Environment-specific actions
    add_action('init', [__CLASS__, 'environment_specific_setup']);
    add_filter('script_loader_tag', [__CLASS__, 'add_asset_attributes'], 10, 3);
    add_filter('style_loader_tag', [__CLASS__, 'add_asset_attributes'], 10, 4);
  }

  /**
   * Environment-specific setup
   */
  public static function environment_specific_setup()
  {
    $config = self::get_config();

    // Development-specific setup
    if (self::$environment === 'development') {
      // Disable caching plugins
      if (function_exists('wp_cache_flush')) {
        add_action('wp_loaded', 'wp_cache_flush');
      }

      // Show admin bar for all users
      show_admin_bar(true);

      // Disable external HTTP requests in development
      if (!defined('WP_HTTP_BLOCK_EXTERNAL')) {
        define('WP_HTTP_BLOCK_EXTERNAL', false);
      }
    }

    // Staging-specific setup
    if (self::$environment === 'staging') {
      // Add staging notice
      add_action('admin_notices', [__CLASS__, 'staging_notice']);
      add_action('wp_footer', [__CLASS__, 'staging_banner']);

      // Disable search engine indexing
      add_filter('pre_option_blog_public', '__return_zero');
    }

    // Production-specific setup
    if (self::$environment === 'production') {
      // Disable file editing
      if (!defined('DISALLOW_FILE_EDIT')) {
        define('DISALLOW_FILE_EDIT', true);
      }

      // Enable automatic updates for minor releases
      add_filter('allow_minor_auto_core_updates', '__return_true');

      // Remove WordPress version from head
      remove_action('wp_head', 'wp_generator');
    }
  }

  /**
   * Add asset attributes based on environment
   */
  public static function add_asset_attributes($tag, $handle, $href = null, $media = null)
  {
    $config = self::get_config();

    // Add cache busting version for development
    if (self::$environment === 'development' && strpos($tag, '?ver=') !== false) {
      $tag = preg_replace('/\?ver=[^"\']*/', '?ver=' . time(), $tag);
    }

    // Add preload for critical assets in production
    if (self::$environment === 'production' && in_array($handle, ['theme-style', 'theme-script'])) {
      $tag = str_replace(' href=', ' rel="preload" as="style" onload="this.onload=null;this.rel=\'stylesheet\'" href=', $tag);
    }

    return $tag;
  }

  /**
   * Show staging notice in admin
   */
  public static function staging_notice()
  {
    echo '<div class="notice notice-warning"><p><strong>STAGING ENVIRONMENT</strong> - This is not the live site.</p></div>';
  }

  /**
   * Show staging banner on frontend
   */
  public static function staging_banner()
  {
    if (!is_admin() && current_user_can('manage_options')) {
      echo '<div style="position: fixed; top: 0; left: 0; right: 0; background: #f56565; color: white; text-align: center; padding: 5px; z-index: 9999; font-weight: bold;">STAGING ENVIRONMENT</div>';
    }
  }

  /**
   * Get current environment
   * 
   * @return string Current environment
   */
  public static function get_environment()
  {
    return self::$environment;
  }

  /**
   * Get environment configuration
   * 
   * @param string $key Optional configuration key
   * @return mixed Configuration value or full config array
   */
  public static function get_config($key = null)
  {
    $config = self::$configs[self::$environment] ?? self::$configs['production'];

    if ($key) {
      return $config[$key] ?? null;
    }

    return $config;
  }

  /**
   * Check if current environment matches
   * 
   * @param string|array $environment Environment(s) to check
   * @return bool True if current environment matches
   */
  public static function is_environment($environment)
  {
    if (is_array($environment)) {
      return in_array(self::$environment, $environment);
    }

    return self::$environment === $environment;
  }

  /**
   * Get environment-specific database configuration
   * 
   * @return array Database configuration
   */
  public static function get_database_config()
  {
    $configs = [
      'development' => [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'name' => getenv('DB_NAME') ?: 'wordpress_dev',
        'user' => getenv('DB_USER') ?: 'root',
        'password' => getenv('DB_PASSWORD') ?: '',
        'charset' => 'utf8mb4',
        'collate' => '',
      ],
      'staging' => [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'name' => getenv('DB_NAME'),
        'user' => getenv('DB_USER'),
        'password' => getenv('DB_PASSWORD'),
        'charset' => 'utf8mb4',
        'collate' => '',
      ],
      'production' => [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'name' => getenv('DB_NAME'),
        'user' => getenv('DB_USER'),
        'password' => getenv('DB_PASSWORD'),
        'charset' => 'utf8mb4',
        'collate' => '',
      ]
    ];

    return $configs[self::$environment] ?? $configs['production'];
  }
}

// Initialize environment configuration
Environment_Config::init();