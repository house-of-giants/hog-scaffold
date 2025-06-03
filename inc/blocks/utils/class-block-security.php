<?php
/**
 * Block Security Utilities
 *
 * Server-side security functions for interactive blocks
 *
 * @package HoGScaffold\Blocks\Utils
 */

namespace HoGScaffold\Blocks\Utils;

/**
 * Block Security class
 */
class Block_Security {


	/**
	 * Sanitize user input data
	 *
	 * @param mixed  $data The data to sanitize
	 * @param string $type The type of sanitization to apply
	 * @return mixed Sanitized data
	 */
	public static function sanitize_input( $data, $type = 'text' ) {
		if ( is_array( $data ) ) {
			return array_map(
				function ( $item ) use ( $type ) {
					return self::sanitize_input( $item, $type );
				},
				$data
			);
		}

		switch ( $type ) {
			case 'email':
				return sanitize_email( $data );
			case 'url':
				return esc_url_raw( $data );
			case 'textarea':
				return sanitize_textarea_field( $data );
			case 'html':
				return wp_kses_post( $data );
			case 'key':
				return sanitize_key( $data );
			case 'slug':
				return sanitize_title( $data );
			case 'int':
				return intval( $data );
			case 'float':
				return floatval( $data );
			case 'text':
			default:
				return sanitize_text_field( $data );
		}
	}

	/**
	 * Validate user input data
	 *
	 * @param mixed $data The data to validate
	 * @param array $rules Validation rules
	 * @return array Validation result
	 */
	public static function validate_input( $data, $rules = array() ) {
		$errors   = array();
		$is_valid = true;

		foreach ( $rules as $field => $rule ) {
			$value = isset( $data[ $field ] ) ? $data[ $field ] : '';

			// Required validation
			if ( isset( $rule['required'] ) && $rule['required'] && empty( $value ) ) {
				$errors[ $field ] = isset( $rule['required_message'] ) ? $rule['required_message'] : $field . ' is required';
				$is_valid         = false;
				continue;
			}

			// Skip other validations if field is empty and not required
			if ( empty( $value ) ) {
				continue;
			}

			// Type-specific validation
			if ( isset( $rule['type'] ) ) {
				switch ( $rule['type'] ) {
					case 'email':
						if ( ! is_email( $value ) ) {
							$errors[ $field ] = isset( $rule['email_message'] ) ? $rule['email_message'] : 'Please enter a valid email address';
							$is_valid         = false;
						}
						break;
					case 'url':
						if ( ! filter_var( $value, FILTER_VALIDATE_URL ) ) {
							$errors[ $field ] = isset( $rule['url_message'] ) ? $rule['url_message'] : 'Please enter a valid URL';
							$is_valid         = false;
						}
						break;
					case 'int':
						if ( ! filter_var( $value, FILTER_VALIDATE_INT ) ) {
							$errors[ $field ] = isset( $rule['int_message'] ) ? $rule['int_message'] : 'Please enter a valid number';
							$is_valid         = false;
						}
						break;
					case 'float':
						if ( ! filter_var( $value, FILTER_VALIDATE_FLOAT ) ) {
							$errors[ $field ] = isset( $rule['float_message'] ) ? $rule['float_message'] : 'Please enter a valid decimal number';
							$is_valid         = false;
						}
						break;
				}
			}

			// Length validation
			if ( isset( $rule['min_length'] ) && strlen( $value ) < $rule['min_length'] ) {
				$errors[ $field ] = isset( $rule['min_length_message'] ) ? $rule['min_length_message'] : $field . ' is too short';
				$is_valid         = false;
			}

			if ( isset( $rule['max_length'] ) && strlen( $value ) > $rule['max_length'] ) {
				$errors[ $field ] = isset( $rule['max_length_message'] ) ? $rule['max_length_message'] : $field . ' is too long';
				$is_valid         = false;
			}

			// Pattern validation
			if ( isset( $rule['pattern'] ) && ! preg_match( $rule['pattern'], $value ) ) {
				$errors[ $field ] = isset( $rule['pattern_message'] ) ? $rule['pattern_message'] : $field . ' format is invalid';
				$is_valid         = false;
			}

			// Custom validation function
			if ( isset( $rule['custom'] ) && is_callable( $rule['custom'] ) ) {
				$custom_result = call_user_func( $rule['custom'], $value, $data );
				if ( $custom_result !== true ) {
					$errors[ $field ] = is_string( $custom_result ) ? $custom_result : $field . ' is invalid';
					$is_valid         = false;
				}
			}
		}

		return array(
			'is_valid' => $is_valid,
			'errors'   => $errors,
		);
	}

	/**
	 * Create and verify nonces for block security
	 *
	 * @param string $action The action name
	 * @param string $name The nonce name (optional)
	 * @return string The nonce value
	 */
	public static function create_nonce( $action, $name = '_wpnonce' ) {
		return wp_create_nonce( $action );
	}

	/**
	 * Verify a nonce
	 *
	 * @param string $nonce The nonce to verify
	 * @param string $action The action name
	 * @return bool Whether the nonce is valid
	 */
	public static function verify_nonce( $nonce, $action ) {
		return wp_verify_nonce( $nonce, $action ) !== false;
	}

	/**
	 * Check user capabilities for block actions
	 *
	 * @param string $capability The capability to check
	 * @param int    $user_id The user ID (optional, defaults to current user)
	 * @return bool Whether the user has the capability
	 */
	public static function check_capability( $capability, $user_id = null ) {
		if ( $user_id === null ) {
			return current_user_can( $capability );
		}

		$user = get_user_by( 'id', $user_id );
		return $user && user_can( $user, $capability );
	}

