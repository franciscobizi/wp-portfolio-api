<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package The_Portfolio
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta">
				<?php
				the_portfolio_posted_on();
				the_portfolio_posted_by();
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php the_portfolio_post_thumbnail(); ?>

	<div class="entry-content">
		<h2>Portfolio description</h2>
		<?php
		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'the-portfolio' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			)
		);

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'the-portfolio' ),
				'after'  => '</div>',
			)
		);
		$team = !empty(get_field("team",get_the_ID())) ? get_field("team",get_the_ID()) : [];
		$gallery = !empty(get_field("images",get_the_ID())) ? get_field("images",get_the_ID()) : [];
		?>

		<h2>Gallery</h2>
		<div class="portfolio-wrapper">
				<?php foreach ($gallery as $image):?>
					<div class="portfolio-wrapper-item">
						<img src="<?php echo $image['photo'];?>" alt="<?php echo get_the_title();?>">
					</div>
				<?php endforeach;?>
		</div><!--Gallery-->
		<h2>Team members</h2>
		<div class="portfolio-wrapper">
				<?php foreach ($team as $user):?>
					<div class="portfolio-wrapper-item">
						<img src="<?php echo $user['photo'];?>" alt="<?php echo $user['name'];?>">
						<p><?php echo $user['description'];?></p>
						<a href="<?php echo $user['social_link'];?>">Social link</a>
					</div>
				<?php endforeach;?>
		</div><!--Gallery-->
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php the_portfolio_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
