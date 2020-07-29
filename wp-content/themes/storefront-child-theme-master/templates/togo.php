<?php
/**
*
 * Template name: To-Go Template
 *
 * @package storefront
 */

/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package storefront
 */

get_header(); ?><!doctype html>
<div id="content" class="site-content" tabindex="-1">
	<div class="col-full">
    <main id="main" class="body wrapper" role="main">
        <header class="page-header">
            <h1><a href="<?php echo site_url();?>" class="logo">The Pantry</a></h1>
            
            <?php if(get_field('sub_head')) {?><h1><?php the_field('sub_head');?></h1><?php } ?>

        </header>
		<?php while ( have_posts() ) : the_post(); ?>
	        <div id="home-main" class="event-page">
	            <article class="page">
	                <div class="page-body ">
	                    <?php the_content();?>
	                </div>
	            </article>
	        </div>
		<?php endwhile; // End of the loop. ?>
        <?php echo do_shortcode('[tribe_events view="month" category="to-go"]'); ?>
    </main>
<?php
get_footer();
