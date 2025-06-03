<?php
/**
 * File Security Handler Class
 *
 * @package HoGScaffold
 */

namespace HoGScaffold\Security;

/**
 * File Security Handler
 */
class File_Security
{

	/**
	 * Maximum file size in bytes (2MB default)
	 */
	const MAX_FILE_SIZE = 2097152;

	/**
	 * Allowed image MIME types
	 */
	const ALLOWED_IMAGE_TYPES = array(
		'image/jpeg',
		'image/png',
		'image/gif',
		'image/webp',
	);

	/**
	 * Allowed document MIME types
	 */
	const ALLOWED_DOCUMENT_TYPES = array(
		'application/pdf',
		'application/msword',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'text/plain',
	);

	/**
	 * Dangerous file extensions
	 */
	const DANGEROUS_EXTENSIONS = array(
		'php',
		'php3',
		'php4',
		'php5',
		'phtml',
		'pl',
		'py',
		'jsp',
		'asp',
		'sh',
		'cgi',
		'exe',
		'bat',
		'cmd',
		'scr',
		'com',
		'pif',
		'vbs',
		'js',
		'jar',
		'htaccess',
	);

	/**
	 * Initialize file security
	 */
	public static function init()
	{
		add_action('wp_ajax_hog_scaffold_file_upload', array(__CLASS__, 'handle_file_upload'));
		add_action('wp_ajax_nopriv_hog_scaffold_file_upload', array(__CLASS__, 'handle_file_upload'));
		add_filter('upload_mimes', array(__CLASS__, 'filter_upload_mimes'));
		add_filter('wp_check_filetype_and_ext', array(__CLASS__, 'check_file_type_and_ext'), 10, 4);
	}

	/**
	 * Handle secure file upload via AJAX
	 */
	public static function handle_file_upload()
	{
		// Verify nonce
		if (!isset($_POST['file_upload_nonce']) || !wp_verify_nonce($_POST['file_upload_nonce'], 'hog_scaffold_file_upload')) {
			wp_send_json_error(array('message' => __('Security verification failed.', 'hog-scaffold')));
			return;
		}

		// Check if file was uploaded
		if (empty($_FILES['file'])) {
			wp_send_json_error(array('message' => __('No file was uploaded.', 'hog-scaffold')));
			return;
		}

		$file = $_FILES['file'];
		$validation = self::validate_file_upload($file);

		if (is_wp_error($validation)) {
			wp_send_json_error(array('message' => $validation->get_error_message()));
			return;
		}

		// Process the file upload
		$upload_result = self::secure_file_upload($file);

		if (is_wp_error($upload_result)) {
			wp_send_json_error(array('message' => $upload_result->get_error_message()));
			return;
		}

		wp_send_json_success($upload_result);
	}

	/**
	 * Validate file upload security
	 *
	 * @param array $file $_FILES array element.
	 * @param array $allowed_types Optional. Specific allowed MIME types.
	 * @param int   $max_size Optional. Maximum file size in bytes.
	 * @return array|WP_Error Validated file info or error.
	 */
	public static function validate_file_upload($file, $allowed_types = array(), $max_size = null)
	{
		if (null === $max_size) {
			$max_size = self::MAX_FILE_SIZE;
		}

		// Check for upload errors
		if ($file['error'] !== UPLOAD_ERR_OK) {
			return new \WP_Error('upload_error', self::get_upload_error_message($file['error']));
		}

		// Check file size
		if ($file['size'] > $max_size) {
			return new \WP_Error(
				'file_too_large',
				sprintf(
					__('File size (%s) exceeds the maximum allowed limit (%s).', 'hog-scaffold'),
					size_format($file['size']),
					size_format($max_size)
				)
			);
		}

		// Check for empty file
		if ($file['size'] === 0) {
			return new \WP_Error('empty_file', __('The uploaded file is empty.', 'hog-scaffold'));
		}

		// Verify file extension
		$file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
		if (in_array($file_extension, self::DANGEROUS_EXTENSIONS, true)) {
			return new \WP_Error('dangerous_file_type', __('File type not allowed for security reasons.', 'hog-scaffold'));
		}

		// Verify actual file type
		$file_info = wp_check_filetype_and_ext($file['tmp_name'], $file['name']);

		if (!$file_info['type'] || !$file_info['ext']) {
			return new \WP_Error('invalid_file_type', __('Invalid file type detected.', 'hog-scaffold'));
		}

		// Check against allowed types if specified
		if (!empty($allowed_types) && !in_array($file_info['type'], $allowed_types, true)) {
			return new \WP_Error('disallowed_file_type', __('File type not allowed.', 'hog-scaffold'));
		}

		// Additional security checks
		$security_check = self::perform_security_scan($file['tmp_name'], $file_info['type']);
		if (is_wp_error($security_check)) {
			return $security_check;
		}

		return $file_info;
	}

