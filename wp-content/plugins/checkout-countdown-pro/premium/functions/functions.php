<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Remove upgrade notices.
 */
function ccfwoo_remove_upgrades() {
	remove_action( 'ccfwoo_form_bottom_ccfwoo_general_section', 'ccfwoo_upgrade_to_pro_settings_bottom', 10 );
	remove_filter( 'ccfwoo_above_settings_sidebars', 'ccfwoo_upgrade_to_pro_sidebar', 10 );
}
add_action( 'ccfwoo_after_core_loaded', 'ccfwoo_remove_upgrades' );

/**
 * Called via AJAX to clear the WC Cart.
 */
function ccfwoo_process_clear_cart() {

	$response = ccfwoo_empty_cart();

	wp_send_json( $response );
}
add_action( 'wp_ajax_ccfwoo_process_clear_cart', 'ccfwoo_process_clear_cart' );
add_action( 'wp_ajax_nopriv_ccfwoo_process_clear_cart', 'ccfwoo_process_clear_cart' );

/**
 * Removes specific product form cart.
 */
function ccfwoo_remove_product_from_cart( $product_id ) {

	if ( ! is_object( WC() ) ) {
		return false;
	}

	$cart         = WC()->instance()->cart;
	$id           = $product_id;
	$cart_id      = $cart->generate_cart_id( $id );
	$cart_item_id = $cart->find_product_in_cart( $cart_id );

	if ( $cart_item_id ) {
		$cart->set_quantity( $cart_item_id, 0 );
		return true;
	} else {
		return false;
	}

}

/**
 * Empties the entire WC Cart.
 */
function ccfwoo_empty_cart() {

	if ( ! is_object( WC() ) ) {
		return false;
	}

	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

		if ( $cart_item_key ) {

			WC()->cart->remove_cart_item( $cart_item_key );

			// Allow third-parties to access the removed product.
			do_action( 'ccfwoo_product_removed_from_cart', $item_data );
		}
	}

	return true;
}

 /**
  * Convert old options to the new options.
  */
function ccfwoo_pro_convert_for_banana() {

	$new_options  = get_option( 'ccfwoo_advanced_section', 'none' );
	$check_enable = get_option( 'ccfwoo_clear_cart', 'none' );
	$check_key    = get_option( 'ccfwoo_license_key', 'none' );

	// No old settings to convert - return.
	if ( $check_enable === 'none' && $check_key == 'none' ) {
		return;
	}

	// New settings already exsits - return.
	if ( is_array( $new_options ) ) {
		return;
	}

	$text_1 = ! empty( get_option( 'ccfwoo_advanced_text_1' ) ) ? get_option( 'ccfwoo_advanced_text_1' ) : '';
	$text_2 = ! empty( get_option( 'ccfwoo_advanced_text_2' ) ) ? get_option( 'ccfwoo_advanced_text_2' ) : '';
	$text_3 = ! empty( get_option( 'ccfwoo_advanced_text_3' ) ) ? get_option( 'ccfwoo_advanced_text_3' ) : '';
	$text_4 = ! empty( get_option( 'ccfwoo_advanced_text_4' ) ) ? get_option( 'ccfwoo_advanced_text_4' ) : '';
	$text_5 = ! empty( get_option( 'ccfwoo_advanced_text_5' ) ) ? get_option( 'ccfwoo_advanced_text_5' ) : '';

	$countdown_text = "$text_1 {days} $text_2 {hours} $text_3 {minutes} $text_4 {seconds} $text_5";

	$basc_options = array(
		'basc_enable'            => get_option( 'ccfwoo_advanced_enable' ),
		'basc_text'              => $countdown_text,
		'basc_days'              => get_option( 'ccfwoo_advanced_days' ),
		'basc_hours'             => get_option( 'ccfwoo_advanced_hours' ),
		'basc_minutes'           => get_option( 'ccfwoo_advanced_minutes' ),
		'basc_expired_text'      => get_option( 'ccfwoo_advanced_expired_text' ),
		'basc_display_due_date'  => get_option( 'ccfwoo_advanced_due_date' ),
		'basc_due_date_language' => get_option( 'ccfwoo_advanced_translate' ),
		'basc_display_style'     => get_option( 'ccfwoo_advanced_style' ),
	);

	$basc_options = ccfwoo_convert_to_new_array( $basc_options );

	$old_options = array(
		// redirect features.
		'redirection_type'        => get_option( 'ccfwoo_redirect_type' ),
		'redirection_seconds'     => get_option( 'ccfwoo_redirect_seconds' ),
		'redirection_url'         => get_option( 'ccfwoo_redirect_url' ),
		// When its Finished.
		'clear_cart_enable'       => get_option( 'ccfwoo_clear_cart' ),
		'clear_cart_wait_seconds' => get_option( 'ccfwoo_clear_cart_seconds' ),
		'loop_enable'             => get_option( 'ccfwoo_loop_countdown' ),
		'loop_wait_seconds'       => get_option( 'ccfwoo_loop_seconds' ),
		'loop_refresh_cart'       => get_option( 'ccfwoo_loop_refresh_totals' ),
		// Ajax support.
		'ajax_support_enable'     => get_option( 'ccfwoo_ajax_support' ),
		'legacy_options'          => get_option( 'ccfwoo_advanced_enable' ),
	);

	$old_options = ccfwoo_convert_to_new_array( $old_options );

	$license_section = array(
		'license' => array(
			'status' => get_option( 'ccfwoo_license_status' ),
			'expiry' => 'Recheck',
			'key'    => get_option( 'ccfwoo_license_key' ),
		),
	);

	update_option( 'ccfwoo_advanced_section', $old_options, false );
	update_option( 'ccfwoo_deprecated_section', $basc_options, false );
	update_option( 'ccfwoo_license_section', $license_section, false );

	if ( get_transient( 'ccfwoo_pro_verify_license' ) ) {
		 delete_transient( 'ccfwoo_pro_verify_license' );
	}
}

