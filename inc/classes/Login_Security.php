<?php
/**
 * Login Security Handler Class
 *
 * @package HoGScaffold
 */

namespace HoGScaffold\Security;

/**
 * Login Security Handler
 */
class Login_Security
{

	/**
	 * Maximum login attempts before lockout
	 */
	const MAX_ATTEMPTS = 5;

	/**
	 * Lockout duration in seconds (30 minutes)
	 */
	const LOCKOUT_DURATION = 1800;

	/**
	 * Initialize login security
	 */
	public static function init()
	{
		add_action('wp_login_failed', array(__CLASS__, 'handle_failed_login'));
		add_filter('authenticate', array(__CLASS__, 'check_login_attempts'), 30, 3);
		add_action('wp_login', array(__CLASS__, 'handle_successful_login'), 10, 2);
		add_filter('login_message', array(__CLASS__, 'add_lockout_message'));
		add_action('wp_logout', array(__CLASS__, 'clear_user_attempts'));

		// Enhanced password requirements
		add_action('user_profile_update_errors', array(__CLASS__, 'validate_password_strength'), 0, 3);
		add_action('validate_password_reset', array(__CLASS__, 'validate_password_strength'), 10, 2);

		// Login monitoring
		add_action('wp_login', array(__CLASS__, 'log_login_attempt'), 10, 2);
		add_action('wp_login_failed', array(__CLASS__, 'log_failed_login'));

		// Prevent brute force on XMLRPC
		add_filter('xmlrpc_login_error', array(__CLASS__, 'handle_xmlrpc_login_error'), 10, 2);
	}

	/**
	 * Handle failed login attempt
	 *
	 * @param string $username Username used in failed login.
	 */
	public static function handle_failed_login($username)
	{
		$ip_address = self::get_user_ip();
		$attempts_key = 'login_attempts_' . md5($ip_address . $username);
		$lockout_key = 'login_lockout_' . md5($ip_address . $username);

		// Get current attempts
		$attempts = get_transient($attempts_key);
		$attempts = $attempts ? $attempts + 1 : 1;

		// Store updated attempts
		set_transient($attempts_key, $attempts, self::LOCKOUT_DURATION);

		// Check if lockout threshold reached
		if ($attempts >= self::MAX_ATTEMPTS) {
			set_transient($lockout_key, time(), self::LOCKOUT_DURATION);

			// Log security event
			log_security_event(
				'login_lockout',
				sprintf('IP %s locked out after %d failed attempts for user %s', $ip_address, $attempts, $username),
				array(
					'ip_address' => $ip_address,
					'username' => $username,
					'attempts' => $attempts,
				)
			);
		}

		// Log failed attempt
		self::log_failed_login($username);
	}

	/**
	 * Check if user/IP is locked out before authentication
	 *
	 * @param WP_User|WP_Error|null $user User if authentication succeeded.
	 * @param string                $username Username.
	 * @param string                $password Password.
	 * @return WP_User|WP_Error User object or error.
	 */
	public static function check_login_attempts($user, $username, $password)
	{
		// Don't block if already an error or successful authentication
		if (is_wp_error($user) || is_a($user, 'WP_User')) {
			return $user;
		}

		$ip_address = self::get_user_ip();
		$lockout_key = 'login_lockout_' . md5($ip_address . $username);

		// Check if locked out
		$lockout_time = get_transient($lockout_key);
		if ($lockout_time) {
			$remaining_time = self::LOCKOUT_DURATION - (time() - $lockout_time);

			if ($remaining_time > 0) {
				return new \WP_Error(
					'login_locked',
					sprintf(
						__('Too many failed login attempts. Please try again in %d minutes.', 'hog-scaffold'),
						ceil($remaining_time / 60)
					)
				);
			} else {
				// Lockout expired, clean up
				delete_transient($lockout_key);
				delete_transient('login_attempts_' . md5($ip_address . $username));
			}
		}

		return $user;
	}

