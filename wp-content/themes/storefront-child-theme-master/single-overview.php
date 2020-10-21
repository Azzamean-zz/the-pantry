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
		    <?php foreach( $ticket_pages as $post ): setup_postdata($post);?>
		    	
		    	<?php
					$today = date('Ymd');
					$expiration = get_field('end_date');
					
					$expiration = strtotime($expiration);
					$now = strtotime('now');
					
/*
					echo 'now: ' . date('F j, Y, g:i a', strtotime('now'));
					echo '<br>';
					echo 'end: ' . date('F j, Y, g:i a', strtotime(get_field('end_date')));
*/
					
					
					$class = '';
					
					if( $expiration < $now ) {
						$class = 'hide';
					}
				?>
		    	
		    	<?php
			    	$tickets = Tribe__Tickets__Tickets::get_all_event_tickets($post->ID);
			    	$capacity = $tickets[0]->capacity;
			    	$sold = $tickets[0]->qty_sold;
			    	$stock = $tickets[0]->stock;
			    	
			    	//echo '<pre>stock: ';
			    	//print_r($tickets[0]->stock);
			    	//echo '</pre>';
			    ?>
	    		<?php
				$date_string = get_field('start_date');
				$date = DateTime::createFromFormat('m/d/Y g:i a', $date_string);
				?>

				<?php if($stock <= 0) { ?>
					<li class="<?php echo $class;?>"><a href="<?php the_permalink(); ?>"><?php echo $date->format('D, F d, g:i a'); ?> - Sold Out</a></li>
				<?php } else { ?>
					<li class="<?php echo $class;?>"><a href="<?php the_permalink(); ?>"><?php echo $date->format('D, F d, g:i a'); ?></a></li>
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
	<script>
		console.log("<?php echo 'now: ' . date('F j, Y, g:i a', strtotime('now'));?>");
	</script>	
<?php
do_action( 'storefront_sidebar' );
get_footer();
