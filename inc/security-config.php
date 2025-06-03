<?php
/**
 * Security configuration and constants
 *
 * @package HoGScaffold
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Security configuration constants
 */

// Rate limiting settings
define( 'HOG_SCAFFOLD_RATE_LIMIT_ATTEMPTS', 5 );
define( 'HOG_SCAFFOLD_RATE_LIMIT_WINDOW', HOUR_IN_SECONDS );

// File upload security
define( 'HOG_SCAFFOLD_MAX_UPLOAD_SIZE', 2 * 1024 * 1024 ); // 2MB
define(
	'HOG_SCAFFOLD_ALLOWED_IMAGE_TYPES',
	array(
		'image/jpeg',
		'image/png',
		'image/gif',
		'image/webp',
	)
);

define(
	'HOG_SCAFFOLD_ALLOWED_DOCUMENT_TYPES',
	array(
		'application/pdf',
		'application/msword',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'text/plain',
	)
);

// Input validation limits
define( 'HOG_SCAFFOLD_MAX_NAME_LENGTH', 100 );
define( 'HOG_SCAFFOLD_MAX_MESSAGE_LENGTH', 2000 );
define( 'HOG_SCAFFOLD_MAX_SUBJECT_LENGTH', 200 );

// Security logging
define( 'HOG_SCAFFOLD_SECURITY_LOG_ENABLED', true );
define( 'HOG_SCAFFOLD_LOG_FAILED_LOGINS', true );
define( 'HOG_SCAFFOLD_LOG_SUSPICIOUS_ACTIVITY', true );

// Content Security Policy settings
define( 'HOG_SCAFFOLD_CSP_DEFAULT_SRC', "'self'" );
define( 'HOG_SCAFFOLD_CSP_SCRIPT_SRC', "'self' 'unsafe-inline' 'unsafe-eval' https://www.google-analytics.com https://www.googletagmanager.com" );
define( 'HOG_SCAFFOLD_CSP_STYLE_SRC', "'self' 'unsafe-inline' https://fonts.googleapis.com" );
define( 'HOG_SCAFFOLD_CSP_IMG_SRC', "'self' data: https: http:" );
define( 'HOG_SCAFFOLD_CSP_FONT_SRC', "'self' https://fonts.gstatic.com" );

// WordPress security hardening
define( 'HOG_SCAFFOLD_HIDE_WP_VERSION', true );
define( 'HOG_SCAFFOLD_DISABLE_XMLRPC', true );
define( 'HOG_SCAFFOLD_REMOVE_GENERATOR_TAG', true );
define( 'HOG_SCAFFOLD_GENERIC_LOGIN_ERRORS', true );

/**
 * Security helper functions
 */

/**
 * Get security configuration value
 *
 * @param string $key Configuration key.
 * @param mixed  $default Default value if key not found.
 * @return mixed Configuration value.
 */
function hog_scaffold_get_security_config( $key, $default = null ) {
	$config = array(
		'rate_limit_attempts'     => HOG_SCAFFOLD_RATE_LIMIT_ATTEMPTS,
		'rate_limit_window'       => HOG_SCAFFOLD_RATE_LIMIT_WINDOW,
		'max_upload_size'         => HOG_SCAFFOLD_MAX_UPLOAD_SIZE,
		'allowed_image_types'     => HOG_SCAFFOLD_ALLOWED_IMAGE_TYPES,
		'allowed_document_types'  => HOG_SCAFFOLD_ALLOWED_DOCUMENT_TYPES,
		'max_name_length'         => HOG_SCAFFOLD_MAX_NAME_LENGTH,
		'max_message_length'      => HOG_SCAFFOLD_MAX_MESSAGE_LENGTH,
		'max_subject_length'      => HOG_SCAFFOLD_MAX_SUBJECT_LENGTH,
		'security_log_enabled'    => HOG_SCAFFOLD_SECURITY_LOG_ENABLED,
		'log_failed_logins'       => HOG_SCAFFOLD_LOG_FAILED_LOGINS,
		'log_suspicious_activity' => HOG_SCAFFOLD_LOG_SUSPICIOUS_ACTIVITY,
		'csp_default_src'         => HOG_SCAFFOLD_CSP_DEFAULT_SRC,
		'csp_script_src'          => HOG_SCAFFOLD_CSP_SCRIPT_SRC,
		'csp_style_src'           => HOG_SCAFFOLD_CSP_STYLE_SRC,
		'csp_img_src'             => HOG_SCAFFOLD_CSP_IMG_SRC,
		'csp_font_src'            => HOG_SCAFFOLD_CSP_FONT_SRC,
		'hide_wp_version'         => HOG_SCAFFOLD_HIDE_WP_VERSION,
		'disable_xmlrpc'          => HOG_SCAFFOLD_DISABLE_XMLRPC,
		'remove_generator_tag'    => HOG_SCAFFOLD_REMOVE_GENERATOR_TAG,
		'generic_login_errors'    => HOG_SCAFFOLD_GENERIC_LOGIN_ERRORS,
	);

	return isset( $config[ $key ] ) ? $config[ $key ] : $default;
}

/**
 * Check if security feature is enabled
 *
 * @param string $feature Feature name.
 * @return bool True if enabled.
 */
function hog_scaffold_is_security_feature_enabled( $feature ) {
	return (bool) hog_scaffold_get_security_config( $feature, false );
}
