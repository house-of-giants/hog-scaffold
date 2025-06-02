<?php
/**
 * Security functions and hardening measures
 *
 * @package HoGScaffold
 */

namespace HoGScaffold\Security;

/**
 * Setup security features
 */
function setup()
{
  // Include file security class
  require_once HOG_SCAFFOLD_INC . 'classes/File_Security.php';
  require_once HOG_SCAFFOLD_INC . 'classes/Login_Security.php';

  add_action('init', __NAMESPACE__ . '\\security_headers');
  add_action('wp_loaded', __NAMESPACE__ . '\\disable_file_editing');
  add_action('login_head', __NAMESPACE__ . '\\remove_wp_version');
  add_action('wp_head', __NAMESPACE__ . '\\remove_wp_version');
  add_filter('login_errors', __NAMESPACE__ . '\\generic_login_error');
  add_filter('the_generator', '__return_empty_string');
  add_action('wp_ajax_hog_scaffold_contact_form', __NAMESPACE__ . '\\process_contact_form');
  add_action('wp_ajax_nopriv_hog_scaffold_contact_form', __NAMESPACE__ . '\\process_contact_form');
  add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_security_scripts');
  add_action('wp_footer', __NAMESPACE__ . '\\output_nonces');

  // Remove version from scripts and styles
  add_filter('style_loader_src', __NAMESPACE__ . '\\remove_version_from_assets', 9999);
  add_filter('script_loader_src', __NAMESPACE__ . '\\remove_version_from_assets', 9999);

  // Disable XMLRPC if configured
  if (hog_scaffold_is_security_feature_enabled('disable_xmlrpc')) {
    add_filter('xmlrpc_enabled', '__return_false');
    add_filter('wp_headers', __NAMESPACE__ . '\\remove_x_pingback');
  }

  // Additional security hardening
  add_action('init', __NAMESPACE__ . '\\security_hardening');
  add_filter('wp_headers', __NAMESPACE__ . '\\security_headers_filter');

  // Initialize file security
  File_Security::init();
  Login_Security::init();

  // Schedule cleanup of temporary files
  if (!wp_next_scheduled('hog_scaffold_cleanup_temp_files')) {
    wp_schedule_event(time(), 'daily', 'hog_scaffold_cleanup_temp_files');
  }
  add_action('hog_scaffold_cleanup_temp_files', array('File_Security', 'cleanup_temp_files'));
}

/**
 * Set security headers
 */
function security_headers()
{
  // Don't set headers in admin area
  if (is_admin()) {
    return;
  }

  // X-Frame-Options: Prevent clickjacking
  if (!headers_sent()) {
    header('X-Frame-Options: SAMEORIGIN');

    // X-XSS-Protection: Enable XSS filtering
    header('X-XSS-Protection: 1; mode=block');

    // X-Content-Type-Options: Prevent MIME type sniffing
    header('X-Content-Type-Options: nosniff');

    // Referrer Policy: Control referrer information
    header('Referrer-Policy: strict-origin-when-cross-origin');

    // Permissions Policy (formerly Feature Policy)
    $permissions_policy = array(
      'camera=()',
      'microphone=()',
      'geolocation=()',
      'payment=()',
      'usb=()',
      'magnetometer=()',
      'accelerometer=()',
      'gyroscope=()',
    );
    header('Permissions-Policy: ' . implode(', ', $permissions_policy));

    // Content Security Policy
    $csp_directives = get_content_security_policy();
    if ($csp_directives) {
      header('Content-Security-Policy: ' . $csp_directives);
    }

    // Strict Transport Security (HTTPS only)
    if (is_ssl()) {
      header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
    }

    // Expect-CT (Certificate Transparency)
    if (is_ssl()) {
      header('Expect-CT: max-age=86400, enforce');
    }

    // Cross-Origin Embedder Policy
    header('Cross-Origin-Embedder-Policy: require-corp');

    // Cross-Origin Opener Policy
    header('Cross-Origin-Opener-Policy: same-origin');

    // Cross-Origin Resource Policy
    header('Cross-Origin-Resource-Policy: same-origin');
  }
}

/**
 * Generate Content Security Policy directives
 *
 * @return string CSP directives.
 */
