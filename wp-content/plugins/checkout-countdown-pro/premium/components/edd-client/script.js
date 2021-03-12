/* global ccfwoo_pro_edd_client */
document.addEventListener('DOMContentLoaded', function (e) { // eslint-disable-line

	const enterLicenseLink = document.querySelector(".ccfwoo_pro-edd-client-cred-link");

	const licenseRow = document.querySelector(".ccfwoo_pro-edd-client-row");

	const pluginRow = document.querySelector('tr[data-plugin="' + ccfwoo_pro_edd_client.slug + '"');

	if (pluginRow && ccfwoo_pro_edd_client.license_status !== 'valid') {	
		pluginRow.classList.add('update');
	}

	/**
	 * Show the license row.
	 */
	enterLicenseLink.addEventListener("click", function (event) {
		event.preventDefault();

		if (licenseRow.style.display === "none") {
			licenseRow.style.display = "";
		  } else {
			licenseRow.style.display = "none";
		  }
	});
	
	const activateActionButton = document.querySelectorAll('.ccfwoo_pro-edd-client-button');

	for (const button of activateActionButton) {
		button.addEventListener("click", function (event) {

			event.preventDefault();
	
			const dashicon = this.querySelector(".dashicons");
	
			dashicon.classList.remove('dashicons-yes-alt');
			dashicon.classList.add('dashicons-update');
			dashicon.classList.add('ccfwoo_pro-edd-client-spin');
			
			const nonce = this.getAttribute("data-nonce");
			const action = this.getAttribute("data-action");
			const operation = this.getAttribute("data-operation");
			const license = document.querySelector(".ccfwoo_pro-edd-client-license-key").value;
	
			const params = {
				nonce,
				action,
				operation,
				license
			};
	
			fetch(ccfwoo_pro_edd_client.ajax_url, {
				method: 'POST',
				credentials: 'same-origin',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
					'Cache-Control': 'no-cache',
				},
				body: new URLSearchParams(params)
			}).then(response => response.json()).then(response => {
		
				const message = document.querySelector('.ccfwoo_pro-edd-client-message');
	
				if (message) {
					message.innerHTML = '<span style="margin-right:3px;" class="dashicons dashicons-info"></span>' + response.data.message;
				}
				
				if (response.success === true) {
	
					dashicon.classList.remove('dashicons-update', 'ccfwoo_pro-edd-client-spin');
					dashicon.classList.add('dashicons-yes-alt');

					if (operation === 'change_beta') {
						this.innerHTML = '<span class="dashicons dashicons-hammer"></span> Revert';
					}
	
					if (response.data.reload === true) {
						location.reload(false);
					}
					return;
				}
				dashicon.classList.remove('dashicons-update', 'ccfwoo_pro-edd-client-spin');
				dashicon.classList.add('dashicons-dismiss');
			})
				.catch(err => console.log(err));
		});
	}
});
