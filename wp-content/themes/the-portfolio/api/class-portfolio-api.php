<?php
/**
 * Portfolio API
 *
 * @package   Portfolio_API
 * @author    Francisco Bizi
 * @license   GPL-2.0+
 */

/**
 * Portfolio post type.
 *
 * @package Portfolio_API
 * @author  Francisco Bizi
 */
class Portfolio_API{

	private $total_pages;
	private $per_page = 3;

	function __construct(){
		$portfolios = get_posts(array(
			'post_type'   => 'portfolio',
			'numberposts' => -1,
		));

		$this->total_pages = ceil(count($portfolios) / $this->per_page);
	}

    public function init() {
		// Register routes
		add_action( 'rest_api_init', array( $this, 'routes' ) );
	}

    /**
	 * Create portfolio.
	 *
	 * @since 1.0.0
	 * @var object | Request object
	 * @return int Post ID.
	 */
	public function create($request) {

		$title       = isset($request['title']) ? wp_strip_all_tags( $request['title'] ) : "";
		$author      = isset($request['author']) ? (int)$request['author'] : 1; 
		$excerpt     = isset($request['excerpt']) ? $request['excerpt'] : "";
		$content     = isset($request['content']) ? $request['content'] : "";
		$thumbnail   = isset($request['thumbnail']) ? $request['thumbnail'] : ""; 
		$gallery     = isset($request['gallery']) ? $request['gallery'] : []; 
		$team        = isset($request['team']) ? $request['team'] : ""; 
		$categories  = isset($request['categories']) ? $request['categories'] : []; 
		
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		$created = array(
			'post_title'    => $title,
			'post_content'  => $content,
			'post_status'   => 'publish',
			'post_author'   => $author,
			'post_excerpt'  => $excerpt,
			'post_type'     => 'portfolio',
		);

		
		// Insert the post into the database
		$created_id = wp_insert_post( $created );

		$img_ext = ['jpg','png','gif'];
		$thumbnail_type = wp_check_filetype($thumbnail);
		
		// set featured image to portfolio
		if(!is_wp_error($created_id) && in_array($thumbnail_type['ext'],$img_ext)){
			$featured_img_id = media_sideload_image( $thumbnail, $created_id, '', 'id' );
			if(!is_wp_error( $featured_img_id)){
				// set image as the post thumbnail
				set_post_thumbnail($created_id, $featured_img_id);
			} 
		}else{
			http_response_code(500);
			return ["message" => "Something went wrong during creation. Try again!"];
		}
		
		// if there are categories
		if(is_array($categories) && count($categories) > 0){
			wp_set_object_terms( $created_id, $categories, 'theportfolio' );
		}
		
		// insert gallery images
		if($created_id && class_exists('ACF') && count($gallery) > 0){
			foreach($gallery as $image){
				$photo_type = wp_check_filetype($image["photo"]);
				if(in_array($photo_type['ext'],$img_ext)){
					$image_id = media_sideload_image( $image["photo"], $created_id, '', 'id' );
					add_row(
						"images",
						array('photo' => $image_id),													
						$created_id
					);
				}
			}
		}

		// insert team info
		if($created_id && class_exists('ACF') && count($team) > 0){
			foreach($team as $user){
				$photo_type = wp_check_filetype($user["photo"]);
				if(in_array($photo_type['ext'],$img_ext)){
					$photo_src = media_sideload_image( $user['photo'], $created_id, '', 'id' );
				}else{
					$photo_src = '';
				}
				add_row(
						"team",
						array(
									'name' => $user['name'],
									'description' => $user['description'],
									'photo' => $photo_src,
									'social_link' => $user['social_link']													
							),
						$created_id
				);
				
			}
		}

		http_response_code(200);
		return ["ID" => $created_id];
    }