	/**
	 * Handle successful login
	 *
	 * @param string  $user_login Username.
	 * @param WP_User $user User object.
	 */
	public static function handle_successful_login($user_login, $user)
	{
		// Clear failed attempts on successful login
		self::clear_user_attempts($user_login);

		// Log successful login
		self::log_login_attempt($user_login, $user);

		// Update last login time
		update_user_meta($user->ID, 'last_login', current_time('mysql'));
		update_user_meta($user->ID, 'last_login_ip', self::get_user_ip());
	}

	/**
	 * Clear login attempts for user
	 *
	 * @param string $username Username.
	 */
	public static function clear_user_attempts($username = '')
	{
		if (empty($username)) {
			$user = wp_get_current_user();
			$username = $user->user_login ?? '';
		}

		if ($username) {
			$ip_address = self::get_user_ip();
			delete_transient('login_attempts_' . md5($ip_address . $username));
			delete_transient('login_lockout_' . md5($ip_address . $username));
		}
	}

	/**
	 * Add lockout message to login form
	 *
	 * @param string $message Current login message.
	 * @return string Modified message.
	 */
	public static function add_lockout_message($message)
	{
		if (isset($_GET['login']) && $_GET['login'] === 'failed') {
			$ip_address = self::get_user_ip();
			$username = $_POST['log'] ?? '';

			if ($username) {
				$attempts_key = 'login_attempts_' . md5($ip_address . $username);
				$attempts = get_transient($attempts_key);

				if ($attempts && $attempts >= 3) {
					$remaining = self::MAX_ATTEMPTS - $attempts;
					$message .= '<div class="login-warning"><p>' .
						sprintf(
							__('Warning: %d more failed attempts will result in a temporary lockout.', 'hog-scaffold'),
							$remaining
						) . '</p></div>';
				}
			}
		}

		return $message;
	}

	/**
	 * Validate password strength
	 *
	 * @param WP_Error $errors Error object.
	 * @param bool     $update Whether updating existing user.
	 * @param WP_User  $user User object.
	 */
	public static function validate_password_strength($errors, $update = null, $user = null)
	{
		// Handle different contexts (user profile vs password reset)
		if (is_wp_error($errors) && isset($_POST['pass1'])) {
			$password = $_POST['pass1'];
		} elseif (isset($update) && is_string($update)) {
			// Password reset context
			$password = $update;
			$errors = is_wp_error($errors) ? $errors : new \WP_Error();
		} else {
			return;
		}

		// Skip for administrators (optional)
		if ($user && user_can($user, 'manage_options')) {
			return;
		}

		// Check password strength
		$strength_result = self::check_password_strength($password);

		if (is_wp_error($strength_result)) {
			if (is_wp_error($errors)) {
				$errors->add($strength_result->get_error_code(), $strength_result->get_error_message());
			}
		}
	}

	/**
	 * Check password strength
	 *
	 * @param string $password Password to check.
	 * @return bool|WP_Error True if strong enough, error otherwise.
	 */
	public static function check_password_strength($password)
	{
		// Minimum length
		if (strlen($password) < 8) {
			return new \WP_Error('weak_password', __('Password must be at least 8 characters long.', 'hog-scaffold'));
		}

		// Must contain uppercase letter
		if (!preg_match('/[A-Z]/', $password)) {
			return new \WP_Error('weak_password', __('Password must contain at least one uppercase letter.', 'hog-scaffold'));
		}

		// Must contain lowercase letter
		if (!preg_match('/[a-z]/', $password)) {
			return new \WP_Error('weak_password', __('Password must contain at least one lowercase letter.', 'hog-scaffold'));
		}

		// Must contain number
		if (!preg_match('/[0-9]/', $password)) {
			return new \WP_Error('weak_password', __('Password must contain at least one number.', 'hog-scaffold'));
		}

		// Must contain special character
		if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
			return new \WP_Error('weak_password', __('Password must contain at least one special character.', 'hog-scaffold'));
		}

