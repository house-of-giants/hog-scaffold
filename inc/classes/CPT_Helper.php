<?php
/**
 * Custom Post Type Helper Class
 *
 * Provides a fluent API for registering custom post types with block editor compatibility.
 *
 * @package HoGScaffold\Classes
 */

namespace HoGScaffold\Classes;

/**
 * CPT Helper Class
 *
 * A comprehensive helper class for registering custom post types with modern WordPress features.
 */
class CPT_Helper {


	/**
	 * Post type key
	 *
	 * @var string
	 */
	private $post_type;

	/**
	 * Singular name
	 *
	 * @var string
	 */
	private $singular;

	/**
	 * Plural name
	 *
	 * @var string
	 */
	private $plural;

	/**
	 * URL slug
	 *
	 * @var string
	 */
	private $slug;

	/**
	 * Registration options
	 *
	 * @var array
	 */
	private $options;

	/**
	 * Block template
	 *
	 * @var array
	 */
	private $block_template = array();

	/**
	 * Meta fields
	 *
	 * @var array
	 */
	private $meta_fields = array();

	/**
	 * Associated taxonomies
	 *
	 * @var array
	 */
	private $taxonomies = array();

	/**
	 * Constructor
	 *
	 * @param string $post_type Post type key.
	 * @param string $singular  Singular name.
	 * @param string $plural    Plural name.
	 * @param string $slug      URL slug.
	 * @param array  $options   Additional options.
	 */
	public function __construct( $post_type, $singular, $plural, $slug, $options = array() ) {
		$this->post_type = $post_type;
		$this->singular  = $singular;
		$this->plural    = $plural;
		$this->slug      = $slug;
		$this->options   = $options;

		// Register the CPT on init.
		add_action( 'init', array( $this, 'register' ) );
		add_action( 'init', array( $this, 'register_meta_fields' ) );
	}

	/**
	 * Register the custom post type
	 *
	 * @return void
	 */
	public function register() {
		$labels = array(
			'name'                  => $this->plural,
			'singular_name'         => $this->singular,
			'menu_name'             => $this->plural,
			'name_admin_bar'        => $this->singular,
			'archives'              => sprintf( __( '%s Archives', 'hog-scaffold' ), $this->singular ),
			'attributes'            => sprintf( __( '%s Attributes', 'hog-scaffold' ), $this->singular ),
			'parent_item_colon'     => sprintf( __( 'Parent %s:', 'hog-scaffold' ), $this->singular ),
			'all_items'             => sprintf( __( 'All %s', 'hog-scaffold' ), $this->plural ),
			'add_new_item'          => sprintf( __( 'Add New %s', 'hog-scaffold' ), $this->singular ),
			'add_new'               => __( 'Add New', 'hog-scaffold' ),
			'new_item'              => sprintf( __( 'New %s', 'hog-scaffold' ), $this->singular ),
			'edit_item'             => sprintf( __( 'Edit %s', 'hog-scaffold' ), $this->singular ),
			'update_item'           => sprintf( __( 'Update %s', 'hog-scaffold' ), $this->singular ),
			'view_item'             => sprintf( __( 'View %s', 'hog-scaffold' ), $this->singular ),
			'view_items'            => sprintf( __( 'View %s', 'hog-scaffold' ), $this->plural ),
			'search_items'          => sprintf( __( 'Search %s', 'hog-scaffold' ), $this->plural ),
			'not_found'             => sprintf( __( 'No %s found', 'hog-scaffold' ), strtolower( $this->plural ) ),
			'not_found_in_trash'    => sprintf( __( 'No %s found in Trash', 'hog-scaffold' ), strtolower( $this->plural ) ),
			'featured_image'        => __( 'Featured Image', 'hog-scaffold' ),
			'set_featured_image'    => __( 'Set featured image', 'hog-scaffold' ),
			'remove_featured_image' => __( 'Remove featured image', 'hog-scaffold' ),
			'use_featured_image'    => __( 'Use as featured image', 'hog-scaffold' ),
			'insert_into_item'      => sprintf( __( 'Insert into %s', 'hog-scaffold' ), strtolower( $this->singular ) ),
			'uploaded_to_this_item' => sprintf( __( 'Uploaded to this %s', 'hog-scaffold' ), strtolower( $this->singular ) ),
			'items_list'            => sprintf( __( '%s list', 'hog-scaffold' ), $this->plural ),
			'items_list_navigation' => sprintf( __( '%s list navigation', 'hog-scaffold' ), $this->plural ),
			'filter_items_list'     => sprintf( __( 'Filter %s list', 'hog-scaffold' ), strtolower( $this->plural ) ),
		);

		$defaults = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_rest'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => $this->slug ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 20,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
			'template'           => $this->block_template,
			'template_lock'      => false,
		);