	/**
	 * Perform security scan on uploaded file
	 *
	 * @param string $file_path Path to temporary file.
	 * @param string $mime_type MIME type of file.
	 * @return bool|WP_Error True if secure, error if suspicious.
	 */
	public static function perform_security_scan($file_path, $mime_type)
	{
		// Check file contents for suspicious patterns
		$file_content = file_get_contents($file_path, false, null, 0, 1024); // Read first 1KB

		// Look for PHP tags in non-PHP files
		if (strpos($mime_type, 'image/') === 0 || strpos($mime_type, 'text/') === 0) {
			if (preg_match('/<\?php|<\?=|<script|javascript:/i', $file_content)) {
				return new \WP_Error('suspicious_content', __('File contains suspicious content.', 'hog-scaffold'));
			}
		}

		// Check for null bytes (directory traversal attempt)
		if (strpos($file_content, "\0") !== false) {
			return new \WP_Error('null_byte_detected', __('File contains invalid characters.', 'hog-scaffold'));
		}

		// Validate image files more thoroughly
		if (strpos($mime_type, 'image/') === 0) {
			$image_info = getimagesize($file_path);
			if (false === $image_info) {
				return new \WP_Error('invalid_image', __('File is not a valid image.', 'hog-scaffold'));
			}

			// Check for embedded PHP in image
			if (function_exists('exif_read_data')) {
				$exif_data = @exif_read_data($file_path);
				if ($exif_data && isset($exif_data['ImageDescription'])) {
					if (preg_match('/<\?php|<script/i', $exif_data['ImageDescription'])) {
						return new \WP_Error('malicious_exif', __('Image contains suspicious metadata.', 'hog-scaffold'));
					}
				}
			}
		}

		return true;
	}

	/**
	 * Secure file upload with sanitization
	 *
	 * @param array $file $_FILES array element.
	 * @param string $upload_dir Optional. Upload directory.
	 * @return array|WP_Error Upload result or error.
	 */
	public static function secure_file_upload($file, $upload_dir = null)
	{
		// Generate secure filename
		$secure_filename = self::generate_secure_filename($file['name']);

		// Use WordPress upload directory if none specified
		if (null === $upload_dir) {
			$upload_dir_info = wp_upload_dir();
			$upload_dir = $upload_dir_info['path'];
		}

		$upload_path = trailingslashit($upload_dir) . $secure_filename;

		// Ensure upload directory exists
		if (!wp_mkdir_p(dirname($upload_path))) {
			return new \WP_Error('upload_dir_error', __('Could not create upload directory.', 'hog-scaffold'));
		}

		// Move uploaded file
		if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
			return new \WP_Error('move_error', __('Could not move uploaded file.', 'hog-scaffold'));
		}

		// Set secure file permissions
		chmod($upload_path, 0644);

		// Create security index file in upload directory if it doesn't exist
		self::create_security_index(dirname($upload_path));

