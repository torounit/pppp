<?php
/**
 * @package PPPP
 * @version 0.7
 */
/*
Plugin Name: Powerful Posts Per Page
Plugin URI: https://github.com/torounit/pppp
Description: Posts per page for custom post types and taxonomies.
Version: 0.7
Author: Toro_Unit
Author URI: http://www.torounit.com
License: GPL2 or Later
Domain Path: /language/
*/



/**
 *
 * Main class.
 *
 * @package PPPP
 * @since 0.6
 *
 */

Class PPPP {

	public function __construct () {

		$this->option = new PPPP_Option();
		$this->core   = new PPPP_Core();
		$this->admin  = new PPPP_Admin();
		$this->add_hooks();
	}

	public function add_hooks() {
		add_action( 'init', array( $this,'load_textdomain') );
		add_action( "pre_get_posts", array($this->core,"pre_get_posts"));
		add_action( "admin_init", array($this->option,"save_option"), 10);
		add_action( "admin_init", array($this->admin,"add_settings_section"), 11 );
		add_action( "admin_init", array($this->admin,"add_settings_fields"), 12 );

		register_uninstall_hook( __FILE__, array( __CLASS__, 'uninstall_hook') );
	}


	public function load_textdomain() {
		load_plugin_textdomain( 'pppp', false, dirname(plugin_basename(__FILE__))."/language" );
	}

	public static function uninstall_hook() {
		PPPP_Option::delete_options();
	}
}



/**
 *
 * Core Action class.
 *
 * @package PPPP
 * @since 0.7
 *
 */

Class PPPP_Core {

	public function pre_get_posts( $query ) {
		if($query->is_main_query() and !is_admin()) {
			$posts_per_page = get_option( "posts_per_page" );
			foreach (PPPP_Util::get_post_types() as $post_type) {
				if($query->is_post_type_archive( $post_type->name )) {
					$query->set("posts_per_page", get_option( "posts_per_page_of_cpt_".$post_type->name, $posts_per_page));
					return;
				}
			}

			foreach (PPPP_Util::get_taxonomies() as $taxonomy) {
				if($query->is_tax( $taxonomy->name )) {
					$query->set("posts_per_page", get_option( "posts_per_page_of_tax_".$taxonomy->name, $posts_per_page));
					return;
				}
			}
		}
	}
}


/**
 *
 * Utility class.
 * This class method is static.
 *
 * @package PPPP
 * @since 0.7
 *
 */

Class PPPP_Util {
	public static function get_post_types() {
		return get_post_types( array('_builtin'=>false, 'publicly_queryable'=>true, 'show_ui' => true, "has_archive" => true), "objects");
	}

	public static function get_taxonomies() {
		return get_taxonomies( array( "public" => true ), "objects");
	}
}


/**
 *
 * Option API class.
 *
 * @package PPPP
 * @since 0.7
 *
 */

Class PPPP_Option {

	public function save_option() {
		if(isset($_POST['submit']) && isset($_POST['_wp_http_referer']) && strpos($_POST['_wp_http_referer'],'options-reading.php') !== FALSE ) {
			self::update_all_options();
		}
	}

	public static function update_all_options() {
		foreach (PPPP_Util::get_post_types() as $post_type) {
			if(isset($_POST["posts_per_page_of_cpt_".$post_type->name])) {
				update_option("posts_per_page_of_cpt_".$post_type->name, $_POST["posts_per_page_of_cpt_".$post_type->name]);
			}
		}

		foreach (PPPP_Util::get_taxonomies() as $taxonomy) {
			if(isset($_POST["posts_per_page_of_tax_".$taxonomy->name])) {
				update_option("posts_per_page_of_tax_".$taxonomy->name, $_POST["posts_per_page_of_tax_".$taxonomy->name]);
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
}


/**
 *
 * Admin Page Actions.
 *
 * @package PPPP
 * @since 0.7
 *
 */

Class PPPP_Admin {

	public function add_settings_section() {
		add_settings_section('pppp', __("Powerful Posts Per Page",'pppp'), array( $this,'section_content'), 'reading');
	}

	public function section_content() {
		?>
		<p><?php _e("Set posts per page for each post type/taxonomy archives.", "pppp");?></p>
		<?php
	}

	public function add_settings_fields() {
		foreach (PPPP_Util::get_post_types() as $post_type) {
			$this->add_settings_field("posts_per_page_of_cpt_".$post_type->name, $post_type, "post_type");
		}
		foreach (PPPP_Util::get_taxonomies() as $taxonomy) {
			$this->add_settings_field("posts_per_page_of_tax_".$taxonomy->name, $taxonomy, "taxonomy");
		}
	}

	private function add_settings_field( $id, $obj, $type ) {
			add_settings_field( $id, sprintf(__("%s archive show at most" ,"pppp"), $obj->label), array($this, "create_field"), "reading", 'pppp', array("label_for" => $id, "obj" => $obj ,"type" => $type));
	}

	public function create_field( $arg ) {
		$obj = $arg["obj"];
		$field = $arg["label_for"];
		$value = get_option($field);
		if( $value === false ) {
			$value = get_option( "posts_per_page" );
		}
		?>
		<input name="<?php echo esc_attr($field);?>" type="number" step="1" min="-1" id="<?php echo esc_attr($field);?>" value="<?php echo esc_attr($value); ?>" class="small-text" /> <?php _e( 'posts' ); ?>
		<?php
	}
}

new PPPP();