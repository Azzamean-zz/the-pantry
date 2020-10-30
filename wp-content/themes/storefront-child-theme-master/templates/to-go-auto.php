<?php
/**
 * Template name: Auto To-Go Page Template
 *
 * @package storefront
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
        <header class="page-header" style="border: none;">
            <h1><a href="<?php echo site_url();?>" class="logo">The Pantry</a></h1>
            
            <h1 class="page-title">To-Go</h1>
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
		            'compare' => '>',
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
	                'terms' => 'to-go'
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
				if( $ticket_pages ):
					$ticketcount = count($ticket_pages);
					$i = 0;
					foreach( $ticket_pages as $ticket_page ):
					
/*
						$ticket_ids = tribe_get_woo_tickets_ids($ticket_page->ID);
												
						$class = '';
						
						if(0 === (get_qty_available($ticket_ids[0]))) { 
							$i++;
						}
						
						if($i >= $ticketcount) {
							$class = ' so-thumb ';
						}
*/

					$tickets = Tribe__Tickets__Tickets::get_all_event_tickets($ticket_page->ID);
						
					$class = '';
			
					if($tickets[0]->stock <= 0) { 
						$i++;
					}
					
					if($i >= $ticketcount) {
						$class = ' so-thumb ';
					}
						
					endforeach;
					
				endif;
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
		
		$first_day = date("Ymd", strtotime(date('m', strtotime('+1 month')).'/01/'.date('Y').' 00:00:00'));
		$last_day = date('Ymt',strtotime('next month'));

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
	                'terms' => 'to-go'
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
				if( $ticket_pages ):
					$ticketcount = count($ticket_pages);
					$i = 0;
					foreach( $ticket_pages as $ticket_page ):
																	
						$tickets = Tribe__Tickets__Tickets::get_all_event_tickets($ticket_page->ID);
						
						$class = '';
				
						if($tickets[0]->stock <= 0) { 
							$i++;
						}
						
						if($i >= $ticketcount) {
							$class = ' so-thumb ';
						}
						
					endforeach;
					
				endif;
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
