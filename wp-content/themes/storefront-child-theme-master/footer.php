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
        <li><a href="<?php echo site_url();?>/about">About</a></li>
        <li><a href="<?php echo site_url();?>/events">Classes</a></li>
        <li><a href="<?php echo site_url();?>/events/togomeals">To Go</a></li>
        <li><a href="<?php echo site_url();?>/private-events">Private Events</a></li>
        <li><a href="<?php echo site_url();?>/meat-collective">Meat Collective</a></li>
        <li><a href="<?php echo site_url();?>/gift-certificates">Gift Certificates</a></li>
        <li><a href="<?php echo site_url();?>/contact">Contact</a></li>
        <li><a href="<?php echo site_url();?>/our-community">Community</a></li>
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