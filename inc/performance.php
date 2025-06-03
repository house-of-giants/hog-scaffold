<?php
/**
 * Performance optimizations for the theme
 *
 * @package HoGScaffold\Performance
 */

namespace HoGScaffold\Performance;

/**
 * Set up performance optimizations
 *
 * @return void
 */
function setup()
{
	$n = function ($function) {
		return __NAMESPACE__ . "\\$function";
	};

	// Critical CSS
	add_action('wp_head', $n('inline_critical_css'), 1);
	add_action('wp_enqueue_scripts', $n('defer_non_critical_css'), 20);

	// Resource hints
	add_action('wp_head', $n('add_resource_hints'), 2);

	// Image optimization
	add_action('after_setup_theme', $n('setup_responsive_images'));
	add_filter('wp_get_attachment_image_attributes', $n('add_lazy_loading'), 10, 3);

	// Enhanced lazy loading
	add_filter('the_content', $n('add_lazy_loading_to_content'));
	add_filter('widget_text', $n('add_lazy_loading_to_content'));
	add_filter('post_thumbnail_html', $n('handle_above_fold_images'), 10, 5);

	// Preload critical assets
	add_action('wp_head', $n('preload_critical_assets'), 3);

	// Cache optimization
	add_action('init', $n('setup_cache_headers'));

	// Enhanced next-gen image format support
	add_filter('upload_mimes', __NAMESPACE__ . '\\add_next_gen_image_support');
	add_filter('file_is_displayable_image', __NAMESPACE__ . '\\enable_next_gen_image_display', 10, 2);
	add_filter('wp_get_attachment_image_src', __NAMESPACE__ . '\\serve_next_gen_images', 10, 4);
	add_filter('wp_get_attachment_image', __NAMESPACE__ . '\\add_next_gen_picture_support', 20, 5);

	// Optimize image size generation
	add_filter('intermediate_image_sizes_advanced', __NAMESPACE__ . '\\optimize_image_sizes');

	// Image compression
	add_filter('jpeg_quality', __NAMESPACE__ . '\\set_jpeg_quality');
	add_filter('wp_editor_set_quality', __NAMESPACE__ . '\\set_editor_quality');
	add_action('wp_generate_attachment_metadata', __NAMESPACE__ . '\\optimize_uploaded_image', 10, 2);

	// Image accessibility
	add_filter('wp_get_attachment_image_attributes', __NAMESPACE__ . '\\enhance_image_accessibility', 20, 3);
	add_filter('the_content', __NAMESPACE__ . '\\validate_content_image_accessibility');
	add_action('admin_notices', __NAMESPACE__ . '\\missing_alt_text_notice');
}

/**
 * Inline critical CSS for above-the-fold content
 *
 * @return void
 */
function inline_critical_css()
{
	$critical_css_path = get_template_directory() . '/dist/css/critical.css';

	if (file_exists($critical_css_path)) {
		$critical_css = file_get_contents($critical_css_path);
		if ($critical_css) {
			echo '<style id="critical-css">' . wp_strip_all_tags($critical_css) . '</style>';
		}
	}
}

/**
 * Defer non-critical CSS loading
 *
 * @return void
 */
function defer_non_critical_css()
{
	// Only defer on frontend, not admin
	if (is_admin()) {
		return;
	}

	// Remove default stylesheet from queue
	wp_dequeue_style('styles');

	// Get the stylesheet URL
	$stylesheet_url = HOG_SCAFFOLD_TEMPLATE_URL . '/dist/css/style.css';
	$version = defined('HOG_SCAFFOLD_VERSION') ? HOG_SCAFFOLD_VERSION : '1.0.0';

	// Add preload and async loading
	echo '<link rel="preload" href="' . esc_url($stylesheet_url) . '?ver=' . esc_attr($version) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
	echo '<noscript><link rel="stylesheet" href="' . esc_url($stylesheet_url) . '?ver=' . esc_attr($version) . '"></noscript>';
}

/**
 * Add resource hints for improved loading
 *
 * @return void
 */
function add_resource_hints()
{
	// Preconnect to Google Fonts if used
	echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';

	// DNS prefetch for common external domains
	echo '<link rel="dns-prefetch" href="//www.google-analytics.com">';
	echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">';
}

