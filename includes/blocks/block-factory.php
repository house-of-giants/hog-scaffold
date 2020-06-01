<?php
/**
 * Register Block
 *
 * @package HoGScaffold
 */

namespace HoGScaffold\Blocks;

/**
 * Initialize Block.
 *
 * @since 1.0.0
 */
class Block {
	/**
	 * Block name.
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Block attributes.
	 *
	 * @var array
	 */
	public $attributes;

	/**
	 * Static reference to the single instance.
	 *
	 * @var object
	 */
	protected static $instance;

	/**
	 * Construct the class.
	 *
	 * @param string $name Block name
	 * @param array  $attributes Block attributes
	 * @param array  $post_types Allowed post types for this Block. Defaults to all
	 *
	 * @return void
	 */
	protected function __construct( $name = '', $attributes = [], $post_types = [] ) {
		if ( empty( $name ) ) {
			return;
		}

		$this->name       = $name;
		$this->attributes = $attributes;
		$this->post_types = $post_types;

		$this->init();
	}

	/**
	 * Initialize the block.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'wp_loaded', [ $this, 'register' ] );
		add_filter( 'allowed_block_types', [ $this, 'allowed_block_types' ], 10, 2 );
	}

	/**
	 * Instantiate new block based on instance name.
	 */
	final public static function get_new() {
		static $instances = array();

		$called_class = get_called_class();

		if ( ! isset( $instances[ $called_class ] ) ) {
			$instances[ $called_class ] = new $called_class();
		}

		return $instances[ $called_class ];
	}

	/**
	 * Register block server-side.
	 *
	 * @return void
	 */
	public function register() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		register_block_type(
			'HoGScaffold/' . $this->name,
			array(
				'attributes'      => $this->attributes,
				'render_callback' => [ $this, 'render' ],
			)
		);
	}

	/**
	 * Default render function
	 *
	 * @param array  $attributes Block attributes.
	 * @param string $content    Block content, if available. A block can be dynamic
	 *                           but still implement its save function to return HTML.
	 *                           The save function output is returned in the $content
	 *                           variable.
	 */
	public function render( $attributes, $content ) {
		return '';
	}

	/**
	 * Loop through allowed block types to render block
	 *
	 * @param array $allowed_block_types Array of block types allowed on a post
	 * @param array $post Post object
	 */
	public function allowed_block_types( $allowed_block_types, $post ) {
		if ( ! empty( $this->post_types ) && is_array( $this->post_types ) ) {
			if ( ! in_array( $post->post_type, $this->post_types, true ) ) {
				if ( isset( $allowed_block_types[ $this->name ] ) ) {
					unset( $allowed_block_types[ $this->name ] );
				}
			}
		}

		if ( 'testimonials' === $post->post_type || 'legal-dictionary' === $post->post_type ) {
			$allowed_block_types = array(
				'core/paragraph',
			);
		}

		return $allowed_block_types;
	}
}
