<?php
/**
 * Plugin Name: Powerful Posts Per Page
 * Plugin URI: https://github.com/torounit/pppp
 * Description: You can change posts per page by taxonomy or category or tag or custom post type.
 * Version: 1.1.0
 * Author: Toro_Unit
 * Author URI: https://torounit.com
 * License: GPL2 or Later
 * Text Domain: pppp
 * Domain Path: /language/
 *
 * @package PPPP
 * @version 1.1.0
 */

define( 'PPPP_PLUGIN_FILE', __FILE__ );

/**
 * Autoloader.
 *
 * @param string $class_name classname.
 */
function pppp_class_loader( $class_name ) {
	$dir       = dirname( __FILE__ );
	$file_name = $dir . '/' . str_replace( '_', '/', $class_name ) . '.php';
	if ( is_readable( $file_name ) ) {
		include $file_name;
	}
}

spl_autoload_register( 'pppp_class_loader' );


/**
 * Main class.
 *
 * @package PPPP
 * @since 0.6
 */
class PPPP {

	/**
	 * PPPP_Module_Init
	 *
	 * @var PPPP_Module_Init
	 */

	private $init;
	/**
	 * PPPP_Module_Option
	 *
	 * @var PPPP_Module_Option
	 */

	private $option;
	/**
	 * PPPP_Module_Option
	 *
	 * @var PPPP_Module_Option
	 */
	private $core;

	/**
	 * PPPP_Module_Option
	 *
	 * @var PPPP_Module_Option
	 */
	private $admin;

	/**
	 * PPPP constructor.
	 */
	public function __construct() {
		$this->init   = new PPPP_Module_Init();
		$this->option = new PPPP_Module_Option();
		$this->core   = new PPPP_Module_Core();
		$this->admin  = new PPPP_Module_Admin();

		do_action( 'pppp_init' );
	}

}


new PPPP();
