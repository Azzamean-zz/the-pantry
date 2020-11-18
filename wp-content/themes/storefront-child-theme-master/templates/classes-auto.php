<?php
/**
 * Template name: Auto Class Page Template
 *
 * @package storefront
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
        <header class="page-header" style="border: none;">
            <h1><a href="<?php echo site_url();?>" class="logo">The Pantry</a></h1>
            
            <h1 class="page-title">Classes</h1>
        </header>
        
        <div class="entry-content">	
	        		
		<?php
		$today = date('Ymd'); 
		$first_day = new DateTime('first day of this month');
		$first_day = $first_day->format('Ymd');
		$last_day = new DateTime('last day of this month');
		$last_day = $last_day->format('Ymd');
		
		$args = array(
		    'post_type' => 'overview',
		    'posts_per_page' => -1,
		    'meta_query' => array(
		        array(
		            'key'     => 'expiration_date',
		            'compare' => '>=',
		            'value'   => $today,
		        ),
		         array(
		            'key'     => 'expiration_date',
		            'compare' => '<=',
		            'value'   => $last_day,
		        )
		    ),
		    'tax_query' => array(
	            array(
	                'taxonomy' => 'overview-categories',
	                'field' => 'slug',
	                'terms' => 'class'
	            ),
	        ),
		);
		
		$query = new WP_Query($args);
	    if ( $query->have_posts() ) {
		?>
		<h2 class="has-text-align-center"><?php echo date('F'); ?></h2>	
		<div class="grid">
		<?php
		    while ( $query->have_posts() ) { $query->the_post();
		    	$ticket_pages = get_field('ticket_pages');
				$now = strtotime(date('m/d/Y g:i a')); 

				$hoursToSubtract = '8';
				$timeToSubtract = ($hoursToSubtract * 60 * 60);				
				$now = $now - $timeToSubtract;

				$ticketcount = 0;
				$i = 0;
				foreach( $ticket_pages as $ticket_page ):
								
					$class = '';
					$end_date = strtotime(get_field('end_date', $ticket_page->ID));
					if($end_date > $now) {
						$ticketcount++;
						$tids = tribe_get_woo_tickets_ids($ticket_page->ID);

						$tickets_handler = tribe( 'tickets.handler' );
						if ( 0 === $tickets_handler->get_ticket_max_purchase( $tids[0] ) ) {				
							$i++;
						}
					}
					
					if($i >= $ticketcount) {
						$class = ' so-thumb ';
						$class .= ' count-' . $ticketcount . '-i-' . $i;
					} else {
						$class = ' count-' . $ticketcount . '-i-' . $i;
					}
				
				endforeach;
				
			?>
				<a class="grid-item <?php { echo $class; } ?>" href="<?php the_permalink(); ?>">
						
					<?php the_post_thumbnail( 'medium','style=max-width:100%;height:auto;');?>
					<div class="so-text">Sold Out</div>	
					<h3><?php echo get_field('title'); ?></h3>	
				</a>
			<?php

	    	}
	    	?>
	    </div>
	    <?php
	    }
		wp_reset_postdata();
		?>
        
		
		<?php
		
		$first_day = date("Ymd", strtotime(date('m', strtotime('last day of next month')).'/01/'.date('Y').' 00:00:00'));
		$last_day = date('Ymt',strtotime('last day of next month'));
		
		$args = array(
		    'post_type' => 'overview',
		    'posts_per_page' => -1,
		    'meta_key'  => 'start_date',
		    'orderby'   => 'meta_value_num',
		    'order'     => 'ASC',
		    'meta_query' => array(
		        array(
		            'key'     => 'expiration_date',
		            'compare' => '>=',
		            'value'   => $first_day,
		        ),
		         array(
		            'key'     => 'expiration_date',
		            'compare' => '<=',
		            'value'   => $last_day,
		        )
		    ),
		    'tax_query' => array(
	            array(
	                'taxonomy' => 'overview-categories',
	                'field' => 'slug',
	                'terms' => 'class'
	            ),
	        ),
		);
		
		$query = new WP_Query($args);
	    if ( $query->have_posts() ) {
		?>
		<h2 class="has-text-align-center"><?php echo date('F',strtotime('first day of +1 month')); ?></h2>	
		<div class="grid">
		<?php
		    while ( $query->have_posts() ) { $query->the_post();		        
		    	$ticket_pages = get_field('ticket_pages');
				$now = strtotime(date('m/d/Y g:i a')); 

				$ticketcount = 0;
				$i = 0;
				foreach( $ticket_pages as $ticket_page ):
								
					$class = '';
					$end_date = strtotime(get_field('end_date', $ticket_page->ID));
									
					$ticketcount++;
					$tids = tribe_get_woo_tickets_ids($ticket_page->ID);

					$tickets_handler = tribe( 'tickets.handler' );
					if ( 0 === $tickets_handler->get_ticket_max_purchase( $tids[0] ) ) {				
						$i++;
					}
					
					if($i >= $ticketcount) {
						$class = ' so-thumb ';
						$class .= ' count-' . $ticketcount . '-i-' . $i;
					} else {
						$class = ' count-' . $ticketcount . '-i-' . $i;
					}
				
				endforeach;
			?>
				<a class="grid-item <?php { echo $class; } ?>" href="<?php the_permalink(); ?>">
					
					<?php the_post_thumbnail( 'medium','style=max-width:100%;height:auto;');?>
					<div class="so-text">Sold Out</div>	
					<h3><?php echo get_field('title'); ?></h3>	
				</a>
			<?php
	    	}
	    	?>
	    </div>
	    <?php
	    }
		wp_reset_postdata();
		?>
		
		
		</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
do_action( 'storefront_sidebar' );
get_footer();
