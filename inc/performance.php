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

	// Preload critical assets
	add_action('wp_head', $n('preload_critical_assets'), 3);

	// Cache optimization
	add_action('init', $n('setup_cache_headers'));
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
		]);
	});
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