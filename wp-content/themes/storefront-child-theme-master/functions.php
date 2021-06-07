<?php

/**
 * Storefront automatically loads the core CSS even if using a child theme as it is more efficient
 * than @importing it in the child theme style.css file.
 *
 * Uncomment the line below if you'd like to disable the Storefront Core CSS.
 *
 * If you don't plan to dequeue the Storefront Core CSS you can remove the subsequent line and as well
 * as the sf_child_theme_dequeue_style() function declaration.
 */
//add_action( 'wp_enqueue_scripts', 'sf_child_theme_dequeue_style', 999 );

/**
 * Dequeue the Storefront Parent theme core CSS
 */
function sf_child_theme_dequeue_style() {
    wp_dequeue_style( 'storefront-style' );
    wp_dequeue_style( 'storefront-woocommerce-style' );
}


/**
 * Create Custom Post Type: Ticket Page
*/
function create_ticket_page() {
	register_post_type(
		'ticket-page',
		array(
			'labels' => array(
				'name' => __("Ticket Page"),
				'singular_name' => __("Ticket Page"),
				'add_new' => __("Add Ticket Page"),
				'add_new_item' => __("Add New Ticket Page"),
				'edit_item' => __("Edit Ticket Page"),
				'new_item' => __("New Ticket Page"),
				'view_item' => __("View Ticket Page"),
				'search_items' => __("Search Ticket Pages"),
				'not_found' => __("No Ticket Pages found."),
				'not_found_in_trash' => __("No Ticket Pages found in trash."),
				'edit' => __("Edit Ticket Pages"),
				'view' => __("View Ticket Pages")
			),
			'exclude_from_search' => true,
			'menu_icon' => 'dashicons-tickets-alt',
			'public' => true,
			'rewrite' => array('slug' => 'ticket-page'),
			'supports' => array('title','editor','author','excerpt','comments','revisions','thumbnail')
		)
	);
	flush_rewrite_rules();
}

function create_ticket_page_taxonomies() {
	register_taxonomy(
		'ticket-page-categories',
		'ticket-page',
		array(
			'hierarchical' => true,
			'label' => 'Ticket Category',
			'query_var' => true,
			'rewrite' => array('slug' => 'ticket-page-categories')
		)
	);
}

function create_ticket_page_columns($columns) {
    $columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __('Name'),
		'class_attendee_count' => __('Class Count'),
	);
	return $columns;
}

function manage_ticket_page_columns($column, $post_id) {
	global $post;
	if($column == 'class_attendee_count') {
		$field = getAttendee($post_id);
		if(!empty($field)) echo $field;
	}
}

add_action('init', 'create_ticket_page');
add_action('init', 'create_ticket_page_taxonomies');
/*
add_filter('manage_edit-ticket-page_columns', 'create_ticket_page_columns' ) ;
add_action('manage_ticket-page_posts_custom_column', 'manage_ticket_page_columns', 10, 2 );
*/


function getAttendee($post_id) {
	$attendee_list = Tribe__Tickets__Tickets::get_event_attendees($post_id); 

	foreach($attendee_list as $attendee) {
		$class[] = $attendee['ticket'];
	}

	$classcounts = array_count_values($class);
	
	foreach($classcounts as $classkey => $classcount) {
		$string = explode(' ', $classkey);
		$last_word = array_pop($string);
		if($last_word == 'class') {
			$newClass[] = $classcount;
		}
	}
	
	return $classcount;
}

/**
 * Create Custom Post Type: Overview
*/
function create_overview() {
	register_post_type(
		'overview',
		array(
			'labels' => array(
				'name' => __("Overviews"),
				'singular_name' => __("Overview"),
				'add_new' => __("Add Overview"),
				'add_new_item' => __("Add New Overview"),
				'edit_item' => __("Edit Overview"),
				'new_item' => __("New Overview"),
				'view_item' => __("View Overview"),
				'search_items' => __("Search Overviews"),
				'not_found' => __("No Overviews found."),
				'not_found_in_trash' => __("No Overviews found in trash."),
				'edit' => __("Edit Overviews"),
				'view' => __("View Overviews")
			),
			'exclude_from_search' => true,
			'menu_icon' => 'dashicons-images-alt',
			'public' => true,
			'rewrite' => array('slug' => 'overview'),
			'supports' => array('title','editor','author','excerpt','comments','revisions', 'thumbnail')
		)
	);
	flush_rewrite_rules();
}

function create_overview_taxonomies() {
	register_taxonomy(
		'overview-categories',
		'overview',
		array(
			'hierarchical' => true,
			'label' => 'Category',
			'query_var' => true,
			'rewrite' => array('slug' => 'overview-categories')
		)
	);
	register_taxonomy(
		'overview-month',
		'overview',
		array(
			'hierarchical' => true,
			'label' => 'Month',
			'query_var' => true,
			'rewrite' => array('slug' => 'overview-month')
		)
	);
}

function create_overview_columns($columns) {
    $columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __('Name'),
		'taxonomy' => __('Taxonomy'),
		'acf-field' => __('Advanced Custom Field'),
	);
	return $columns;
}

