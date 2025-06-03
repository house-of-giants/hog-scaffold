<?php
/**
 * Custom Post Types Initialization
 *
 * Define all custom post types here using the CPT_Helper framework.
 *
 * @package HoGScaffold\CPT
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use HoGScaffold\Classes\CPT_Helper;

/**
 * Initialize all custom post types
 *
 * This function is called from the main CPT setup and should contain
 * all CPT definitions for the theme.
 */

/**
 * Portfolio CPT - Complete Example Implementation
 *
 * This serves as a comprehensive example of how to use the CPT_Helper framework
 * to create a fully-featured custom post type with block editor integration.
 */
$portfolio = new CPT_Helper(
	'portfolio',
	'Portfolio Item',
	'Portfolio Items',
	'portfolio',
	array(
		'menu_icon'     => 'dashicons-portfolio',
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'has_archive'   => true,
		'public'        => true,
		'menu_position' => 25,
		'description'   => __( 'Showcase your work and projects with detailed portfolio items.', 'hog-scaffold' ),
	)
);

// Add meta fields for portfolio items
$portfolio
	->add_meta_field(
		'client_name',
		array(
			'type'         => 'string',
			'description'  => __( 'Client or company name for this project', 'hog-scaffold' ),
			'single'       => true,
			'show_in_rest' => true,
		),
		'text'
	)
	->add_meta_field(
		'project_date',
		array(
			'type'         => 'string',
			'description'  => __( 'Project completion or launch date', 'hog-scaffold' ),
			'single'       => true,
			'show_in_rest' => true,
		),
		'date'
	)
	->add_meta_field(
		'project_url',
		array(
			'type'         => 'string',
			'description'  => __( 'Live project URL (if applicable)', 'hog-scaffold' ),
			'single'       => true,
			'show_in_rest' => true,
		),
		'url'
	)
	->add_meta_field(
		'technologies',
		array(
			'type'         => 'string',
			'description'  => __( 'Technologies and tools used (comma-separated)', 'hog-scaffold' ),
			'single'       => true,
			'show_in_rest' => true,
		),
		'textarea'
	)
	->add_meta_field(
		'project_status',
		array(
			'type'         => 'string',
			'description'  => __( 'Current status of the project', 'hog-scaffold' ),
			'single'       => true,
			'show_in_rest' => true,
		),
		'select',
		array(
			'completed'   => __( 'Completed', 'hog-scaffold' ),
			'in-progress' => __( 'In Progress', 'hog-scaffold' ),
			'on-hold'     => __( 'On Hold', 'hog-scaffold' ),
			'cancelled'   => __( 'Cancelled', 'hog-scaffold' ),
		)
	)
	->add_meta_field(
		'featured_project',
		array(
			'type'         => 'boolean',
			'description'  => __( 'Mark as featured project', 'hog-scaffold' ),
			'single'       => true,
			'show_in_rest' => true,
		),
		'checkbox'
	);

// Add taxonomies for portfolio organization
$portfolio
	->add_taxonomy(
		'portfolio_category',
		'Portfolio Category',
		'Portfolio Categories',
		'portfolio-category',
		array(
			'hierarchical' => true,
			'description'  => __( 'Categorize portfolio items by type or industry', 'hog-scaffold' ),
		)
	)
	->add_taxonomy(
		'portfolio_tag',
		'Portfolio Tag',
		'Portfolio Tags',
		'portfolio-tag',
		array(
			'hierarchical' => false,
			'description'  => __( 'Tag portfolio items with relevant keywords', 'hog-scaffold' ),
		)
	)
	->add_taxonomy(
		'portfolio_skill',
		'Skill',
		'Skills',
		'skill',
		array(
			'hierarchical' => false,
			'description'  => __( 'Skills and technologies demonstrated in this project', 'hog-scaffold' ),
		)
	);

