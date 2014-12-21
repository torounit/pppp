<?php

/**
 *
 * Option API class.
 *
 * @package PPPP
 * @since 0.7
 *
 */



Class PPPP_Module_Option extends PPPP_Module {

	public function add_hook() {
		add_action( "admin_init", array($this,"save_option"), 10);
		register_uninstall_hook( PPPP_PLUGIN_FILE, array( __CLASS__, 'uninstall_hook') );

	}

	public function save_option() {
		if(isset($_POST['submit']) && isset($_POST['_wp_http_referer']) && strpos($_POST['_wp_http_referer'],'options-reading.php') !== FALSE ) {
			self::update_all_options();
		}
	}

	public static function update_all_options() {
		foreach (PPPP_Util::get_post_types() as $post_type) {
			if(isset($_POST["posts_per_page_of_cpt_".$post_type->name])) {
				self::update_option("posts_per_page_of_cpt_".$post_type->name, $_POST["posts_per_page_of_cpt_".$post_type->name]);
			}
		}

		foreach (PPPP_Util::get_taxonomies() as $taxonomy) {
			if(isset($_POST["posts_per_page_of_tax_".$taxonomy->name])) {
				self::update_option("posts_per_page_of_tax_".$taxonomy->name, $_POST["posts_per_page_of_tax_".$taxonomy->name]);
			}
		}
	}

	public static function delete_all_options() {
		foreach (PPPP_Util::get_post_types() as $post_type) {
			delete_option("posts_per_page_of_cpt_".$post_type->name);
		}

		foreach (PPPP_Util::get_taxonomies() as $taxonomy) {
			delete_option("posts_per_page_of_tax_".$taxonomy->name);
		}
	}

	public static function update_option( $key, $value ) {
		$value = intval( $value );
		if( $value < -1 ) {
			$value = -1;
		}
		update_option( $key, $value );
	}

	public static function uninstall_hook() {
		self::delete_all_options();
	}

}