function manage_overview_columns($column, $post_id) {
	global $post;
	switch($column) {
		case 'taxonomy':
			$test = get_post_meta($post_id, 'overview', true);
			if(!empty($test)) echo $test;
			break;
		case 'acf-field':
			$field = get_post_meta($post_id, 'acf-field', true);
			if(!empty($field)) echo $field;
			break;
		default : break;
	}
}

add_action('init', 'create_overview');
add_action('init', 'create_overview_taxonomies');
add_filter('manage_edit-overview_columns', 'create_overview_columns' ) ;
add_action('manage_overview_posts_custom_column', 'manage_overview_columns', 10, 2 );


/**
 * Create Custom Post Type: Grid Page
*/
function create_grid_page() {
	register_post_type(
		'grid-page',
		array(
			'labels' => array(
				'name' => __("Grid Page"),
				'singular_name' => __("Grid Page"),
				'add_new' => __("Add Grid Page"),
				'add_new_item' => __("Add New Grid Page"),
				'edit_item' => __("Edit Grid Page"),
				'new_item' => __("New Grid Page"),
				'view_item' => __("View Grid Page"),
				'search_items' => __("Search Grid Pages"),
				'not_found' => __("No Grid Pages found."),
				'not_found_in_trash' => __("No Grid Page found in trash."),
				'edit' => __("Edit Grid Pages"),
				'view' => __("View Grid Pages")
			),
			'exclude_from_search' => true,
			'menu_icon' => 'dashicons-grid-view',
			'public' => true,
			'rewrite' => array('slug' => 'grid-page'),
			'supports' => array('title','editor','author','excerpt','comments','revisions')
		)
	);
	flush_rewrite_rules();
}

function create_grid_page_taxonomies() {
	register_taxonomy(
		'grid-page-categories',
		'grid-page',
		array(
			'hierarchical' => true,
			'label' => 'Category',
			'query_var' => true,
			'rewrite' => array('slug' => 'grid-page-categories')
		)
	);
}

function create_grid_page_columns($columns) {
    $columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __('Name'),
		'taxonomy' => __('Taxonomy'),
		'acf-field' => __('Advanced Custom Field'),
	);
	return $columns;
}

function manage_grid_page_columns($column, $post_id) {
	global $post;
	switch($column) {
		case 'taxonomy':
			$test = get_post_meta($post_id, 'grid-page', true);
			if(!empty($test)) echo $test;
			break;
		case 'acf-field':
			$field = get_post_meta($post_id, 'acf-field', true);
			if(!empty($field)) echo $field;
			break;
		default : break;
	}
}

add_action('init', 'create_grid_page');
add_action('init', 'create_grid_page_taxonomies');
add_filter('manage_edit-grid_page_columns', 'create_grid_page_columns' ) ;
add_action('manage_grid_page_posts_custom_column', 'manage_grid_page_columns', 10, 2 );

/**
 * Create Custom Post Type: Test
 * Create Taxonomies: Test Categories
 * Create Columns: Test
 * Manage Columns: Test
*/
function create_team() {
	register_post_type(
		'team',
		array(
			'labels' => array(
				'name' => __("Team"),
				'singular_name' => __("Team"),
				'add_new' => __("Add Team"),
				'add_new_item' => __("Add New Team"),
				'edit_item' => __("Edit Team"),
				'new_item' => __("New Team"),
				'view_item' => __("View Team"),
				'search_items' => __("Search Team"),
				'not_found' => __("No Team found."),
				'not_found_in_trash' => __("No Team found in trash."),
				'edit' => __("Edit Team"),
				'view' => __("View Team")
			),
			'exclude_from_search' => true,
			'menu_icon' => 'dashicons-universal-access',
			'public' => true,
			'rewrite' => array('slug' => 'team'),
			'supports' => array('title','editor','author','excerpt','comments','revisions')
		)
	);
	flush_rewrite_rules();
}

function create_team_taxonomies() {
	register_taxonomy(
		'team-categories',
		'team',
		array(
			'hierarchical' => true,
			'label' => 'Category',
			'query_var' => true,
			'rewrite' => array('slug' => 'team-categories')
		)
	);
}

function create_team_columns($columns) {
    $columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __('Name'),
		'taxonomy' => __('Taxonomy'),
		'acf-field' => __('Advanced Custom Field'),
	);
	return $columns;
}

function manage_team_columns($column, $post_id) {
	global $post;
	switch($column) {
		case 'taxonomy':
			$test = get_post_meta($post_id, 'team', true);
			if(!empty($test)) echo $test;
			break;
		case 'acf-field':
			$field = get_post_meta($post_id, 'acf-field', true);
			if(!empty($field)) echo $field;
			break;
		default : break;
	}
}

add_action('init', 'create_team');
add_action('init', 'create_team_taxonomies');
add_filter('manage_edit-team_columns', 'create_team_columns' ) ;
add_action('manage_team_posts_custom_column', 'manage_team_columns', 10, 2 );