// Set block template for consistent content structure
$portfolio->set_block_template(
	array(
		// Project overview section
		array(
			'core/heading',
			array(
				'level'       => 2,
				'placeholder' => __( 'Project Overview', 'hog-scaffold' ),
				'className'   => 'portfolio-section-heading',
			),
		),
		array(
			'core/paragraph',
			array(
				'placeholder' => __( 'Provide a brief overview of the project, its goals, and your role...', 'hog-scaffold' ),
				'className'   => 'portfolio-overview',
			),
		),

		// Project gallery
		array(
			'core/heading',
			array(
				'level'     => 3,
				'content'   => __( 'Project Gallery', 'hog-scaffold' ),
				'className' => 'portfolio-section-heading',
			),
		),
		array(
			'core/gallery',
			array(
				'linkTo'    => 'media',
				'className' => 'portfolio-gallery',
			),
		),

		// Project details in columns
		array(
			'core/columns',
			array( 'className' => 'portfolio-details' ),
			array(
				// Left column - Project details
				array(
					'core/column',
					array(),
					array(
						array(
							'core/heading',
							array(
								'level'     => 3,
								'content'   => __( 'Project Details', 'hog-scaffold' ),
								'className' => 'portfolio-section-heading',
							),
						),
						array(
							'core/list',
							array(
								'placeholder' => __( 'Add project details like timeline, scope, deliverables...', 'hog-scaffold' ),
								'className'   => 'portfolio-details-list',
							),
						),
					),
				),

				// Right column - Technologies used
				array(
					'core/column',
					array(),
					array(
						array(
							'core/heading',
							array(
								'level'     => 3,
								'content'   => __( 'Technologies Used', 'hog-scaffold' ),
								'className' => 'portfolio-section-heading',
							),
						),
						array(
							'core/paragraph',
							array(
								'placeholder' => __( 'List the technologies, tools, and frameworks used in this project...', 'hog-scaffold' ),
								'className'   => 'portfolio-technologies',
							),
						),
					),
				),
			),
		),

		// Challenges and solutions
		array(
			'core/heading',
			array(
				'level'     => 3,
				'content'   => __( 'Challenges & Solutions', 'hog-scaffold' ),
				'className' => 'portfolio-section-heading',
			),
		),
		array(
			'core/paragraph',
			array(
				'placeholder' => __( 'Describe any challenges faced during the project and how you solved them...', 'hog-scaffold' ),
				'className'   => 'portfolio-challenges',
			),
		),

		// Results and outcomes
		array(
			'core/heading',
			array(
				'level'     => 3,
				'content'   => __( 'Results & Outcomes', 'hog-scaffold' ),
				'className' => 'portfolio-section-heading',
			),
		),
		array(
			'core/paragraph',
			array(
				'placeholder' => __( 'Share the results, metrics, or outcomes achieved by this project...', 'hog-scaffold' ),
				'className'   => 'portfolio-results',
			),
		),
	)
);

// Enable admin metabox for additional field management
$portfolio->add_admin_metabox();

/**
 * Add custom REST API endpoints for portfolio
 */
add_action(
	'rest_api_init',
	function () {
		// Featured portfolio items endpoint
		register_rest_route(
			'hog-scaffold/v1',
			'/portfolio/featured',
			array(
				'methods'             => 'GET',
				'callback'            => 'hog_scaffold_get_featured_portfolio',
				'permission_callback' => '__return_true',
			)
		);

		// Portfolio by category endpoint
		register_rest_route(
			'hog-scaffold/v1',
			'/portfolio/category/(?P<slug>[a-zA-Z0-9_-]+)',
			array(
				'methods'             => 'GET',
				'callback'            => 'hog_scaffold_get_portfolio_by_category',
				'permission_callback' => '__return_true',
			)
		);
	}
);

/**
 * Get featured portfolio items
 *
 * @param WP_REST_Request $request Request object.
 * @return WP_REST_Response
 */
