<?php
/**
 * Block Patterns class
 *
 * @package BlocksPluginTemplate
 */

namespace BlocksPluginTemplate;

/**
 * Class Patterns
 *
 * @package BlocksPluginTemplate
 */
class Patterns extends Module {

	/**
	 * Registers the block
	 */
	public function register() {
		if ( ! function_exists( 'register_block_pattern' ) ) {
			return;
		}

		$this->_add_action( 'init', 'init' );
	}

	/**
	 * Init actions.
	 *
	 * @return void
	 */
	public function init() {

		// Pattern Category.
		register_block_pattern_category(
			'block_pattern_category',
			[
				'label' => esc_html__( 'Blocks Plugin Template', 'blocks-plugin-template' ),
			]
		);

		// Custom Patterns.
		$patterns = apply_filters(
			'block_patterns_to_register',
			[
				'example-pattern' => [
					'title'       => esc_html__( 'Example Pattern', 'blocks-plugin-template' ),
					'description' => esc_html__( 'Template to be used for a Example Pattern.', 'blocks-plugin-template' ),
					'categories'  => [ 'blocks-plugin-template' ],
				],
			]
		);

		foreach ( $patterns as $pattern_name => $pattern_properties ) {
			$content = $this->get_pattern_content( $pattern_name );

			if ( ! $content ) {
				continue;
			}

			$pattern_properties['content'] = $content;

			\register_block_pattern(
				'blocks-plugin-template/' . $pattern_name,
				$pattern_properties
			);
		}
	}

	/**
	 * Helper Function to get pattern content/HTML.
	 *
	 * @param string $pattern_name Pattern Name.
	 * @return string|false
	 */
	private function get_pattern_content( $pattern_name ) {
		/**
		 * Allow pattern content to be filtered.
		 */
		$content = apply_filters(
			'corpnews_get_pattern_content',
			null,
			$pattern_name
		);

		if ( null !== $content ) {
			return $content;
		}

		$path = plugin_path( 'src/block-patterns/' ) . $pattern_name . '.html';

		if ( ! file_exists( $path ) ) {
			return false;
		}
		ob_start();
		require $path;
		return ob_get_clean();
	}
}
