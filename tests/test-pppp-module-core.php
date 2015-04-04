<?php
/**
 * @runTestsInSeparateProcesses
 */
Class Test_PPPP_Module_Core extends WP_UnitTestCase {

	public function setUp() {

		parent::setUp();

		create_initial_taxonomies();

		update_option( 'posts_per_page', 10 );
	}


	/**
	 * @test
	 */
	public function test_post_type() {

		$post_type = rand_str( 12 );

		update_option( 'posts_per_page_of_cpt_' . $post_type, 5 );

		register_post_type( $post_type,
			array(
				"public"      => true,
				"has_archive" => true,
			)
		);

		$this->factory->post->create_many( 10, array( 'post_type' => $post_type ) );

		add_action( 'wp', function () {
			global /** @var WP_Query $wp_query */
			$wp_query;
			$this->assertCount( 5, $wp_query->posts );
		} );

		$this->go_to( get_post_type_archive_link( $post_type ) );

	}

	/**
	 * @test
	 */
	public function test_taxonomy() {
		$taxonomy = rand_str( 12 );

		update_option( 'posts_per_page_of_tax_' . $taxonomy, 5 );
		register_taxonomy( $taxonomy, 'post',
			array(
				"public" => true,
			)
		);
		$term_id = $this->factory->term->create( array( 'taxonomy' => $taxonomy ) );
		$ids = $this->factory->post->create_many( 10,
			[
				'tax_input' => [
					$taxonomy => [ $term_id ]
				]
			]
		);

		foreach( $ids as $id ) {
			wp_add_object_terms( $id, $term_id, $taxonomy );
		}
		
		add_action( 'wp', function () {
			global /** @var WP_Query $wp_query */
			$wp_query;
			$this->assertEquals( 5, $wp_query->post_count );
		} );

		$this->go_to( get_term_link( $term_id, $taxonomy ) );
	}

}