	/**
	 * Return portfolios collection.
	 *
	 * @since 1.0.0
	 *
	 * @return array Portfolios.
	 */
	public function get_all() {

		$cpage = (int)$_GET['cpage'];
		$offset = ($cpage * $this->per_page);

        $portfolios = get_posts(array(
			'post_type'   => 'portfolio',
			'numberposts' => $this->per_page,
			'offset'      => $offset,
		));

		$items = [];

		if(count($portfolios) > 0){
			foreach($portfolios as $post){
				$thumbnail = !empty(get_the_post_thumbnail_url($post->ID, 'full')) ? get_the_post_thumbnail_url($post->ID, 'full') : "";
				$team = !empty(get_field("team",$post->ID)) ? get_field("team",$post->ID) : [];
				$gallery = !empty(get_field("images",$post->ID)) ? get_field("images",$post->ID) : [];
				$categories = wp_get_post_terms( $post->ID, 'theportfolio', array('fields' => 'names') );

				array_push($items,[
					"ID" 			=> $post->ID,
					"title" 		=> $post->post_title,
					"excerpt"	 	=> $post->post_excerpt,
					"content" 		=> $post->post_content,
					"thumbnail"		=> $thumbnail,
					"permalink"		=> get_post_permalink($post->ID),
					"gallery" 		=> $gallery,
					"team" 			=> $team,
					"categories" 	=> $categories,

					]);
			}
		}

        return ['items' => $items, 'cpage' => $cpage, 'total_pages' => $this->total_pages];
	}
	
	/**
	 * Return portfolios IDs collection.
	 *
	 * @since 1.0.0
	 *
	 * @return array Portfolios.
	 */
	public function get_all_ids() {

		$cpage = (int)$_GET['cpage'];
		$offset = ($cpage * $this->per_page);

        $portfolios = get_posts(array(
			'post_type'   => 'portfolio',
			'numberposts' => $this->per_page,
			'offset'      => $offset,
		));

		$ids = [];

		if(count($portfolios) > 0){
			foreach($portfolios as $post){
				array_push($ids,["ID" => $post->ID]);
			}
		}

        return ['items' => $ids, 'cpage' => $cpage, 'total_pages' => $this->total_pages];
    }

    /**
	 * Return portfolio.
	 *
	 * @since 1.0.0
	 *
	 * @return array Portfolio.
	 */
	public function get_one($request) {
		$id = isset($request['id']) ? (int)$request['id'] : null;
		
		$post = get_post($id);

		$item = [];
		if(isset($post->post_type) && $post->post_type != 'portfolio'){
			return ['item' => $item];
		}


		if($post){
				$thumbnail = !empty(get_the_post_thumbnail_url($post->ID, 'full')) ? get_the_post_thumbnail_url($post->ID, 'full') : "";
				$team = !empty(get_field("team",$post->ID)) ? get_field("team",$post->ID) : [];
				$gallery = !empty(get_field("images",$post->ID)) ? get_field("images",$post->ID) : [];
				$categories = wp_get_post_terms( $post->ID, 'theportfolio', array('fields' => 'names') );

				array_push($item,[
					"ID" 			=> $post->ID,
					"title" 		=> $post->post_title,
					"excerpt"	 	=> $post->post_excerpt,
					"content" 		=> $post->post_content,
					"thumbnail"		=> $thumbnail,
					"permalink"		=> get_post_permalink($post->ID),
					"gallery" 		=> $gallery,
					"team" 			=> $team,
					"categories" 	=> $categories,

					]);
		}

        return ['item' => $item];
    }

	/**
	 * Set routes.
	 *
	 * @since 1.0.0
	 */
	public function routes() {

		// create portfolio
        register_rest_route('api/v1','/portfolios',array(
            'methods'   => 'POST',
			'callback'  => array($this, 'create'),
			'permission_callback' => function() { return ''; }, 
		));

		// get portfolios
        register_rest_route('api/v1','/portfolios',array(
            'methods'   => 'GET',
			'callback'  => array($this, 'get_all'),
			'permission_callback' => function() { return ''; }, 
		));

		// get portfolios's ids
        register_rest_route('api/v1','/portfolios/ids',array(
            'methods'   => 'GET',
			'callback'  => array($this, 'get_all_ids'),
			'permission_callback' => function() { return ''; }, 
		));

		// get portfolio
        register_rest_route('api/v1','/portfolios/(?P<id>[\d]+)',array(
            'methods'   => 'GET',
			'callback'  => array($this, 'get_one'),
			'permission_callback' => function() { return ''; }, 
		));
    }

	
}

$cpt_api = new Portfolio_API;
$cpt_api->init();