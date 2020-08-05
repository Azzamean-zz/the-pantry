<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Adds the pro scripts to enqueue.
 */
function ccfwoo_pro_scripts() {

	$localized_data = array(
		'ajax_url'                => admin_url( 'admin-ajax.php' ),
		'redirection_type'        => ccfwoo_get_option( 'redirection_type', 'ccfwoo_advanced_section', false ),
		'redirection_seconds'     => ccfwoo_get_option( 'redirection_seconds', 'ccfwoo_advanced_section', false ),
		'redirection_url'         => ccfwoo_get_option( 'redirection_url', 'ccfwoo_advanced_section', false ),
		'clear_cart_enable'       => ccfwoo_get_option( 'clear_cart_enable', 'ccfwoo_advanced_section', false ),
		'clear_cart_wait_seconds' => ccfwoo_get_option( 'clear_cart_wait_seconds', 'ccfwoo_advanced_section', false ),
		'loop_enable'             => ccfwoo_get_option( 'loop_enable', 'ccfwoo_advanced_section', false ),
		'loop_wait_seconds'       => ccfwoo_get_option( 'loop_wait_seconds', 'ccfwoo_advanced_section', false ),
		'loop_refresh_cart'       => ccfwoo_get_option( 'loop_refresh_cart', 'ccfwoo_advanced_section', false ),
		'ajax_support_enable'     => ccfwoo_get_option( 'ajax_support_enable', 'ccfwoo_advanced_section', false ),
		'reset_add_to_cart'       => ccfwoo_get_option( 'reset_add_to_cart', 'ccfwoo_advanced_section', false ),
		'should_reset'            => apply_filters( 'ccfwoo_pro_reset_interval', false ), // used when items added to cart.
		'cart_url'                => ! empty( wc_get_cart_url() ) ? wc_get_cart_url() : false,

		// BASC.
		'basc_days'               => ccfwoo_get_option( 'basc_days', 'ccfwoo_deprecated_section', 0 ),
		'basc_hours'              => ccfwoo_get_option( 'basc_hours', 'ccfwoo_deprecated_section', 0 ),
		'basc_minutes'            => ccfwoo_get_option( 'basc_minutes', 'ccfwoo_deprecated_section', 0 ),
		'basc_text'               => ccfwoo_get_option( 'basc_text', 'ccfwoo_deprecated_section', false ),
		'basc_expired_text'       => ccfwoo_get_option( 'basc_expired_text', 'ccfwoo_deprecated_section', false ),
		'basc_display_due_date'   => ccfwoo_get_option( 'basc_display_due_date', 'ccfwoo_deprecated_section', false ),
		'basc_due_date_language'  => ccfwoo_get_option( 'basc_due_date_language', 'ccfwoo_deprecated_section', false ),
	);

	// Enqueue JS.
	wp_enqueue_script( 'checkout-countdown-pro', plugin_dir_url( __FILE__ ) . '../js/checkout-countdown-pro.min.js', array( 'jquery' ), '3.1.1', true );
	// Localize JS.
	wp_localize_script( 'checkout-countdown-pro', 'ccfwooPro', $localized_data );

	// Add the BASC JS, only if legacy features or BASC is enabled.
	if ( ccfwoo_get_option( 'basc_enable', 'ccfwoo_deprecated_section', false ) === 'on' || ccfwoo_get_option( 'legacy_options', 'false', false ) === 'on' ) {
		wp_enqueue_script( 'checkout-countdown-basc', plugin_dir_url( __FILE__ ) . '../js/checkout-countdown-basc.min.js', array( 'jquery' ), '3.1.1', true );
		// Localize basc.
		wp_localize_script( 'checkout-countdown-basc', 'ccfwooPro', $localized_data );
	}

}
add_action( 'ccfwoo_enqueue_scripts', 'ccfwoo_pro_scripts' );

function ccfwoo_pro_expired_message_seconds( $seconds ) {
	$seconds = ccfwoo_get_option( 'expired_message_seconds', 'ccfwoo_advanced_section', $seconds );

	return $seconds;
}
add_filter( 'ccfwoo_expired_message_seconds', 'ccfwoo_pro_expired_message_seconds', 10, 1 );
