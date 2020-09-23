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
		<?php
		while ( have_posts() ) :
			the_post();
		?>

		<?php
		$overview_pages = get_field('overviews');
		if( $overview_pages ): 
		?>
        	<div class="grid">
		    <?php foreach( $overview_pages as $post ): setup_postdata($post); ?>
			<?php $image = get_field('overview_image'); ?>
				<a class="grid-item" href="<?php the_permalink(); ?>">
					<img src="<?php echo $image['url'];?>">
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
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
do_action( 'storefront_sidebar' );
get_footer();
