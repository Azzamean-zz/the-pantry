<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?><div class="field-settings-tab-container field-settings-advanced" style="display:none;">
    <div>
        <?php
            $url = get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/sklep/woocommerce-checkout-fields/' : 'https://www.wpdesk.net/products/flexible-checkout-fields-pro-woocommerce/';
		    echo sprintf( __( '%sGo PRO &rarr;%s to add conditional logic based on products/categories, fields and shipping method.' , 'flexible-checkout-fields' ), '<a href="' . $url . '?utm_source=flexible-checkout-fields-settings&utm_medium=link-advanced-tab&utm_campaign=settings-docs-link" target="_blank">', '</a>' );
		?>
    </div>
</div>
