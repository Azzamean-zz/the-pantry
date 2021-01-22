<?php
/**
 *  To Go Reminder email
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

<p>This email is to remind you that your lovingly prepared <?= esc_html( $class_name ); ?> meal will be ready for pickup at The Pantry today between 4:30pm-6pm. We're now licensed to sell wine, so you can purchase a bottle of red, white, or rosé to go with your dinner when you pick up your meal!</p>

<p>We're located at 1417 NW 70th Street, behind Delancey Pizzeria in Ballard. Our garden entrance is on Alonzo Avenue, just west of Delancey. Your meal will be ready for pickup in Classroom A, which will be the second door on your left as you walk up the garden entrance. There is neighborhood street parking in the surrounding blocks. Feel free to call 206.436.1064 if you have any trouble finding us.</p>

<p>Due to limited space, we are unable to hold on to meals overnight. Be sure to make it in by 6:00pm and allow 6 feet between you and others while picking up your food. </p>

<p>While tonight's meal is fully prepared, here is the <a href="<?php echo $packet; ?>">recipe packet</a> for your enjoyment. Happy cooking!</p>

<p>Thank you for supporting us, we're looking forward to feeding you!<br>
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
