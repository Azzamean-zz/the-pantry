<?php
/**
 * Template name: Calendar Template
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

<link href='<?php echo site_url(); ?>/wp-content/themes/storefront-child-theme-master/lib/main.css' rel='stylesheet' />
<script src='<?php echo site_url(); ?>/wp-content/themes/storefront-child-theme-master/lib/main.js'></script>

<?php 
$first_day = new DateTime('today');
$first_day = $first_day->format('Y-m-d');
?>

<script>
	document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialDate: '<?php echo $first_day;?>',
      editable: true,
      selectable: true,
      height: 'auto',
      businessHours: true,
      dayMaxEvents: true, // allow "more" link when too many events
      events: [
	    <?php
		$today = date('m/d/Y g:i a');
	    $args = array(
		    'post_type' => 'overview',
		    'posts_per_page' => -1,
			'meta_query' => array(
			     array(
			        'key'		=> 'start_date',
			        'compare'	=> '>=',
			        'value'		=> $today,
			        'type'      => 'DATETIME',
			    )
		    ),
		    'tax_query' => array(
	            array(
	                'taxonomy' => 'overview-categories',
	                'field' => 'slug',
	                'terms' => 'class'
	            ),
	        ),
		);
		
		$query = new WP_Query($args);
	    if ( $query->have_posts() ) {
		    while ( $query->have_posts() ) { $query->the_post();
			    
				$link = get_permalink();
				$sold = get_field('stock');
				$ticket_pages = get_field('ticket_pages');
				foreach( $ticket_pages as $post ): setup_postdata($post);
					
					$start = DateTime::createFromFormat('m/d/Y g:i a', get_field('start_date', $post->ID));
					$end = DateTime::createFromFormat('m/d/Y g:i a', get_field('end_date', $post->ID));
					$title = str_replace("&#8211;", "-", get_the_title($post->ID));
					$title = substr($title, strpos($title, "-") + 1);
				?>
				{
		          title: '<?php echo $title;?>',
		          url: '<?php echo $link;?>',
		          start: '<?php echo $start->format("Y-m-d");?>T<?php echo $start->format("H:i:s")?>',
		          end: '<?php echo $end->format("Y-m-d" );?>T<?php echo $end->format("H:i:s")?>',
		        },
			    <?php
				endforeach;
				wp_reset_postdata();
	   		}
	    }
		wp_reset_postdata();
		?>
      ]
    });

    calendar.render();
  });

</script>	

	<?php while ( have_posts() ) : the_post(); ?>
<div id="content" class="site-content" tabindex="-1">
	<div class="col-full">
    <main id="main" class="body wrapper" role="main">
        <header class="page-header">
            <h1><a href="<?php echo site_url();?>" class="logo">The Pantry</a></h1>
            
            <?php if(get_field('sub_head')) {?><h1><?php the_field('sub_head');?></h1><?php } ?>

        </header>
        <div id="home-main">
            <div id='calendar'></div>
        </div>
	<?php endwhile; // End of the loop. ?>
    </main>
<?php
get_footer();