		$args = wp_parse_args( $this->options, $defaults );

		register_post_type( $this->post_type, $args );
	}

	/**
	 * Set block template for the CPT
	 *
	 * @param array $blocks Array of block definitions.
	 * @return self
	 */
	public function set_block_template( $blocks ) {
		$this->block_template = $blocks;
		return $this;
	}

	/**
	 * Add a meta field to the CPT
	 *
	 * @param string $key         Meta key.
	 * @param array  $args        Meta field arguments.
	 * @param string $input_type  Input type for admin UI.
	 * @param array  $options     Options for select/radio fields.
	 * @return self
	 */
	public function add_meta_field( $key, $args = array(), $input_type = 'text', $options = array() ) {
		$defaults = array(
			'type'         => 'string',
			'description'  => '',
			'single'       => true,
			'show_in_rest' => true,
		);

		$this->meta_fields[ $key ] = array(
			'args'       => wp_parse_args( $args, $defaults ),
			'input_type' => $input_type,
			'options'    => $options,
		);

		return $this;
	}

	/**
	 * Register meta fields
	 *
	 * @return void
	 */
	public function register_meta_fields() {
		foreach ( $this->meta_fields as $key => $field ) {
			register_post_meta( $this->post_type, $key, $field['args'] );
		}
	}

	/**
	 * Add a taxonomy to the CPT
	 *
	 * @param string $taxonomy Taxonomy key.
	 * @param string $singular Singular name.
	 * @param string $plural   Plural name.
	 * @param string $slug     URL slug.
	 * @param array  $options  Additional options.
	 * @return self
	 */
	public function add_taxonomy( $taxonomy, $singular, $plural, $slug, $options = array() ) {
		$this->taxonomies[ $taxonomy ] = array(
			'singular' => $singular,
			'plural'   => $plural,
			'slug'     => $slug,
			'options'  => $options,
		);

		add_action( 'init', array( $this, 'register_taxonomies' ) );

		return $this;
	}

	/**
	 * Register taxonomies
	 *
	 * @return void
	 */
	public function register_taxonomies() {
		foreach ( $this->taxonomies as $taxonomy => $config ) {
			$labels = array(
				'name'                       => $config['plural'],
				'singular_name'              => $config['singular'],
				'menu_name'                  => $config['plural'],
				'all_items'                  => sprintf( __( 'All %s', 'hog-scaffold' ), $config['plural'] ),
				'parent_item'                => sprintf( __( 'Parent %s', 'hog-scaffold' ), $config['singular'] ),
				'parent_item_colon'          => sprintf( __( 'Parent %s:', 'hog-scaffold' ), $config['singular'] ),
				'new_item_name'              => sprintf( __( 'New %s Name', 'hog-scaffold' ), $config['singular'] ),
				'add_new_item'               => sprintf( __( 'Add New %s', 'hog-scaffold' ), $config['singular'] ),
				'edit_item'                  => sprintf( __( 'Edit %s', 'hog-scaffold' ), $config['singular'] ),
				'update_item'                => sprintf( __( 'Update %s', 'hog-scaffold' ), $config['singular'] ),
				'view_item'                  => sprintf( __( 'View %s', 'hog-scaffold' ), $config['singular'] ),
				'separate_items_with_commas' => sprintf( __( 'Separate %s with commas', 'hog-scaffold' ), strtolower( $config['plural'] ) ),
				'add_or_remove_items'        => sprintf( __( 'Add or remove %s', 'hog-scaffold' ), strtolower( $config['plural'] ) ),
				'choose_from_most_used'      => sprintf( __( 'Choose from the most used %s', 'hog-scaffold' ), strtolower( $config['plural'] ) ),
				'popular_items'              => sprintf( __( 'Popular %s', 'hog-scaffold' ), $config['plural'] ),
				'search_items'               => sprintf( __( 'Search %s', 'hog-scaffold' ), $config['plural'] ),
				'not_found'                  => sprintf( __( 'No %s found', 'hog-scaffold' ), strtolower( $config['plural'] ) ),
				'no_terms'                   => sprintf( __( 'No %s', 'hog-scaffold' ), strtolower( $config['plural'] ) ),
				'items_list'                 => sprintf( __( '%s list', 'hog-scaffold' ), $config['plural'] ),
				'items_list_navigation'      => sprintf( __( '%s list navigation', 'hog-scaffold' ), $config['plural'] ),
			);

			$defaults = array(
				'labels'            => $labels,
				'hierarchical'      => true,
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'show_tagcloud'     => true,
				'show_in_rest'      => true,
				'rewrite'           => array( 'slug' => $config['slug'] ),
			);

			$args = wp_parse_args( $config['options'], $defaults );

			register_taxonomy( $taxonomy, $this->post_type, $args );
		}
	}

	/**
	 * Add admin metabox for custom fields
	 *
	 * @return self
	 */
	public function add_admin_metabox() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta_fields' ) );
		return $this;
	}

	/**
	 * Add meta boxes
	 *
	 * @return void
	 */
	public function add_meta_boxes() {
		if ( empty( $this->meta_fields ) ) {
			return;
		}

		add_meta_box(
			$this->post_type . '_meta',
			sprintf( __( '%s Details', 'hog-scaffold' ), $this->singular ),
			array( $this, 'render_meta_box' ),
			$this->post_type,
			'normal',
			'high'
		);
	}

	/**
	 * Render meta box
	 *
	 * @param \WP_Post $post Post object.
	 * @return void
	 */
	public function render_meta_box( $post ) {
		wp_nonce_field( $this->post_type . '_meta_nonce', $this->post_type . '_meta_nonce' );

		echo '<table class="form-table">';
		foreach ( $this->meta_fields as $key => $field ) {
			$value = get_post_meta( $post->ID, $key, true );
			$label = ucwords( str_replace( '_', ' ', $key ) );

			echo '<tr>';
			echo '<th scope="row"><label for="' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label></th>';
			echo '<td>';

			switch ( $field['input_type'] ) {
				case 'textarea':
					echo '<textarea id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" rows="4" cols="50">' . esc_textarea( $value ) . '</textarea>';
					break;
				case 'select':
					if ( isset( $field['options'] ) ) {
						echo '<select id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '">';
						foreach ( $field['options'] as $option_value => $option_label ) {
								echo '<option value="' . esc_attr( $option_value ) . '"' . selected( $value, $option_value, false ) . '>' . esc_html( $option_label ) . '</option>';
						}
						echo '</select>';
					}
					break;
				case 'checkbox':
					echo '<input type="checkbox" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="1"' . checked( $value, '1', false ) . ' />';
					break;
				case 'url':
					echo '<input type="url" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_url( $value ) . '" class="regular-text" />';
					break;
				case 'email':
					echo '<input type="email" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" class="regular-text" />';
					break;
				case 'date':
					echo '<input type="date" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" class="regular-text" />';
					break;
				default:
					echo '<input type="text" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" class="regular-text" />';
					break;
			}

			if ( ! empty( $field['args']['description'] ) ) {
				echo '<p class="description">' . esc_html( $field['args']['description'] ) . '</p>';
			}

			echo '</td>';
			echo '</tr>';
		}
		echo '</table>';
	}

	/**
	 * Save meta fields
	 *
	 * @param int $post_id Post ID.
	 * @return void
	 */
	public function save_meta_fields( $post_id ) {
		// Check if this is an autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check post type.
		if ( get_post_type( $post_id ) !== $this->post_type ) {
			return;
		}

		// Check nonce.
		if ( ! isset( $_POST[ $this->post_type . '_meta_nonce' ] ) || ! wp_verify_nonce( $_POST[ $this->post_type . '_meta_nonce' ], $this->post_type . '_meta_nonce' ) ) {
			return;
		}

		// Check permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Save meta fields.
		foreach ( $this->meta_fields as $key => $field ) {
			if ( isset( $_POST[ $key ] ) ) {
				$value = sanitize_text_field( $_POST[ $key ] );
				update_post_meta( $post_id, $key, $value );
			} else {
				delete_post_meta( $post_id, $key );
			}
		}
	}

	/**
	 * Get post type key
	 *
	 * @return string
	 */
	public function get_post_type() {
		return $this->post_type;
	}

	/**
	 * Get meta fields
	 *
	 * @return array
	 */
	public function get_meta_fields() {
		return $this->meta_fields;
	}

	/**
	 * Get taxonomies
	 *
	 * @return array
	 */
	public function get_taxonomies() {
		return $this->taxonomies;
	}
}
