<?php

/******************************************************************************************************/
/******************************************************************************************************/
/******************************************************************************************************/
/**
 * Sending A Upcoming Event Email
 */
	
function get_reminder_email($user_first_name, $event_id, $event_date_time, $mailer) {
	$template = 'emails/reminder.php';
	return wc_get_template_html($template, array(
		'first_name' => $user_first_name,
		'email_heading' => 'Hope you enjoy the event on',
		'event_id' => $event_id,
		'event_date_time' => $event_date_time,
		'sent_to_admin' => false,
		'plain_text' => false,
		'email' => $mailer
	));
}
	
/******************************************************************************************************/
/******************************************************************************************************/
/******************************************************************************************************/
/**
 * Sending A Upcoming Event Email
 */
function send_membership_email($membership_plan, $args) {
	$user_id = $args['user_id'];
	$user_data = get_userdata($user_id);
	$user_meta = get_user_meta($user_id);
	$user_email = $user_data->user_email;
	$user_first_name = $user_meta['first_name'][0];
	$site_name = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	$membership_plan_id = $membership_plan->id;
	$options = wp_load_alloptions();
	$wc_memberships_rules = unserialize($options['wc_memberships_rules']);
	if($wc_memberships_rules) {
		foreach($wc_memberships_rules as $key => $value) {
			if(($membership_plan_id == $value['membership_plan_id']) && $value['content_type_name'] == 'event') {
				if($value['object_ids'] && is_array($value['object_ids'])) {
					$event_id = $value['object_ids'][0];
					$event_title = get_the_title($event_id);
					$event_date_time_display = date('n/j', strtotime(get_field('date-time', $event_id))) . ' @ ' . date('g:i A', strtotime(get_field('date-time', $event_id))) . ' ET';
					//$event_date_time_return = date('YmdT', strtotime(get_field('date-time', $event_id)));
					$event_date_time_return = date('Ymd',strtotime(get_field('date-time', $event_id))) .'T'. date('Hi',strtotime(get_field('date-time', $event_id))). '00/' . date('Ymd',strtotime(get_field('date-time', $event_id))) .'T'. date('Hi',strtotime(get_field('date-time', $event_id))) . '00';
					$event_url = get_permalink($event_id);
					$product = get_posts(array('post_type' => 'product', 'title' => get_the_title($event_id), 'posts_per_page' => 1));
					$product_url = get_permalink($product[0]->ID);
					$mailer = WC()->mailer();
				    $recipient = $user_email;
				    $subject = 'Viewing instructions for your upcoming Ambient Live event';
				    $content = get_upcoming_event_email($user_first_name, $event_title, $event_date_time_display, $event_date_time_return, $event_url, $product_url, $mailer);
				    $headers = “Content-Type: text/html\r\n”;
				    $mailer->send($recipient, $subject, $content, $headers);
				}
			}
		}
	}
}
function get_upcoming_event_email($user_first_name, $event_title, $event_date_time_display, $event_date_time_return, $event_url, $product_url, $mailer) {
	$template = 'emails/upcoming-event.php';
	return wc_get_template_html($template, array(
		'first_name' => $user_first_name,
		'email_heading' => 'Hope you enjoy the event on',
		'event_title' => $event_title,
		'event_date_time_display' => $event_date_time_display,
		'event_date_time_return' => $event_date_time_return,
		'event_url' => $event_url,
		'product_url' => $product_url,
		'sent_to_admin' => false,
		'plain_text' => false,
		'email' => $mailer
	));
}
// add_action('wc_memberships_user_membership_saved', 'send_membership_email', 999, 2);