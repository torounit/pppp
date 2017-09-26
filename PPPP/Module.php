<?php
/**
 * Module interface.
 *
 * @package PPPP
 */

/**
 * Class PPPP_Module
 */
abstract class PPPP_Module {

	/**
	 * PPPP_Module constructor.
	 */
	public function __construct() {
		add_action( 'pppp_init', array( $this, 'add_hook' ) );
	}

	/**
	 * Hook point.
	 *
	 * @return void
	 */
	abstract function add_hook();

}

