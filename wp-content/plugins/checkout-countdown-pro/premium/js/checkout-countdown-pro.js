/* global ccfwooPro */
/*
 * window.ccfwooController - Access the Checkout Countdown Controller
 * ccfwooPro - Access localized data.
 */
/*
 * When Document is ready.
 */
document.addEventListener('DOMContentLoaded', function () {
	// Turn on AJAX Support.
	ccfwooAjaxSupport();

	// Restart countdown time after a product has been added to cart.
	if (ccfwooPro.reset_add_to_cart === 'on' && ccfwooPro.should_reset) {
		window.ccfwooController.restartInterval();
	}

});

/*
 * Event when the countdown has reached zero but may not be finished.
 */
document.addEventListener('ccfwooReachedZero', function (event) { // eslint-disable-line
	if (ccfwooPro.loop_enable === 'on') {
		window.ccfwooController.startInterval(); // Start the interval.
	}
	// Redirection features if needed.
	ccfwooRedirection();

	// Refresh the cart totals.
	if (ccfwooPro.loop_enable === 'on' && ccfwooPro.loop_refresh_cart === 'on') {
		ccfwooRefreshCart();
	}
});

/*
 * Event when the countdown has finished counting.
 */
document.addEventListener('ccfwooReachedZero', function (event) { // eslint-disable-line
	if (ccfwooPro.clear_cart_enable === 'on') {
		ccfwooClearCart();
	}
});

/*
 * Handle Redirection.
 */
function ccfwooRedirection() {
	// Redirect to cart, but not if we are on the cart.
	if (ccfwooPro.redirection_type && ccfwooPro.redirection_type === 'cart' && document.location.href.indexOf('cart') === -1) {
		setTimeout(function () {
			window.location.href = ccfwooPro.cart_url;
		}, 3000);
	}

	// Redirect to a custom URL but not if we are on it.
	if (ccfwooPro.redirection_type && ccfwooPro.redirection_type === 'url' && document.location.href.indexOf(ccfwooPro.redirection_url) === -1) {
		setTimeout(function () {
			window.location.href = ccfwooPro.redirection_url; // users url
		}, 3000);
	}
}
/*
 * Clear the WooCommerce Cart via Ajax.
 */
function ccfwooClearCart() {

	jQuery.ajax({
		type: 'GET',
		url: ccfwooPro.ajax_url,
		data: {
			action: 'ccfwoo_process_clear_cart',
		},
		success (data) { // eslint-disable-line
			ccfwooRefreshCart();
			window.ccfwooController.stopInterval(true);
			window.ccfwooController.setHtml('expired');
		},
	});
}

/*
 * Ajax support for starting and stopping the countdown.
 */
function ccfwooAjaxSupport() {
	if (ccfwooPro.ajax_support_enable === 'on') {

		jQuery(document.body).on('added_to_cart', function () {
			// Reset the countdown number when product has been added to cart.
			if (ccfwooPro.reset_add_to_cart === 'on' && window.ccfwooController.isCounting()) {
				window.ccfwooController.restartInterval();
			}
		});
		// NOTE in the future We can use cart fragments to get the number of items + data.
		jQuery(document.body).on('wc_fragment_refresh updated_wc_div added_to_cart removed_from_cart', function () {
			ccfwooDecideInterval();
		});
	}
}

/*
 * Count and set the ccfwooController.setCartItems() from woocommerce_items_in_cart cookie.
 */
function ccfwooDecideInterval() {

	// Get the amount of items from the cookie.
	let items = window.ccfwooGetCookie('woocommerce_items_in_cart');
	// turn items into interger.
	items = items ? parseInt(items) : 0;
	// Set set cart items to the controller.
	window.ccfwooController.setCartItems(items);

	if (!window.ccfwooController.hasCart() && window.ccfwooController.isCounting() === true) {
		window.ccfwooController.stopInterval(true);
		window.ccfwooController.setHtml('banner');
	} else if (window.ccfwooController.hasCart()) {
		window.ccfwooController.startInterval();
	}
}
/*
 * Refresh the cart and checkout contents by trigger the events on the body.
 */
function ccfwooRefreshCart() {
	window.ccfwooController.triggerEvent('body', 'wc_fragment_refresh');
	window.ccfwooController.triggerEvent('body', 'update_checkout');
	window.ccfwooController.triggerEvent('body', 'wc_update_cart');
}
