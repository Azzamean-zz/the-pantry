<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package storefront
 */

?>
</main>
<header class="pageFooter">
<nav>
    <ul>
	    
		<?php
			$defaults = array(
				'echo'            => true,
				'menu'			  => 'Main ',
				'container'		  => false,
				'items_wrap'      => '%3$s',
				'fallback_cb'     => 'wp_page_menu',
				'depth'           => 0,
				'walker'          => ''
			);
			
			wp_nav_menu( $defaults );
		?>

        <li><a href="<?php echo site_url();?>/cart">Cart 
        <?php
        global $woocommerce;
        if($woocommerce->cart->get_cart_total()) {
            echo '(';
            echo $woocommerce->cart->get_cart_total();
            echo ')';
        }
        ?> 
        </a></li>
    </ul>
</nav>

<small class="copyright">&copy; <a href="<?php echo site_url();?>" class="logo"><?php echo get_bloginfo('name');?></a> <?php echo date('Y');?></small>

<small class="credit"><a href="http://neversinkcreative.com">Design by Sam</a></small>
</header>
<?php do_action( 'storefront_after_footer' ); ?>
<?php wp_footer(); ?>