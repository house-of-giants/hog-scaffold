<?php
/**
 * Gutenberg Blocks setup
 *
 * @package HoGScaffold\Core
 */

namespace HoGScaffold\Blocks;

use HoGScaffold\Blocks\Example;
use HoGScaffold\Blocks\HeroBlock;
use HoGScaffold\Blocks\ServiceCardsBlock;
use HoGScaffold\Blocks\TeamProfilesBlock;
use HoGScaffold\Blocks\TestimonialsBlock;
use HoGScaffold\Blocks\CtaBlock;
use HoGScaffold\Blocks\InteractiveTabs;

/**
 * Set up blocks
 *
 * @return void
 */
function setup() {
	$n = function ( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	add_action( 'enqueue_block_editor_assets', $n( 'blocks_editor_scripts' ) );

	add_filter( 'block_categories', $n( 'blocks_categories' ), 10, 2 );

	add_action( 'init', $n( 'register_theme_blocks' ) );

	add_action( 'init', $n( 'block_patterns_and_categories' ) );
}

/**
 * Add in blocks that are registered in this theme
 *
 * @return void
 */
function register_theme_blocks() {
	// Filter the plugins URL to allow us to have blocks in themes with linked assets. i.e editorScripts
	add_filter( 'plugins_url', __NAMESPACE__ . '\filter_plugins_url', 10, 2 );

	// Require custom blocks.
	require_once HOG_SCAFFOLD_BLOCK_DIR . '/example-block/register.php';
	require_once HOG_SCAFFOLD_BLOCK_DIR . '/hero-block/register.php';
	require_once HOG_SCAFFOLD_BLOCK_DIR . '/service-cards-block/register.php';
	require_once HOG_SCAFFOLD_BLOCK_DIR . '/team-profiles-block/register.php';
	require_once HOG_SCAFFOLD_BLOCK_DIR . '/testimonials-block/register.php';
	require_once HOG_SCAFFOLD_BLOCK_DIR . '/cta-block/register.php';
	require_once HOG_SCAFFOLD_BLOCK_DIR . '/interactive-tabs-block/register.php';

	// Call block register functions for each block.
	Example\register();
	HeroBlock\register();
	ServiceCardsBlock\register();
	TeamProfilesBlock\register();
	TestimonialsBlock\register();
	CtaBlock\register();
	InteractiveTabs\register();

	// Remove the filter after we register the blocks
	remove_filter( 'plugins_url', __NAMESPACE__ . '\filter_plugins_url', 10, 2 );
}

/**
 * Filter the plugins_url to allow us to use assets from theme.
 *
 * @param string $url  The plugins url
 * @param string $path The path to the asset.
 *
 * @return string The overridden url to the block asset.
 */
function filter_plugins_url( $url, $path ) {
	$file = preg_replace( '/\.\.\//', '', $path );
	return trailingslashit( get_stylesheet_directory_uri() ) . $file;
}

/**
 * Enqueue editor-only JavaScript/CSS for blocks.
 *
 * @return void
 */
function blocks_editor_scripts() {

	wp_enqueue_script(
		'blocks-editor',
		HOG_SCAFFOLD_TEMPLATE_URL . '/dist/js/blocks-editor.js',
		array( 'wp-i18n', 'wp-element', 'wp-blocks', 'wp-components', 'wp-editor', 'wp-compose', 'wp-data', 'wp-dom', 'wp-dom-ready', 'wp-edit-post' ),
		HOG_SCAFFOLD_VERSION,
		false
	);

	$localized_variables = array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
	);
	wp_localize_script( 'blocks-editor', 'localizedVariables', $localized_variables );

	wp_enqueue_style(
		'shared-style',
		HOG_SCAFFOLD_TEMPLATE_URL . '/dist/css/shared-style.css',
		array(),
		HOG_SCAFFOLD_VERSION
	);

	if ( is_admin() ) {
		wp_enqueue_style(
			'admin-style',
			HOG_SCAFFOLD_TEMPLATE_URL . '/dist/css/admin-style.css',
			array(),
			HOG_SCAFFOLD_VERSION
		);

		/*
		* Import editor styles with .editor-styles-wrapper prefix
		* See https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#enqueuing-the-editor-style
		*/
		add_theme_support( 'editor-styles' );
		add_editor_style( '/dist/css/editor-style.css' );
	}
}

/**
 * Filters the registered block categories.
 *
 * @param array  $categories Registered categories.
 * @param object $post       The post object.
 *
 * @return array Filtered categories.
 */
function blocks_categories( $categories, $post ) {
	if ( ! in_array( $post->post_type, array( 'post', 'page' ), true ) ) {
		return $categories;
	}

	return array_merge(
		$categories,
		array(
			array(
				'slug'  => 'hog-scaffold-blocks',
				'title' => __( 'Custom Blocks', 'hog' ),
			),
		)
	);
}

