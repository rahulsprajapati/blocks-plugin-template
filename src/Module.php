<?php
/**
 * Module
 *
 * @package BlocksPluginTemplate
 */

namespace BlocksPluginTemplate;

/**
 * Class Module
 *
 * @package BlocksPluginTemplate
 */
class Module implements \ArrayAccess {

	/**
	 * The array of data assigned to a module.
	 *
	 * @access private
	 * @var array
	 */
	private $_data = array(); // phpcs:ignore

	/**
	 * Sets a new value to an offset.
	 *
	 * @access public
	 * @param string $offset The offset name.
	 * @param mixed  $value The offset value.
	 */
	public function __set( $offset, $value ) {
		$this->offsetSet( $offset, $value );
	}

	/**
	 * Returns offset value. Returns NULL if offset doesn't exist.
	 *
	 * @access public
	 * @param string $offset The offset name.
	 * @return mixed The offset value if exists, otherwise NULL.
	 */
	public function __get( $offset ) {
		return $this->offsetGet( $offset );
	}

	/**
	 * Checks whether an offset exists or not.
	 *
	 * @access public
	 * @param string $offset The offset name to check.
	 * @return boolean TRUE if offset exists, otherwise FALSE.
	 */
	public function __isset( $offset ) {
		return $this->offsetExists( $offset );
	}

	/**
	 * Removes offset from a module.
	 *
	 * @access public
	 * @param string $offset The offset name to remove.
	 */
	public function __unset( $offset ) {
		$this->offsetUnset( $offset );
	}

	/**
	 * Checks whether an offset exists or not.
	 *
	 * @access public
	 * @param string $offset The offset name to check.
	 * @return boolean TRUE if offset exists, otherwise FALSE.
	 */
	public function offsetExists( $offset ): bool {
		return array_key_exists( $offset, $this->_data );
	}

	/**
	 * Returns offset value. Returns NULL if offset doesn't exist.
	 *
	 * @access public
	 * @param string $offset The offset name.
	 * @return mixed The offset value if exists, otherwise NULL.
	 */
	public function offsetGet( $offset ): mixed {
		return array_key_exists( $offset, $this->_data )
			? $this->_data[ $offset ]
			: null;
	}

	/**
	 * Sets a new value to an offset.
	 *
	 * @access public
	 * @param string $offset The offset name.
	 * @param mixed  $value The offset value.
	 */
	public function offsetSet( $offset, $value ): void {
		$this->_data[ $offset ] = $value;
	}

	/**
	 * Removes offset from a module.
	 *
	 * @access public
	 * @param string $offset The offset name to remove.
	 */
	public function offsetUnset( $offset ): void {
		unset( $this->_data[ $offset ] );
	}

	/**
	 * Registers module and its submodules.
	 *
	 * @access public
	 */
	public function register() {
	}

	/**
	 * Registers a new hook for an action.
	 *
	 * @access protected
	 * @param string $tag The action name to register hook to.
	 * @param string $method The callback method to call.
	 * @param int    $priority The hook priority.
	 * @param int    $accepted_args The number of arguments.
	 */
	protected function _add_action( $tag, $method, $priority = 10, $accepted_args = 1 ) { // phpcs:ignore
		add_action( $tag, array( $this, $method ), $priority, $accepted_args );
	}

	/**
	 * Removes hook from an action.
	 *
	 * @access protected
	 * @param string $tag The action name to remove hook from.
	 * @param string $method The callback method to remove.
	 * @param int    $priority The hook priority.
	 */
	protected function _remove_action( $tag, $method, $priority = 10 ) { // phpcs:ignore
		remove_action( $tag, array( $this, $method ), $priority );
	}

	/**
	 * Registers a new hook for a filter.
	 *
	 * @access protected
	 * @param string $tag The filter name to register hook to.
	 * @param string $method The callback method to call.
	 * @param int    $priority The hook priority.
	 * @param int    $accepted_args The number of arguments.
	 */
	protected function _add_filter( $tag, $method, $priority = 10, $accepted_args = 1 ) { // phpcs:ignore
		add_filter( $tag, array( $this, $method ), $priority, $accepted_args );
	}

	/**
	 * Removes hook from a filter.
	 *
	 * @access protected
	 * @param string $tag The filter name to remove hook from.
	 * @param string $method The callback method to remove.
	 * @param int    $priority The hook priority.
	 */
	protected function _remove_filter( $tag, $method, $priority = 10 ) { // phpcs:ignore
		remove_filter( $tag, array( $this, $method ), $priority );
	}

}