/**
 * Preload critical assets
 *
 * @return void
 */
function preload_critical_assets()
{
	// Preload critical fonts
	$critical_fonts = [
		'/dist/fonts/main-font.woff2',
		'/dist/fonts/main-font-bold.woff2',
	];

	foreach ($critical_fonts as $font) {
		$font_path = get_template_directory() . $font;
		if (file_exists($font_path)) {
			echo '<link rel="preload" href="' . esc_url(HOG_SCAFFOLD_TEMPLATE_URL . $font) . '" as="font" type="font/woff2" crossorigin>';
		}
	}

	// Preload critical JavaScript
	$critical_js_url = HOG_SCAFFOLD_TEMPLATE_URL . '/dist/js/frontend.js';
	echo '<link rel="preload" href="' . esc_url($critical_js_url) . '" as="script">';
}

/**
 * Setup responsive image sizes
 *
 * @return void
 */
function setup_responsive_images()
{
	// Add custom image sizes for responsive images
	add_image_size('small', 300, 200, true);
	add_image_size('medium-large', 600, 400, true);
	add_image_size('xl', 1200, 800, true);
	add_image_size('xxl', 1920, 1280, true);

	// Add additional breakpoint sizes for better responsive coverage
	add_image_size('mobile', 375, 250, true);
	add_image_size('tablet', 768, 512, true);
	add_image_size('desktop', 1440, 960, true);
	add_image_size('hero', 1920, 800, false); // For hero/banner images

	// Add WebP support
	add_filter('wp_image_editors', function ($editors) {
		if (!in_array('WP_Image_Editor_Imagick', $editors)) {
			array_unshift($editors, 'WP_Image_Editor_Imagick');
		}
		return $editors;
	});

	// Add to image size options in admin
	add_filter('image_size_names_choose', function ($sizes) {
		return array_merge($sizes, [
			'small' => __('Small (300x200)', 'hog-scaffold'),
			'medium-large' => __('Medium Large (600x400)', 'hog-scaffold'),
			'xl' => __('Extra Large (1200x800)', 'hog-scaffold'),
			'xxl' => __('XX Large (1920x1280)', 'hog-scaffold'),
			'mobile' => __('Mobile (375x250)', 'hog-scaffold'),
			'tablet' => __('Tablet (768x512)', 'hog-scaffold'),
			'desktop' => __('Desktop (1440x960)', 'hog-scaffold'),
			'hero' => __('Hero Banner (1920x800)', 'hog-scaffold'),
		]);
	});

	// Enhance responsive image sizes calculation
	add_filter('wp_calculate_image_sizes', __NAMESPACE__ . '\\calculate_responsive_sizes', 10, 5);

	// Add support for art direction with picture element
	add_filter('wp_get_attachment_image', __NAMESPACE__ . '\\maybe_use_picture_element', 10, 5);

	// Enhanced next-gen image format support
	add_filter('upload_mimes', __NAMESPACE__ . '\\add_next_gen_image_support');
	add_filter('file_is_displayable_image', __NAMESPACE__ . '\\enable_next_gen_image_display', 10, 2);
	add_filter('wp_get_attachment_image_src', __NAMESPACE__ . '\\serve_next_gen_images', 10, 4);
	add_filter('wp_get_attachment_image', __NAMESPACE__ . '\\add_next_gen_picture_support', 20, 5);
}

/**
 * Calculate more accurate responsive image sizes
 *
 * @param string $sizes Sizes attribute
 * @param array|string $size Image size
 * @param string $image_src Image source URL
 * @param array $image_meta Image metadata
 * @param int $attachment_id Attachment ID
 * @return string Enhanced sizes attribute
 */
