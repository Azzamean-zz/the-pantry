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
	   		<div class="cal-tools">
	       		<a href="<?php echo site_url();?>/to-go" class="active"><span class="fa fa-th"></span> Grid View</a>
	       		<a href="<?php echo site_url();?>/to-go/to-go-calendar" class=""><span class="fa fa-calendar-alt"></span> Calendar</a>
	   		</div>	
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
		?>
				<a class="grid-item <?php { echo get_field('stock'); } ?>" href="<?php the_permalink(); ?>">
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
		
		$first_day = date("Ymd", strtotime(date('m', strtotime('last day of next month')).'/01/'.date('Y', strtotime('+1 year')).' 00:00:00'));
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
	                'terms' => 'to-go'
	            ),
	        ),
		);
		
		$query = new WP_Query($args);
	    if ( $query->have_posts() ) {
		?>
		<h2 class="has-text-align-center"><?php echo date('F',strtotime('first day of +1 month')); ?></h2>	
		<div class="grid">
		<?php  while ( $query->have_posts() ) { $query->the_post(); ?>
				<a class="grid-item <?php { echo get_field('stock'); } ?>" href="<?php the_permalink(); ?>">
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