/**
 * Create Custom Post Type: Test
 * Create Taxonomies: Test Categories
 * Create Columns: Test
 * Manage Columns: Test
*/
function create_shopping_lists() {
	register_post_type(
		'shopping-lists',
		array(
			'labels' => array(
				'name' => __("Shopping Lists"),
				'singular_name' => __("Shopping Lists"),
				'add_new' => __("Add Shopping List"),
				'add_new_item' => __("Add New Shopping List"),
				'edit_item' => __("Edit Shopping List"),
				'new_item' => __("New Shopping List"),
				'view_item' => __("View Shopping List"),
				'search_items' => __("Search Shopping Lists"),
				'not_found' => __("No Shopping Lists found."),
				'not_found_in_trash' => __("No Shopping Lists found in trash."),
				'edit' => __("Edit Shopping Lists"),
				'view' => __("View Shopping Lists")
			),
			'exclude_from_search' => true,
			'menu_icon' => 'dashicons-cart',
			'public' => true,
			'rewrite' => array('slug' => 'shopping-lists'),
			'supports' => array('title','editor','author','excerpt','comments','revisions')
		)
	);
	flush_rewrite_rules();
}

function create_shopping_lists_taxonomies() {
	register_taxonomy(
		'shopping-lists-categories',
		'shopping-lists',
		array(
			'hierarchical' => true,
			'label' => 'Category',
			'query_var' => true,
			'rewrite' => array('slug' => 'shopping-lists-categories')
		)
	);
}

function create_shopping_lists_columns($columns) {
    $columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __('Name'),
		'taxonomy' => __('Taxonomy'),
		'acf-field' => __('Advanced Custom Field'),
	);
	return $columns;
}

function manage_shopping_lists_columns($column, $post_id) {
	global $post;
	switch($column) {
		case 'taxonomy':
			$test = get_post_meta($post_id, 'shopping-lists', true);
			if(!empty($test)) echo $test;
			break;
		case 'acf-field':
			$field = get_post_meta($post_id, 'acf-field', true);
			if(!empty($field)) echo $field;
			break;
		default : break;
	}
}

add_action('init', 'create_shopping_lists');
add_action('init', 'create_shopping_lists_taxonomies');
add_filter('manage_edit-shopping_lists_columns', 'create_shopping_lists_columns' ) ;
add_action('manage_shopping_lists_posts_custom_column', 'manage_shopping_lists_columns', 10, 2 );

remove_action( 'woocommerce_before_single_product', 'woocommerce_output_all_notices', 10 );
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_all_notices', 10 );

if( function_exists('acf_add_options_page')) {
	acf_add_options_page(array(
		'page_title' => 'Theme Options',
		'menu_title' => 'Theme Options',
		'menu_slug' => 'theme-options',
		'capability' => 'edit_posts',
		'redirect' => true
	));
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Emails',
		'menu_title'	=> 'Emails',
		'parent_slug'	=> 'theme-options',
	));
}


/**
 * Changes the redirect URL for the Return To Shop button in the cart.
 *
 * @return string
 */
function wc_empty_cart_redirect_url() {
	return '/classes';
}
add_filter( 'woocommerce_return_to_shop_redirect', 'wc_empty_cart_redirect_url' );

add_filter( 'gettext', 'change_woocommerce_return_to_shop_text', 20, 3 );

function change_woocommerce_return_to_shop_text( $translated_text, $text, $domain ) {

        switch ( $translated_text ) {

            case 'Return to shop' :

                $translated_text = __( 'Return to Classes', 'woocommerce' );
                break;

        }

    return $translated_text;
}

/**
 * Change the Get Tickets on List View and Single Events
 *
 * @param string $translation The translated text.
 * @param string $text        The text to translate.
 * @param string $domain      The domain slug of the translated text.
 * @param string $context     The option context string.
 *
 * @return string The translated text or the custom text.
 */
 
/*
add_filter( 'gettext_with_context', 'tribe_change_get_tickets', 20, 4 );
function tribe_change_get_tickets( $translation, $text, $context = "" , $domain) {
 
  if ( $domain != 'default'
       && strpos( $domain, 'event-' ) !== 0
  ) {
    return $translation;
  }
 
  $ticket_text = [
    // Get Tickets on List View
    'Get %s'      => 'Purchase',
    // Get Tickets Form - Single View
    'Get Tickets' => 'Purchase',
  ];
 
  // If we don't have replacement text, bail.
  if ( empty( $ticket_text[ $text ] ) ) {
    return $translation;
  }
 
  return $ticket_text[ $text ];
}
*/

/**
 * Auto Complete all WooCommerce orders.
 */
add_action( 'woocommerce_thankyou', 'custom_woocommerce_auto_complete_order' );
function custom_woocommerce_auto_complete_order( $order_id ) { 
    if ( ! $order_id ) {
        return;
    }

    $order = wc_get_order( $order_id );
    $order->update_status( 'completed' );
}

/**
 * Change "Coupon"
 */
add_filter( 'gettext', 'woocommerce_rename_coupon_field_on_cart', 10, 3 );
add_filter( 'gettext', 'woocommerce_rename_coupon_field_on_cart', 10, 3 );
add_filter('woocommerce_coupon_error', 'rename_coupon_label', 10, 3);
add_filter('woocommerce_coupon_message', 'rename_coupon_label', 10, 3);
add_filter('woocommerce_cart_totals_coupon_label', 'rename_coupon_label',10, 1);
add_filter( 'woocommerce_checkout_coupon_message', 'woocommerce_rename_coupon_message_on_checkout' );


