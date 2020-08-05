/* global ccfwoo_pro_edd_client */
jQuery(document).ready(function () {
	let hasUpdate = false;
	let toggle = false;

	// Find our plugin by the slug.
	const selector = 'tr[data-plugin="' + ccfwoo_pro_edd_client.slug + '"';

	const pluginRow = jQuery(selector);

	if (ccfwoo_pro_edd_client.license_status !== 'valid') {
		pluginRow.addClass('update');
	}

	const body = jQuery('body');

	body.on('click', '.ccfwoo_pro-edd-client-cred-link', function () {
		// toggle = if license row is showing.
		toggle = toggle === false ? true : false;

		// Check for the update class. It removes the seperator line.
		if (!hasUpdate) {
			hasUpdate = pluginRow.hasClass('update') ? 'yes' : 'no';
		}

		// if row is showing.
		if (toggle === true) {
			// if row has update class to remove seperator line.
			if (hasUpdate === 'no') {
				pluginRow.addClass('update');
			}
		} else {
			// Toggle is off.
			if (hasUpdate === 'no') {
				pluginRow.removeClass('update');
			}
		}

		jQuery(this)
			.closest('tr')
			.next('.ccfwoo_pro-edd-client-row')
			.toggle();
		jQuery(this)
			.closest('p')
			.next('.ccfwoo_pro-edd-client-row')
			.toggle();
	});

	body.on('click', '.ccfwoo_pro-edd-client-button', function (event) {
		event.preventDefault();

		jQuery('.dashicons', this)
			.removeClass('dashicons-yes-alt')
			.addClass('dashicons-update');
		jQuery('.dashicons', this).addClass('ccfwoo_pro-edd-client-spin');
		
		jQuery.ajax({
			context: this,
			url: ccfwoo_pro_edd_client.ajax_url,
			type: 'post',
			dataType: 'json',
			data: {
				nonce: jQuery(this).attr('data-nonce'),
				action: jQuery(this).attr('data-action'),
				operation: jQuery(this).attr('data-operation'),
				license: jQuery(this)
					.prev('.ccfwoo_pro-edd-client-license-key')
					.val(),
			},
			success (response) {
				jQuery('.ccfwoo_pro-edd-client-msg').remove();
				jQuery(this)
					.parent()
					.append('<div class="ccfwoo_pro-edd-client-msg"><span class="dashicons dashicons-info"></span> ' + response.data.message + '</div>');

				if (response.success === true) {
					jQuery('.dashicons', this)
						.removeClass('dashicons-update ccfwoo_pro-edd-client-spin')
						.addClass('dashicons-yes-alt');

					// if (jQuery(this).attr('data-operation') === 'change_beta') {
					//     jQuery(this).html('<span class="dashicons dashicons-hammer"></span> Revert');
					// }

					if (response.data.reload === true) {
						setTimeout(function () {
							location.reload(false);
						}, 1000);
					}
				} else {
					jQuery('.dashicons', this)
						.removeClass('dashicons-update ccfwoo_pro-edd-client-spin')
						.addClass('dashicons-dismiss');
				}
			},
		});
	});
});