function get_content_security_policy()
{
  $site_url = parse_url(home_url(), PHP_URL_HOST);

  // Base CSP directives
  $directives = array(
    "default-src 'self'",
    "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.google-analytics.com https://www.googletagmanager.com https://connect.facebook.net https://platform.twitter.com",
    "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://use.fontawesome.com",
    "img-src 'self' data: https: http:",
    "font-src 'self' https://fonts.gstatic.com https://use.fontawesome.com data:",
    "connect-src 'self' https://www.google-analytics.com https://api.wordpress.org",
    "frame-src 'self' https://www.youtube.com https://player.vimeo.com https://www.google.com",
    "object-src 'none'",
    "base-uri 'self'",
    "form-action 'self'",
    "frame-ancestors 'self'",
    "upgrade-insecure-requests",
  );

  // Allow customization via filter
  $directives = apply_filters('hog_scaffold_csp_directives', $directives);

  return implode('; ', $directives);
}

/**
 * Disable file editing in WordPress admin
 */
function disable_file_editing()
{
  if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT', true);
  }
  if (!defined('DISALLOW_FILE_MODS')) {
    define('DISALLOW_FILE_MODS', true);
  }
}

/**
 * Remove WordPress version information
 */
function remove_wp_version()
{
  return '';
}

/**
 * Remove version parameters from asset URLs
 *
 * @param string $src Asset URL.
 * @return string Modified URL without version parameter.
 */
function remove_version_from_assets($src)
{
  if (strpos($src, 'ver=')) {
    $src = remove_query_arg('ver', $src);
  }
  return $src;
}

/**
 * Return generic login error message
 *
 * @return string Generic error message.
 */
function generic_login_error()
{
  return __('Invalid username or password.', 'hog-scaffold');
}

/**
 * Sanitize text input with additional validation
 *
 * @param string $input Raw input to sanitize.
 * @param int    $max_length Maximum allowed length.
 * @return string Sanitized input.
 */
function sanitize_text_input($input, $max_length = 255)
{
  $sanitized = sanitize_text_field($input);

  if (strlen($sanitized) > $max_length) {
    $sanitized = substr($sanitized, 0, $max_length);
  }

  return $sanitized;
}

/**
 * Sanitize and validate email
 *
 * @param string $email Email to validate.
 * @return string|false Sanitized email or false if invalid.
 */
function sanitize_email_input($email)
{
  $sanitized = sanitize_email($email);

  if (!is_email($sanitized)) {
    return false;
  }

  return $sanitized;
}

/**
 * Sanitize textarea with length limit
 *
 * @param string $input Raw textarea input.
 * @param int    $max_length Maximum allowed length.
 * @return string Sanitized textarea content.
 */
function sanitize_textarea_input($input, $max_length = 5000)
{
  $sanitized = sanitize_textarea_field($input);

  if (strlen($sanitized) > $max_length) {
    $sanitized = substr($sanitized, 0, $max_length);
  }

  return $sanitized;
}

/**
 * Validate and sanitize phone number
 *
 * @param string $phone Phone number to validate.
 * @return string|false Sanitized phone or false if invalid.
 */
function sanitize_phone_input($phone)
{
  $sanitized = preg_replace('/[^0-9+\-\(\)\s]/', '', $phone);

  // Basic phone validation (at least 10 digits)
  $digits_only = preg_replace('/[^0-9]/', '', $sanitized);

  if (strlen($digits_only) < 10) {
    return false;
  }

  return $sanitized;
}

/**
 * Sanitize URL with protocol validation
 *
 * @param string $url URL to sanitize.
 * @param array  $allowed_protocols Allowed protocols.
 * @return string|false Sanitized URL or false if invalid.
 */
function sanitize_url_input($url, $allowed_protocols = array('http', 'https'))
{
  $sanitized = esc_url_raw($url, $allowed_protocols);

  if (empty($sanitized) || !filter_var($sanitized, FILTER_VALIDATE_URL)) {
    return false;
  }

  return $sanitized;
}

/**
 * Verify nonce with action and context
 *
 * @param string $nonce_value Nonce value to verify.
 * @param string $action Nonce action.
 * @param string $nonce_name Nonce field name (default: '_wpnonce').
 * @return bool True if nonce is valid.
 */
function verify_nonce($nonce_value, $action, $nonce_name = '_wpnonce')
{
  if (empty($nonce_value)) {
    return false;
  }

  return wp_verify_nonce($nonce_value, $action);
}

/**
 * Check user capabilities with role fallback
 *
 * @param string|array $capability Required capability or array of capabilities.
 * @param int          $user_id User ID (default: current user).
 * @return bool True if user has capability.
 */
