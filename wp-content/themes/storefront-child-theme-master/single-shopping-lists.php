<?php
/**
 * The template for displaying Single Team pages.
 *
 * This page template will display any functions hooked into the `homepage` action.
 * By default this includes a variety of product displays and the page content itself. To change the order or toggle these components
 * use the Homepage Control plugin.
 * https://wordpress.org/plugins/homepage-control/
 *
 * Template name: Single Shopping Lists
 *
 * @package storefront
 */

get_header(); ?>
	<?php while ( have_posts() ) : the_post(); ?>
<div id="content" class="site-content" tabindex="-1">
	<div class="col-full">
    <main id="main" class="body wrapper" role="main">
        <div id="home-main">
            <article class="page">
	            <header class="page-header">
				<a href="https://thepantryseattle.com/" class="logo">The Pantry</a>
				<h1><?php the_title();?></h1>
				</header>
	            
                <div class="page-body">
                    <?php the_content();?>
                </div>
            </article>
        </div>
	<?php endwhile; // End of the loop. ?>
    </main>
<?php
get_footer();