function calculate_responsive_sizes($sizes, $size, $image_src, $image_meta, $attachment_id)
{
	// Default responsive sizes based on context and image size
	if (is_array($size)) {
		$width = $size[0];
	} else {
		$image_sizes = wp_get_additional_image_sizes();
		$width = isset($image_sizes[$size]['width']) ? $image_sizes[$size]['width'] : 1200;
	}

	// Context-aware responsive sizing
	if (is_singular()) {
		// Single post/page context
		if ($width <= 375) {
			return '(max-width: 375px) 100vw, 375px';
		} elseif ($width <= 768) {
			return '(max-width: 768px) 100vw, (max-width: 1024px) 75vw, 768px';
		} elseif ($width <= 1200) {
			return '(max-width: 768px) 100vw, (max-width: 1024px) 80vw, (max-width: 1440px) 75vw, 1200px';
		} else {
			return '(max-width: 768px) 100vw, (max-width: 1024px) 90vw, (max-width: 1440px) 85vw, 1920px';
		}
	} elseif (is_home() || is_archive()) {
		// Archive/blog context - typically smaller images in grid
		if ($width <= 375) {
			return '(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 375px';
		} elseif ($width <= 600) {
			return '(max-width: 768px) 100vw, (max-width: 1024px) 50vw, (max-width: 1440px) 33vw, 600px';
		} else {
			return '(max-width: 768px) 100vw, (max-width: 1024px) 50vw, (max-width: 1440px) 40vw, 768px';
		}
	} else {
		// Default responsive sizing
		if ($width <= 600) {
			return '(max-width: 600px) 100vw, 600px';
		} elseif ($width <= 1200) {
			return '(max-width: 768px) 100vw, (max-width: 1200px) 80vw, 1200px';
		} else {
			return '(max-width: 768px) 100vw, (max-width: 1200px) 90vw, 1920px';
		}
	}
}

/**
 * Maybe use picture element for art direction
 *
 * @param string $html Image HTML
 * @param int $attachment_id Attachment ID
 * @param string|array $size Image size
 * @param bool $icon Whether image is treated as icon
 * @param array $attr Image attributes
 * @return string Enhanced HTML with picture element if needed
 */
function maybe_use_picture_element($html, $attachment_id, $size, $icon, $attr)
{
	// Only apply to specific contexts or when specifically requested
	if (!isset($attr['use_picture']) || !$attr['use_picture']) {
		return $html;
	}

	// Get different sized versions for art direction
	$mobile_src = wp_get_attachment_image_src($attachment_id, 'mobile');
	$tablet_src = wp_get_attachment_image_src($attachment_id, 'tablet');
	$desktop_src = wp_get_attachment_image_src($attachment_id, $size);

	if (!$mobile_src || !$tablet_src || !$desktop_src) {
		return $html;
	}

	// Extract alt text and other attributes from original HTML
	preg_match('/alt="([^"]*)"/', $html, $alt_matches);
	$alt_text = isset($alt_matches[1]) ? $alt_matches[1] : '';

	preg_match('/class="([^"]*)"/', $html, $class_matches);
	$css_classes = isset($class_matches[1]) ? $class_matches[1] : '';

	// Build picture element
	$picture_html = '<picture>';

	// Mobile source
	$picture_html .= sprintf(
		'<source media="(max-width: 767px)" srcset="%s">',
		esc_url($mobile_src[0])
	);

	// Tablet source
	$picture_html .= sprintf(
		'<source media="(min-width: 768px) and (max-width: 1023px)" srcset="%s">',
		esc_url($tablet_src[0])
	);

	// Desktop source (default)
	$picture_html .= sprintf(
		'<img src="%s" alt="%s" class="%s" loading="lazy" decoding="async">',
		esc_url($desktop_src[0]),
		esc_attr($alt_text),
		esc_attr($css_classes)
	);

	$picture_html .= '</picture>';

	return $picture_html;
}

/**
 * Add lazy loading attributes to images
 *
 * @param array $attr Image attributes
 * @param WP_Post $attachment Image attachment post
 * @param string|array $size Image size
 * @return array Modified attributes
 */
function add_lazy_loading($attr, $attachment, $size)
{
	// Skip if in admin or if already has loading attribute
	if (is_admin() || isset($attr['loading'])) {
		return $attr;
	}

	// Add native lazy loading
	$attr['loading'] = 'lazy';

	// Add decoding attribute for better performance
	$attr['decoding'] = 'async';

	return $attr;
}

/**
 * Setup cache headers for static assets
 *
 * @return void
 */
