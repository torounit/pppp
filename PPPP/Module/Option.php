<?php
/**
 * Option maneger.
 *
 * @package PPPP
 */

/**
 * Option API class.
 *
 * @since 0.7
 */
class PPPP_Module_Option extends PPPP_Module {

	/**
	 * Hooks.
	 */
	public function add_hook() {
		register_uninstall_hook( PPPP_PLUGIN_FILE, array( __CLASS__, 'uninstall_hook' ) );
	}

	/**
	 * Delete options.
	 */
	public static function uninstall_hook() {
		foreach ( PPPP_Util::get_post_types() as $post_type ) {
			delete_option( 'posts_per_page_of_cpt_' . $post_type->name );
		}

		foreach ( PPPP_Util::get_taxonomies() as $taxonomy ) {
			delete_option( 'posts_per_page_of_tax_' . $taxonomy->name );
		}
	}

}
