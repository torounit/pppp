<?php
/**
 * Actions in Loading Plugin
 *
 * @package PPPP
 */

/**
 * Init
 *
 * @since 0.7.2
 */
class PPPP_Module_Init extends PPPP_Module {

	/**
	 * Hooks.
	 */
	public function add_hook() {
		add_action( 'init', array( $this, 'load_textdomain' ) );
	}

	/**
	 * Load translation.
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'pppp', false, 'pppp/language' );
	}
}
