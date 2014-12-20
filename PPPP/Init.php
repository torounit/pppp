<?php

/**
 *
 * Actions in Loading Plugin class.
 *
 * @package PPPP
 * @since 0.7.2
 *
 */

Class PPPP_Init extends PPPP_Module {

	public function add_hook() {
		add_action( 'init', array( $this,'load_textdomain') );
		register_uninstall_hook( __FILE__, array( __CLASS__, 'uninstall_hook') );
	}

	public function load_textdomain() {
		load_plugin_textdomain( 'pppp', false, "pppp/language" );

	}

	public static function uninstall_hook() {
		PPPP_Option::delete_all_options();
	}

}