/**
 * Manage block patterns and block pattern categories
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-patterns/
 *
 * @return void
 */
function block_patterns_and_categories() {
	// Register custom block pattern categories
	register_block_pattern_category(
		'hog-scaffold-sections',
		array(
			'label'       => __( 'Theme Sections', 'hog-scaffold' ),
			'description' => __( 'Complete page sections for building layouts', 'hog-scaffold' ),
		)
	);

	register_block_pattern_category(
		'hog-scaffold-content',
		array(
			'label'       => __( 'Content Blocks', 'hog-scaffold' ),
			'description' => __( 'Reusable content components', 'hog-scaffold' ),
		)
	);

	// Register block patterns from pattern files
	$pattern_files = array(
		'hero'            => array(
			'title'       => __( 'Hero Section', 'hog-scaffold' ),
			'description' => __( 'A prominent hero section with heading, description, and call-to-action buttons.', 'hog-scaffold' ),
			'categories'  => array( 'hog-scaffold-sections', 'header', 'featured' ),
			'keywords'    => array( 'hero', 'banner', 'header', 'cta', 'featured' ),
		),
		'services-grid'   => array(
			'title'       => __( 'Services Grid', 'hog-scaffold' ),
			'description' => __( 'A flexible grid layout for showcasing services or features with icons, titles, and descriptions.', 'hog-scaffold' ),
			'categories'  => array( 'hog-scaffold-sections', 'text', 'featured' ),
			'keywords'    => array( 'services', 'grid', 'features', 'columns', 'icons' ),
		),
		'about-section'   => array(
			'title'       => __( 'About Section', 'hog-scaffold' ),
			'description' => __( 'A compelling about section with image, headline, description, and key features.', 'hog-scaffold' ),
			'categories'  => array( 'hog-scaffold-sections', 'text', 'featured' ),
			'keywords'    => array( 'about', 'company', 'story', 'features', 'image' ),
		),
		'testimonials'    => array(
			'title'       => __( 'Testimonials Section', 'hog-scaffold' ),
			'description' => __( 'Customer testimonials with quotes, names, and company information.', 'hog-scaffold' ),
			'categories'  => array( 'hog-scaffold-sections', 'text', 'featured' ),
			'keywords'    => array( 'testimonials', 'reviews', 'quotes', 'customers', 'feedback' ),
		),
		'team-section'    => array(
			'title'       => __( 'Team Section', 'hog-scaffold' ),
			'description' => __( 'Meet the team section with member photos, names, roles, and descriptions.', 'hog-scaffold' ),
			'categories'  => array( 'hog-scaffold-sections', 'text', 'featured' ),
			'keywords'    => array( 'team', 'staff', 'members', 'about', 'people' ),
		),
		'contact-section' => array(
			'title'       => __( 'Contact Section', 'hog-scaffold' ),
			'description' => __( 'Contact section with form fields, contact information, and call-to-action.', 'hog-scaffold' ),
			'categories'  => array( 'hog-scaffold-sections', 'contact', 'featured' ),
			'keywords'    => array( 'contact', 'form', 'email', 'phone', 'address', 'get in touch' ),
		),
		'faq-section'     => array(
			'title'       => __( 'FAQ Section', 'hog-scaffold' ),
			'description' => __( 'Frequently asked questions section with expandable answers.', 'hog-scaffold' ),
			'categories'  => array( 'hog-scaffold-sections', 'text', 'featured' ),
			'keywords'    => array( 'faq', 'questions', 'answers', 'accordion', 'help', 'support' ),
		),
	);

	// Register each pattern
	foreach ( $pattern_files as $pattern_slug => $pattern_data ) {
		$pattern_file = get_template_directory() . '/patterns/' . $pattern_slug . '.php';

		if ( file_exists( $pattern_file ) ) {
			// Get the pattern content
			ob_start();
			include $pattern_file;
			$pattern_content = ob_get_clean();

			// Extract the content after the PHP header
			$pattern_content = preg_replace( '/^<\?php.*?\?>\s*/s', '', $pattern_content );

			// Register the pattern
			register_block_pattern(
				'hog-scaffold/' . $pattern_slug,
				array(
					'title'         => $pattern_data['title'],
					'description'   => $pattern_data['description'],
					'content'       => $pattern_content,
					'categories'    => $pattern_data['categories'],
					'keywords'      => $pattern_data['keywords'],
					'viewportWidth' => 1200,
				)
			);
		}
	}

	// Unregister default WordPress patterns that might conflict
	$patterns_to_remove = array(
		'core/query-standard-posts',
		'core/query-medium-posts',
		'core/query-small-posts',
		'core/query-grid-posts',
		'core/query-large-title-posts',
		'core/social-links-shared-background-color',
	);

	foreach ( $patterns_to_remove as $pattern ) {
		unregister_block_pattern( $pattern );
	}
}