function woocommerce_rename_coupon_field_on_cart( $translated_text, $text, $text_domain ) {
	// bail if not modifying frontend woocommerce text
	if ( is_admin() || 'woocommerce' !== $text_domain ) {
		return $translated_text;
	}
	if ( 'Coupon:' === $text ) {
		$translated_text = 'Gift Certificate:';
	}

	if ('Coupon has been removed.' === $text){
		$translated_text = 'Gift certificate has been removed.';
	}

	if ( 'Apply coupon' === $text ) {
		$translated_text = 'Apply';
	}

	if ( 'Coupon code' === $text ) {
		$translated_text = 'Add Gift Certificate / Credit';
	
	} 

	return $translated_text;
}


// rename the "Have a Coupon?" message on the checkout page
function woocommerce_rename_coupon_message_on_checkout() {
	return 'Have a code?' . ' ' . __( '<a href="#" class="showcoupon">Click here to enter your code</a>', 'woocommerce' ) . '';
}


function rename_coupon_label($err, $err_code=null, $something=null){

	$err = str_ireplace("Coupon","Gift Certificate ",$err);

	return $err;
}

/* Tribe, prevent woo tickets email to be sent */
add_filter( 'tribe_tickets_plus_email_enabled', '__return_false' );

/*
* The Events Calendar WooCommerce Tickets - Change You'll receive your tickets in another email.
* @ Version 4.3.1
*/
add_filter('wootickets_email_message', 'woo_tickets_filter_completed_order', 10 );
function woo_tickets_filter_completed_order($text) {
	$text = "";
 	return $text;
}

/**
 * Note: DO NOT! alter or remove the code above this text and only add your custom PHP functions below this text.
 */
 
//on event post save, add categories to all tickets 
function updated_ticket_product_cat( $post_id ) {

//get categories
$terms = get_the_terms($post_id, 'tribe_events_cat');

//Get ticket id
$tickets = Tribe__Tickets__Tickets::get_all_event_tickets($post_id);

	if($terms) {

		//add categories to tickets
		$count = count($terms);
		if ( $count > 0 ){
			foreach ( $terms as $term ) {
				
				foreach($tickets as $ticket) {
					$ticket_id = $ticket->ID;
					wp_set_object_terms( $ticket_id , $term->slug, 'product_cat', true );
				}				
			}
		}
	}
}
add_action( 'save_post', 'updated_ticket_product_cat' );

function get_event_id_from_order_id($id) {
	$order = new WC_Order($id);
	$items = $order->get_items();
	$tickets = array();
		
	$info = "";
	
	foreach ( $items as $item_id => $item ) {
		$product_id = $item['product_id'];
		
		$event_id = get_tribe_event_ID_from_product($product_id);

		return $event_id;

	}
	
}
			
function get_tribe_event_ID_from_product($product_id) {
	if ( class_exists( 'Tribe__Tickets_Plus__Commerce__WooCommerce__Main' )) {
		$my_tribe = new Tribe__Tickets_Plus__Commerce__WooCommerce__Main();
		$my_event_id = get_post_meta($product_id, $my_tribe->event_key, true);
		return $my_event_id;
	}
}

//** Add fields to order emails - customer

function gum_woo_checkout_fields( $order_id ) {
	  
	//* NEW	
	$fieldset_meta = get_post_meta( $order_id, Tribe__Tickets_Plus__Meta::META_KEY, true );
	$cnt = 1;
	
	if (! $fieldset_meta ) return;

	echo '<h3> Attendees </h3>';
	
	foreach( $fieldset_meta AS $item => $value ) {
		
		foreach( $fieldset_meta[$item] AS $key => $value ) {
				
				$att_name 		= (isset( $value['name'] )) ? $value['name'] : '';
				$att_lname 		= (isset( $value['last-name'] )) ? $value['last-name'] : '';
				$att_email 		= (isset( $value['email'] )) ? $value['email'] : '';
				$att_phone 		= (isset( $value['phone'] )) ? $value['phone'] : '';
				
				echo '<p>';
				echo '<strong>Attendee - '. $cnt .'</strong><br />';
				echo 'Name: '. $att_name .'<br />';
				echo 'Email: '. $att_email .'<br />';
				echo 'Phone: '. $att_phone .'<br />';
				echo '</p>';
			
			$cnt++;
			
		}	
	}
}

//add_action( 'woocommerce_email_customer_details', 'gum_woo_checkout_fields_email_customer' );

