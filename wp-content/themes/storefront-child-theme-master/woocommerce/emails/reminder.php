<?php
/**
 *  Reminder email
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

<p>
This email is to remind you that you are signed up to attend our online <?= esc_html( $class_name_friendly ); ?> class with <?= esc_html( $instructor ); ?> on <?= esc_html( $class_date ); ?>! Class will begin at <?= esc_html( $class_time ); ?> PT and will run for about 2-2.5 hours. The virtual waiting room will open about 10 minutes before class, so plan to get there a bit early to make sure you're good-to-go.</p>	

<p>There are a few things to prepare ahead of class:</p>

<?php echo $prep_instructions; ?>

<p>Make sure you have downloaded <a href="https://zoom.us/">Zoom</a> (be sure to check your sound and video) and charged your device! New to Zoom? You can read a quick how-to by <a href="https://support.zoom.us/hc/en-us/articles/206175806#h_12512067-340a-4ca9-8d5b-f52a7ed016fb">clicking here</a>.</p>	

<p>Here is your <a href="<?php echo $packet; ?>">class packet</a> to help you get set up, we suggest that you read it over now! We also suggest having the class packet handy when class begins, as we’ll reference it quite a bit throughout. You can also view the <a href="<?php echo $list_link; ?>">shopping list</a> to get prepped for class!</p>

<p>We encourage you to download Zoom if you haven’t already, as that is where the class will be held. Here is the link that you’ll need when it’s time:</p>

<?php echo $zoom_info; ?>
 
<p>If you’ve ordered an ingredient kit, wine pairing, or additional equipment for your class, you can pick that up from the Pantry the day before class anytime between 11am and 6pm. We're located at 1417 NW 70th Street, with a garden entrance on Alonzo Avenue. Please practice social distancing as you pick up your kit, and remain outside while one of our staff members assists you. Your kit may contain alcohol, if it does please be prepared to present ID. Feel free to call 206.436.1064 if you have any trouble finding us.</p>

<p>While we aren’t able to gather with you in person, we are thrilled to see you online soon! Thank you for being a part of this!</p>

<p>In community,<br>
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
