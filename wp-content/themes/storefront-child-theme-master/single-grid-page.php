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
            
            <h1 class="page-title">Classes</h1>

        </header>
        
        <div class="entry-content">	
		<?php
		while ( have_posts() ) :
			the_post();
		?>
		
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
					$i = 0;
					foreach( $ticket_pages as $ticket_page ):
					
					$tickets = Tribe__Tickets__Tickets::get_all_event_tickets($ticket_page->ID);
					
					$class = '';
					
					if($tickets[0]->capacity == 0) { 
						$class = ' so-thumb ';
					}
						
					endforeach;
				endif;
				?>
			
				<a class="grid-item <?php { echo $class; } ?> 
					<?php
					$today = date('Ymd');
					$expiration = get_field('expiration_date');
					$expiration = strtotime($expiration);
					$now = strtotime('now');
					
					if( $expiration < $now ) {
						echo 'hide';
					}
					?>" href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail( 'large','style=max-width:100%;height:auto;');?>
					<div class="so-text">Sold Out</div>	
					<h3><?php the_title(); ?></h3>	
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
			<?php $image = get_field('overview_image'); ?>
			
					<?php 
					if(tribe_events_has_tickets($post->ID)) {
						$class = ' sold-out-thumb ';
					} else {
						$class = ' ';
					} 
					?>
			
				<a class="grid-item <?php $class;?>" href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail( 'large','style=max-width:100%;height:auto;');?>
					<h3><?php the_title(); ?></h3>	
				</a>
		    <?php endforeach; ?>
		    </div>
		    <?php 
		    // Reset the global post object so that the rest of the page works correctly.
		    wp_reset_postdata(); ?>
		<?php endif; ?>

		<h2 class="has-text-align-center"><?php the_field('third_month_title');?></h2>	
		
		<?php
		$overview_pages = get_field('third_month_overviews');
		if( $overview_pages ): 
		?>
        	<div class="grid">
		    <?php foreach( $overview_pages as $post ): setup_postdata($post); ?>
			<?php $image = get_field('overview_image'); ?>
			
					<?php 
					if(tribe_events_has_tickets($post->ID)) {
						$class = ' sold-out-thumb ';
					} else {
						$class = ' ';
					} 
					?>
			
				<a class="grid-item <?php $class;?>" href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail( 'large','style=max-width:100%;height:auto;');?>
					<h3><?php the_title(); ?></h3>	
				</a>
		    <?php endforeach; ?>
		    </div>
		    <?php 
		    // Reset the global post object so that the rest of the page works correctly.
		    wp_reset_postdata(); ?>
		<?php endif; ?>
		
		<?php
		endwhile; // End of the loop.
		?>
		</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
do_action( 'storefront_sidebar' );
get_footer();
