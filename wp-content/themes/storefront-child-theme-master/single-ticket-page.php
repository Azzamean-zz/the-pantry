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
			
			<?php $event_id = get_the_ID(); ?>
			<?php if( current_user_can('editor') || current_user_can('administrator') ) {  ?>
			    <div class="attendee-list-link">
				    <a href="<?php echo site_url();?>/attendee-list?event=<?php echo $event_id;?>">View Attendees (Since 10/11)</a>
			    </div>	
			<?php } ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header show-this">
					<h1 class="entry-title"><?php the_title();?></h1>
				</header>
				
				<h3><?php echo $start_date; ?> - <?php echo $end_date;?></h3>	

				<?php
				do_action( 'storefront_single_post_top' );
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
			$shortcode = get_post_meta($post->ID,'waitlist_shortcode',true);
			echo do_shortcode($shortcode);

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
