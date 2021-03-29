<?php
/**
 * Template name: Test Template
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
<style>
	h2 {
		margin-top: 30px;
	}
</style>	
	<?php while ( have_posts() ) : the_post(); ?>
<div id="content" class="site-content" tabindex="-1">
	<div class="col-full">
    <main id="main" class="body wrapper" role="main">
        <header class="page-header">
            <h1><a href="<?php echo site_url();?>" class="logo">The Pantry</a></h1>
            
            <?php if(get_field('sub_head')) {?><h1><?php the_field('sub_head');?></h1><?php } ?>

        </header>
        <div id="home-main" style="margin-bottom: 100px;">
            <article class="page">
                <div class="page-body ">
	            <h2>Reminder Email +3 days</h2>	
				<?php
				$tz = 'America/Los_Angeles';
				$today = new DateTime('now', new DateTimeZone($tz));
				$today = $today->format('Y-m-d H:i:s');
				
				$start_day = new DateTime('today midnight', new DateTimeZone($tz));
				$start_day->modify('+3 day');
				$start_day = $start_day->format('Y-m-d H:i:s');
				
				$end_day = new DateTime('tomorrow midnight', new DateTimeZone($tz));
				$end_day->modify('+3 day');
				$end_day = $end_day->format('Y-m-d H:i:s');
			
				// echo $start_day;
			
			    $args = array(
				    'post_type' => 'ticket-page',
				    'posts_per_page' => -1,
				    'order'	=> 'ASC',
				    'orderby'        => 'meta_value',
				    'meta_key'       => 'start_date',
				    'meta_type'      => 'DATETIME',
					'post_status'    => 'publish',
					'meta_query' => array(
					     array(
					        'key'		=> 'start_date',
					        'compare'	=> 'BETWEEN',
					        'value'		=> array( $start_day, $end_day ),
					        'type'      => 'DATETIME',
					    ),
				    ),
				    'tax_query' => array(
			            array(
			                'taxonomy' => 'ticket-page-categories',
			                'field' => 'slug',
			                'terms' => 'class'
			            ),
			            array(
			                'taxonomy' => 'ticket-page-categories',
			                'field' => 'slug',
			                'terms' => 'no-reminder',
			                'operator' => 'NOT IN'
			            ),
					    'relation' => 'AND',
			        ),
				);
			
				$query = new WP_Query($args);
			    if ( $query->have_posts() ) {
				    while ( $query->have_posts() ) { $query->the_post();						    
					    
						$class_start_date = new DateTime(get_field('start_date'));
						$class_date = $class_start_date->format('F j, Y');
						$class_time = $class_start_date->format('g:ia');
						
					    $class_link = get_the_permalink();
					    $class_name = get_the_title();
					    $instructor = get_field('instructor');
					    $zoom_info = get_field('zoom_info');
					    
					    if(get_field('prep_instructions')) {
						    $prep_instructions = get_field('prep_instructions');
					    } else {
						    $prep_instructions = '';
					    }
					    
					    if(get_field('shopping_list')) {
						    $list_link = get_field('shopping_list');
						    $list_link = $list_link['url'];
					    } else {
						    $list_link ='';
					    }
					    
					    if(get_field('recipe_packet')) {
						    $packet = get_field('recipe_packet');
						    $packet = $packet['url'];
					    } else {
						    $packet = '';
					    }
					    
					    $class_id = get_the_ID();
					    
					    $attendee_list = Tribe__Tickets__Tickets::get_event_attendees($class_id);
					    $emails = [];
					    $email = '';
					    foreach($attendee_list as $attendee) {
						    if(isset($attendee['attendee_meta']['email']['value'])){
						    	$attendee_name = $attendee['attendee_meta']['name']['value'];					
						    	$email = $attendee['attendee_meta']['email']['value'];
							    $recipient = 'brett@deicreative.com';
			
							    $class_name_friendly = str_replace("&#8211;", "-", $class_name);
								$class_name_friendly = substr($class_name_friendly, strpos($class_name_friendly, "-") + 1);
			
							    $subject = $class_date . ' ' . $class_time . ' ' . $class_name_friendly . ' ' . 'online class & ingredient kit reminder';
								
								echo $subject . ' ' . $email;
								echo '<br>';
								echo $packet;
								echo '<br>';
								echo '<br>';
								$emails[] = $attendee['attendee_meta']['email']['value'];
							}
			
						}
												    
					}
				} wp_reset_postdata();
			
				?>
                
                <h2>Shopping List Email +7 days</h2>	
                <?php
	            	$tz = 'America/Los_Angeles';
					$today = new DateTime('now', new DateTimeZone($tz));
					$today = $today->format('Y-m-d H:i:s');
					
					$start_day = new DateTime('today midnight', new DateTimeZone($tz));
					$start_day->modify('+7 day');
					$start_day = $start_day->format('Y-m-d H:i:s');
					
					$end_day = new DateTime('tomorrow midnight', new DateTimeZone($tz));
					$end_day->modify('+7 day');
					$end_day = $end_day->format('Y-m-d H:i:s');
				
					// echo $start_day;
				
				    $args = array(
					    'post_type' => 'ticket-page',
					    'posts_per_page' => -1,
					    'order'	=> 'ASC',
					    'orderby'        => 'meta_value',
					    'meta_key'       => 'start_date',
					    'meta_type'      => 'DATETIME',
						'meta_query' => array(
						     array(
						        'key'		=> 'start_date',
						        'compare'	=> 'BETWEEN',
						        'value'		=> array( $start_day, $end_day ),
						        'type'      => 'DATETIME',
						    ),
					    ),
					    'tax_query' => array(
				            array(
				                'taxonomy' => 'ticket-page-categories',
				                'field' => 'slug',
				                'terms' => 'class'
				            ),
				            array(
				                'taxonomy' => 'ticket-page-categories',
				                'field' => 'slug',
				                'terms' => 'no-reminder',
				                'operator' => 'NOT IN'
				            ),
						    'relation' => 'AND',
				        ),
					);
				
					$query = new WP_Query($args);
				    if ( $query->have_posts() ) {
					    while ( $query->have_posts() ) { $query->the_post();						    
						    
							$class_start_date = new DateTime(get_field('start_date'));
							$class_date = $class_start_date->format('F j, Y');
							$class_time = $class_start_date->format('g:i a');
							
						    $class_link = get_the_permalink();
						    $class_name = get_the_title();
						    $instructor = get_field('instructor');
						    if(get_field('shopping_list')) {
							    $list_link = get_field('shopping_list');
							    $list_link = $list_link['url'];
						    }
						    $class_id = get_the_ID();
						    
						    $attendee_list = Tribe__Tickets__Tickets::get_event_attendees($class_id);
						    $emails = [];
						    $i = 1;
						    foreach($attendee_list as $attendee) {
							    if(isset($attendee['attendee_meta']['email']['value'])){
							    	$attendee_name = $attendee['attendee_meta']['name']['value'];					
							    	$email = $attendee['attendee_meta']['email']['value'];
				
								    $recipient = 'hornerbrett@gmail.com';
								    $class_name_friendly = str_replace("&#8211;", "-", $class_name);
									$class_name_friendly = substr($class_name_friendly, strpos($class_name_friendly, "-") + 1);
								    
								    $subject = $class_date . ' ' . $class_time . ' ' . $class_name_friendly . ' ' . 'class shopping list';

									echo $subject . ' ' . $email;
									echo '<br>';
									$emails[] = $attendee['attendee_meta']['email']['value'];
									$i++;
								}
				
							}
													    
						}
					} wp_reset_postdata();

	            ?>
	            <h2>Class Survey Email -1 days</h2>	
                <?php
	            	$tz = 'America/Los_Angeles';
					$today = new DateTime('now', new DateTimeZone($tz));
					$today = $today->format('Y-m-d H:i:s');
					
					$start_day = new DateTime('today midnight', new DateTimeZone($tz));
					$start_day->modify('-1 day');
					$start_day = $start_day->format('Y-m-d H:i:s');
					
					$end_day = new DateTime('tomorrow midnight', new DateTimeZone($tz));
					$end_day->modify('-1 day');
					$end_day = $end_day->format('Y-m-d H:i:s');
				
					// echo $start_day;
				
				    $args = array(
					    'post_type' => 'ticket-page',
					    'posts_per_page' => -1,
					    'order'	=> 'ASC',
					    'orderby'        => 'meta_value',
					    'meta_key'       => 'start_date',
					    'meta_type'      => 'DATETIME',
						'meta_query' => array(
						     array(
						        'key'		=> 'start_date',
						        'compare'	=> 'BETWEEN',
						        'value'		=> array( $start_day, $end_day ),
						        'type'      => 'DATETIME',
						    ),
					    ),
					    'tax_query' => array(
				            array(
				                'taxonomy' => 'ticket-page-categories',
				                'field' => 'slug',
				                'terms' => 'class'
				            ),
				            array(
				                'taxonomy' => 'ticket-page-categories',
				                'field' => 'slug',
				                'terms' => 'no-reminder',
				                'operator' => 'NOT IN'
				            ),
						    'relation' => 'AND',
				        ),
					);
				
					$query = new WP_Query($args);
				    if ( $query->have_posts() ) {
					    while ( $query->have_posts() ) { $query->the_post();						    
						    
							$class_start_date = new DateTime(get_field('start_date'));
							$class_date = $class_start_date->format('F j, Y');
							$class_time = $class_start_date->format('g:i a');
							
						    $class_link = get_the_permalink();
						    $class_name = get_the_title();
						    $instructor = get_field('instructor');
						    if(get_field('shopping_list')) {
							    $list_link = get_field('shopping_list');
							    $list_link = $list_link['url'];
						    }
						    $class_id = get_the_ID();
						    
						    $attendee_list = Tribe__Tickets__Tickets::get_event_attendees($class_id);
						    $emails = [];
						    foreach($attendee_list as $attendee) {
							    if(isset($attendee['attendee_meta']['email']['value'])){
							    	$attendee_name = $attendee['attendee_meta']['name']['value'];					
							    	$email = $attendee['attendee_meta']['email']['value'];
				
								    $recipient = 'hornerbrett@gmail.com';
								    $class_name_friendly = str_replace("&#8211;", "-", $class_name);
									$class_name_friendly = substr($class_name_friendly, strpos($class_name_friendly, "-") + 1);
								    
								    $subject = $class_date . ' ' . $class_time . ' ' . $class_name_friendly . ' ' . 'class evaluation';

									echo $subject . ' ' . $email;
									echo '<br>';
									$emails[] = $attendee['attendee_meta']['email']['value'];
								}
				
							}
													    
						}
					} wp_reset_postdata();

	            ?>
	            <h2>Dinner Reminder Email +0 days</h2>	
                <?php
	            	$tz = 'America/Los_Angeles';
					$today = new DateTime('now', new DateTimeZone($tz));
					$today = $today->format('Y-m-d H:i:s');
					
					$start_day = new DateTime('today midnight', new DateTimeZone($tz));
					$start_day = $start_day->format('Y-m-d H:i:s');
					
					$end_day = new DateTime('tomorrow midnight', new DateTimeZone($tz));
					$end_day = $end_day->format('Y-m-d H:i:s');
									
				    $args = array(
					    'post_type' => 'ticket-page',
					    'posts_per_page' => -1,
					    'order'	=> 'ASC',
					    'orderby'        => 'meta_value',
					    'meta_key'       => 'start_date',
					    'meta_type'      => 'DATETIME',
						'meta_query' => array(
						     array(
						        'key'		=> 'start_date',
						        'compare'	=> 'BETWEEN',
						        'value'		=> array( $start_day, $end_day ),
						        'type'      => 'DATETIME',
						    ),
					    ),
					    'tax_query' => array(
				            array(
				                'taxonomy' => 'ticket-page-categories',
				                'field' => 'slug',
				                'terms' => 'to-go'
				            ),
							array(
				                'taxonomy' => 'ticket-page-categories',
				                'field' => 'slug',
				                'terms' => 'no-reminder',
				                'operator' => 'NOT IN'
				            ),
						    'relation' => 'AND',
				        ),
					);
				
					$query = new WP_Query($args);
				    if ( $query->have_posts() ) {
					    while ( $query->have_posts() ) { $query->the_post();						    
						    
							$class_start_date = new DateTime(get_field('start_date'));
							$class_date = $class_start_date->format('F j, Y');
							$class_time = $class_start_date->format('g:i a');
							
						    $class_link = get_the_permalink();
						    $class_name = get_the_title();
						    $instructor = get_field('instructor');
						    if(get_field('shopping_list')) {
							    $list_link = get_field('shopping_list');
							    $list_link = $list_link['url'];
						    }
						    $class_id = get_the_ID();
						    
						    $attendee_list = Tribe__Tickets__Tickets::get_event_attendees($class_id);
						    $emails = [];
						    foreach($attendee_list as $attendee) {
							    if(isset($attendee['attendee_meta']['email']['value'])){
							    	$attendee_name = $attendee['attendee_meta']['name']['value'];					
							    	$email = $attendee['attendee_meta']['email']['value'];
				
								    $recipient = 'hornerbrett@gmail.com';
								    $class_name_friendly = str_replace("&#8211;", "-", $class_name);
									$class_name_friendly = substr($class_name_friendly, strpos($class_name_friendly, "-") + 1);
								    
								    $subject = $class_date . ' ' . $class_time . ' ' . $class_name_friendly . ' ' . 'pickup reminder + recipes!';

									echo $subject . ' ' . $email;
									echo '<br>';
									$emails[] = $attendee['attendee_meta']['email']['value'];
								}
				
							}
													    
						}
					} wp_reset_postdata();
					
					// send_reminder_email();
					// send_shopping_list_email();
					// send_class_evaluation_email();
					// send_to_go_reminder_email();
	            ?>
                </div>
            </article>
        </div>
	<?php endwhile; // End of the loop. ?>
    </main>
<?php
	
get_footer();
