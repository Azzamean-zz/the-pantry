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
			'supports' => array('title','editor','author','excerpt','comments','revisions')
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
			'label' => 'Category',
			'query_var' => true,
			'rewrite' => array('slug' => 'ticket-page-categories')
		)
	);
}

function create_ticket_page_columns($columns) {
    $columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __('Name'),
		'taxonomy' => __('Taxonomy'),
		'acf-field' => __('Advanced Custom Field'),
	);
	return $columns;
}

function manage_ticket_page_columns($column, $post_id) {
	global $post;
	switch($column) {
		case 'taxonomy':
			$test = get_post_meta($post_id, 'ticket-page', true);
			if(!empty($test)) echo $test;
			break;
		case 'acf-field':
			$field = get_post_meta($post_id, 'acf-field', true);
			if(!empty($field)) echo $field;
			break;
		default : break;
	}
}

add_action('init', 'create_ticket_page');
add_action('init', 'create_ticket_page_taxonomies');
add_filter('manage_edit-ticket_page_columns', 'create_ticket_page_columns' ) ;
add_action('manage_ticket_page_posts_custom_column', 'manage_ticket_page_columns', 10, 2 );

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
add_action( 'save_post', 'updated_ticket_product_cat' );

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
 * Remove related products output
 */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );