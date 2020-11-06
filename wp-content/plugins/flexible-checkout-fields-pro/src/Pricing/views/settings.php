<?php
/**
 * Template for field settings.
 *
 * @var string $option_prefix Prefix of name atts for fields.
 * @var array  $price_values List of field values.
 * @var string $price_value_label Format for sprintf() function.
 * @var array  $price_types Options of price.
 * @var array  $tax_classes Options of tax class.
 * @var array  $field_data Settings of field pricing.
 *
 * @package WPDesk\FCF\Pro
 */

?>

<div class="field-settings-tab-container field-settings-pricing" style="display: none;">
	<div>
		<label>
			<input type="checkbox"
				data-qa-id="field-pricing-enabled"
				name="<?php echo esc_attr( $option_prefix ); ?>[pricing_enabled]"
				value="1"
				<?php echo ( $field_data['pricing_enabled'] ?? false ) ? 'checked' : ''; ?>>
			<?php echo esc_html__( 'Enable Pricing', 'flexible-checkout-fields-pro' ); ?>
		</label>
	</div>
	<div class="field-settings-pricing-options"
		style="display: <?php echo ( $field_data['pricing_enabled'] ?? false ) ? 'block' : 'none'; ?>">
		<div class="rules">
			<?php foreach ( $price_values as $value_key => $value_label ) : ?>
				<fieldset>
					<legend>
						<?php echo wp_kses_post( sprintf( $price_value_label, sprintf( '"%s"', $value_label ) ) ); ?>
					</legend>
					<div class="field-settings-pricing-options-columns">
						<div class="field-settings-pricing-options-column field-settings-pricing-options-column--wide">
							<label>
								<p><?php echo esc_html__( 'Price type', 'flexible-checkout-fields-pro' ); ?></p>
								<select class="validation field_validation"
										data-qa-id="field-pricing-options-type"
										name="<?php echo esc_attr( $option_prefix ); ?>[pricing_values][<?php echo esc_attr( $value_key ); ?>][type]">
									<?php foreach ( $price_types as $option_key => $option_value ) : ?>
										<option value="<?php echo esc_attr( $option_key ); ?>"
												<?php echo ( $option_key === ( $field_data['pricing_values'][ $value_key ]['type'] ?? '' ) ) ? 'selected' : ''; ?>>
											<?php echo esc_html( $option_value ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</label>
						</div>
						<div class="field-settings-pricing-options-column">
							<label>
								<p><?php echo esc_html__( 'Value', 'flexible-checkout-fields-pro' ); ?></p>
								<input class="default"
									data-qa-id="field-pricing-options-value"
									type="number"
									value="<?php echo esc_attr( $field_data['pricing_values'][ $value_key ]['value'] ?? '' ); ?>"
									id="<?php echo esc_attr( $option_prefix ); ?>[pricing_values][<?php echo esc_attr( $value_key ); ?>][value]"
									name="<?php echo esc_attr( $option_prefix ); ?>[pricing_values][<?php echo esc_attr( $value_key ); ?>][value]">
							</label>
						</div>
					</div>
					<div class="field-settings-pricing-options-tax"
						style="display: <?php echo ( ( $field_data['pricing_values'][ $value_key ]['value'] ?? 0 ) > 0 ) ? 'block' : 'none'; ?>">
						<label>
							<p><?php echo esc_html__( 'Tax class', 'flexible-checkout-fields-pro' ); ?></p>
							<select class="validation field_validation"
									data-qa-id="field-pricing-options-tax"
									name="<?php echo esc_attr( $option_prefix ); ?>[pricing_values][<?php echo esc_attr( $value_key ); ?>][tax_class]">
								<?php foreach ( $tax_classes as $option_key => $option_value ) : ?>
									<option value="<?php echo esc_attr( $option_key ); ?>"
											<?php echo ( $option_key === ( $field_data['pricing_values'][ $value_key ]['tax_class'] ?? '' ) ) ? 'selected' : ''; ?>>
										<?php echo esc_html( $option_value ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</label>
					</div>
				</fieldset>
			<?php endforeach; ?>
		</div>
	</div>
</div>
