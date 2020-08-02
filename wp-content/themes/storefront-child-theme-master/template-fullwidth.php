<?php
/**
 * The template for displaying full width pages.
 *
 * Template Name: Full width
 *
 * @package storefront
 */

get_header(); ?>
<div id="content" class="site-content" tabindex="-1">
    <main id="main" class="body wrapper" role="main">
		<header class="page-header" style="border-bottom: none;">
		    <h1><a href="<?php echo site_url();?>" class="logo">The Pantry</a></h1>    
		    <h1 class="page-title"><?php the_title();?></h1>
		</header>
    </main>
</div>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
			while ( have_posts() ) :
				the_post();

				do_action( 'storefront_page_before' );

				get_template_part( 'content', 'page' );

				/**
				 * Functions hooked in to storefront_page_after action
				 *
				 * @hooked storefront_display_comments - 10
				 */
				do_action( 'storefront_page_after' );

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