function setup_cache_headers()
{
	if (!is_admin()) {
		// Set cache headers for static assets via .htaccess rules
		// This will be handled in the .htaccess file for better performance

		// Add version query strings to bust cache when needed
		add_filter('style_loader_src', __NAMESPACE__ . '\\add_version_to_assets', 10, 2);
		add_filter('script_loader_src', __NAMESPACE__ . '\\add_version_to_assets', 10, 2);
	}
}

/**
 * Add version parameter to assets for cache busting
 *
 * @param string $src Asset source URL
 * @param string $handle Asset handle
 * @return string Modified source URL
 */
function add_version_to_assets($src, $handle)
{
	// Only add version to theme assets
	if (strpos($src, HOG_SCAFFOLD_TEMPLATE_URL) !== false) {
		$version = defined('HOG_SCAFFOLD_VERSION') ? HOG_SCAFFOLD_VERSION : '1.0.0';

		// If no version parameter exists, add it
		if (strpos($src, 'ver=') === false) {
			$src = add_query_arg('ver', $version, $src);
		}
	}

	return $src;
}

/**
 * Get the critical CSS file path for current page template
 *
 * @return string Critical CSS file path
 */
function get_critical_css_file()
{
	$template = '';

	if (is_front_page()) {
		$template = 'home';
	} elseif (is_single()) {
		$template = 'single';
	} elseif (is_page()) {
		$template = 'page';
	} elseif (is_archive()) {
		$template = 'archive';
	} else {
		$template = 'default';
	}

	return get_template_directory() . "/dist/css/critical-{$template}.css";
}

/**
 * Generate critical CSS for different templates
 * This would typically be called during build process
 *
 * @return void
 */
function generate_critical_css()
{
	// This is a placeholder for the critical CSS generation
	// In a real implementation, this would use tools like:
	// - Critical (npm package)
	// - Penthouse
	// - CriticalCSS
	// or integrate with build tools like Webpack plugins

	$templates = ['home', 'single', 'page', 'archive', 'default'];
	$css_dir = get_template_directory() . '/dist/css/';

	foreach ($templates as $template) {
		$critical_file = $css_dir . "critical-{$template}.css";

		// Create placeholder files if they don't exist
		if (!file_exists($critical_file)) {
			file_put_contents($critical_file, "/* Critical CSS for {$template} template */\n");
		}
	}
}

/**
 * Add next-gen image format support to WordPress uploads
 *
 * @param array $mime_types Current allowed mime types
 * @return array Enhanced mime types array
 */
function add_next_gen_image_support($mime_types)
{
	// Add WebP support
	$mime_types['webp'] = 'image/webp';

	// Add AVIF support (for bleeding edge browsers)
	$mime_types['avif'] = 'image/avif';

	return $mime_types;
}

/**
 * Enable display of next-gen image formats in WordPress admin
 *
 * @param bool $result Current result
 * @param string $path Image path
 * @return bool Whether image is displayable
 */
function enable_next_gen_image_display($result, $path)
{
	if ($result === false) {
		$next_gen_types = ['image/webp', 'image/avif'];
		$info = @getimagesize($path);

		if (false !== $info && in_array($info['mime'], $next_gen_types)) {
			$result = true;
		}
	}

	return $result;
}

/**
 * Serve next-gen images when browser supports them
 *
 * @param array|false $image Array of image data or false
 * @param int $attachment_id Attachment ID
 * @param string|array $size Image size
 * @param bool $icon Whether image is treated as icon
 * @return array|false Modified image data
 */
function serve_next_gen_images($image, $attachment_id, $size, $icon)
{
	// Skip if no image data or in admin
	if (!$image || is_admin()) {
		return $image;
	}

	// Check if browser supports next-gen formats
	$accepts_avif = strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'image/avif') !== false;
	$accepts_webp = strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'image/webp') !== false;

	if (!$accepts_avif && !$accepts_webp) {
		return $image;
	}

	// Get original image path
	$upload_dir = wp_upload_dir();
	$image_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $image[0]);
	$path_info = pathinfo($image_path);

	// Try AVIF first (better compression)
	if ($accepts_avif) {
		$avif_path = $path_info['dirname'] . '/' . $path_info['filename'] . '.avif';
		$avif_url = str_replace($upload_dir['basedir'], $upload_dir['baseurl'], $avif_path);

		if (file_exists($avif_path)) {
			$image[0] = $avif_url;
			return $image;
		}
	}

	// Fallback to WebP
	if ($accepts_webp) {
		$webp_path = $path_info['dirname'] . '/' . $path_info['filename'] . '.webp';
		$webp_url = str_replace($upload_dir['basedir'], $upload_dir['baseurl'], $webp_path);

		if (file_exists($webp_path)) {
			$image[0] = $webp_url;
			return $image;
		}
	}

	return $image;
}

