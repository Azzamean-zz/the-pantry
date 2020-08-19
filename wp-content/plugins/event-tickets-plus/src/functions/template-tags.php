<?php
if ( ! function_exists( 'tribe_tickets_is_edd_active' ) ) {
	/**
	 * Check if Easy Digital Downloads is active.
	 *
	 * @since 4.7.3
	 * @since 4.12.3 Changed from class_exists() check to function_exists() check.
	 *
	 * @return bool Whether the core ecommerce plugin is active.
	 */
	function tribe_tickets_is_edd_active() {
		return function_exists( 'EDD' );
	}
}

if ( ! function_exists( 'tribe_tickets_is_woocommerce_active' ) ) {
	/**
	 * Check if WooCommerce is active.
	 *
	 * @since 4.12.3
	 *
	 * @return bool Whether the core ecommerce plugin is active.
	 */
	function tribe_tickets_is_woocommerce_active() {
		return function_exists( 'WC' );
	}
}