		// Check against common passwords
		$common_passwords = array(
			'password',
			'password123',
			'123456',
			'123456789',
			'qwerty',
			'abc123',
			'password1',
			'admin',
			'letmein',
			'welcome',
		);

		if (in_array(strtolower($password), $common_passwords, true)) {
			return new \WP_Error('weak_password', __('Password is too common. Please choose a more unique password.', 'hog-scaffold'));
		}

		return true;
	}

	/**
	 * Log login attempt
	 *
	 * @param string  $user_login Username.
	 * @param WP_User $user User object.
	 */
	public static function log_login_attempt($user_login, $user)
	{
		if (!hog_scaffold_is_security_feature_enabled('log_failed_logins')) {
			return;
		}

		log_security_event(
			'successful_login',
			sprintf('User %s logged in successfully', $user_login),
			array(
				'username' => $user_login,
				'user_id' => $user->ID,
				'user_roles' => $user->roles,
				'ip_address' => self::get_user_ip(),
			)
		);
	}

	/**
	 * Log failed login attempt
	 *
	 * @param string $username Username used in failed login.
	 */
	public static function log_failed_login($username)
	{
		if (!hog_scaffold_is_security_feature_enabled('log_failed_logins')) {
			return;
		}

		log_security_event(
			'failed_login',
			sprintf('Failed login attempt for username: %s', $username),
			array(
				'username' => $username,
				'ip_address' => self::get_user_ip(),
			)
		);
	}

	/**
	 * Handle XMLRPC login errors to prevent brute force
	 *
	 * @param IXR_Error $error XMLRPC error.
	 * @param WP_Error  $user WordPress error.
	 * @return IXR_Error Modified error.
	 */
	public static function handle_xmlrpc_login_error($error, $user)
	{
		// Delay response to slow down brute force attempts
		sleep(3);

		return $error;
	}

	/**
	 * Get user IP address
	 *
	 * @return string IP address.
	 */
	public static function get_user_ip()
	{
		// Check for various headers that might contain the real IP
		$headers = array(
			'HTTP_CF_CONNECTING_IP',     // Cloudflare
			'HTTP_X_FORWARDED_FOR',      // Load balancer/proxy
			'HTTP_X_REAL_IP',            // Nginx proxy
			'HTTP_X_FORWARDED',          // Proxy
			'HTTP_X_CLUSTER_CLIENT_IP',  // Cluster
			'HTTP_CLIENT_IP',            // Proxy
			'REMOTE_ADDR',               // Standard
		);

		foreach ($headers as $header) {
			if (!empty($_SERVER[$header])) {
				$ip = $_SERVER[$header];

				// Handle comma-separated list (from X-Forwarded-For)
				if (strpos($ip, ',') !== false) {
					$ip_list = explode(',', $ip);
					$ip = trim($ip_list[0]);
				}

				// Validate IP address
				if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
					return $ip;
				}
			}
		}

		return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
	}

	/**
	 * Get login statistics for admin dashboard
	 *
	 * @return array Login statistics.
	 */
	public static function get_login_stats()
	{
		global $wpdb;

		// This would require a custom table for detailed stats
		// For now, return basic info
		return array(
			'total_users' => count_users()['total_users'],
			'active_sessions' => self::count_active_sessions(),
			'locked_accounts' => self::count_locked_accounts(),
		);
	}

	/**
	 * Count active user sessions
	 *
	 * @return int Number of active sessions.
	 */
	private static function count_active_sessions()
	{
		// This is a simplified implementation
		$users = get_users(array('meta_key' => 'session_tokens'));
		return count($users);
	}

	/**
	 * Count currently locked accounts
	 *
	 * @return int Number of locked accounts.
	 */
	private static function count_locked_accounts()
	{
		global $wpdb;

		// Count transients with lockout prefix
		$lockout_count = $wpdb->get_var(
			"SELECT COUNT(*) FROM {$wpdb->options} 
			WHERE option_name LIKE '_transient_login_lockout_%'"
		);

		return (int) $lockout_count;
	}
}