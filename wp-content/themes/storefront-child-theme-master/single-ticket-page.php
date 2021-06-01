<?php
/**
 * The template for displaying all single posts.
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

			$post_id = get_the_ID();

			$date_string = get_field('start_date');
			$end_date_string = get_field('end_date');

			$start_date = DateTime::createFromFormat('m/d/Y g:i a', $date_string);
			$start_date = $start_date->format('D, F d, g:i a');

			$end_date = DateTime::createFromFormat('m/d/Y g:i a', $end_date_string);
			$end_date = $end_date->format('g:i a');
			?>
			<?php
			do_action( 'storefront_single_post_before' );
			?>

			<?php
				$event_id = get_the_ID();
				$tickets = find_tickets($event_id);
			?>
<!--
			<pre>
				<?php // print_r($tickets[0]->stock); ?>
			</pre>	
-->

			<?php if($tickets[0]->stock <= 0) {
				update_field('ticket_stock', 'sold-out', $post_id);
			} else {
				update_field('ticket_stock', '', $post_id);
			}
			?>

			<?php if( current_user_can('editor') || current_user_can('administrator') ) {  ?>
			    <div class="attendee-list-link">
				    <a href="<?php echo site_url();?>/attendee-list?event=<?php echo $event_id;?>">View Attendees</a>
			    </div>
			<?php } ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header show-this">
					<h1 class="entry-title"><?php the_title();?></h1>
				</header>

				<h3><?php echo $start_date; ?> - <?php echo $end_date;?></h3>

				<?php
				do_action( 'storefront_single_post_top' );

				//if(has_term('class','ticket-page-categories')) {
				?>
<!-- 					<h3 style="margin-bottom:30px; text-align: right;">Note: Ingredient Kits are available up until 2 days prior the class date</h3> -->
				<?php

				/**
				 * Functions hooked into storefront_single_post add_action
				 *
				 * @hooked storefront_post_header          - 10
				 * @hooked storefront_post_content         - 30
				 */
				do_action( 'storefront_single_post' );
				/**
				 * Functions hooked in to storefront_single_post_bottom action
				 *
				 * @hooked storefront_post_nav         - 10
				 * @hooked storefront_display_comments - 20
				 */
				do_action( 'storefront_single_post_bottom' );
				?>
			</article><!-- #post-## -->

			<?php

			$post_id = get_the_ID();

			$ticket_ids = tribe_get_woo_tickets_ids($post_id);
			echo do_shortcode('[xoo_wl_form id="' . $ticket_ids[0] . '" type="inline_toggle" text="Join Waitlist"]');

			$ticket_pages = get_field('other_dates');
			if( $ticket_pages ): ?>
				<hr class="wp-block-separator">
				<h2>Other Dates</h2>
				<p class="text-center"><i>All events are listed in Pacific Time.</i></p>
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


						$hoursToSubtract = '8';
						$timeToSubtract = ($hoursToSubtract * 60 * 60);

						$now = time();
						$now = $now - $timeToSubtract;

						$class = '';

						$newDate = DateTime::createFromFormat('m/d/Y H:i a', get_field('start_date', $post->ID));
						$newDates[] = $newDate->format('Ymd');

						if( $now > $date ) {
							$class = 'hide';
						} else {
							$ticketcount++;
							if ( 0 === $tickets_handler->get_ticket_max_purchase( $ticket_ids[0] ) ) {
								$i++;
							}

							// update_field('new_date', $newDate, $post_id);

						}

					?>

			    	<?php
					$date_string = get_field('start_date');
					$start_date = DateTime::createFromFormat('m/d/Y g:i a', $date_string);

					$end_date_string = get_field('end_date');
				    $end_date = DateTime::createFromFormat('m/d/Y g:i a', $end_date_string);

				    $is_same_date = $start_date->format('Y-m-d') === $end_date->format('Y-m-d');

					?>
					<?php if( $is_same_date ): ?>
                            <li class="<?php echo $class;?>"><a href="<?php the_permalink(); ?>"><?php echo $start_date->format('D, F d, g:i a');?><?php echo 0 === $tickets_handler->get_ticket_max_purchase( $ticket_ids[0] ) ? ' - Sold Out' : '' ?></a></li>
                    <?php else: ?>
                            <li class="<?php echo $class;?>"><a href="<?php the_permalink(); ?>"><?php echo $start_date->format('F d');?> & <?php echo $end_date->format('d'); ?><?php echo 0 === $tickets_handler->get_ticket_max_purchase( $ticket_ids[0] ) ? ' - Sold Out' : '' ?></a></li>
                    <?php endif; ?>
			    <?php endforeach; ?>
			    </ul>
			    <?php
			    // Reset the global post object so that the rest of the page works correctly.
			    wp_reset_postdata(); ?>
			<?php endif; ?>


			<?php

			do_action( 'storefront_single_post_after' );

		endwhile; // End of the loop.
		?>
		</div>
		</article>
		</main><!-- #main -->
	</div><!-- #primary -->
<?php

do_action( 'storefront_sidebar' );
get_footer();
