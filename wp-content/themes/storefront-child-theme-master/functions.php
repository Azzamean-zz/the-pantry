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
 * Note: DO NOT! alter or remove the code above this text and only add your custom PHP functions below this text.
 */

 /**
 * Remove related products output
 */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );