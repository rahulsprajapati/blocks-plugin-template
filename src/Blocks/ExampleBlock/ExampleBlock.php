<?php
/**
 * Example Block
 *
 * @package BlocksPluginTemplate
 */

namespace BlocksPluginTemplate\Blocks\ExampleBlock;

use BlocksPluginTemplate\Block;

/**
 * Class Banner
 *
 * @package BlocksPluginTemplate\Blocks
 */
class ExampleBlock extends Block {

	/**
	 * Post types the block should be registered for.
	 *
	 * @var array
	 */
	protected $post_types = [ 'post', 'page' ];

	/**
	 * Renders the block
	 *
	 * @param array  $attributes Attributes
	 * @param string $content Rendered Content
	 *
	 * @return string
	 */
	public function render( $attributes, $content ) {
		ob_start();
		?>
		<div class="example-block">
			<h2><?php echo esc_html( $attributes['title'] ); ?></h2>
			<p><?php echo esc_html( $attributes['content'] ); ?></p>
		</div>
		<?php
		return ob_get_clean();
	}
}