function check_user_capability($capability, $user_id = 0)
{
  if (0 === $user_id) {
    $user_id = get_current_user_id();
  }

  if (0 === $user_id) {
    return false;
  }

  if (is_array($capability)) {
    foreach ($capability as $cap) {
      if (user_can($user_id, $cap)) {
        return true;
      }
    }
    return false;
  }

  return user_can($user_id, $capability);
}

/**
 * Validate file upload security
 *
 * @param array $file $_FILES array element.
 * @param array $allowed_types Allowed MIME types.
 * @param int   $max_size Maximum file size in bytes.
 * @return array|WP_Error Validated file info or error.
 */
function validate_file_upload($file, $allowed_types = array(), $max_size = 2097152)
{ // 2MB default
  if (empty($file['name']) || empty($file['tmp_name'])) {
    return new \WP_Error('no_file', __('No file was uploaded.', 'hog-scaffold'));
  }

  // Check file size
  if ($file['size'] > $max_size) {
    return new \WP_Error('file_too_large', __('File size exceeds the maximum allowed limit.', 'hog-scaffold'));
  }

  // Verify actual file type
  $file_info = wp_check_filetype_and_ext($file['tmp_name'], $file['name']);

  if (!$file_info['type'] || !$file_info['ext']) {
    return new \WP_Error('invalid_file_type', __('Invalid file type.', 'hog-scaffold'));
  }

  // Check against allowed types if specified
  if (!empty($allowed_types) && !in_array($file_info['type'], $allowed_types, true)) {
    return new \WP_Error('disallowed_file_type', __('File type not allowed.', 'hog-scaffold'));
  }

  return $file_info;
}

/**
 * Process contact form with security measures
 */
function process_contact_form()
{
  // Verify nonce
  if (!isset($_POST['contact_nonce']) || !wp_verify_nonce($_POST['contact_nonce'], 'hog_scaffold_contact_form')) {
    wp_send_json_error(array('message' => __('Security check failed.', 'hog-scaffold')));
    return;
  }

  // Rate limiting check (basic implementation)
  $user_ip = $_SERVER['REMOTE_ADDR'] ?? '';
  $rate_limit_key = 'contact_form_' . md5($user_ip);
  $submissions = get_transient($rate_limit_key);

  if (false !== $submissions && $submissions >= 5) {
    wp_send_json_error(array('message' => __('Too many submissions. Please try again later.', 'hog-scaffold')));
    return;
  }

  // Sanitize and validate inputs
  $name = isset($_POST['name']) ? sanitize_text_input($_POST['name'], 100) : '';
  $email = isset($_POST['email']) ? sanitize_email_input($_POST['email']) : '';
  $phone = isset($_POST['phone']) ? sanitize_phone_input($_POST['phone']) : '';
  $message = isset($_POST['message']) ? sanitize_textarea_input($_POST['message'], 2000) : '';

  // Validate required fields
  $errors = array();

  if (empty($name)) {
    $errors['name'] = __('Name is required.', 'hog-scaffold');
  }

  if (empty($email) || false === $email) {
    $errors['email'] = __('Valid email address is required.', 'hog-scaffold');
  }

  if (empty($message)) {
    $errors['message'] = __('Message is required.', 'hog-scaffold');
  }

  // Process form if no errors
  if (empty($errors)) {
    // Store submission or send email here
    // For now, just simulate success

    // Update rate limiting
    $new_count = false !== $submissions ? $submissions + 1 : 1;
    set_transient($rate_limit_key, $new_count, HOUR_IN_SECONDS);

    wp_send_json_success(array('message' => __('Thank you for your message. We will get back to you soon.', 'hog-scaffold')));
  } else {
    wp_send_json_error(array('errors' => $errors));
  }
}

/**
 * Log security events (basic implementation)
 *
 * @param string $event Event type.
 * @param string $description Event description.
 * @param array  $context Additional context.
 */
function log_security_event($event, $description, $context = array())
{
  if (!WP_DEBUG_LOG) {
    return;
  }

  $log_entry = array(
    'timestamp' => current_time('Y-m-d H:i:s'),
    'event' => $event,
    'description' => $description,
    'user_id' => get_current_user_id(),
    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
    'context' => $context,
  );

  error_log('SECURITY_EVENT: ' . wp_json_encode($log_entry));
}

/**
 * Enqueue security-related scripts and styles
 */