/**
 * Add next-gen image support using picture element
 *
 * @param string $html Image HTML
 * @param int $attachment_id Attachment ID
 * @param string|array $size Image size
 * @param bool $icon Whether image is treated as icon
 * @param array $attr Image attributes
 * @return string Enhanced HTML with picture element for next-gen formats
 */
function add_next_gen_picture_support($html, $attachment_id, $size, $icon, $attr)
{
	// Skip if in admin or if picture element already handled
	if (is_admin() || strpos($html, '<picture>') !== false) {
		return $html;
	}

	// Skip if specifically disabled
	if (isset($attr['no_next_gen']) && $attr['no_next_gen']) {
		return $html;
	}

	// Get image sources
	$original_src = wp_get_attachment_image_src($attachment_id, $size);
	if (!$original_src) {
		return $html;
	}

	// Get upload directory info
	$upload_dir = wp_upload_dir();
	$image_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $original_src[0]);
	$path_info = pathinfo($image_path);

	// Check for next-gen format availability
	$avif_path = $path_info['dirname'] . '/' . $path_info['filename'] . '.avif';
	$webp_path = $path_info['dirname'] . '/' . $path_info['filename'] . '.webp';

	$avif_exists = file_exists($avif_path);
	$webp_exists = file_exists($webp_path);

	// If no next-gen formats exist, return original
	if (!$avif_exists && !$webp_exists) {
		return $html;
	}

	// Extract attributes from original HTML
	preg_match('/alt="([^"]*)"/', $html, $alt_matches);
	$alt_text = isset($alt_matches[1]) ? $alt_matches[1] : '';

	preg_match('/class="([^"]*)"/', $html, $class_matches);
	$css_classes = isset($class_matches[1]) ? $class_matches[1] : '';

	preg_match('/srcset="([^"]*)"/', $html, $srcset_matches);
	$srcset = isset($srcset_matches[1]) ? $srcset_matches[1] : '';

	preg_match('/sizes="([^"]*)"/', $html, $sizes_matches);
	$sizes = isset($sizes_matches[1]) ? $sizes_matches[1] : '';

	// Build picture element with next-gen formats
	$picture_html = '<picture>';

	// AVIF source (highest priority)
	if ($avif_exists) {
		$avif_url = str_replace($upload_dir['basedir'], $upload_dir['baseurl'], $avif_path);
		$avif_srcset = generate_next_gen_srcset($attachment_id, $size, 'avif');

		$picture_html .= sprintf(
			'<source type="image/avif" srcset="%s"%s>',
			$avif_srcset ?: esc_url($avif_url),
			$sizes ? ' sizes="' . esc_attr($sizes) . '"' : ''
		);
	}

	// WebP source (fallback)
	if ($webp_exists) {
		$webp_url = str_replace($upload_dir['basedir'], $upload_dir['baseurl'], $webp_path);
		$webp_srcset = generate_next_gen_srcset($attachment_id, $size, 'webp');

		$picture_html .= sprintf(
			'<source type="image/webp" srcset="%s"%s>',
			$webp_srcset ?: esc_url($webp_url),
			$sizes ? ' sizes="' . esc_attr($sizes) . '"' : ''
		);
	}

	// Original format (final fallback)
	$picture_html .= sprintf(
		'<img src="%s" alt="%s"%s%s%s loading="lazy" decoding="async">',
		esc_url($original_src[0]),
		esc_attr($alt_text),
		$css_classes ? ' class="' . esc_attr($css_classes) . '"' : '',
		$srcset ? ' srcset="' . esc_attr($srcset) . '"' : '',
		$sizes ? ' sizes="' . esc_attr($sizes) . '"' : ''
	);

	$picture_html .= '</picture>';

	return $picture_html;
}

