<?php
/**
 * Portfolio Post Type
 *
 * @package   Portfolio_Post_Type
 * @author    Francisco Bizi
 * @license   GPL-2.0+
 */

/**
 * Portfolio post type.
 *
 * @package Portfolio_Post_Type
 * @author  Francisco Bizi
 */
class Portfolio_Post_Type{
	/**
	 * Post type ID.
	 *
	 * @since 1.0.0
	 *
	 * @type string
	 */
	protected $post_type = 'portfolio';

	/**
	 * Return post type default arguments.
	 *
	 * @since 1.0.0
	 *
	 * @return array Post type default arguments.
	 */
	protected function cpt_args() {
		$labels = array(
			'name'                  => __( 'Portfolios', 'the-portfolio' ),
			'singular_name'         => __( 'Portfolio Item', 'the-portfolio' ),
			'menu_name'             => _x( 'Portfolio', 'admin menu', 'the-portfolio' ),
			'name_admin_bar'        => _x( 'Portfolio Item', 'add new on admin bar', 'the-portfolio' ),
			'add_new'               => __( 'Add New Item', 'the-portfolio' ),
			'add_new_item'          => __( 'Add New Portfolio Item', 'the-portfolio' ),
			'new_item'              => __( 'Add New Portfolio Item', 'the-portfolio' ),
			'edit_item'             => __( 'Edit Portfolio Item', 'the-portfolio' ),
			'view_item'             => __( 'View Item', 'the-portfolio' ),
			'all_items'             => __( 'All Portfolio Items', 'the-portfolio' ),
			'search_items'          => __( 'Search Portfolio', 'the-portfolio' ),
			'parent_item_colon'     => __( 'Parent Portfolio Item:', 'the-portfolio' ),
			'not_found'             => __( 'No portfolio items found', 'the-portfolio' ),
			'not_found_in_trash'    => __( 'No portfolio items found in trash', 'the-portfolio' ),
			'filter_items_list'     => __( 'Filter portfolio items list', 'the-portfolio' ),
			'items_list_navigation' => __( 'Portfolio items list navigation', 'the-portfolio' ),
			'items_list'            => __( 'Portfolio items list', 'the-portfolio' ),
		);

		$supports = array(
			'title',
			'editor',
			'excerpt',
			'thumbnail',
			'comments',
			'author',
			'custom-fields',
			'revisions',
		);

		$args = array(
			'labels'          => $labels,
			'supports'        => $supports,
			'public'          => true,
			'capability_type' => 'post',
			'rewrite'         => array( 'slug' => 'portfolio', ), // Permalinks format
			'menu_position'   => 5,
			'menu_icon'       => ( version_compare( $GLOBALS['wp_version'], '3.8', '>=' ) ) ? 'dashicons-portfolio' : false ,
			'has_archive'     => true,
			'show_in_rest'    => true,
			'taxonomies'      => array('category'),
		);

		return $args; 
	}

	/**
	 * Register the post type.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		register_post_type( $this->post_type, $this->cpt_args() );
	}

	/**
	 * Get the post type name.
	 *
	 * @since 1.0.0
	 */
	public function get_post_type() {
		return $this->post_type;
	}

	
}
