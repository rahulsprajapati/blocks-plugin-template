<?php
/**
 * Gutenberg Plugin class
 *
 * @package BlocksPluginTemplate
 */

namespace BlocksPluginTemplate\Plugins;

use BlocksPluginTemplate\Module;

/**
 * Class ExamplePlugin
 *
 * @package BlocksPluginTemplate
 */
class ExamplePlugin extends Module {

	/**
	 * Registers the block
	 */
	public function register() {
		$this->_add_action( 'init', 'register_meta' );
		$this->_add_action( 'admin_enqueue_scripts', 'register_scripts' );
	}

	/**
	 * Registers meta fields.
	 */
	public function register_meta() {
		/**
		 * Post finder plugin meta.
		 */
		register_post_meta(
			'post',
			'example_plugin_meta',
			[
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			]
		);
	}

	/**
	 * Enqueue scripts for blocks in admin.
	 *
	 * @return void
	 */
	public function register_scripts() {
		$current_id = (int) get_the_ID();

		wp_localize_script(
			'blocks_plugin_template',
			'blocksPluginData',
			[
				'current_id' => $current_id,
			]
		);
	}
}
