<?php
/**
 * Portfolio Post Type
 *
 * @package   Portfolio_Post_Type
 * @author    Francisco Bizi
 */

/**
 * Portfolio category taxonomy.
 *
 * @package Portfolio_Post_Type
 * @author  Francisco Bizi
 */
class Portfolio_Post_Type_Taxonomy {
	/**
	 * Taxonomy ID.
	 *
	 * @since 1.0.0
	 *
	 * @type string
	 */
	protected $taxonomy = 'theportfolio';

	/**
	 * Taxonomy object.
	 *
	 * @since 1.0.0
	 *
	 * @type array
	 */
	protected $taxonomy_object_type = [];

	/**
	 * Return taxonomy default arguments.
	 *
	 * @since 1.0.0
	 *
	 * @return array Taxonomy default arguments.
	 */
	protected function tax_args() {
		$labels = array(
			'name'                       => __( 'Portfolio Categories', 'the-portfolio' ),
			'singular_name'              => __( 'Portfolio Category', 'the-portfolio' ),
			'menu_name'                  => __( 'Portfolio Categories', 'the-portfolio' ),
			'edit_item'                  => __( 'Edit Portfolio Category', 'the-portfolio' ),
			'update_item'                => __( 'Update Portfolio Category', 'the-portfolio' ),
			'add_new_item'               => __( 'Add New Portfolio Category', 'the-portfolio' ),
			'new_item_name'              => __( 'New Portfolio Category Name', 'the-portfolio' ),
			'parent_item'                => __( 'Parent Portfolio Category', 'the-portfolio' ),
			'parent_item_colon'          => __( 'Parent Portfolio Category:', 'the-portfolio' ),
			'all_items'                  => __( 'All Portfolio Categories', 'the-portfolio' ),
			'search_items'               => __( 'Search Portfolio Categories', 'the-portfolio' ),
			'popular_items'              => __( 'Popular Portfolio Categories', 'the-portfolio' ),
			'separate_items_with_commas' => __( 'Separate portfolio categories with commas', 'the-portfolio' ),
			'add_or_remove_items'        => __( 'Add or remove portfolio categories', 'the-portfolio' ),
			'choose_from_most_used'      => __( 'Choose from the most used portfolio categories', 'the-portfolio' ),
			'not_found'                  => __( 'No portfolio categories found.', 'the-portfolio' ),
			'items_list_navigation'      => __( 'Portfolio categories list navigation', 'the-portfolio' ),
			'items_list'                 => __( 'Portfolio categories list', 'the-portfolio' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_tagcloud'     => false,
			'rewrite'           => array( 'slug' => $this->taxonomy ),
			'show_admin_column' => true,
			'query_var'         => true,
			'show_in_rest'      => true,
			'hierarchical'      => true,
		);

		return $args;
	}

	/**
	 * Register a taxonomy.
	 *
	 * Setting the object type explicitly to null registers the taxonomy but doesn't associate it with any objects, so
	 * it won't be directly available within the Admin UI. You will need to manually register it using the 'taxonomy'
	 * parameter (passed through $args) when registering a custom post_type (see register_post_type()), or using
	 * register_taxonomy_for_object_type().
	 *
	 * @since 1.0.0
	 *
	 * @param  array|string $object_type Optional. Name of the object type. Default is null.
	 */
	public function register( $object_type = null ) {
		if($object_type != null){
			array_push($this->taxonomy_object_type, $object_type);
		}
		register_taxonomy( $this->taxonomy, $this->taxonomy_object_type, $this->tax_args );
	}

	/**
	 * Get the post type tax name.
	 *
	 * @since 1.0.0
	 */
	public function get_taxonomy() {
		return $this->taxonomy;
	}
}