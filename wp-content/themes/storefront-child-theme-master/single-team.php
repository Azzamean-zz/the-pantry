<?php
/**
 * The template for displaying Single Team pages.
 *
 * This page template will display any functions hooked into the `homepage` action.
 * By default this includes a variety of product displays and the page content itself. To change the order or toggle these components
 * use the Homepage Control plugin.
 * https://wordpress.org/plugins/homepage-control/
 *
 * Template name: Single Team
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
	            <?php if(get_field('team_image')) { ?>
	            <div class="header-photo">
		            <?php $image = get_field('team_image');?>
		            <img src="<?php echo $image['url'];?>"
	            </div>	
	            <?php } ?>
	            
	            <header class="page-header">
				<a href="https://thepantryseattle.com/" class="logo">The Pantry</a>
				<h1>About <?php the_title();?></h1>
				</header>
	            
                <div class="page-body columns">
                    <?php the_content();?>
                </div>
                <ul class="homeLinks">
	                
	                <?php
					
					// Check rows exists.
					if( have_rows('links') ):
					
					    // Loop through rows.
					    while( have_rows('links') ) : the_row();
					?>
					    <li><a href="<?php the_sub_field('link_url'); ?>"><?php the_sub_field('link_text'); ?></a></li>
					<?php
					    // End loop.
					    endwhile;
					
					// No value.
					else :
					    // Do something...
					endif;
	                ?>
	                
                </ul>
            </article>
        </div>
	<?php endwhile; // End of the loop. ?>
    </main>
<?php
get_footer();
