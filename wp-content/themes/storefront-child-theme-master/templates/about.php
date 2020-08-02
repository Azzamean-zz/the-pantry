<?php
/**
 * Template name: About Template
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package storefront
 */

get_header(); ?>
	<?php while ( have_posts() ) : the_post(); ?>
<div id="content" class="site-content" tabindex="-1">
	<div class="col-full">
    <main id="main" class="body wrapper" role="main">
        <header class="page-header">
            <h1><a href="<?php echo site_url();?>" class="logo">The Pantry</a></h1>
            
            <?php if(get_field('sub_head')) {?><h1><?php the_field('sub_head');?></h1><?php } ?>

        </header>
        <div id="home-main">
            <article class="page">
                <div class="page-body ">
                    <?php the_content();?>
                </div>
                <ul class="homeLinks">
	                
	                <?php
					$args = array(
						'post_type' => 'team',
						'tax_query' => array(
					        array(
					            'taxonomy' => 'team-categories',
					            'field'    => 'slug',
					            'terms'    => 'guest',
					            'operator' => 'NOT IN',
					        ),
					    ),
					    'posts_per_page' => -1
					);
					// The Query
					$query = new WP_Query( $args );
					 
					// The Loop
					if ( $query->have_posts() ) {
					    while ( $query->have_posts() ) {
						$query->the_post();
					?>
						<li><a href="<?php the_permalink(); ?>"><?php the_title();?></a></li>
					
					<?php
					}
					} else {
					    // no posts found
					}
					/* Restore original Post Data */
					wp_reset_postdata();
	                ?>
					
                </ul>
                
                <div class="page-body">
	                <?php the_field('guest_content');?>
                </div>	
                
                    <ul class="homeLinks">
	                
	                <?php
					$args = array(
						'post_type' => 'team',
						'tax_query' => array(
					        array(
					            'taxonomy' => 'team-categories',
					            'field'    => 'slug',
					            'terms'    => 'guest',
					            'posts_per_page' => -1
					        ),
					    ),
					);
					// The Query
					$query = new WP_Query( $args );
					 
					// The Loop
					if ( $query->have_posts() ) {
					    while ( $query->have_posts() ) {
						$query->the_post();
					?>
						<li><a href="<?php the_permalink(); ?>"><?php the_title();?></a></li>
					
					<?php
					}
					} else {
					    // no posts found
					}
					/* Restore original Post Data */
					wp_reset_postdata();
	                ?>
					
                </ul>

                
            </article>
        </div>
	<?php endwhile; // End of the loop. ?>
    </main>
<?php
get_footer();