		return array(
			'file' => $upload_path,
			'url' => wp_upload_dir()['url'] . '/' . $secure_filename,
			'type' => $file['type'],
			'size' => $file['size'],
		);
	}

	/**
	 * Generate secure filename
	 *
	 * @param string $original_filename Original filename.
	 * @return string Secure filename.
	 */
	public static function generate_secure_filename($original_filename)
	{
		// Get file extension
		$file_extension = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
		$basename = pathinfo($original_filename, PATHINFO_FILENAME);

		// Sanitize basename
		$sanitized_basename = sanitize_file_name($basename);
		$sanitized_basename = preg_replace('/[^a-zA-Z0-9\-_]/', '', $sanitized_basename);

		// Limit filename length
		$sanitized_basename = substr($sanitized_basename, 0, 50);

		// Add timestamp for uniqueness
		$timestamp = current_time('timestamp');
		$random = wp_generate_password(8, false);

		return $sanitized_basename . '_' . $timestamp . '_' . $random . '.' . $file_extension;
	}

	/**
	 * Create security index file in directory
	 *
	 * @param string $directory Directory path.
	 */
	public static function create_security_index($directory)
	{
		$index_file = trailingslashit($directory) . 'index.php';
		$htaccess_file = trailingslashit($directory) . '.htaccess';

		// Create index.php if it doesn't exist
		if (!file_exists($index_file)) {
			file_put_contents($index_file, '<?php // Silence is golden');
		}

		// Create .htaccess for additional protection
		if (!file_exists($htaccess_file)) {
			$htaccess_content = "# Deny direct access\nOptions -Indexes\n<FilesMatch \"\.php$\">\nOrder allow,deny\nDeny from all\n</FilesMatch>";
			file_put_contents($htaccess_file, $htaccess_content);
		}
	}

	/**
	 * Filter allowed upload MIME types
	 *
	 * @param array $mimes Allowed MIME types.
	 * @return array Filtered MIME types.
	 */
	public static function filter_upload_mimes($mimes)
	{
		// Remove potentially dangerous file types
		unset($mimes['exe']);
		unset($mimes['php']);

		// Add secure types if needed
		$mimes['webp'] = 'image/webp';

		return $mimes;
	}

	/**
	 * Enhanced file type checking
	 *
	 * @param array  $wp_check_filetype_and_ext File data.
	 * @param string $file File path.
	 * @param string $filename File name.
	 * @param array  $mimes Allowed MIME types.
	 * @return array Modified file data.
	 */
	public static function check_file_type_and_ext($wp_check_filetype_and_ext, $file, $filename, $mimes)
	{
		// Additional security checks can be added here
		return $wp_check_filetype_and_ext;
	}

	/**
	 * Get human-readable upload error message
	 *
	 * @param int $error_code PHP upload error code.
	 * @return string Error message.
	 */
	private static function get_upload_error_message($error_code)
	{
		switch ($error_code) {
			case UPLOAD_ERR_INI_SIZE:
				return __('The uploaded file exceeds the upload_max_filesize directive in php.ini.', 'hog-scaffold');
			case UPLOAD_ERR_FORM_SIZE:
				return __('The uploaded file exceeds the MAX_FILE_SIZE directive.', 'hog-scaffold');
			case UPLOAD_ERR_PARTIAL:
				return __('The uploaded file was only partially uploaded.', 'hog-scaffold');
			case UPLOAD_ERR_NO_FILE:
				return __('No file was uploaded.', 'hog-scaffold');
			case UPLOAD_ERR_NO_TMP_DIR:
				return __('Missing a temporary folder.', 'hog-scaffold');
			case UPLOAD_ERR_CANT_WRITE:
				return __('Failed to write file to disk.', 'hog-scaffold');
			case UPLOAD_ERR_EXTENSION:
				return __('A PHP extension stopped the file upload.', 'hog-scaffold');
			default:
				return __('Unknown upload error.', 'hog-scaffold');
		}
	}

	/**
	 * Clean up old temporary files
	 *
	 * @param int $max_age Maximum age in seconds (default: 24 hours).
	 */
	public static function cleanup_temp_files($max_age = 86400)
	{
		$upload_dir = wp_upload_dir();
		$temp_dir = trailingslashit($upload_dir['basedir']) . 'temp/';

		if (!is_dir($temp_dir)) {
			return;
		}

		$files = glob($temp_dir . '*');
		$current_time = time();

		foreach ($files as $file) {
			if (is_file($file) && ($current_time - filemtime($file)) > $max_age) {
				unlink($file);
			}
		}
	}
}