/**
 * Generate srcset for next-gen image formats
 *
 * @param int $attachment_id Attachment ID
 * @param string|array $size Image size
 * @param string $format Image format (webp, avif)
 * @return string Generated srcset or empty string
 */
function generate_next_gen_srcset($attachment_id, $size, $format)
{
	$image_meta = wp_get_attachment_metadata($attachment_id);
	if (!$image_meta || !isset($image_meta['sizes'])) {
		return '';
	}

	$upload_dir = wp_upload_dir();
	$srcset_sources = [];

	// Get all available sizes for this attachment
	$sizes = $image_meta['sizes'];
	$original_file = get_attached_file($attachment_id);
	$original_path_info = pathinfo($original_file);

	foreach ($sizes as $size_name => $size_data) {
		// Construct next-gen format path
		$size_file_name = $original_path_info['filename'] . '-' . $size_data['width'] . 'x' . $size_data['height'] . '.' . $format;
		$size_file_path = $original_path_info['dirname'] . '/' . $size_file_name;

		if (file_exists($size_file_path)) {
			$size_url = str_replace($upload_dir['basedir'], $upload_dir['baseurl'], $size_file_path);
			$srcset_sources[] = $size_url . ' ' . $size_data['width'] . 'w';
		}
	}

	return implode(', ', $srcset_sources);
}

/**
 * Add lazy loading to content
 *
 * @param string $content Post content
 * @return string Modified post content
 */
function add_lazy_loading_to_content($content)
{
	// Add lazy loading to images in the content
	$content = preg_replace_callback('/<img[^>]+>/', function ($matches) {
		$img = $matches[0];
		$img = preg_replace('/src="([^"]+)"/', 'src="$1" loading="lazy" decoding="async"', $img);
		return $img;
	}, $content);

	return $content;
}

/**
 * Handle above-the-fold images
 *
 * @param string $html Image HTML
 * @param int $attachment_id Attachment ID
 * @param string|array $size Image size
 * @param bool $icon Whether image is treated as icon
 * @param array $attr Image attributes
 * @return string Modified image HTML
 */
function handle_above_fold_images($html, $attachment_id, $size, $icon, $attr)
{
	// Skip if in admin or if already has loading attribute
	if (is_admin() || isset($attr['loading'])) {
		return $html;
	}

	// Add native lazy loading
	$attr['loading'] = 'lazy';

	// Add decoding attribute for better performance
	$attr['decoding'] = 'async';

	// Skip if not above-the-fold
	if (!is_front_page() && !is_single() && !is_page()) {
		return $html;
	}

	// Get image sources
	$src = wp_get_attachment_image_src($attachment_id, $size);
	if (!$src) {
		return $html;
	}

	// Build image HTML with lazy loading attributes
	$img = sprintf(
		'<img src="%s" alt="%s" class="%s" loading="lazy" decoding="async">',
		esc_url($src[0]),
		esc_attr($attr['alt'] ?? ''),
		esc_attr($attr['class'] ?? '')
	);

	return $img;
}

/**
 * Optimize image size generation
 *
 * @param array $sizes Array of image sizes
 * @return array Filtered image sizes
 */
function optimize_image_sizes($sizes)
{
	// Remove unnecessary default WordPress sizes that overlap with our custom ones
	// Keep only essential default sizes and let our custom sizes handle the rest
	$sizes_to_remove = [
		'medium_large', // We have our own medium-large
		'1536x1536',    // Large size not needed with our custom sizes
		'2048x2048',    // Extra large size not needed
	];

	foreach ($sizes_to_remove as $size) {
		if (isset($sizes[$size])) {
			unset($sizes[$size]);
		}
	}

	return $sizes;
}

/**
 * Set JPEG quality
 *
 * @param int $quality Current JPEG quality
 * @return int Modified JPEG quality
 */
function set_jpeg_quality($quality)
{
	// Optimize for web delivery - balance between quality and file size
	return 82; // Good balance between quality and compression
}

/**
 * Set editor quality
 *
 * @param int $quality Current editor quality
 * @return int Modified editor quality
 */
function set_editor_quality($quality)
{
	// Use same quality for image editor operations
	return 82; // Consistent with JPEG quality
}

/**
 * Optimize uploaded image
 *
 * @param array $metadata Image metadata
 * @param int $attachment_id Attachment ID
 * @return array Modified image metadata
 */
