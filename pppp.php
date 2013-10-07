<?php
/*
Plugin Name: Powerful Posts Per Page
Plugin URI: https://github.com/torounit/pppp
Description: Posts per page for custom post types and taxonomies
Version: 0.6
Author: Toro_Unit
Author URI: http://www.torounit.com
License: GPL2 or Later
*/



/**
 *
 * PPPP
 * @package PPPP
 * @version 0.6
 *
 */

Class PPPP {

	private $posts_per_page;

	public function __construct () {
		$this->posts_per_page = get_option( "posts_per_page" );
		$this->add_hooks();
		register_uninstall_hook( __FILE__, array( __CLASS__, 'uninstall_hook') );
	}


	public function add_hooks() {
		add_action( 'init', array( $this,'load_textdomain') );
		add_action( "pre_get_posts", array($this,"pre_get_posts"));
		add_action( "admin_init", array($this,"update_option"), 10);
		add_action( "admin_init", array($this,"add_settings_section"), 11 );
		add_action( "admin_init", array($this,"add_settings_fields"), 12 );
	}


	public function load_textdomain() {
		load_plugin_textdomain('pppp',false, dirname(plugin_basename(__FILE__))."/language" );
	}



	private static function get_post_types() {
		return get_post_types( array('_builtin'=>false, 'publicly_queryable'=>true, 'show_ui' => true, "has_archive" => true), "objects");
	}

	private static function get_taxonomies() {
		return get_taxonomies( array( "public" => true ), "objects");
	}


	public function pre_get_posts( $query ) {
		if($query->is_main_query() and !is_admin()) {
			foreach (self::get_post_types() as $post_type) {
				if($query->is_post_type_archive( $post_type->name )) {
					$query->set("posts_per_page", get_option( "posts_per_page_of_cpt_".$post_type->name, $this->posts_per_page));
					return;
				}
			}

			foreach (self::get_taxonomies() as $taxonomy) {
				if($query->is_tax( $taxonomy->name )) {
					$query->set("posts_per_page", get_option( "posts_per_page_of_tax_".$taxonomy->name, $this->posts_per_page));
					return;
				}
			}
		}
	}


	public function update_option() {
		if(isset($_POST['submit']) && isset($_POST['_wp_http_referer']) && strpos($_POST['_wp_http_referer'],'options-reading.php') !== FALSE ) {

			foreach (self::get_post_types() as $post_type) {
				if($_POST["posts_per_page_of_cpt_".$post_type->name]) {
					update_option("posts_per_page_of_cpt_".$post_type->name, $_POST["posts_per_page_of_cpt_".$post_type->name]);
				}
			}

			foreach (self::get_taxonomies() as $taxonomy) {
				if($_POST["posts_per_page_of_tax_".$taxonomy->name]) {
					update_option("posts_per_page_of_tax_".$taxonomy->name, $_POST["posts_per_page_of_tax_".$taxonomy->name]);
				}
			}
		}
	}

	public function add_settings_section() {
		add_settings_section('pppp', __("Powerful Posts Per Page",'pppp'), array( $this,'section_content'), 'reading');
	}

	public function section_content() {
		?>
		<p><?php _e("Set posts per page for each post type/taxonomy archives.", "pppp");?></p>
		<?php
	}

	public function add_settings_fields() {
		foreach (self::get_post_types() as $post_type) {
			$this->add_settings_field("posts_per_page_of_cpt_".$post_type->name, $post_type, "post_type");
		}
		foreach (self::get_taxonomies() as $taxonomy) {
			$this->add_settings_field("posts_per_page_of_tax_".$taxonomy->name, $taxonomy, "taxonomy");
		}
	}

	public function add_settings_field( $id, $obj, $type ) {
			add_settings_field( $id, sprintf(__("%s archive show at most" ,"pppp"), $obj->label), array($this, "create_field"), "reading", 'pppp', array("label_for" => $id, "obj" => $obj ,"type" => $type));
	}

	public function create_field( $arg ) {
		$obj = $arg["obj"];
		$field = $arg["label_for"];
		$value = esc_attr( get_option($field, $this->posts_per_page) );
		?>
		<input name="<?php echo $field;?>" type="number" step="1" min="-1" id="<?php echo $field;?>" value="<?php echo $value; ?>" class="small-text" /> <?php _e( 'posts' ); ?>
		<?php
	}


	public static function uninstall_hook() {
		foreach (self::get_post_types() as $post_type) {
			delete_option("posts_per_page_of_cpt_".$post_type->name);
		}

		foreach (self::get_taxonomies() as $taxonomy) {
			delete_option("posts_per_page_of_tax_".$taxonomy->name);
		}
	}

}

new PPPP();