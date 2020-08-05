/*
 * Deprecated functions for BASC payments.
 */
jQuery(document).ready(function() {
	function ccfwoo_bacs_countdown(ccfwoo_advanced_seconds) {
		ccfwoo_advanced_seconds = parseInt(final) || ccfwoo_advanced_seconds;

		function ccfwoo_basc_tick() {
			ccfwoo_advanced_seconds--;
			//sessionStorage.setItem('ccfwoo_advanced_seconds', ccfwoo_advanced_seconds)

			var current_hours = parseInt(ccfwoo_advanced_seconds / 60 / 60 / 60);
			var current_minutes = parseInt(ccfwoo_advanced_seconds / 60 / 60);
			var current_minutes = parseInt(ccfwoo_advanced_seconds / 60);
			var current_ccfwoo_advanced_seconds = ccfwoo_advanced_seconds % 60;

			seconds = Math.floor(ccfwoo_advanced_seconds);
			minutes = Math.floor(seconds / 60);
			hours = Math.floor(minutes / 60);
			days = Math.floor(hours / 24);

			hours = hours - days * 24;
			minutes = minutes - days * 24 * 60 - hours * 60;
			seconds = seconds - days * 24 * 60 * 60 - hours * 60 * 60 - minutes * 60;

			if (document.getElementById('ccfwoo-advanced-countdown')) {
				if (ccfwooPro.basc_display_due_date == 'on') {
					if (ccfwooPro.basc_due_date_language) {
						var options = {
							weekday: 'long',
							year: 'numeric',
							month: 'long',
							day: 'numeric',
							hour: 'numeric',
							minute: 'numeric',
							second: 'numeric',
							timeZoneName: 'long',
						};

						var full_date = expire.toLocaleDateString(ccfwooPro.basc_due_date_language, options);
					} else {
						var full_date = expire;
					}

					var due_date = '<br>' + full_date;
				} else {
					var due_date = '';
				}

				var counter = document.getElementById('cc-countdown-timer');

				var replace_days = ccfwooPro.basc_text.replace('{days}', days);
				var replace_hours = replace_days.replace('{hours}', hours);
				var replace_minutes = replace_hours.replace('{minutes}', minutes);
				var replace_seconds = replace_minutes.replace('{seconds}', seconds);

				var basc_text = replace_seconds;

				jQuery('#ccfwoo-advanced-countdown').html(basc_text + due_date);
			}

			// Starts the timer when it's turned on and at 0
			if (ccfwoo_advanced_seconds > 0) {
				setTimeout(ccfwoo_basc_tick, 1000);
			} else {
				clearTimeout(ccfwoo_basc_tick);
				localStorage.clear('ccfwoo_advanced_set_date');

				if (document.getElementById('ccfwoo-advanced-countdown')) {
					jQuery('#ccfwoo-advanced-countdown').text(ccfwooPro.basc_expired_text);
				}
			}
		}

		ccfwoo_basc_tick();
	}

	if (document.getElementById('ccfwoo-advanced-countdown')) {
		// Work out our duration in seconds
		var days = ccfwooPro.basc_days * 24 * 60 * 60;
		var hours = ccfwooPro.basc_hours * 60 * 60;
		var minutes = ccfwooPro.basc_minutes * 60;

		duriation = days + hours + minutes;

		var now = new Date();

		localStorage.setItem('ccfwoo_advanced_now_date', now);

		if (!localStorage.getItem('ccfwoo_advanced_set_date')) {
			var expire = new Date();
			expire.setSeconds(expire.getSeconds() + duriation);

			localStorage.setItem('ccfwoo_advanced_set_date', expire);
		}

		var expire = new Date(localStorage.getItem('ccfwoo_advanced_set_date'));

		final = (+expire - +now) / 1000;
		localStorage.setItem('ccfwoo_advanced_seconds', final);
		final_count = localStorage.setItem('ccfwoo_advanced_seconds', final);

		ccfwoo_bacs_countdown(final_count); // We run our duration
	}
});
