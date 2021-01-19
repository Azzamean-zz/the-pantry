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
                    
                 <?php
	            function send_reminder_email() {
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

									$mailer = WC()->mailer();
								    $recipient = 'hornerbrett@gmail.com';
								    $subject = 'Shopping list for Upcoming Class';
								    $content = get_reminder_email($attendee_name, $class_name, $instructor, $class_id, $class_date, $class_time, $list_link, $mailer);

								    $headers = "Content-Type: text/html\r\n";
								    $mailer->send($recipient, $subject, $content, $headers);

									$emails[] = $attendee['attendee_meta']['email']['value'];
								}

							}
													    
						}
					} wp_reset_postdata();
				}
				// send_reminder_email();		
			?>
                    
                </div>
            </article>
        </div>
	<?php endwhile; // End of the loop. ?>
    </main>
<?php
	
function get_reminder_email($attendee_name, $class_name, $instructor, $class_id, $class_date, $class_time, $list_link, $mailer) {
	$template = 'emails/shopping-list.php';
	return wc_get_template_html($template, array(
		'attendee_name' => $attendee_name,
		'email_heading' => 'Shopping List Reminder',
		'class_name' => $class_name,
		'instructor' => $instructor,
		'class_id' => $class_id,
		'class_date' => $class_date,
		'class_time' => $class_time,
		'list_link' => $list_link,
		'sent_to_admin' => false,
		'plain_text' => false,
		'email' => $mailer
	));
}
	
get_footer();
