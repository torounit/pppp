<?php
/**
 * Admin.
 *
 * @package PPPP
 */

/**
 * Admin Page Actions.
 *
 * @since 0.7
 */
class PPPP_Module_Admin extends PPPP_Module {

	/**
	 * Add actions.
	 */
	public function add_hook() {
		add_action( 'admin_init', array( $this, 'add_settings_section' ), 11 );
		add_action( 'admin_init', array( $this, 'add_settings_fields' ), 12 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_css_js' ) );
		add_action( 'admin_footer', array( $this, 'pointer_js' ) );
	}

	/**
	 * Register form section.
	 */
	public function add_settings_section() {
		add_settings_section(
			'pppp',
			__( 'Powerful Posts Per Page', 'pppp' ),
			array( $this, 'section_description' ),
			'reading'
		);
	}

	/**
	 * Section title.
	 */
	public function section_description() {
		?><p><?php esc_html_e( 'Set posts per page for each post type/taxonomy archives.', 'pppp' ); ?></p>
		<?php
	}

	/**
	 * Create input fields.
	 */
	public function add_settings_fields() {
		foreach ( PPPP_Util::get_post_types() as $post_type ) {
			$this->add_settings_field( 'posts_per_page_of_cpt_' . $post_type->name, $post_type );
		}
		foreach ( PPPP_Util::get_taxonomies() as $taxonomy ) {
			$this->add_settings_field( 'posts_per_page_of_tax_' . $taxonomy->name, $taxonomy );
		}
	}

	/**
	 * Register field
	 *
	 * @param string                   $id field id.
	 * @param WP_Post_Type|WP_Taxonomy $obj post type or taxonomy.
	 */
	private function add_settings_field( $id, $obj ) {
		add_settings_field(
			$id,
			/* translators: %s: Name of post type or taxonomy label */
			sprintf( __( '%s archive show at most', 'pppp' ), $obj->label ),
			array( $this, 'create_field' ),
			'reading',
			'pppp',
			array(
				'label_for' => $id,
			)
		);

		register_setting(
			'reading',
			$id,
			array( $this, 'sanitize' )
		);
	}

	/**
	 * Field markup.
	 *
	 * @param array $args add_settings_field callback param.
	 */
	public function create_field( $args ) {
		$field = $args['label_for'];
		$value = get_option( $field );
		if ( false === $value ) {
			$value = get_option( 'posts_per_page' );
		}
		?>
		<input name="<?php echo esc_attr( $field ); ?>" type="number" step="1" min="-1" id="<?php echo esc_attr( $field ); ?>" value="<?php echo esc_attr( $value ); ?>" class="small-text"/> <?php esc_html_e( 'posts' ); ?>
		<?php
	}

	/**
	 * Sanitize posts per page.
	 *
	 * @param int|string $maybeint Data you wish to have converted to a integer.
	 *
	 * @return int
	 */
	public function sanitize( $maybeint ) {
		$value = intval( $maybeint );
		if ( $value < - 1 ) {
			return - 1;
		}
		return $value;
	}


	/**
	 * Enqueue CSS and JS
	 *
	 * @since 0.7.2
	 */
	public function enqueue_css_js() {
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );
	}


	/**
	 * Add js for pointer
	 *
	 * @since 0.7.2
	 */
	public function pointer_js() {
		if ( ! is_network_admin() ) {
			$dismissed = explode( ',', get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
			if ( array_search( 'pppp_pointer072', $dismissed, true ) === false ) {
				$content = '<h3>' . __( 'Powerful Posts Per Page', 'pppp' ) . '</h3>' . __( "<p>From <a href='options-reading.php'>Reading</a>, set posts per page for each post type/taxonomy archives.</p>", 'pppp' );
				?>
				<script type="text/javascript">
					jQuery(function ($) {

						$('#menu-settings .wp-has-submenu').pointer({
							content: "<?php echo wp_kses_post( $content ); ?>",
							position: {"edge": "left", "align": "center"},
							close: function () {
								$.post('admin-ajax.php', {
									action: 'dismiss-wp-pointer',
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
