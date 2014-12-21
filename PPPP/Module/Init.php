<?php

/**
 *
 * Actions in Loading Plugin class.
 *
 * @package PPPP
 * @since 0.7.2
 *
 */

Class PPPP_Module_Init extends PPPP_Module {

	public function add_hook() {
		add_action( 'init', array( $this,'load_textdomain') );
	}

	public function load_textdomain() {
		load_plugin_textdomain( 'pppp', false, "pppp/language" );

	}
}