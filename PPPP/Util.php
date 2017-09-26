<?php
/**
 * Utility.
 *
 * @package PPPP
 */

/**
 * Utility class.
 * This class method is static.
 *
 * @since 0.7
 */
class PPPP_Util {

	/**
	 * PPPP_Util constructor. Cannot create instance.
	 */
	private function __construct() {
	}

	/**
	 * Post types.
	 *
	 * @var WP_Post_Type[]
	 */
	private static $post_types;

	/**
	 * Taxonomies
	 *
	 * @var WP_Taxonomy[]
	 */
	private static $taxonomies;

	/**
	 * Get post types.
	 *
	 * @return WP_Post_Type[]
	 */
	public static function get_post_types() {
		if ( ! self::$post_types ) {
			$param = array(
				'_builtin'           => false,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'has_archive'        => true,
			);
			self::$post_types = get_post_types( $param, 'objects' );
		}

		return self::$post_types;
	}

	/**
	 * Get taxonomies.
	 *
	 * @return WP_Taxonomy[]
	 */
	public static function get_taxonomies() {
		if ( ! self::$taxonomies ) {
			$param = array(
				'public' => true,
			);
			self::$taxonomies = get_taxonomies( $param, 'objects' );
		}

		return self::$taxonomies;
	}
}

