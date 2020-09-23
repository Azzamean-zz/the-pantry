<?php
/**
 *
 * This template can be overridden by copying it to yourtheme/templates/waitlist-woocommerce/emails/xoo-wl-admin-notify-email.php.
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 * @see     https://docs.xootix.com/waitlist-for-woocommerce/
 * @version 3.0
 */

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}


?>

<?php do_action( 'xoo_wl_email_header', $emailObj ); ?>

<table cellpadding="0" border="0" cellspacing="0" width="100%" style="font-size: <?php echo $fontSize.'px'; ?>">
	<tr>
		<td align="center">
			<?php echo $body_text; ?>
		</td>
	</tr>
</table>

<?php do_action( 'xoo_wl_email_footer', $emailObj ); ?>