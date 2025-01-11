<?php
/**
 * Main Block plugin class
 *
 * @package BlocksPluginTemplate
 */

namespace BlocksPluginTemplate;

use BlocksPluginTemplate\Plugins\ExamplePlugin;
use BlocksPluginTemplate\Blocks\ExampleBlock\ExampleBlock;

/**
 * Class Plugin
 *
 * @package BlocksPluginTemplate
 */
class Plugin extends Module {

	/**
	 * Registered Block Instances.
	 *
	 * @var array
	 */
	public $blocks = [];

	/**
	 * Sets up the hooks and plugin classes
	 */
	public function register() {
		$this->_add_action( 'enqueue_block_editor_assets', 'enqueue_scripts', 9 );
		$this->_add_action( 'init', 'register_blocks' );

		$patterns = new Patterns();
		$patterns->register();

		$related_posts = new ExamplePlugin();
		$related_posts->register();
	}

	/**
	 * Enqueues scripts and styles used by the plugin.
	 */
	public function enqueue_scripts() {
		if ( ! file_exists( plugin_path( 'build/index.asset.php' ) ) ) {
			return;
		}

		$script_data = include plugin_path( 'build/index.asset.php' );

		wp_enqueue_script(
			'blocks_plugin_template',
			plugin_url( 'build/index.js' ),
			$script_data['dependencies'],
			$script_data['version'],
			true
		);

		wp_enqueue_style(
			'blocks-plugin-template-styles',
			plugin_url( 'build/index.css' ),
			array(),
			$script_data['version']
		);

		wp_localize_script(
			'blocks_plugin_template',
			'BlocksPluginTemplate',
			[
				'adminUrl' => admin_url(),
			]
		);
	}

	/**
	 * Register all the blocks.
	 *
	 * @return void
	 */
	public function register_blocks() {
		/**
		 * Allow early exclusion of blocks to register.
		 */
		$blocks_to_register = apply_filters(
			'blocks_plugin_template_to_register',
			[
				ExampleBlock::class,
			]
		);

		$this->blocks = array_map(
			function ( $block_class ) {
				$block = new $block_class();

				if ( $block instanceof Block ) {
					$block->register();
				}

				return $block;
			},
			$blocks_to_register
		);
	}

	/**
	 * Adds all blocks from this plugin to the whitelist of blocks in the theme
	 *
	 * @param array $blocks Current whitelist
	 *
	 * @return mixed
	 */
	public function allowed_blocks( $blocks ) {
		$new_blocks = array_map(
			function ( Block $block ) {
				return $block->get_name();
			},
			$this->blocks
		);

		$blocks = array_merge( $blocks, array_values( $new_blocks ) );

		return $blocks;
	}

}
