<?php
/**
 * Portfolio Post Type
 *
 * @package   Portfolio_Post_Type
 * @author    Francisco Bizi
 * @license   GPL-2.0+
 */

/**
 * Register post types and taxonomies.
 *
 * @package Portfolio_Post_Type
 * @author  Francisco Bizi
 */
class Portfolio_Post_Type_Tax_Registrations {

	public function init() {
		// Add the portfolio post type and taxonomies
		add_action( 'init', array( $this, 'register' ) );
	}

	/**
	 * Initiate registrations of post type and taxonomies.
	 */
	public function register() {
		global $portfolio_cpt, $portfolio_cpt_taxonomy;

		$portfolio_cpt = new Portfolio_Post_Type;
		$portfolio_cpt->register();

		$portfolio_cpt_taxonomy = new Portfolio_Post_Type_Taxonomy;
		$portfolio_cpt_taxonomy->register($portfolio_cpt->get_post_type());

	}

	
}

$cpt = new Portfolio_Post_Type_Tax_Registrations;
$cpt->init();