function gum_woo_checkout_fields_email_customer( $order ) {
	  
	 
	  
	//* NEW	
	$fieldset_meta = get_post_meta( $order->get_id(), Tribe__Tickets_Plus__Meta::META_KEY, true );
	$cnt = 1;
	
	if (! $fieldset_meta ) return;

	echo '<h3> Attendee Information</h3>';
	
	foreach( $fieldset_meta AS $item => $value ) {
		
		foreach( $fieldset_meta[$item] AS $key => $value ) {
				
				$att_name 		= (isset( $value['name'] )) ? $value['name'] : '';
				$att_email 		= (isset( $value['email'] )) ? $value['email'] : '';
				$att_phone 		= (isset( $value['phone'] )) ? $value['phone'] : '';
				$att_wine 		= (isset( $value['please-select-your-wine'] )) ? $value['please-select-your-wine'] : '';
				// echo $value['do-you-need-a-vegetarian-option'];
				
				echo '<p>';
				echo 'Name: '. $att_name .'<br />';
				echo 'Email: '. $att_email .'<br />';
				if($att_phone) {
					echo 'Phone: '. $att_phone .'<br />';
				}
				
				if(isset($value['do-you-need-a-vegetarian-option'])){
					echo 'Vegetarian Option: Yes<br />';
				}
				
				if($att_wine) {
					echo 'Wine: '. $att_wine .'<br />';
				}
				echo '</p>';
			
			$cnt++;
			
		}	
	}
}

 /**
 * Remove page header
 */
function sf_change_homepage_title( $args ) {
    remove_action( 'storefront_page', 'storefront_page_header', 10 );
}
add_action( 'wp', 'sf_change_homepage_title' );

 /**
 * Waitlist Button
 */

add_filter( 'update_waitlist_button_text', 'change_waitlist_join_button_text' );
function change_waitlist_join_button_text( $text ) {
	return __( 'Add to Waitlist' );
}

add_filter( 'wcwl_event_waitlist_message_text', 'change_waitlist_message_text' );
function change_waitlist_message_text( $text ) {
	return __( 'Check the box alongside any Sold Out items and enter your email address to be emailed when those items become available!' );
}

function tribe_events_set_notice_past_events( $notice, $key ) {
if ( 'event-past' === $key ) {
$notice = 'Event registration has closed';
}
return $notice;
}

add_filter( 'tribe_events_set_notice', 'tribe_events_set_notice_past_events', 10, 2 );

 /**
 * Remove customer details from emails


add_action( 'woocommerce_email', function ( $email_class ) {
    remove_action( 'woocommerce_email_customer_details', array( $email_class, 'customer_details' ), 20, 3 );
});
 */
 
 // Removes Order Notes Title - Additional Information & Notes Field
add_filter( 'woocommerce_enable_order_notes_field', '__return_false', 9999 );



// Remove Order Notes Field
add_filter( 'woocommerce_checkout_fields' , 'remove_order_notes' );

function remove_order_notes( $fields ) {
     unset($fields['order']['order_comments']);
     return $fields;
}

add_filter( 'woocommerce_registration_error_email_exists', function( $html ) {
	$url =  wc_get_page_permalink( 'myaccount' );
	$url = add_query_arg( 'redirect_checkout', 1, $url );
	$html = str_replace( 'Please log in', '<a href="/my-account"><strong>Please log in</strong></a>', $html );
	return $html;
} );

add_filter( 'woocommerce_login_redirect', function( $redirect, $user ) {
	if ( $_GET['redirect_checkout'] ) {
        return wc_get_checkout_url();
    } elseif ( wp_get_referer() ) {
		return wp_get_referer();
	} else {
		return $redirect;
	}
}, 10, 2 );

/**
 * Change the Get Tickets on List View and Single Events
 *
 * @param string $translation The translated text.
 * @param string $text        The text to translate.
 * @param string $domain      The domain slug of the translated text.
 * @param string $context     The option context string.
 *
 * @return string The translated text or the custom text.
 */
 
add_filter( 'gettext_with_context', 'tribe_change_get_tickets', 20, 4 );
function tribe_change_get_tickets( $translation, $text, $context = "" , $domain) {
 
  if ( $domain != 'default'
       && strpos( $domain, 'event-' ) !== 0
  ) {
    return $translation;
  }
 
  $ticket_text = [
    // Get Tickets on List View
    'Get %s'      => 'Purchase',
    // Get Tickets Form - Single View
    'Get Tickets' => 'Purchase',
  ];
 
  // If we don't have replacement text, bail.
  if ( empty( $ticket_text[ $text ] ) ) {
    return $translation;
  }
 
  return $ticket_text[ $text ];
}

function find_tickets($id) {
	return Tribe__Tickets__Tickets::get_all_event_tickets($id);
}

/* Tribe, retrive Woo tickets ids for a give post_id - returns array or false if event tickets plus is not active */
function tribe_get_woo_tickets_ids( $post_id ) {

	// bail if event tickets plus is not active
	if ( !class_exists('Tribe__Tickets_Plus__Commerce__WooCommerce__Main') ) return false;
	
	$tickets_provider = Tribe__Tickets_Plus__Commerce__WooCommerce__Main::get_instance();
	
	return $tickets_provider->get_tickets_ids ( $post_id );

}

function removing_customer_details_in_emails( $order, $sent_to_admin, $plain_text, $email ){
    $wmail = WC()->mailer();
    remove_action( 'woocommerce_email_customer_details', array( $wmail, 'email_addresses' ), 20, 3 );
}
add_action( 'woocommerce_email_customer_details', 'removing_customer_details_in_emails', 5, 4 );

function get_qty_available($id) {
	if (class_exists('Tribe__Tickets__Tickets_Handler')) {
		$qty = (new Tribe__Tickets__Tickets_Handler)->get_ticket_max_purchase($id);
		return $qty;
	}
}