	/**
	 * Rate limiting for block actions
	 *
	 * @param string $action The action name
	 * @param int    $limit The rate limit (requests per hour)
	 * @param int    $user_id The user ID (optional)
	 * @return bool Whether the action is allowed
	 */
	public static function check_rate_limit( $action, $limit = 60, $user_id = null ) {
		if ( $user_id === null ) {
			$user_id = get_current_user_id();
		}

		$transient_key = "rate_limit_{$action}_{$user_id}";
		$current_count = get_transient( $transient_key );

		if ( $current_count === false ) {
			// First request in this hour
			set_transient( $transient_key, 1, HOUR_IN_SECONDS );
			return true;
		}

		if ( $current_count >= $limit ) {
			return false;
		}

		// Increment the counter
		set_transient( $transient_key, $current_count + 1, HOUR_IN_SECONDS );
		return true;
	}

	/**
	 * Log security events
	 *
	 * @param string $event The event type
	 * @param array  $data Additional data to log
	 * @param string $level The log level (info, warning, error)
	 * @return void
	 */
	public static function log_security_event( $event, $data = array(), $level = 'info' ) {
		if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
			return;
		}

		$log_data = array(
			'timestamp'  => current_time( 'mysql' ),
			'event'      => $event,
			'user_id'    => get_current_user_id(),
			'ip_address' => self::get_client_ip(),
			'user_agent' => isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : '',
			'data'       => $data,
		);

		error_log(
			sprintf(
				'[BLOCK_SECURITY] %s: %s - User: %d, IP: %s',
				strtoupper( $level ),
				$event,
				$log_data['user_id'],
				$log_data['ip_address']
			)
		);
	}

	/**
	 * Get client IP address
	 *
	 * @return string The client IP address
	 */
	private static function get_client_ip() {
		$ip_headers = array(
			'HTTP_CLIENT_IP',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_X_CLUSTER_CLIENT_IP',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED',
			'REMOTE_ADDR',
		);

		foreach ( $ip_headers as $header ) {
			if ( ! empty( $_SERVER[ $header ] ) ) {
				$ip = $_SERVER[ $header ];
				// Handle comma-separated list of IPs
				if ( strpos( $ip, ',' ) !== false ) {
					$ip = trim( explode( ',', $ip )[0] );
				}
				if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) ) {
					return $ip;
				}
			}
		}

		return isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
	}

	/**
	 * Escape output for safe display
	 *
	 * @param mixed  $data The data to escape
	 * @param string $context The context (html, attr, url, js)
	 * @return mixed Escaped data
	 */
	public static function escape_output( $data, $context = 'html' ) {
		if ( is_array( $data ) ) {
			return array_map(
				function ( $item ) use ( $context ) {
					return self::escape_output( $item, $context );
				},
				$data
			);
		}

		switch ( $context ) {
			case 'attr':
				return esc_attr( $data );
			case 'url':
				return esc_url( $data );
			case 'js':
				return esc_js( $data );
			case 'textarea':
				return esc_textarea( $data );
			case 'html':
			default:
				return esc_html( $data );
		}
	}

	/**
	 * Generate a secure random token
	 *
	 * @param int $length The token length
	 * @return string Random token
	 */
	public static function generate_token( $length = 32 ) {
		if ( function_exists( 'random_bytes' ) ) {
			return bin2hex( random_bytes( $length / 2 ) );
		}

		// Fallback for older PHP versions
		return wp_generate_password( $length, false );
	}

	/**
	 * Validate file upload security
	 *
	 * @param array $file The uploaded file array
	 * @param array $options Validation options
	 * @return array Validation result
	 */
	public static function validate_file_upload( $file, $options = array() ) {
		$defaults = array(
			'max_size'           => 5 * 1024 * 1024, // 5MB
			'allowed_types'      => array( 'image/jpeg', 'image/png', 'image/gif', 'image/webp' ),
			'allowed_extensions' => array( 'jpg', 'jpeg', 'png', 'gif', 'webp' ),
		);

		$options  = wp_parse_args( $options, $defaults );
		$errors   = array();
		$is_valid = true;

		// Check for upload errors
		if ( $file['error'] !== UPLOAD_ERR_OK ) {
			$errors[] = 'File upload failed';
			$is_valid = false;
			return array(
				'is_valid' => $is_valid,
				'errors'   => $errors,
			);
		}

		// Check file size
		if ( $file['size'] > $options['max_size'] ) {
			$errors[] = 'File size exceeds maximum allowed size';
			$is_valid = false;
		}

		// Check file type
		if ( ! in_array( $file['type'], $options['allowed_types'] ) ) {
			$errors[] = 'File type not allowed';
			$is_valid = false;
		}

		// Check file extension
		$extension = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );
		if ( ! in_array( $extension, $options['allowed_extensions'] ) ) {
			$errors[] = 'File extension not allowed';
			$is_valid = false;
		}

		// Additional security checks
		$finfo = finfo_open( FILEINFO_MIME_TYPE );
		if ( $finfo ) {
			$mime_type = finfo_file( $finfo, $file['tmp_name'] );
			if ( ! in_array( $mime_type, $options['allowed_types'] ) ) {
				$errors[] = 'File content does not match extension';
				$is_valid = false;
			}
			finfo_close( $finfo );
		}

		return array(
			'is_valid' => $is_valid,
			'errors'   => $errors,
		);
	}
}