function enqueue_security_scripts()
{
  // Enqueue security JavaScript
  wp_enqueue_script(
    'hog-scaffold-security',
    HOG_SCAFFOLD_TEMPLATE_URL . '/assets/js/security.js',
    array('jquery'),
    HOG_SCAFFOLD_VERSION,
    true
  );

  // Localize script with AJAX URL and nonces
  wp_localize_script(
    'hog-scaffold-security',
    'hogScaffoldAjax',
    array(
      'ajaxurl' => admin_url('admin-ajax.php'),
    )
  );
}

/**
 * Output nonces in footer for JavaScript access
 */
function output_nonces()
{
  $nonces = array(
    'contact_form' => wp_create_nonce('hog_scaffold_contact_form'),
    'file_upload' => wp_create_nonce('hog_scaffold_file_upload'),
    'generic_action' => wp_create_nonce('hog_scaffold_generic_action'),
  );

  echo '<script type="text/javascript">';
  echo 'window.hogScaffoldNonces = ' . wp_json_encode($nonces) . ';';
  echo '</script>';
}

/**
 * Remove X-Pingback header
 *
 * @param array $headers HTTP headers.
 * @return array Modified headers.
 */
function remove_x_pingback($headers)
{
  unset($headers['X-Pingback']);
  return $headers;
}

/**
 * Additional security hardening measures
 */
function security_hardening()
{
  // Remove WordPress version from RSS feeds
  add_filter('the_generator', '__return_empty_string');

  // Disable WordPress file editing from admin
  if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT', true);
  }

  // Remove Windows Live Writer support
  remove_action('wp_head', 'wlwmanifest_link');

  // Remove EditURI/RSD link
  remove_action('wp_head', 'rsd_link');

  // Remove shortlink
  remove_action('wp_head', 'wp_shortlink_wp_head');

  // Remove adjacent posts links
  remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');

  // Remove emoji scripts and styles
  remove_action('wp_head', 'print_emoji_detection_script', 7);
  remove_action('wp_print_styles', 'print_emoji_styles');
  remove_action('admin_print_scripts', 'print_emoji_detection_script');
  remove_action('admin_print_styles', 'print_emoji_styles');

  // Disable REST API for non-authenticated users (optional)
  if (!is_user_logged_in()) {
    add_filter('rest_authentication_errors', __NAMESPACE__ . '\\restrict_rest_api');
  }

  // Additional WordPress core hardening
  add_action('init', __NAMESPACE__ . '\\wordpress_core_hardening');

  // Disable user enumeration
  add_action('init', __NAMESPACE__ . '\\disable_user_enumeration');

  // Remove server information
  if (!headers_sent()) {
    header_remove('X-Powered-By');
    header_remove('Server');
  }
}

/**
 * Restrict REST API access for non-authenticated users
 *
 * @param WP_Error|null|bool $result Error if authentication is required.
 * @return WP_Error|null|bool Modified result.
 */
function restrict_rest_api($result)
{
  if (!empty($result)) {
    return $result;
  }

  if (!is_user_logged_in()) {
    return new \WP_Error(
      'rest_not_logged_in',
      __('You are not currently logged in.', 'hog-scaffold'),
      array('status' => 401)
    );
  }

  return $result;
}

/**
 * Additional security headers via filter
 *
 * @param array $headers HTTP headers.
 * @return array Modified headers.
 */
function security_headers_filter($headers)
{
  // Strict transport security (HTTPS only)
  if (is_ssl()) {
    $headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains';
  }

  return $headers;
}

/**
 * Create secure form with nonce field
 *
 * @param string $action Nonce action.
 * @param string $name Nonce field name.
 * @param bool   $referer Include referer field.
 * @param bool   $echo Echo or return the nonce field.
 * @return string Nonce field HTML.
 */
function create_secure_form_field($action, $name = '_wpnonce', $referer = true, $echo = true)
{
  $nonce_field = wp_nonce_field($action, $name, $referer, false);

  if ($echo) {
    echo $nonce_field;
  }

  return $nonce_field;
}

/**
 * Verify form submission with multiple security checks
 *
 * @param string $nonce_action Nonce action to verify.
 * @param string $nonce_name Nonce field name.
 * @param array  $required_fields Required form fields.
 * @return array|WP_Error Verification result or error.
 */