add_filter('woocommerce_checkout_get_value','__return_empty_string',10);
 
function yourprefix_add_to_content($content) { 
	$start = get_field('start_date');					
	$start = (new DateTime($start))->modify('-48 hours')->format('F d, Y');
	
    if((is_singular('ticket-page')) &&  (has_term('class','ticket-page-categories'))) {
        $content .= '<h3 style="margin-bottom:50px; text-align: center;">Note: Ingredient Kits are available up until 2 days prior the class date</h3>';
    }
    
    return $content;    
}

// add_filter( 'the_content', 'yourprefix_add_to_content' );		
 
 /**
 * Remove related products output
 */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

 /**
 * Get waitlist Counts
 */

function wcwl_get_count_for_event( $event_id ) {
   $tickets = Tribe__Tickets__Tickets::get_all_event_tickets( $event_id );
   if ( $tickets ) {
      foreach ( $tickets as $ticket ) {
         $waitlist = get_post_meta( $ticket->ID, 'woocommerce_waitlist', true );
         if ( $waitlist ) {
            echo( $ticket->name . ': ' . count( $waitlist ) );
            echo 'There is a waitlist';
         } else {
	         echo 'there is no waitlist';
         }
      }
   }
}

/*
add_action( 'admin_init', 'wcwl_get_event_counts' );
function wcwl_get_event_counts() {
   wcwl_get_count_for_event( 42682 );
}
*/

 /**
 * Reminder Emails
 */
 
function send_reminder_email() {
	$tz = 'America/Los_Angeles';
	$today = new DateTime('now', new DateTimeZone($tz));
	$today = $today->format('Y-m-d H:i:s');
	
	$start_day = new DateTime('today midnight', new DateTimeZone($tz));
	$start_day->modify('+3 day');
	$start_day = $start_day->format('Y-m-d H:i:s');
	
	$end_day = new DateTime('tomorrow midnight', new DateTimeZone($tz));
	$end_day->modify('+3 day');
	$end_day = $end_day->format('Y-m-d H:i:s');
	
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
			$class_date = $class_start_date->format('F j');
			$class_time = $class_start_date->format('g:ia');
			
		    $class_link = get_the_permalink();
		    $class_name = get_the_title();
		    $instructor = get_field('instructor');
		    $zoom_info = get_field('zoom_info');
		    $email_text = get_field('reminder', 'options');
		    
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
		    foreach($attendee_list as $attendee) {
			    if(isset($attendee['attendee_meta']['email']['value'])){
			    	$attendee_name = $attendee['attendee_meta']['name']['value'];					
			    	$email = $attendee['attendee_meta']['email']['value'];
					// $email = 'hornerbrett@gmail.com';
					
					$mailer = WC()->mailer();
				    $recipient = $email;

				    $class_name_friendly = str_replace("&#8211;", "-", $class_name);
					$class_name_friendly = substr($class_name_friendly, strpos($class_name_friendly, "-") + 1);
				    $class_name_friendly = preg_replace('/^([^,]*).*$/', '$1', $class_name_friendly);

				    $subject = $class_date . ' ' . $class_time . ' ' . htmlentities($class_name_friendly) . ' ' . 'online class & ingredient kit reminder';
				    $content = get_reminder_email($email_text, $attendee_name, $class_name, $class_name_friendly, $instructor, $class_id, $class_date, $class_time, $list_link, $packet, $zoom_info, $prep_instructions, $mailer);

				    $headers = "Content-Type: text/html\r\n";
				    $headers .= "Bcc: info@thepantryseattle.com" . "\r\n";
				    $headers .= "List-Unsubscribe: <mailto:info@thepantryseattle.com?subject=unsubscribe>\r\n";
				    $mailer->send($recipient, $subject, $content, $headers);

					$emails[] = $attendee['attendee_meta']['email']['value'];
				}
			}
									    
		}
	} wp_reset_postdata();
}

function get_reminder_email($email_text, $attendee_name, $class_name, $class_name_friendly, $instructor, $class_id, $class_date, $class_time, $list_link, $packet, $zoom_info, $prep_instructions, $mailer) {
	$template = 'emails/reminder.php';
	return wc_get_template_html($template, array(
		'attendee_name' => $attendee_name,
		'email_heading' => 'Hi there!',
		'class_name' => $class_name,
		'class_name_friendly' => $class_name_friendly,
		'instructor' => $instructor,
		'class_id' => $class_id,
		'class_date' => $class_date,
		'class_time' => $class_time,
		'list_link' => $list_link,
		'packet' => $packet,
		'zoom_info' => $zoom_info,
		'prep_instructions' => $prep_instructions,
		'email_text' => $email_text,
		'sent_to_admin' => false,
		'plain_text' => false,
		'email' => $mailer
	));
}

 /**
 * Shopping List Emails
 */
 
