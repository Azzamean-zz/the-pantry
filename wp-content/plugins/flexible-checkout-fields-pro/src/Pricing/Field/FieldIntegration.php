<?php
/**
 * Support fields integration for Pricing.
 *
 * @package WPDesk\FCF\Pro
 */

namespace WPDesk\FCF\Pro\Pricing\Field;

use WPDesk\FCF\Pro\Pricing\Field\FieldInterface;
use FCFProVendor\WPDesk\View\Renderer\SimplePhpRenderer;
use FCFProVendor\WPDesk\View\Resolver\DirResolver;

/**
 * FieldIntegration class for Pricing.
 */
class FieldIntegration {

	/**
	 * Class object for field type.
	 *
	 * @var FieldInterface
	 */
	private $field_object;

	/**
	 * Class object for template rendering.
	 *
	 * @var SimplePhpRenderer
	 */
	private $renderer;

	/**
	 * Class constructor.
	 *
	 * @param FieldInterface $field_object Class object of field type.
	 */
	public function __construct( FieldInterface $field_object ) {
		$this->field_object = $field_object;
		$this->renderer     = $this->get_renderer();
	}

	/**
	 * Integrate with WordPress and with other plugins using action/filter system.
	 *
	 * @return void
	 */
	public function hooks() {
		add_filter( 'wp_footer', [ $this, 'add_scripts_in_footer' ] );
		add_action( 'flexible_checkout_fields_field_tabs_content', [ $this, 'add_pricing_settings_for_field' ], 10, 4 );
	}

	/**
	 * Adds scripts in footer to trigger update_checkout after field change.
	 *
	 * @internal
	 */
	public function add_scripts_in_footer() {
		if ( ! is_checkout() || ! $this->field_object->is_pricing_enabled() ) {
			return;
		}

		?>
		<script>
			jQuery(function($) {
				jQuery('[name^="<?php echo esc_html( $this->field_object->get_field_name() ); ?>"]').change(function() {
					$('body').trigger('update_checkout');
				});
			});
		</script>
		<?php
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
	public function add_pricing_settings_for_field( string $section_key, string $field_name, array $field, array $settings ) {
		if ( $field_name !== $this->field_object->get_field_name() ) {
			return;
		}

		$template_args = [
			'option_prefix'     => sprintf( 'inspire_checkout_fields[settings][%s][%s]', $section_key, $field_name ),
			'price_values'      => $this->field_object->get_options_for_price_values(),
			'price_value_label' => $this->field_object->get_field_label_for_settings(),
			'price_types'       => $this->get_options_for_price_type(),
			'tax_classes'       => $this->get_options_for_tax_class(),
			'field_data'        => $settings[ $section_key ][ $field_name ],
		];
		echo $this->renderer->render( 'settings', $template_args ); // phpcs:ignore
	}

	/**
	 * Init class for template rendering.
	 *
	 * @return SimplePhpRenderer Class object for HTML rendering.
	 */
	private function get_renderer() {
		return new SimplePhpRenderer( new DirResolver( dirname( __DIR__ ) . '/views' ) );
	}

	/**
	 * Returns list of price types.
	 *
	 * @return array List of options.
	 */
	private function get_options_for_price_type() {
		return [
			'fixed'                => __( 'Fixed', 'flexible-checkout-fields-pro' ),
			'percent_subtotal'     => __( 'Percentage of Subtotal (ex. VAT)', 'flexible-checkout-fields-pro' ),
			'percent_subtotal_tax' => __( 'Percentage of Subtotal (incl. VAT)', 'flexible-checkout-fields-pro' ),
			'percent_total'        => __( 'Percentage of Total', 'flexible-checkout-fields-pro' ),
		];
	}

	/**
	 * Returns list of tax classes.
	 *
	 * @return array List of options.
	 */
	private function get_options_for_tax_class() {
		$options = [
			'' => __( 'None', 'flexible-checkout-fields-pro' ),
		];
		if ( ! wc_tax_enabled() ) {
			return $options;
		}

		return array_merge(
			$options,
			[
				'standard' => __( 'Standard rates', 'flexible-checkout-fields-pro' ),
			],
			array_combine( \WC_Tax::get_tax_class_slugs(), \WC_Tax::get_tax_classes() )
		);
	}
}
