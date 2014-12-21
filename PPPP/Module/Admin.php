<?php
/**
 *
 * Admin Page Actions.
 *
 * @package PPPP
 * @since 0.7
 *
 */

Class PPPP_Module_Admin extends PPPP_Module {

	public function add_hook() {
		add_action( "admin_init", array($this, "add_settings_section"), 11 );
		add_action( "admin_init", array($this ,"add_settings_fields"), 12 );
		add_action( 'admin_enqueue_scripts', array( $this,'enqueue_css_js') );
		add_action( 'admin_footer', array( $this,'pointer_js') );
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



	/**
	 *
	 * enqueue CSS and JS
	 * @since 0.7.2
	 *
	 */
	public function enqueue_css_js() {
		wp_enqueue_style('wp-pointer');
		wp_enqueue_script('wp-pointer');
	}


	/**
	 *
	 * add js for pointer
	 * @since 0.7.2
	 */
	public function pointer_js() {
		if(!is_network_admin()) {
			$dismissed = explode(',', get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ));
			if(array_search('pppp_pointer072', $dismissed) === false){
				$content = "<h3>".__("Powerful Posts Per Page",'pppp')."</h3>".__("<p>From <a href='options-reading.php'>Reading</a>, set posts per page for each post type/taxonomy archives.</p>", "pppp");
			?>
				<script type="text/javascript">
				jQuery(function($) {

					$("#menu-settings .wp-has-submenu").pointer({
						content: "<?php echo $content;?>",
						position: {"edge":"left","align":"center"},
						close: function() {
							$.post('admin-ajax.php', {
								action:'dismiss-wp-pointer',
								pointer: 'pppp_pointer072'
							})

						}
					}).pointer("open");
				});
				</script>
			<?php
			}
		}
	}

}