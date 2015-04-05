<?php
/**
 * @package PPPP
 * @version 0.8.0
 */
/*
Plugin Name: Powerful Posts Per Page
Plugin URI: https://github.com/torounit/pppp
Description: Posts per page for custom post types and taxonomies.
Version: 0.8.0
Author: Toro_Unit
Author URI: http://www.torounit.com
License: GPL2 or Later
Domain Path: /language/
*/


define( "PPPP_PLUGIN_FILE", __FILE__ );

function pppp_class_loader( $class_name ) {
	$dir = dirname(__FILE__);
	$file_name = $dir . "/". str_replace("_", "/", $class_name).".php";
	if( is_readable($file_name) ) {
		include $file_name;
	}
}
spl_autoload_register("pppp_class_loader");


/**
 *
 * Main class.
 *
 * @package PPPP
 * @since 0.6
 *
 */

Class PPPP {

	private $init, $option, $core, $admin;

	public function __construct () {

		$this->init = new PPPP_Module_Init();
		$this->option = new PPPP_Module_Option();
		$this->core   = new PPPP_Module_Core();
		$this->admin  = new PPPP_Module_Admin();

		do_action("PPPP_init");
	}


}


new PPPP();
