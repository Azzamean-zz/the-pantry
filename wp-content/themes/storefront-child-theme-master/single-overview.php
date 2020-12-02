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
		<p class="text-center"><i>All events are listed in Pacific Time.</i></p>		
		
		<?php
		$ticket_pages = get_field('ticket_pages');
		if( $ticket_pages ): ?>
        	<ul class="homeLinks">
	        <?php
		    $ticketcount = 0;
			$i = 0;	
			?>
		    <?php foreach( $ticket_pages as $post ): setup_postdata($post);?>
		    	
		    	<?php
			    	$ticket_ids = tribe_get_woo_tickets_ids($post->ID);
					$tickets_handler = tribe( 'tickets.handler' );
				
					$end = strtotime('tomorrow midnight');
					
					// $end = date("m/d/Y h:i:s A T",$end);
					
					$expiration = get_field('end_date');
					
					$expiration = strtotime($expiration);
					$date = date("Y-m-d", $expiration);
					
					$date = strtotime('+1 day', strtotime($date));
					
					$now = time();
					
					$hoursToSubtract = '8';
					$timeToSubtract = ($hoursToSubtract * 60 * 60);
					
// 					$end = $end - $timeToSubtract;
					
					$now = $now - $timeToSubtract;
										
					$class = '';
/*
					echo gmdate("Y-m-d H:i:s", $now);
					echo '<br>';
					echo gmdate("Y-m-d H:i:s", $end);
					echo '<br>';
					echo '<br>';
*/

					if( $date < $end ) {
						$class = 'hide';
					} else {
						$ticketcount++;
						if ( 0 === $tickets_handler->get_ticket_max_purchase( $ticket_ids[0] ) ) {
							$i++;
						}
					}
				?>
		    	
		    	<?php
							
				$date_string = get_field('start_date');
				$date = DateTime::createFromFormat('m/d/Y g:i a', $date_string);
								
				?>
				
				<?php if ( 0 === $tickets_handler->get_ticket_max_purchase( $ticket_ids[0] ) ) { ?>
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
		
		<?php
		$post_id = get_the_ID();
		if($i >= $ticketcount) {
			if((get_field('stock')) != 'sold-out') {
				update_field('stock', 'sold-out', $post_id);
			}
		} else {
			update_field('stock', '', $post_id);
		}
		?>
		
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
