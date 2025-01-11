<?php
/**
 * Plugin Name:       Blocks Plugin Template
 * Plugin URI:        https://github.com/rahulsprajapati/blocks-plugin-template
 * Description:       Gutenberg blocks custom plugin.
 * Version:           0.0.1
 * Requires at least: 6.7
 * Requires PHP:      8.0
 * Author:            Rahul Prajapati
 * Author URI:        https://github.com/rahulsprajapati
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       blocks-plugin-template
 * Domain Path:       /languages
 *
 * @package BlocksPluginTemplate
 */

namespace BlocksPluginTemplate;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

const VERSION = '0.0.1';

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * Retrieves a URL to a file in the plugin's directory.
 *
 * @param  string $path Relative path of the desired file.
 *
 * @return string       Fully qualified URL pointing to the desired file.
 */
function plugin_url( $path ) {
	return plugins_url( $path, __FILE__ );
}

/**
 * Retrieves a path to a file in the plugin's directory.
 *
 * @param  string $path Relative path of the desired file.
 *
 * @return string       Fully qualified path pointing to the desired file.
 */
function plugin_path( $path ) {
	return __DIR__ . '/' . $path;
}

/**
 * Runs the setup functions and returns the main plugin object
 *
 * @return Plugin
 */
function blocks_plugin_init() {

	static $plugin_template_blocks;

	if ( empty( $plugin_template_blocks ) ) {
		$plugin_template_blocks = new Plugin();
		$plugin_template_blocks->register();
	}

	return $plugin_template_blocks;
}

blocks_plugin_init();

