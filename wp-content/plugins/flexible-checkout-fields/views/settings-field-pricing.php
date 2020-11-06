<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?><div class="field-settings-tab-container field-settings-pricing" style="display:none;">
    <div>
        <?php
            $url = get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/sklep/woocommerce-checkout-fields/' : 'https://www.wpdesk.net/products/flexible-checkout-fields-pro-woocommerce/';
		    echo sprintf( __( '%sGo PRO &rarr;%s In this tab it is possible to add a fixed or percentage price to the field and set the tax on this price.' , 'flexible-checkout-fields' ), '<a href="' . $url . '?utm_source=flexible-checkout-fields-settings&utm_medium=link-pricing-tab&utm_campaign=settings-docs-link" target="_blank">', '</a>' );
		?>
    </div>
</div>
