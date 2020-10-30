<?php
/**
 * The template for displaying all grid pages.
 *
 * @package storefront
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
        <header class="page-header" style="border: none;">
            <h1><a href="<?php echo site_url();?>" class="logo">The Pantry</a></h1>
            
            <h1 class="page-title"><?php the_title();?></h1>

        </header>
        
        <div class="entry-content">	
	        		
		<h2 class="has-text-align-center"><?php the_field('current_month_title');?></h2>	
		
		<?php
		$overview_pages = get_field('current_month_overviews');
		if( $overview_pages ): 
		?>
        	<div class="grid">
		    <?php foreach( $overview_pages as $post ): setup_postdata($post); ?>
			
				<?php 
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
*/

						$tickets = Tribe__Tickets__Tickets::get_all_event_tickets($ticket_page->ID);
						
						$class = '';
				
						if($tickets[0]->stock <= 0) { 
							$class = ' so-thumb ';
						}
						
						if($i >= $ticketcount) {
							$class = ' so-thumb ';
						}
						
					endforeach;
					
				endif;
				?>
			
				<a class="grid-item <?php { echo $class; } ?> 
					<?php
					$today = date('Ymd');
					$expiration = get_field('expiration_date');
					$expiration = strtotime($expiration . "+12hours");
					$now = strtotime('now');
					
					if( $expiration < $now ) {
						echo 'hide';
					}
					?>" href="<?php the_permalink(); ?>">
						
					<?php the_post_thumbnail( 'medium','style=max-width:100%;height:auto;');?>
					<div class="so-text">Sold Out</div>	
					<h3><?php 
					if(get_field('title')) { 
						echo get_field('title'); 
					} else {
						the_title();
					}
					?></h3>	
				</a>
		    <?php endforeach; ?>
		    </div>
		    <?php 
		    // Reset the global post object so that the rest of the page works correctly.
		    wp_reset_postdata(); ?>
		<?php endif; ?>

		<h2 class="has-text-align-center"><?php the_field('next_month_title');?></h2>	
		
		<?php
		$overview_pages = get_field('next_month_overviews');
		if( $overview_pages ): 
		?>
        	<div class="grid">
		    <?php foreach( $overview_pages as $post ): setup_postdata($post); ?>
			
				<?php 
				$ticket_pages = get_field('ticket_pages');
				if( $ticket_pages ):
					$ticketcount = count($ticket_pages);
					$i = 0;
					foreach( $ticket_pages as $ticket_page ):
					
						$tickets = Tribe__Tickets__Tickets::get_all_event_tickets($ticket_page->ID);
						
						$class = '';
				
						if($tickets[0]->stock <= 0) {
							$class = ' so-thumb ';
						}
						
						if($i >= $ticketcount) {
							$class = ' so-thumb ';
						}
						
					endforeach;
					
				endif;
				?>
			
				<a class="grid-item <?php { echo $class; } ?> 
					<?php
					$today = date('Ymd');
					$expiration = get_field('expiration_date');
					$expiration = strtotime($expiration . "+12hours");
					$now = strtotime('now');
					
					if( $expiration < $now ) {
						echo 'hide';
					}
					?>" href="<?php the_permalink(); ?>">
						
					<?php the_post_thumbnail( 'medium','style=max-width:100%;height:auto;');?>
					<div class="so-text">Sold Out</div>	
					<h3><?php 
					if(get_field('title')) { 
						echo get_field('title'); 
					} else {
						the_title();
					}
					?></h3>	
				</a>
		    <?php endforeach; ?>
		    </div>
		    <?php 
		    // Reset the global post object so that the rest of the page works correctly.
		    wp_reset_postdata(); ?>
		<?php endif; ?>

<?php
do_action( 'storefront_sidebar' );
get_footer();
