<?php
/**
 *
 * This template can be overridden by copying it to yourtheme/templates/waitlist-woocommerce/emails/global/xoo-wl-email-footer.php.
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
							</td><!-- End Content -->
						</tr>
					</table><!-- End 600px inner container -->
					<?php do_action( 'xoo_wl_email_footer_content', $emailObj ); ?>
				</td>
			</tr>
		</table><!-- End Main Container -->
	</body>
</html>