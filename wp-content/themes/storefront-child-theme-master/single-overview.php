<?php
/**
 * The template for displaying all overview pages.
 *
 * @package storefront
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		while ( have_posts() ) :
			the_post();

			do_action( 'storefront_single_post_before' );

			get_template_part( 'content', 'single' );

			do_action( 'storefront_single_post_after' );

		endwhile; // End of the loop.
		?>

		<?php
		$ticket_pages = get_field('ticket_pages');
		if( $ticket_pages ): ?>
        	<ul class="homeLinks">
		    <?php foreach( $ticket_pages as $post ): ?>
			<?php
				if(tribe_events_has_tickets($post->ID)) {
					echo 'yep';
				} else {
					echo 'nope';
				}
			?>
			<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
		    <?php endforeach; ?>
		    </ul>
		    <?php 
		    // Reset the global post object so that the rest of the page works correctly.
		    wp_reset_postdata(); ?>
		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
do_action( 'storefront_sidebar' );
get_footer();