function send_shopping_list_email() {
	$tz = 'America/Los_Angeles';
	$today = new DateTime('now', new DateTimeZone($tz));
	$today = $today->format('Y-m-d H:i:s');
	
	$start_day = new DateTime('today midnight', new DateTimeZone($tz));
	$start_day->modify('+7 day');
	$start_day = $start_day->format('Y-m-d H:i:s');
	
	$end_day = new DateTime('tomorrow midnight', new DateTimeZone($tz));
	$end_day->modify('+7 day');
	$end_day = $end_day->format('Y-m-d H:i:s');
	$email_text = get_field('shopping_list', 'options');
    
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
                'terms' => 'no-shopping',
                'operator' => 'NOT IN'
            ),
		    'relation' => 'AND',
        ),
	);

	$query = new WP_Query($args);
    if ( $query->have_posts() ) {
	    while ( $query->have_posts() ) { $query->the_post();						    
		    
			$class_start_date = new DateTime(get_field('start_date'));
			$class_date = $class_start_date->format('F j');
			$class_time = $class_start_date->format('g:i a');
			
		    $class_link = get_the_permalink();
		    $class_name = get_the_title();
		    $instructor = get_field('instructor');
		    if(get_field('shopping_list')) {
			    $list_link = get_field('shopping_list');
			    $list_link = $list_link['url'];
		    } else {
			    $list_link = '';
		    }
		    $class_id = get_the_ID();
		    
		    $attendee_list = Tribe__Tickets__Tickets::get_event_attendees($class_id);
		    $emails = [];
		    $email = '';
		    foreach($attendee_list as $attendee) {
			    if(isset($attendee['attendee_meta']['email']['value'])){
			    	$attendee_name = $attendee['attendee_meta']['name']['value'];					
			    	$email = $attendee['attendee_meta']['email']['value'];
					// $email = 'hornerbrett@gmail.com';
					
					$mailer = WC()->mailer();
				    $recipient = $email;

				    $class_name_friendly = str_replace("&#8211;", "-", $class_name);
					$class_name_friendly = substr($class_name_friendly, strpos($class_name_friendly, "-") + 1);
				    $class_name_friendly = preg_replace('/^([^,]*).*$/', '$1', $class_name_friendly);

				    $subject = $class_date . ' ' . htmlentities($class_name_friendly) . ' ' . 'class shopping list';

				    $content = get_shopping_list_email($email_text, $attendee_name, $class_name, $class_name_friendly, $instructor, $class_id, $class_date, $class_time, $list_link, $mailer);

				    $headers = "Content-Type: text/html\r\n";
				    $headers .= "Bcc: info@thepantryseattle.com" . "\r\n";
				    $headers .= "List-Unsubscribe: <mailto:info@thepantryseattle.com?subject=unsubscribe>\r\n";
				    $mailer->send($recipient, $subject, $content, $headers);

					$emails[] = $attendee['attendee_meta']['email']['value'];
				}

			}
									    
		}
	} wp_reset_postdata();
}

function get_shopping_list_email($email_text, $attendee_name, $class_name, $class_name_friendly, $instructor, $class_id, $class_date, $class_time, $list_link, $mailer) {
	$template = 'emails/shopping-list.php';
	return wc_get_template_html($template, array(
		'attendee_name' => $attendee_name,
		'email_heading' => 'Hello friends,',
		'class_name' => $class_name,
		'class_name_friendly' => $class_name_friendly,
		'instructor' => $instructor,
		'class_id' => $class_id,
		'class_date' => $class_date,
		'class_time' => $class_time,
		'list_link' => $list_link,
		'email_text' => $email_text,
		'sent_to_admin' => false,
		'plain_text' => false,
		'email' => $mailer
	));
}

 /**
 * Class Evaluation
 */
 
function send_class_evaluation_email() {
	$tz = 'America/Los_Angeles';
	$today = new DateTime('now', new DateTimeZone($tz));
	$today = $today->format('Y-m-d H:i:s');
	
	$start_day = new DateTime('today midnight', new DateTimeZone($tz));
	$start_day->modify('-1 day');
	$start_day = $start_day->format('Y-m-d H:i:s');
	
	$end_day = new DateTime('tomorrow midnight', new DateTimeZone($tz));
	$end_day->modify('-1 day');
	$end_day = $end_day->format('Y-m-d H:i:s');
	$email_text = get_field('class_evaluation', 'options');

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
			$class_date = $class_start_date->format('F j');
			$class_time = $class_start_date->format('g:i a');
			
		    $class_link = get_the_permalink();
		    $class_name = get_the_title();
		    $instructor = get_field('instructor');
		    if(get_field('class_survey')) {
			    $survey = get_field('class_survey');
		    } else {
			    $survey = '*survey link*';
		    }
		    $class_id = get_the_ID();
		    
		    $attendee_list = Tribe__Tickets__Tickets::get_event_attendees($class_id);
		    $emails = [];
		    foreach($attendee_list as $attendee) {
			    if(isset($attendee['attendee_meta']['email']['value'])){
			    	$attendee_name = $attendee['attendee_meta']['name']['value'];					
			    	$email = $attendee['attendee_meta']['email']['value'];
					// $email = 'hornerbrett@gmail.com';

					$mailer = WC()->mailer();
				    $recipient = $email;

				    $class_name_friendly = str_replace("&#8211;", "-", $class_name);
					$class_name_friendly = substr($class_name_friendly, strpos($class_name_friendly, "-") + 1);
				    $class_name_friendly = preg_replace('/^([^,]*).*$/', '$1', $class_name_friendly);
				    
					$subject = $class_date . ' ' . htmlentities($class_name_friendly) . ' ' . 'class evaluation';

				    $content = get_class_evaluation_email($email_text, $attendee_name, $class_name, $instructor, $class_id, $class_date, $class_time, $survey, $mailer);

				    $headers = "Content-Type: text/html\r\n";
				    $headers .= "Bcc: info@thepantryseattle.com" . "\r\n";
				    $headers .= "List-Unsubscribe: <mailto:info@thepantryseattle.com?subject=unsubscribe>\r\n";
				    $mailer->send($recipient, $subject, $content, $headers);

					$emails[] = $attendee['attendee_meta']['email']['value'];
				}

			}
									    
		}
	} wp_reset_postdata();
}

