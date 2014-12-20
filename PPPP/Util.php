<?php

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

	private function __construct() {
	}

	private static $post_types = null;
	private static $taxonomies = null;

	public static function get_post_types() {
		if( !self::$post_types ) {
			self::$post_types =  get_post_types( array('_builtin'=>false, 'publicly_queryable'=>true, 'show_ui' => true, "has_archive" => true), "objects");
		}
		return self::$post_types;
	}

	public static function get_taxonomies() {
		if( !self::$taxonomies ) {
			self::$taxonomies = get_taxonomies( array( "public" => true ), "objects");
		}
		return self::$taxonomies;
	}
}

