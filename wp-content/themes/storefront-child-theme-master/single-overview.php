<?php
/**
 * The template for displaying all overview pages.
 *
 * @package storefront
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		
		<header class="page-header" style="border: none;">
            <h1><a href="<?php echo site_url();?>" class="logo">The Pantry</a></h1>
        </header>

		<article class="page">
		<div class="page-body ">
		<?php
		while ( have_posts() ) :
			the_post();

			do_action( 'storefront_single_post_before' );

			get_template_part( 'content', 'single' );

			do_action( 'storefront_single_post_after' );

		endwhile; // End of the loop.
		?>
		
		<hr class="wp-block-separator">
		<h2>Dates</h2>
		<p class="text-center"><i>All classes are listed in Pacific Daylight Time.</i></p>		
		
		<?php
		$ticket_pages = get_field('ticket_pages');
		if( $ticket_pages ): ?>
        	<ul class="homeLinks">
		    <?php foreach( $ticket_pages as $post ): ?>
		    
		    	<?php
			    	$tickets = Tribe__Tickets__Tickets::get_all_event_tickets($post->ID);
			    ?>
	    		<?php
				$date_string = get_field('start_date');
				$date = DateTime::createFromFormat('m/d/Y g:i a', $date_string);
				?>

				<?php if($tickets[0]->capacity > 0) { ?>
					<li><a href="<?php the_permalink(); ?>"><?php echo $date->format('D, F d, g:i a'); ?></a></li>
				<?php } else { ?>
					<li><a href="<?php the_permalink(); ?>"><?php echo $date->format('D, F d, g:i a'); ?> - Sold Out</a></li>
				<?php } ?>
		    <?php endforeach; ?>
		    </ul>
		    <?php 
		    // Reset the global post object so that the rest of the page works correctly.
		    wp_reset_postdata(); ?>
		<?php endif; ?>
		</div>
		</article>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
do_action( 'storefront_sidebar' );
get_footer();