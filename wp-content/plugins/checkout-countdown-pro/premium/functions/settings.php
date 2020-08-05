<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

 /**
  * Creates a new settings section.
  *
  * @param array
  *
  * @return array
  */
function ccfwoo_pro_settings_section( $array ) {
	$array[] = array(
		'id'       => 'ccfwoo_advanced_section',
		'title'    => __( 'Advanced', 'checkout-countdown-pro' ),
	);

	if ( ccfwoo_get_option( 'legacy_options', 'ccfwoo_advanced_section', false ) === 'on' ) {
		$array[] = array(
			'id'       => 'ccfwoo_deprecated_section',
			'title'    => __( 'Deprecated', 'checkout-countdown-pro' ),
		);
	}

	return $array;
}

 add_filter( 'ccfwoo_extend_settings_sections', 'ccfwoo_pro_settings_section' );

/**
 * Adds new fields under our new section `extended_section`.
 *
 * @param array
 *
 * @return array
 */
function ccfwoo_pro_setting_fields( $array ) {

	$array['ccfwoo_advanced_section'] = array(
		array(
			'name'  => 'clear_cart_section',
			'label' => __( 'Clear Cart', 'checkout-countdown-pro' ),
			'desc'  => __( 'Clear the customer\'s cart when the countdown has reached zero. <b>Note this only works while the customer is on the site.</b><br />We recommend our <a href="https://puri.io/plugin/woocommerce-clear-cart/" target="_blank">Clear Cart & Sessions for WooCommerce</a> as a complete cart management tool.' ),
			'type'  => 'subheading',
			'class' => 'subheading',
		),
		array(
			'name'  => 'clear_cart_enable',
			'label' => __( 'Enable Clear Cart', 'checkout-countdown-pro' ),
			'desc'  => __( 'Clear customer\'s cart when the countdown reaches zero in their browser.', 'checkout-countdown-pro' ),
			'type'  => 'checkbox',
		),
		array(
			'name'  => 'reset_heading',
			'label' => __( 'Reset Time', 'checkout-countdown-pro' ),
			'desc'  => __( 'Reset/restart the Checkout Countdown time when specific actions happen.' ),
			'type'  => 'subheading',
			'class' => 'subheading',
		),
		array(
			'name'  => 'reset_add_to_cart',
			'label' => __( 'Reset countdown when a product is added to cart', 'checkout-countdown-pro' ),
			'desc'  => __( 'Enable', 'checkout-countdown-pro' ),
			'type'  => 'checkbox',
		),

		array(
			'name'  => 'loop_section',
			'label' => __( 'Loop/Restart', 'checkout-countdown-pro' ),
			'desc'  => __( 'Looping will restart the countdown once the count has reached zero.' ),
			'type'  => 'subheading',
			'class' => 'subheading',
		),
		array(
			'name'  => 'loop_enable',
			'label' => __( 'Enable Looping', 'checkout-countdown-pro' ),
			'desc'  => __( 'Enable', 'checkout-countdown-pro' ),
			'type'  => 'checkbox',
		),
		array(
			'name'  => 'loop_refresh_cart',
			'label' => __( 'Refresh Cart on Loop', 'checkout-countdown-pro' ),
			'desc'  => __( 'Refreshes the cart products details with each loop.', 'checkout-countdown-pro' ),
			'type'  => 'checkbox',
		),

		array(
			'name'  => 'redirection_section',
			'label' => __( 'Redirection', 'checkout-countdown-pro' ),
			'desc'  => __( 'Redirect customers to a specifc URL when the countdown reaches zero.' ),
			'type'  => 'subheading',
			'class' => 'subheading',
		),
		array(
			'name'    => 'redirection_type',
			'label'   => __( 'Redirection Type', 'checkout-countdown-pro' ),
			'desc'    => '',
			'type'    => 'select',
			'options' => array(
				null   => 'Off',
				'cart' => 'Redirect to Cart',
				'url'  => 'Custom URL',
			),
		),
		array(
			'name'        => 'redirection_url',
			'label'       => __( 'Redirection URL', 'checkout-countdown-pro' ),
			'placeholder' => __( 'https://yourdomain.com/checkout' ),
			'type'        => 'text',
			'default'     => '',
		),
		array(
			'name'  => 'ajax_support_section',
			'label' => __( 'AJAX Support', 'checkout-countdown-pro' ),
			'desc'  => __( 'Allows the countdown to start instantly when a product is added to the cart via AJAX.<br /> Only use this if you know your theme supports "add to cart" via AJAX.' ),
			'type'  => 'subheading',
			'class' => 'subheading',
		),
		array(
			'name'  => 'ajax_support_enable',
			'label' => __( 'Enable AJAX Support', 'checkout-countdown-pro' ),
			'desc'  => __( 'Enable', 'checkout-countdown-pro' ),
			'type'  => 'checkbox',
		),
		array(
			'name'  => 'additional_section',
			'label' => __( 'Additional Configuration', 'checkout-countdown-pro' ),
			'desc'  => __( 'Additional settings to customize the countdown.' ),
			'type'  => 'subheading',
			'class' => 'subheading',
		),
		array(
			'name'        => 'expired_message_seconds',
			'label'       => __( 'Expired Message Time', 'checkout-countdown-pro' ),
			'desc'        => __( 'Change the amount of time the expire message displays.', 'checkout-countdown-pro' ),
			'default'     => 6,
			'min'         => 6,
			'step'        => 1,
			'max'         => 10000,
			'type'        => 'number',
		),
		array(
			'name'  => 'legacy_options_section',
			'label' => __( 'Deprecated features', 'checkout-countdown-pro' ),
			'desc'  => __( '<b>These are legacy featues which are no longer supported</b>. You can keep them on if you are already using them.' ),
			'type'  => 'subheading',
			'class' => 'subheading',
		),
		array(
			'name'  => 'legacy_options',
			'label' => __( 'Enable legacy options', 'checkout-countdown-pro' ),
			'desc'  => '',
			'type'  => 'checkbox',
		),
	);

	$array['ccfwoo_deprecated_section'] = array(
		array(
			'name'  => 'basc_section',
			'label' => __( 'BASC Gateway Countdown', 'checkout-countdown-pro' ),
			'desc'  => __( 'Enable Countdown on the Thank you page for BASC Payments' ),
			'type'  => 'subheading',
			'class' => 'subheading',
		),
		array(
			'name'  => 'basc_enable',
			'label' => __( 'Enable BACS Countdown', 'checkout-countdown-pro' ),
			'desc'  => __( 'Enable on the thank you page if you are using manual transfers', 'checkout-countdown-pro' ),
			'type'  => 'checkbox',
		),
		array(
			'name'    => 'basc_display_style',
			'label'   => __( 'Display Style', 'checkout-countdown-pro' ),
			'desc'    => '',
			'type'    => 'radio',
			'options' => array(
				null     => 'Default WooCommerce',
				'custom' => 'Custom',
			),
		),
		array(
			'name'        => 'basc_days',
			'label'       => __( 'Days', 'checkout-countdown-pro' ),
			'desc'        => __( 'Amount of days', 'checkout-countdown-pro' ),
			'placeholder' => __( '0', 'checkout-countdown-pro' ),
			'min'         => 0,
			'step'        => 1,
			'max'         => 100,
			'type'        => 'number',
		),
		array(
			'name'        => 'basc_hours',
			'label'       => __( 'hours', 'checkout-countdown-pro' ),
			'desc'        => __( 'Amount of hours', 'checkout-countdown-pro' ),
			'placeholder' => __( '0', 'checkout-countdown-pro' ),
			'min'         => 0,
			'step'        => 1,
			'max'         => 100,
			'type'        => 'number',
		),
		array(
			'name'        => 'basc_minutes',
			'label'       => __( 'minutes', 'checkout-countdown-pro' ),
			'desc'        => __( 'Amount of minutes', 'checkout-countdown-pro' ),
			'placeholder' => __( '0', 'checkout-countdown-pro' ),
			'min'         => 0,
			'step'        => 1,
			'max'         => 100,
			'type'        => 'number',
		),
		array(
			'name'        => 'basc_text',
			'label'       => __( 'Counting Down Text', 'checkout-countdown-pro' ),
			'desc'        => __( 'Use tags to insert the time where needed.<br />You can also use basic html.', 'checkout-countdown-pro' ),
			'placeholder' => __( 'We can only hold your order for {days} days {hours} hours {minutes} minutes and {seconds} seconds!', 'checkout-countdown-pro' ),
			'type'        => 'textarea',
			'default'     => 'We can only hold your order for {days} days {hours} hours {minutes} minutes and {seconds} seconds!',
		),
		array(
			'name' => 'html',
			'desc' => __( '<b>Available Tags</b><br /><code>{days}</code> <code>{hours}</code> <code>{minutes}</code> <code>{seconds}</code><br />', 'checkout-countdown-pro' ),
			'type' => 'html',
		),
		array(
			'name'        => 'basc_expired_text',
			'label'       => __( 'Expired text', 'checkout-countdown-pro' ),
			'type'        => 'textarea',
			'default'     => '',
		),
		array(
			'name'  => 'basc_display_due_date',
			'label' => __( 'Enable Due Date', 'checkout-countdown-pro' ),
			'desc'  => __( 'Due date will display along side the countdown', 'checkout-countdown-pro' ),
			'type'  => 'checkbox',
		),
		array(
			'name'        => 'basc_due_date_language',
			'label'       => __( 'Due Language', 'checkout-countdown-pro' ),
			'desc'        => __( 'Use ID for Indonesia' ),
			'placeholder' => __( 'ID' ),
			'type'        => 'text',
			'default'     => '',
		),
	);

	return $array;
}

 add_filter( 'ccfwoo_extend_setting_fields', 'ccfwoo_pro_setting_fields' );
