<?php
/*
 *
 */
get_header();
?>

	<main id="primary" class="site-main">
	    <div class="container">
			<?php
			while ( have_posts() ) :
				the_post();
				if(isset($_GET['ajax'])):
					get_template_part( 'template-parts/content', 'ajax' );
				else:
					get_template_part( 'template-parts/content', 'default' );
				endif;
			endwhile; // End of the loop.
			?>
		</div>
	</main><!-- #main -->
<?php
get_footer();