function get_class_evaluation_email($email_text, $attendee_name, $class_name, $instructor, $class_id, $class_date, $class_time, $survey, $mailer) {
	$template = 'emails/class-evaluation.php';
	return wc_get_template_html($template, array(
		'attendee_name' => $attendee_name,
		'email_heading' => 'Hello!',
		'class_name' => $class_name,
		'instructor' => $instructor,
		'class_id' => $class_id,
		'class_date' => $class_date,
		'class_time' => $class_time,
		'survey' => $survey,
		'email_text' => $email_text,
		'sent_to_admin' => false,
		'plain_text' => false,
		'email' => $mailer
	));
}

 /**
 * To Go Reminder
 */
 
function send_to_go_reminder_email() {
	$tz = 'America/Los_Angeles';
	$today = new DateTime('now', new DateTimeZone($tz));
	$today = $today->format('Y-m-d H:i:s');
	
	$start_day = new DateTime('today midnight', new DateTimeZone($tz));
	$start_day = $start_day->format('Y-m-d H:i:s');
	
	$end_day = new DateTime('tomorrow midnight', new DateTimeZone($tz));
	$end_day = $end_day->format('Y-m-d H:i:s');
	$email_text = get_field('to-go', 'options');

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
			$class_date = $class_start_date->format('F j');
			$class_time = $class_start_date->format('g:i a');
			
		    $class_link = get_the_permalink();
		    $class_name = get_the_title();
		    $instructor = get_field('instructor');
		    
		    if(get_field('recipe_packet')) {
			    $packet = get_field('recipe_packet');
			    $packet = $packet['url'];
		    } else {
			    $packet = '';
		    }
		    
		    $class_id = get_the_ID();
		    
		    $attendee_list = Tribe__Tickets__Tickets::get_event_attendees($class_id);
		    $emails = [];
		    foreach($attendee_list as $attendee) {
			    if(isset($attendee['attendee_meta']['email']['value'])){
			    	$attendee_name = $attendee['attendee_meta']['name']['value'];					
			    	$email = $attendee['attendee_meta']['email']['value'];
					// $email = 'hornerbrett@gmail.com';
					
					$mailer = WC()->mailer();
				    $recipient = $email;

				    $class_name_friendly = str_replace("&#8211;", "-", $class_name);
					$class_name_friendly = substr($class_name_friendly, strpos($class_name_friendly, "-") + 1);
				    $class_name_friendly = preg_replace('/^([^,]*).*$/', '$1', $class_name_friendly);

					$subject = $class_date . ' ' . htmlentities($class_name_friendly) . ' ' . 'pickup reminder + recipes!';

				    $content = get_to_go_reminder_email($email_text, $class_name_friendly, $class_name, $class_id, $class_date, $packet, $class_time, $mailer);

				    $headers = "Content-Type: text/html\r\n";
				    $headers .= "Bcc: info@thepantryseattle.com" . "\r\n";
				    $headers .= "List-Unsubscribe: <mailto:info@thepantryseattle.com?subject=unsubscribe>\r\n";
				    $mailer->send($recipient, $subject, $content, $headers);

					$emails[] = $attendee['attendee_meta']['email']['value'];
				}

			}
									    
		}
	} wp_reset_postdata();
}

function send_email_test() {
	$mailer = WC()->mailer();
	$headers = "Content-Type: text/html\r\n";
	$mailer->send('patrick@newtheory.is', 'test', 'test', $headers);
}

function get_to_go_reminder_email($email_text, $class_name_friendly, $class_name, $class_id, $class_date, $packet, $class_time, $mailer) {
	$template = 'emails/to-go-reminder.php';
	return wc_get_template_html($template, array(
		'email_heading' => 'Hello!',
		'class_name' => $class_name,
		'class_name_friendly' => $class_name_friendly,
		'class_id' => $class_id,
		'class_date' => $class_date,
		'class_time' => $class_time,
		'email_text' => $email_text,
		'packet' => $packet,
		'sent_to_admin' => false,
		'plain_text' => false,
		'email' => $mailer
	));
}

add_action( 'send_reminder_email', 'send_reminder_email');
add_action( 'send_shopping_list_email', 'send_shopping_list_email');
add_action( 'send_class_evaluation_email', 'send_class_evaluation_email');
add_action( 'send_to_go_reminder_email', 'send_to_go_reminder_email');