function verify_form_submission($nonce_action, $nonce_name = '_wpnonce', $required_fields = array())
{
  // Verify nonce
  if (!isset($_POST[$nonce_name]) || !wp_verify_nonce($_POST[$nonce_name], $nonce_action)) {
    return new \WP_Error('invalid_nonce', __('Security verification failed.', 'hog-scaffold'));
  }

  // Verify referer
  if (!wp_verify_nonce($_POST['_wp_http_referer'] ?? '', $nonce_action)) {
    // Note: This is optional and might be too strict for some use cases
  }

  // Check required fields
  $missing_fields = array();
  foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
      $missing_fields[] = $field;
    }
  }

  if (!empty($missing_fields)) {
    return new \WP_Error(
      'missing_fields',
      __('Required fields are missing.', 'hog-scaffold'),
      array('missing_fields' => $missing_fields)
    );
  }

  return array(
    'success' => true,
    'message' => __('Form verification successful.', 'hog-scaffold')
  );
}

/**
 * Check if a security feature is enabled
 *
 * @param string $feature Feature name.
 * @return bool Whether feature is enabled.
 */
function hog_scaffold_is_security_feature_enabled($feature)
{
  $security_features = get_option('hog_scaffold_security_features', array(
    'disable_xmlrpc' => true,
    'log_failed_logins' => true,
    'enforce_strong_passwords' => true,
    'limit_login_attempts' => true,
    'security_headers' => true,
    'file_upload_security' => true,
  ));

  return isset($security_features[$feature]) && $security_features[$feature];
}

/**
 * Get security configuration
 *
 * @return array Security configuration.
 */
function get_security_config()
{
  return array(
    'max_login_attempts' => defined('HOG_SCAFFOLD_RATE_LIMIT_ATTEMPTS') ? HOG_SCAFFOLD_RATE_LIMIT_ATTEMPTS : 5,
    'lockout_duration' => defined('HOG_SCAFFOLD_RATE_LIMIT_WINDOW') ? HOG_SCAFFOLD_RATE_LIMIT_WINDOW : HOUR_IN_SECONDS,
    'max_upload_size' => defined('HOG_SCAFFOLD_MAX_UPLOAD_SIZE') ? HOG_SCAFFOLD_MAX_UPLOAD_SIZE : 2 * 1024 * 1024,
    'allowed_image_types' => defined('HOG_SCAFFOLD_ALLOWED_IMAGE_TYPES') ? HOG_SCAFFOLD_ALLOWED_IMAGE_TYPES : array('image/jpeg', 'image/png', 'image/gif', 'image/webp'),
    'security_features' => get_option('hog_scaffold_security_features', array()),
  );
}

/**
 * Initialize security settings on theme activation
 */
function init_security_settings()
{
  // Set default security features
  $default_features = array(
    'disable_xmlrpc' => true,
    'log_failed_logins' => true,
    'enforce_strong_passwords' => true,
    'limit_login_attempts' => true,
    'security_headers' => true,
    'file_upload_security' => true,
  );

  if (!get_option('hog_scaffold_security_features')) {
    update_option('hog_scaffold_security_features', $default_features);
  }
}

// Initialize security settings on theme activation
add_action('after_switch_theme', __NAMESPACE__ . '\\init_security_settings');

/**
 * Security dashboard widget for admin
 */
function add_security_dashboard_widget()
{
  if (current_user_can('manage_options')) {
    wp_add_dashboard_widget(
      'hog_scaffold_security_widget',
      __('Security Status', 'hog-scaffold'),
      __NAMESPACE__ . '\\security_dashboard_widget_content'
    );
  }
}
add_action('wp_dashboard_setup', __NAMESPACE__ . '\\add_security_dashboard_widget');

/**
 * Security dashboard widget content
 */
