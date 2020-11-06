<?php
/**
 * Enqueues scripts for WordPress Admin Dashboard.
 *
 * @package WPDesk\FCF\Pro
 */

namespace WPDesk\FCF\Pro\Admin;

use FCFProVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FCFProVendor\WPDesk\PluginBuilder\Plugin\HookablePluginDependant;
use FCFProVendor\WPDesk\PluginBuilder\Plugin\PluginAccess;

/**
 * Assets class.
 */
class Assets implements Hookable, HookablePluginDependant {

	use PluginAccess;

	/**
	 * Integrate with WordPress and with other plugins using action/filter system.
	 *
	 * @return void
	 */
	public function hooks() {
		add_action( 'admin_enqueue_scripts', [ $this, 'load_assets_for_admin' ] );
	}

	/**
	 * Initializes loading assets in WordPress Admin Dashboard.
	 *
	 * @internal
	 */
	public function load_assets_for_admin() {
		$screen = get_current_screen();
		if ( ! $screen || ( $screen->base !== 'woocommerce_page_inspire_checkout_fields_settings' ) ) {
			return;
		}

		$this->load_styles_for_admin();
		$this->load_scripts_for_admin();
	}

	/**
	 * Enqueues styles in WordPress Admin Dashboard.
	 */
	private function load_styles_for_admin() {
		wp_register_style(
			'fcf-pro-admin',
			trailingslashit( $this->plugin->get_plugin_assets_url() ) . 'css/admin-new.css',
			[],
			$this->plugin->get_script_version()
		);
		wp_enqueue_style( 'fcf-pro-admin' );
	}

	/**
	 * Enqueues scripts in WordPress Admin Dashboard.
	 */
	private function load_scripts_for_admin() {
		wp_register_script(
			'fcf-pro-admin',
			trailingslashit( $this->plugin->get_plugin_assets_url() ) . 'js/admin-new.js',
			[],
			$this->plugin->get_script_version(),
			true
		);
		wp_enqueue_script( 'fcf-pro-admin' );
	}
}