function optimize_uploaded_image($metadata, $attachment_id)
{
	if (!$metadata || !isset($metadata['file'])) {
		return $metadata;
	}

	$upload_dir = wp_upload_dir();
	$file_path = $upload_dir['basedir'] . '/' . $metadata['file'];

	if (!file_exists($file_path)) {
		return $metadata;
	}

	$file_type = wp_check_filetype($file_path);

	// Optimize based on file type
	switch ($file_type['type']) {
		case 'image/jpeg':
			$metadata = optimize_jpeg_image($file_path, $metadata);
			break;
		case 'image/png':
			$metadata = optimize_png_image($file_path, $metadata);
			break;
		case 'image/webp':
			$metadata = optimize_webp_image($file_path, $metadata);
			break;
	}

	return $metadata;
}

/**
 * Optimize JPEG image
 *
 * @param string $file_path Path to image file
 * @param array $metadata Image metadata
 * @return array Modified metadata
 */
function optimize_jpeg_image($file_path, $metadata)
{
	if (!extension_loaded('gd') && !extension_loaded('imagick')) {
		return $metadata;
	}

	// Use Imagick if available for better compression
	if (extension_loaded('imagick')) {
		try {
			$image = new \Imagick($file_path);

			// Set progressive JPEG for faster loading
			$image->setInterlaceScheme(\Imagick::INTERLACE_PLANE);

			// Optimize compression
			$image->setImageCompressionQuality(82);
			$image->setImageCompression(\Imagick::COMPRESSION_JPEG);

			// Strip metadata to reduce file size
			$image->stripImage();

			// Write optimized image
			$image->writeImage($file_path);
			$image->destroy();

		} catch (\Exception $e) {
			// Fallback to GD if Imagick fails
			return optimize_jpeg_with_gd($file_path, $metadata);
		}
	} else {
		return optimize_jpeg_with_gd($file_path, $metadata);
	}

	return $metadata;
}

/**
 * Optimize JPEG with GD
 *
 * @param string $file_path Path to image file
 * @param array $metadata Image metadata
 * @return array Modified metadata
 */
function optimize_jpeg_with_gd($file_path, $metadata)
{
	$image = imagecreatefromjpeg($file_path);
	if (!$image) {
		return $metadata;
	}

	// Save with optimized quality
	imagejpeg($image, $file_path, 82);
	imagedestroy($image);

	return $metadata;
}

/**
 * Optimize PNG image
 *
 * @param string $file_path Path to image file
 * @param array $metadata Image metadata
 * @return array Modified metadata
 */
function optimize_png_image($file_path, $metadata)
{
	if (!extension_loaded('gd') && !extension_loaded('imagick')) {
		return $metadata;
	}

	// Use Imagick if available for better PNG optimization
	if (extension_loaded('imagick')) {
		try {
			$image = new \Imagick($file_path);

			// Optimize PNG compression
			$image->setImageCompressionQuality(95);
			$image->setImageCompression(\Imagick::COMPRESSION_ZIP);

			// Strip metadata
			$image->stripImage();

			// Write optimized image
			$image->writeImage($file_path);
			$image->destroy();

		} catch (\Exception $e) {
			// Fallback to GD if Imagick fails
			return optimize_png_with_gd($file_path, $metadata);
		}
	} else {
		return optimize_png_with_gd($file_path, $metadata);
	}

	return $metadata;
}

/**
 * Optimize PNG with GD
 *
 * @param string $file_path Path to image file
 * @param array $metadata Image metadata
 * @return array Modified metadata
 */
function optimize_png_with_gd($file_path, $metadata)
{
	$image = imagecreatefrompng($file_path);
	if (!$image) {
		return $metadata;
	}

	// Enable PNG compression
	imagesavealpha($image, true);
	imagepng($image, $file_path, 6); // Compression level 6 (good balance)
	imagedestroy($image);

	return $metadata;
}

/**
 * Optimize WebP image
 *
 * @param string $file_path Path to image file
 * @param array $metadata Image metadata
 * @return array Modified metadata
 */
