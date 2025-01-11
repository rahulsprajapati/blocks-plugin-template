<?php
/**
 * Abstract block class
 *
 * @package BlocksPluginTemplate
 */

namespace BlocksPluginTemplate;

/**
 * Class Block
 *
 * @package BlocksPluginTemplate
 */
abstract class Block extends Module {

	/**
	 * Path to the metadata block.json file.
	 *
	 * @var string
	 */
	protected $metadata_file;

	/**
	 * Post types the block should be registered for.
	 *
	 * @var array
	 */
	protected $post_types = [];

	/**
	 * constructor.
	 */
	public function __construct() {
		$this->metadata_file = $this->get_dir() . 'block.json';
	}

	/**
	 * Registers the block
	 */
	public function register() {
		$name             = $this->get_name();
		$this->post_types = apply_filters( "blocks_plugin_template_{$name}_post_types", $this->post_types );

		$this->_add_filter( 'allowed_block_types_all', 'allowed_post_types', 1000, 2 );

		if ( ! function_exists( 'register_block_type_from_metadata' ) ) {
			return;
		}

		$name = $this->get_name();

		$block_args = apply_filters(
			"blocks_plugin_template_args_{$name}",
			[
				'render_callback' => [ $this, 'render' ],
			]
		);

		register_block_type_from_metadata(
			$this->metadata_file,
			$block_args
		);

		add_filter( 'blocks_plugin_template_json', [ $this, 'json_proxy' ] );
	}

	/**
	 * Get the post types for this module.
	 *
	 * @return array
	 */
	public function get_post_types() {
		return $this->post_types;
	}

	/**
	 * Allow the block only on allowed post types.
	 *
	 * @param bool|string[]            $allowed_block_types  Array of block type slugs, or boolean to enable/disable all.
	 *                                                      Default true (all registered block types supported).
	 * @param \WP_Block_Editor_Context $editor_context The current block editor context.
	 * @return array
	 */
	public function allowed_post_types( $allowed_block_types, $editor_context ) {
		if ( empty( $this->post_types ) || empty( $editor_context->post ) || in_array( $editor_context->post->post_type, $this->post_types, true ) ) {
			return $allowed_block_types;
		}

		if ( ! is_array( $allowed_block_types ) ) {
			$allowed_block_types = array_values( wp_list_pluck( \WP_Block_Type_Registry::get_instance()->get_all_registered(), 'name' ) );
		}

		return array_values( array_diff( $allowed_block_types, [ $this->get_name() ] ) );
	}

	/**
	 * Returns the name of the block
	 *
	 * @return string
	 */
	public function get_name() {
		$metadata = json_decode( file_get_contents( $this->metadata_file ), true ); // phpcs:ignore
		if ( ! is_array( $metadata ) || empty( $metadata ) || empty( $metadata['name'] ) ) {
			return false;
		}

		return $metadata['name'];
	}

	/**
	 * Renders the dynamic block
	 *
	 * @param array  $attributes Attributes
	 * @param string $content Content
	 *
	 * @return string
	 */
	abstract public function render( $attributes, $content );

	/**
	 * Internal JSON method to pass additional config to blocks.
	 *
	 * @param array $json JSON to be passed to blocks.
	 *
	 * @return array
	 * @internal
	 */
	public function json_proxy( $json ) {
		$json[ $this->get_name() ] = $this->json();

		return $json;
	}

	/**
	 * JSON config to pass to block editor.
	 *
	 * @return array
	 */
	public function json() {
		return [];
	}

	/**
	 * Get the directory path of the inherited child class.
	 *
	 * @return string
	 */
	protected function get_dir() {
		return trailingslashit( dirname( ( new \ReflectionClass( static::class ) )->getFileName() ) );
	}

	/**
	 * Load block template from theme and fallback to default template included.
	 *
	 * @param string $slug The slug name for the generic template.
	 * @param string $name The name of the specialised template.
	 * @param array  $args Optional. Additional arguments passed to the template.
	 *                     Default empty array.
	 * @param string $default Default template name in the block folder.
	 * @return void|false Void on success, false if the template does not exist.
	 */
	public function load_template( $slug, $name = null, $args = [], $default = 'template' ) {
		$path      = 'templates/blocks/';
		$to_locate = [
			$path . $slug . '.php',
		];

		if ( ! empty( $name ) ) {
			array_unshift( $to_locate, $path . $this->clean_template_name( "{$slug}-{$name}" ) . '.php' );
		}

		$template = locate_template( $to_locate );

		if ( ! empty( $template ) ) {
			ob_start();
			load_template(
				$template,
				false,
				$args
			);
			return ob_get_clean();
		}

		ob_start();
		require $this->get_dir() . $default . '.php';
		return ob_get_clean();
	}

	/**
	 * Clean template name from block or variation name
	 *
	 * @param string $name Name of the block or variation.
	 * @return string
	 */
	protected function clean_template_name( $name ) {
		return str_replace( [ 'blocks-plugin-template/', '/', '_' ], [ '', '-', '-' ], $name );
	}

}
