<?php
/**
 * Adds settings in WordPress Admin Dashboard for Pricing.
 *
 * @package WPDesk\FCF\Pro
 */

namespace WPDesk\FCF\Pro\Pricing;

use FCFProVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FCFProVendor\WPDesk\View\Renderer\SimplePhpRenderer;
use FCFProVendor\WPDesk\View\Resolver\DirResolver;
use WPDesk\FCF\Pro\Pricing\Fields;

/**
 * Settings class for Pricing.
 */
class Settings implements Hookable {

	const OPTION_PRICING_ENABLED = 'pricing_enabled';
	const OPTION_PRICING_VALUES  = 'pricing_values';

	/**
	 * Class object for template rendering.
	 *
	 * @var SimplePhpRenderer
	 */
	private $renderer;

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->set_renderer();
	}

	/**
	 * Integrate with WordPress and with other plugins using action/filter system.
	 *
	 * @return void
	 */
	public function hooks() {
		add_filter( 'flexible_checkout_fields_field_tabs', [ $this, 'add_tab_for_pricing_settings' ] );
		add_action( 'flexible_checkout_fields_field_tabs_content', [ $this, 'add_pricing_settings_for_tab' ], 10, 4 );
		add_action( 'flexible_checkout_fields_field_tabs_content_js', [ $this, 'add_js_pricing_settings_for_tab' ] );
	}

	/**
	 * Init class for template rendering.
	 */
	private function set_renderer() {
		$this->renderer = new SimplePhpRenderer( new DirResolver( __DIR__ . '/views' ) );
	}

	/**
	 * Adds tab in field settings.
	 *
	 * @param array $tabs List of exists tabs in field settings.
	 *
	 * @return array List of tabs.
	 *
	 * @internal
	 */
	public function add_tab_for_pricing_settings( array $tabs ) {
		$tabs[] = [
			'hash'  => 'pricing',
			'title' => __( 'Pricing', 'flexible-checkout-fields-pro' ),
		];
		return $tabs;
	}

	/**
	 * Adds HTML for settings of pricing tab.
	 *
	 * @param string $section_key Key of checkout section.
	 * @param string $field_name Name of field.
	 * @param array  $field Data of field.
	 * @param array  $settings Settings of checkout fields.
	 *
	 * @internal
	 */
	public function add_pricing_settings_for_tab( string $section_key, string $field_name, array $field, array $settings ) {
		$field_type = $field['type'] ?? 'text';
		if ( in_array( $field_type, Fields::SUPPORTED_FIELD_TYPES, true ) && isset( $field['custom_field'] ) && $field['custom_field'] ) {
			return;
		}

		echo $this->renderer->render( 'settings-disabled' ); // phpcs:ignore
	}

	/**
	 * Adds JavaScript for settings of pricing tab.
	 *
	 * @internal
	 */
	public function add_js_pricing_settings_for_tab() {
		echo $this->renderer->render( 'settings-js' ); // phpcs:ignore
	}
}