function optimize_webp_image($file_path, $metadata)
{
	if (!extension_loaded('gd') && !extension_loaded('imagick')) {
		return $metadata;
	}

	// Use Imagick if available
	if (extension_loaded('imagick')) {
		try {
			$image = new \Imagick($file_path);

			// Optimize WebP compression
			$image->setImageCompressionQuality(82);
			$image->setImageFormat('webp');

			// Strip metadata
			$image->stripImage();

			// Write optimized image
			$image->writeImage($file_path);
			$image->destroy();

		} catch (\Exception $e) {
			// WebP optimization failed
		}
	}

	return $metadata;
}

/**
 * Enhance image accessibility
 *
 * @param array $attr Image attributes
 * @param WP_Post $attachment Image attachment post
 * @param string|array $size Image size
 * @return array Modified attributes
 */
function enhance_image_accessibility($attr, $attachment, $size)
{
	// Ensure alt text is present
	if (empty($attr['alt'])) {
		// Try to get alt text from attachment
		$alt_text = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);

		if (empty($alt_text)) {
			// Fallback to attachment title or filename
			$alt_text = $attachment->post_title ?: basename(get_attached_file($attachment->ID), '.' . pathinfo(get_attached_file($attachment->ID), PATHINFO_EXTENSION));
		}

		$attr['alt'] = $alt_text;
	}

	// Add ARIA attributes for complex images
	if (isset($attr['aria-describedby']) || isset($attr['longdesc'])) {
		$attr['role'] = 'img';
	}

	// Ensure decorative images are marked appropriately
	if (isset($attr['decorative']) && $attr['decorative']) {
		$attr['alt'] = '';
		$attr['role'] = 'presentation';
		$attr['aria-hidden'] = 'true';
	}

	return $attr;
}

/**
 * Validate content image accessibility
 *
 * @param string $content Post content
 * @return string Modified post content
 */
function validate_content_image_accessibility($content)
{
	// Find images without alt text and add appropriate attributes
	$content = preg_replace_callback('/<img([^>]*)>/i', function ($matches) {
		$img_tag = $matches[0];
		$attributes = $matches[1];

		// Check if alt attribute exists
		if (!preg_match('/alt\s*=\s*["\'][^"\']*["\']/i', $attributes)) {
			// Add empty alt for decorative images or extract from src
			if (preg_match('/src\s*=\s*["\']([^"\']*)["\']/', $attributes, $src_matches)) {
				$filename = basename($src_matches[1]);
				$alt_text = pathinfo($filename, PATHINFO_FILENAME);
				$alt_text = str_replace(['-', '_'], ' ', $alt_text);
				$alt_text = ucwords($alt_text);

				$img_tag = str_replace('<img', '<img alt="' . esc_attr($alt_text) . '"', $img_tag);
			}
		}

		// Add loading and decoding attributes if not present
		if (!preg_match('/loading\s*=/i', $img_tag)) {
			$img_tag = str_replace('<img', '<img loading="lazy"', $img_tag);
		}

		if (!preg_match('/decoding\s*=/i', $img_tag)) {
			$img_tag = str_replace('<img', '<img decoding="async"', $img_tag);
		}

		return $img_tag;
	}, $content);

	return $content;
}

/**
 * Display missing alt text notice in admin
 *
 * @return void
 */
function missing_alt_text_notice()
{
	global $pagenow;

	// Only show on media library pages
	if ($pagenow !== 'upload.php' && $pagenow !== 'post.php' && $pagenow !== 'post-new.php') {
		return;
	}

	// Check for images without alt text
	$images_without_alt = get_posts([
		'post_type' => 'attachment',
		'post_mime_type' => 'image',
		'meta_query' => [
			'relation' => 'OR',
			[
				'key' => '_wp_attachment_image_alt',
				'compare' => 'NOT EXISTS'
			],
			[
				'key' => '_wp_attachment_image_alt',
				'value' => '',
				'compare' => '='
			]
		],
		'posts_per_page' => 1,
		'fields' => 'ids'
	]);

	if (!empty($images_without_alt)) {
		echo '<div class="notice notice-warning is-dismissible">';
		echo '<p><strong>Accessibility Notice:</strong> Some images in your media library are missing alt text. ';
		echo '<a href="' . admin_url('upload.php') . '">Review your images</a> to improve accessibility.</p>';
		echo '</div>';
	}
}