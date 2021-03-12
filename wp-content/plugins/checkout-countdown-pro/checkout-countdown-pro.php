<?php
/**
 * Plugin Name:       Checkout Countdown for WooCommerce Pro
 * Description:       A flexible WooCommerce cart/checkout countdown to help improve cart conversion.
 * Version:           3.1.3
 * Author:            Puri.io
 * Author URI:        https://puri.io/
 * Text Domain:       checkout-countdown-pro
 *
 * WC requires at least: 3.0
 * WC tested up to: 4.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Get the value of a settings field
 *
 * @param string $option settings field name.
 * @param string $section the section name this field belongs to.
 * @param string $default default text if it's not found.
 *
 * @return mixed
 */
if ( ! function_exists( 'ccfwoo_get_option' ) ) {
	function ccfwoo_get_option( $option, $section = false, $default = '' ) {

		$section = $section === false ? 'ccfwoo_general_section' : $section;

		$options = get_option( $section );

		if ( isset( $options[ $option ] ) ) {
			return $options[ $option ];
		}
		return $default;
	}
}
if ( ! function_exists( 'ccfwoo_pro_admin_notifications' ) ) {
	function ccfwoo_pro_admin_notifications() {

		$compatability = apply_filters( 'ccfwoo_extend_setup', array() );

		if ( isset( $compatability['pro'] ) && isset( $compatability['pro']['version'] ) ) {

			if ( version_compare( $compatability['pro']['version'], '3.0.0' ) < 0 ) {
				$class   = 'notice notice-error';
				$message = __( 'Update required to Checkout Countdown Pro 3.0+ or downgrade to Checkout Countdown Free 2.4.4', 'checkout-countdown-for-woocommerce' );
				$button  = '<a href="https://puri.io/blog/checkout-countdown-3-0-release-notes/" target="_blank">Read why in our release notes.</a>
';

				printf( '<div class="%1$s"><p>%2$s - %3$s</p></div>', esc_attr( $class ), esc_html( $message ), $button );
			}
		}
	}
	add_action( 'admin_notices', 'ccfwoo_pro_admin_notifications' );
}



/**
 * CCFWOO_PRO_Init int the plugin.
 */
class CCFWOO_PRO_Init {
	/**
	 * Access all plugin constants
	 *
	 * @var array
	 */
	public $constants;

	/**
	 * Access notices class.
	 *
	 * @var class
	 */
	private $notices;

	/**
	 * Plugin init.
	 */
	public function __construct() {

		$this->constants = array(
			'name'           => 'Checkout Countdown for WooCommerce Pro',
			'version'        => '3.1.3',
			'prefix'         => 'ccfwoo_pro',
			'admin_page'     => 'checkout-countdown',
			'slug'           => plugin_basename( __FILE__, ' . php' ),
			'base'           => plugin_basename( __FILE__ ),
			'name_sanitized' => basename( __FILE__, '. php' ),
			'path'           => plugin_dir_path( __FILE__ ),
			'url'            => plugin_dir_url( __FILE__ ),
			'file'           => __FILE__,
		);

		// EDD Client for updates.
		require_once plugin_dir_path( __FILE__ ) . 'premium/components/edd-client/edd-client.php';

		$plugin_data = array(
			'name'        => 'Checkout Countdown for WooCommerce',
			'item_id'     => '827',
			'store_url'   => 'https://puri.io/',
			'version'     => '3.1.3',
			'author'      => 'Puri.io',
			'support_url' => 'https://puri.io/support/',
		);

		new ccfwoo_pro_EDD_Client( __FILE__, $plugin_data );

		// include Notices.
		include_once plugin_dir_path( __FILE__ ) . 'classes/class-admin-notices.php';
		// Set notices to class.
		$this->notices = new ccfwoo_pro_admin_notices();
		// Load plugin when all plugins are loaded.
		add_action( 'plugins_loaded', array( $this, 'loading' ) );
	}

	/**
	 * Plugin init.
	 */
	public function loading() {

		// Deactivate the free version.
		if ( class_exists( 'CCFWOO_Init' ) ) {

			$free_plugin = new CCFWOO_Init();

			// Required if functions are not yet loaded.
			require_once ABSPATH . 'wp-admin/includes/plugin.php';

			deactivate_plugins( $free_plugin->constants['base'] );

			$result = deactivate_plugins( $free_plugin->constants['base'] );

			$this->notices->add_notice(
				'warning',
				'Heads up - Checkout Countdown for WooCommerce Pro is standalone. The free version has been deactivated automatically.'
			);

			return;
		}

		// Check for older versions of Checkout Countdown.
		if ( function_exists( 'ccfwoo_setup' ) ) {
			$this->notices->add_notice(
				'warning',
				'Heads up - Checkout Countdown for WooCommerce Pro is standalone. Please deactivate other versions of Checkout Countdown.'
			);

			return;
		}

		require_once plugin_dir_path( __FILE__ ) . 'premium/init.php';

		// Require core files.
		$enable_countdown = ccfwoo_get_option( 'enable' );

		if ( $enable_countdown === 'on' ) {
			require_once plugin_dir_path( __FILE__ ) . 'functions/functions.php';
			require_once plugin_dir_path( __FILE__ ) . 'functions/enqueue.php';
			require_once plugin_dir_path( __FILE__ ) . 'functions/shortcode.php';
		}

		require_once plugin_dir_path( __FILE__ ) . 'settings/settings.php';

		new Checkout_Countdown_Main( $this->constants );
	}

}

new CCFWOO_PRO_Init();