function security_dashboard_widget_content()
{
  $login_stats = Login_Security::get_login_stats();
  $security_config = get_security_config();

  echo '<div class="security-dashboard">';
  echo '<h4>' . __('Security Overview', 'hog-scaffold') . '</h4>';

  echo '<ul>';
  echo '<li><strong>' . __('Total Users:', 'hog-scaffold') . '</strong> ' . $login_stats['total_users'] . '</li>';
  echo '<li><strong>' . __('Active Sessions:', 'hog-scaffold') . '</strong> ' . $login_stats['active_sessions'] . '</li>';
  echo '<li><strong>' . __('Locked Accounts:', 'hog-scaffold') . '</strong> ' . $login_stats['locked_accounts'] . '</li>';
  echo '<li><strong>' . __('HTTPS Enabled:', 'hog-scaffold') . '</strong> ' . (is_ssl() ? __('Yes', 'hog-scaffold') : __('No', 'hog-scaffold')) . '</li>';
  echo '</ul>';

  echo '<h4>' . __('Security Features', 'hog-scaffold') . '</h4>';
  echo '<ul>';
  foreach ($security_config['security_features'] as $feature => $enabled) {
    $status = $enabled ? __('Enabled', 'hog-scaffold') : __('Disabled', 'hog-scaffold');
    $class = $enabled ? 'enabled' : 'disabled';
    echo '<li><span class="security-feature ' . $class . '">' . ucwords(str_replace('_', ' ', $feature)) . ': ' . $status . '</span></li>';
  }
  echo '</ul>';

  echo '<style>
		.security-feature.enabled { color: #46b450; }
		.security-feature.disabled { color: #dc3232; }
		.security-dashboard ul { margin: 0; }
		.security-dashboard li { margin-bottom: 5px; }
	</style>';

  echo '</div>';
}

/**
 * WordPress core hardening measures
 */
function wordpress_core_hardening()
{
  // Disable pingbacks
  add_filter('xmlrpc_methods', __NAMESPACE__ . '\\disable_xmlrpc_pingback');

  // Remove WordPress version from admin footer
  add_filter('update_footer', '__return_empty_string', 11);

  // Disable WordPress application passwords
  add_filter('wp_is_application_passwords_available', '__return_false');

  // Disable directory browsing
  add_action('init', __NAMESPACE__ . '\\disable_directory_browsing');

  // Disable WordPress heartbeat on frontend
  add_action('init', __NAMESPACE__ . '\\disable_heartbeat');
}

/**
 * Disable user enumeration
 */
function disable_user_enumeration()
{
  // Disable user enumeration via REST API
  add_filter('rest_endpoints', __NAMESPACE__ . '\\disable_rest_user_endpoints');

  // Disable user enumeration via author archives
  add_action('template_redirect', __NAMESPACE__ . '\\disable_author_enumeration');
}

/**
 * Disable REST API user endpoints
 *
 * @param array $endpoints REST API endpoints.
 * @return array Modified endpoints.
 */
function disable_rest_user_endpoints($endpoints)
{
  if (!is_user_logged_in()) {
    if (isset($endpoints['/wp/v2/users'])) {
      unset($endpoints['/wp/v2/users']);
    }
    if (isset($endpoints['/wp/v2/users/(?P<id>[\d]+)'])) {
      unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
    }
  }
  return $endpoints;
}

/**
 * Disable author enumeration via author archives
 */
function disable_author_enumeration()
{
  if (is_author() && !is_user_logged_in()) {
    wp_redirect(home_url(), 301);
    exit;
  }

  // Block author parameter in URLs
  if (isset($_GET['author']) && !is_user_logged_in()) {
    wp_redirect(home_url(), 301);
    exit;
  }
}

/**
 * Disable XMLRPC pingback methods
 *
 * @param array $methods XMLRPC methods.
 * @return array Modified methods.
 */
function disable_xmlrpc_pingback($methods)
{
  unset($methods['pingback.ping']);
  unset($methods['pingback.extensions.getPingbacks']);
  return $methods;
}

/**
 * Disable directory browsing
 */
function disable_directory_browsing()
{
  // Create index.php files in key directories if they don't exist
  $directories = array(
    ABSPATH . 'wp-content/',
    ABSPATH . 'wp-content/themes/',
    ABSPATH . 'wp-content/plugins/',
    ABSPATH . 'wp-content/uploads/',
  );

  foreach ($directories as $dir) {
    $index_file = $dir . 'index.php';
    if (is_dir($dir) && !file_exists($index_file)) {
      file_put_contents($index_file, '<?php // Silence is golden');
    }
  }
}

/**
 * Disable WordPress heartbeat on frontend
 */
function disable_heartbeat()
{
  // Disable on frontend
  if (!is_admin()) {
    wp_deregister_script('heartbeat');
  }

  // Modify heartbeat settings in admin
  add_filter('heartbeat_settings', __NAMESPACE__ . '\\modify_heartbeat_settings');
}

/**
 * Modify heartbeat settings
 *
 * @param array $settings Heartbeat settings.
 * @return array Modified settings.
 */
function modify_heartbeat_settings($settings)
{
  // Slow down heartbeat to every 60 seconds
  $settings['interval'] = 60;
  return $settings;
}