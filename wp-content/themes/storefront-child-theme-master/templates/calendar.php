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
$first_day = new DateTime('first day of this month');
$first_day = $first_day->format('Y-m-d');
?>

<script>
	document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
	  headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: ''
      },
	  views: {
		dayGridMonth : { buttonText: 'Month View' },
        listMonth: { buttonText: 'List View' }
      },
      initialView: 'dayGridMonth',
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
				if($ticket_pages) {
				foreach( $ticket_pages as $post ): setup_postdata($post);
					
					$start = DateTime::createFromFormat('m/d/Y g:i a', get_field('start_date', $post->ID));
					$end = DateTime::createFromFormat('m/d/Y g:i a', get_field('end_date', $post->ID));
					$title = str_replace("&#8211;", "-", get_the_title($post->ID));
					$title = substr($title, strpos($title, "-") + 1);
// 					$link = get_the_permalink();
					$unixstart = strtotime(get_field('start_date', $post->ID));
					$unixtoday = strtotime('today UTC+8');
					
					if($unixstart >= $unixtoday) {
				?>
				{
		          title: '<?php if($sold){ echo 'Sold Out - '; }?><?php echo html_entity_decode($title);?>',
		          url: '<?php echo $link;?>',
		          className: "<?php echo $sold;?>",
		          start: '<?php echo $start->format("Y-m-d");?>T<?php echo $start->format("H:i:s")?>',
		          end: '<?php echo $end->format("Y-m-d" );?>T<?php echo $end->format("H:i:s")?>',
		        },
			    <?php
				}
				endforeach;
				}
				wp_reset_postdata();
	   		}
	    }
		wp_reset_postdata();
		?>
      ]
    });
	
    calendar.render();
    
    windowSize();
    
    function windowSize() {
		widthOutput = window.innerWidth;
		if(widthOutput < 990) {
			calendar.changeView('listMonth');
		} else {
			calendar.changeView('dayGridMonth');
		}
	}
	
	window.onresize = windowSize;
    
  });

</script>	

	<?php while ( have_posts() ) : the_post(); ?>
<div id="content" class="site-content" tabindex="-1">
	<div class="col-full">
    <main id="main" class="body wrapper" role="main">
        <header class="page-header" style="border: none;">
		    <h1><a href="<?php echo site_url();?>" class="logo">The Pantry</a></h1>
		    <h1 class="page-title">Classes</h1>
		</header>
        <div id="home-main">
	        <div class="cal-container">
           		<div class="cal-tools">
	           		<a href="<?php echo site_url();?>/classes" class="<?php if(is_page('classes')){ echo 'active'; }?>"><span class="fa fa-th"></span> Grid View</a>
	           		<a href="<?php echo site_url();?>/classes/calendar" class="active"><span class="fa fa-calendar-alt"></span> Calendar</a>
           		</div>	
           		<div id="calendar"></div>
	        </div>	
        </div>
	<?php endwhile; // End of the loop. ?>
    </main>
    
<script>
/*
	$(function(){
		$(window).resize(function() {
			$windowWidth = $window.width();
			if($windowWidth < 990) {
				calendar.changeView('listMonth');
			} else {
				calendar.changeView('dayGridMonth');
			}
		}).triggerHandler('resize');
	});
*/
	
</script>	
<?php
get_footer();
