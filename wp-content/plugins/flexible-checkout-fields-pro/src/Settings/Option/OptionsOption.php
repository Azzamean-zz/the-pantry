<?php
/**
 * .
 *
 * @package WPDesk\FPF\Pro
 */

namespace WPDesk\FCF\Pro\Settings\Option;

use WPDesk\FCF\Free\Settings\Option\OptionAbstract;
use WPDesk\FCF\Free\Settings\Option\OptionInterface;
use WPDesk\FCF\Free\Settings\Tab\GeneralTab;
use WPDesk\FCF\Pro\Settings\Option\OptionsKeyOption;
use WPDesk\FCF\Pro\Settings\Option\OptionsValueOption;

/**
 * Supports option settings for field.
 */
class OptionsOption extends OptionAbstract implements OptionInterface {

	const FIELD_NAME = 'option';

	/**
	 * Returns name of option.
	 *
	 * @return string Option name.
	 */
	public function get_option_name(): string {
		return self::FIELD_NAME;
	}

	/**
	 * Returns name of option tab.
	 *
	 * @return string Tab name.
	 */
	public function get_option_tab(): string {
		return GeneralTab::TAB_NAME;
	}

	/**
	 * Returns type of option.
	 *
	 * @return string Option name.
	 */
	public function get_option_type(): string {
		return self::FIELD_TYPE_REPEATER;
	}

	/**
	 * Returns label of option.
	 *
	 * @return string Option label.
	 */
	public function get_option_label(): string {
		return __( 'Options', 'flexible-checkout-fields-pro' );
	}

	/**
	 * Returns content for label tooltip.
	 *
	 * @return string Tooltip content.
	 */
	public function get_label_tooltip(): string {
		return __( 'Enter a value and a label for each field option. The value will not be visible in the form. The label will be visible.', 'flexible-checkout-fields-pro' );
	}

	/**
	 * Returns label of option row (for Repeater field).
	 *
	 * @return string Option row label.
	 */
	public function get_option_row_label(): string {
		/* translators: %s row index */
		return __( 'Option #%s', 'flexible-checkout-fields-pro' );
	}

	/**
	 * Returns default value of option.
	 *
	 * @return string|array Default value.
	 */
	public function get_default_value() {
		return [
			[
				'key'   => '',
				'value' => '',
			],
		];
	}

	/**
	 * Returns status whether change of option value initiates refresh event.
	 *
	 * @return bool Status of refresh event.
	 */
	public function is_refresh_trigger(): bool {
		return true;
	}

	/**
	 * Returns subfields of option, if exists.
	 *
	 * @return OptionInterface[] List of option children.
	 */
	public function get_children(): array {
		return [
			OptionsKeyOption::FIELD_NAME   => new OptionsKeyOption(),
			OptionsValueOption::FIELD_NAME => new OptionsValueOption(),
		];
	}

	/**
	 * Returns updated settings of field contain values for this option.
	 *
	 * @param array $field_data Original settings of field.
	 * @param array $field_settings Settings of field.
	 *
	 * @return array Updated settings of field.
	 */
	public function update_field_data( array $field_data, array $field_settings ): array {
		$option_name = $this->get_option_name();
		if ( $option_name === '' ) {
			return $field_data;
		}

		$options = $field_settings[ $option_name ] ?? $this->get_default_value();
		if ( is_array( $options ) ) {
			return $options;
		}

		$options = explode( "\n", $options );
		$rows    = [];
		foreach ( $options as $option ) {
			$values = explode( ':', $option );
			if ( count( $values ) < 2 ) {
				continue;
			}

			$rows[] = [
				'key'   => trim( $values[0] ?? '' ),
				'value' => trim( $values[1] ?? '' ),
			];
		}

		$field_data[ $option_name ] = $rows;
		return $field_data;
	}

	/**
	 * Returns updated settings of field contain submitted values.
	 *
	 * @param array $field_data Current settings of field.
	 * @param array $field_settings Settings of field.
	 *
	 * @return array Updated settings of field.
	 */
	public function save_field_data( array $field_data, array $field_settings ): array {
		$option_name  = $this->get_option_name();
		$option_value = $field_settings[ $option_name ] ?? $this->get_default_value();

		$rows = [];
		foreach ( $option_value as $option_row ) {
			if ( ! $option_row || ( $option_row['key'] === '' ) || ( $option_row['value'] === '' ) ) {
				continue;
			}
			$rows[] = sprintf( '%s : %s', $option_row['key'], $option_row['value'] );
		}

		$field_data[ $option_name ] = ( $rows ) ? implode( "\n", $rows ) : '';
		return $field_data;
	}
}