function hog_scaffold_get_featured_portfolio( $request ) {
	$args = array(
		'post_type'      => 'portfolio',
		'posts_per_page' => $request->get_param( 'per_page' ) ?: 6,
		'post_status'    => 'publish',
		'meta_query'     => array(
			array(
				'key'     => 'featured_project',
				'value'   => '1',
				'compare' => '=',
			),
		),
	);

	$posts = get_posts( $args );
	$data  = array();

	foreach ( $posts as $post ) {
		$data[] = array(
			'id'             => $post->ID,
			'title'          => $post->post_title,
			'excerpt'        => $post->post_excerpt,
			'link'           => get_permalink( $post->ID ),
			'featured_image' => get_the_post_thumbnail_url( $post->ID, 'medium' ),
			'client_name'    => get_post_meta( $post->ID, 'client_name', true ),
			'project_date'   => get_post_meta( $post->ID, 'project_date', true ),
			'technologies'   => get_post_meta( $post->ID, 'technologies', true ),
		);
	}

	return rest_ensure_response( $data );
}

/**
 * Get portfolio items by category
 *
 * @param WP_REST_Request $request Request object.
 * @return WP_REST_Response
 */
function hog_scaffold_get_portfolio_by_category( $request ) {
	$category_slug = $request->get_param( 'slug' );

	$args = array(
		'post_type'      => 'portfolio',
		'posts_per_page' => $request->get_param( 'per_page' ) ?: 10,
		'post_status'    => 'publish',
		'tax_query'      => array(
			array(
				'taxonomy' => 'portfolio_category',
				'field'    => 'slug',
				'terms'    => $category_slug,
			),
		),
	);

	$posts = get_posts( $args );
	$data  = array();

	foreach ( $posts as $post ) {
		$data[] = array(
			'id'             => $post->ID,
			'title'          => $post->post_title,
			'excerpt'        => $post->post_excerpt,
			'link'           => get_permalink( $post->ID ),
			'featured_image' => get_the_post_thumbnail_url( $post->ID, 'medium' ),
			'client_name'    => get_post_meta( $post->ID, 'client_name', true ),
			'project_date'   => get_post_meta( $post->ID, 'project_date', true ),
		);
	}

	return rest_ensure_response( $data );
}

/**
 * Add portfolio-specific admin columns
 */
add_filter(
	'manage_portfolio_posts_columns',
	function ( $columns ) {
		$new_columns = array();

		foreach ( $columns as $key => $value ) {
			$new_columns[ $key ] = $value;

			// Add custom columns after title
			if ( $key === 'title' ) {
				$new_columns['client']       = __( 'Client', 'hog-scaffold' );
				$new_columns['project_date'] = __( 'Project Date', 'hog-scaffold' );
				$new_columns['featured']     = __( 'Featured', 'hog-scaffold' );
			}
		}

		return $new_columns;
	}
);

/**
 * Populate custom admin columns
 */
add_action(
	'manage_portfolio_posts_custom_column',
	function ( $column, $post_id ) {
		switch ( $column ) {
			case 'client':
				$client = get_post_meta( $post_id, 'client_name', true );
				echo $client ? esc_html( $client ) : '—';
				break;

			case 'project_date':
				$date = get_post_meta( $post_id, 'project_date', true );
				if ( $date ) {
					echo esc_html( date( 'M Y', strtotime( $date ) ) );
				} else {
					echo '—';
				}
				break;

			case 'featured':
				$featured = get_post_meta( $post_id, 'featured_project', true );
				echo $featured ? '⭐' : '—';
				break;
		}
	},
	10,
	2
);

/**
 * Make custom columns sortable
 */
add_filter(
	'manage_edit-portfolio_sortable_columns',
	function ( $columns ) {
		$columns['client']       = 'client_name';
		$columns['project_date'] = 'project_date';
		$columns['featured']     = 'featured_project';
		return $columns;
	}
);

/**
 * Handle sorting for custom columns
 */
add_action(
	'pre_get_posts',
	function ( $query ) {
		if ( ! is_admin() || ! $query->is_main_query() ) {
			return;
		}

		$orderby = $query->get( 'orderby' );

		if ( 'client_name' === $orderby ) {
			$query->set( 'meta_key', 'client_name' );
			$query->set( 'orderby', 'meta_value' );
		} elseif ( 'project_date' === $orderby ) {
			$query->set( 'meta_key', 'project_date' );
			$query->set( 'orderby', 'meta_value' );
		} elseif ( 'featured_project' === $orderby ) {
			$query->set( 'meta_key', 'featured_project' );
			$query->set( 'orderby', 'meta_value' );
		}
	}
);
