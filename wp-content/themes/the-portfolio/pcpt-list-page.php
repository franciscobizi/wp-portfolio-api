<?php
/*
 Template Name: Portfolio List Template
 */
get_header();
?>

	<main id="primary" class="site-main">
	    <div class="container">
			<h1>Portfolios list</h1>
			<?php
			$posts = new WP_Query( array(
				'posts_per_page'=>3,
				'post_type'=>'portfolio',
				'paged' => get_query_var('paged') ? get_query_var('paged') : 1
				) 
			); 
			?>
			<div class="portfolio-wrapper">
				<?php while ($posts->have_posts()) : $posts->the_post(); ?>
					<div class="portfolio-wrapper-item">
					    <a href="<?php echo get_the_permalink();?>" title="<?php echo get_the_title();?>">
						   <?php $image_src = get_the_post_thumbnail_url( get_the_ID(), 'full' )?>
						   <img src="<?php echo $image_src;?>" alt="<?php echo get_the_title();?>">
						   <p> <?php echo get_the_excerpt();?></p>
						</a>
					</div>
				<?php endwhile;?>
			</div>
			<?php
			$big = 999999999; // need an unlikely integer
			echo paginate_links( array(
				'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
				'format' => '?paged=%#%',
				'current' => max( 1, get_query_var('paged') ),
				'total' => $posts->max_num_pages
				) );
				
				wp_reset_postdata();
			?>
		</div>
	</main><!-- #main -->
<?php
get_footer();