add_action( 'wp_loaded', 'ccfwoo_pro_convert_for_banana' );


function ccfwoo_convert_to_new_array( $old_array ) {

	$new_array = array();

	foreach ( $old_array as $key => $value ) {

		if ( empty( $value ) ) {
			// Skip if empty or false.
			continue;
		}
		if ( is_serialized( $value ) ) {
			// unserialize
			$value = unserialize( $value );
		}
		if ( is_array( $value ) ) {
			$value = $value[0];
		}
		if ( $value === 'yes' ) {
			$value = 'on';
		}

		$new_array[ $key ] = $value;
	}
	return $new_array;
}

// BASC
function ccfwoo_pro_bacs_count() {

	$enable_legacy = ccfwoo_get_option( 'legacy_options', 'ccfwoo_advanced_section', false );
	$enable_basc   = ccfwoo_get_option( 'basc_enable', 'ccfwoo_deprecated_section', false );
	$style         = ccfwoo_get_option( 'basc_display_style', 'ccfwoo_deprecated_section', false );

	if ( $enable_basc === 'on' && $enable_legacy === 'on' ) {

		if ( $style === 'custom' ) {

			echo '<div id="ccfwoo-advanced-countdown"></div>';

		} else {
			echo '<div id="ccfwoo-advanced-countdown" class="woocommerce-error"></div>';
		}
	}

}

add_action( 'woocommerce_thankyou_bacs', 'ccfwoo_pro_bacs_count' );


function ccfwoo_advanced_shortcode() {

		// Advanced feature for thank you pages
		$enable_legacy = ccfwoo_get_option( 'legacy_options', 'ccfwoo_advanced_section', false );
		$enable_basc   = ccfwoo_get_option( 'basc_enable', 'ccfwoo_deprecated_section', false );
		$style         = ccfwoo_get_option( 'basc_display_style', 'ccfwoo_deprecated_section', false );

	if ( $enable_basc === 'on' && $enable_legacy === 'on' ) {

		if ( $style === 'custom' ) {

			echo '<div id="ccfwoo-advanced-countdown"></div>';

		} else {
				echo '<div id="ccfwoo-advanced-countdown" class="woocommerce-error"></div>';
		}
	}

}
add_shortcode( 'cc-countdown-advanced', 'ccfwoo_advanced_shortcode' );
add_shortcode( 'cc_countdown_advanced', 'ccfwoo_advanced_shortcode' );
