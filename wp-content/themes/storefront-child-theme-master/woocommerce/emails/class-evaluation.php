<?php
/**
 *  Class Evaluation email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-completed-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p>Thank you for recently attending a Pantry cooking class online! It was lovely to share a virtual table with you, gathering and cooking in community. We are learning and growing in this new online format, and truly value your feedback.</p>

<p>If you have a few moments, would you mind filling out <a href="<?php echo $survey;?>">this survey?</a></p>

<p>Quick note: If you would like a direct response to your feedback, please send us an email. We are currently unable to track contact info in our survey, but would love to help in whatever way we can!</p>

<p>Thank you for hanging out with us!</p>

<p>Kindly,<br>
The Pantry Family</p>
<?php

echo '<hr style="margin-bottom: 20px;
    margin-top: 20px;
    border: none;
    border-bottom: 1px solid #ccc;">';

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
