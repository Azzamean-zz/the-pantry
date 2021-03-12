<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once plugin_dir_path( __FILE__ ) . 'edd-client-updater.php';

if ( ! class_exists( 'ccfwoo_pro_EDD_Client' ) ) :
	class ccfwoo_pro_EDD_Client {

		protected $plugin = array();

		/*
		 * Instantiate the ccfwoo_pro_EDD_Client class
		 */
		public function __construct( $plugin_path, $plugin_data ) {

			if ( ! is_admin() ) {
				return;
			}

			$this->plugin['name']           = $plugin_data['name'];
			$this->plugin['item_id']        = $plugin_data['item_id'];
			$this->plugin['version']        = $plugin_data['version'];
			$this->plugin['support_url']    = ! empty( $plugin_data['support_url'] ) ? $plugin_data['support_url'] : false;
			$this->plugin['author']         = $plugin_data['author'];
			$this->plugin['machine_name']   = str_replace( ' ', '-', strtolower( $this->plugin['name'] ) );
			$this->plugin['path']           = $plugin_path;
			$this->plugin['slug']           = plugin_basename( $this->plugin['path'] );
			$this->plugin['store_url']      = $plugin_data['store_url'];

			$this->plugin['license']        = trim( get_option( $this->plugin['machine_name'] . '_license_key' ) );
			$this->plugin['license_status'] = get_option( $this->plugin['machine_name'] . '_license_status' );
			$this->plugin['beta']           = get_option( $this->plugin['machine_name'] . '_beta' );
			$this->plugin['ajax_url']       = admin_url( 'admin-ajax.php' );

			// Enqueue Scripts.
			add_action( 'admin_print_scripts-plugins.php', array( $this, 'enqueue_scripts' ) );
			add_action( 'admin_print_styles-plugins.php', array( $this, 'enqueue_styles' ) );

			$this->check();

			// License GUI.
			if ( $this->plugin['license_status'] !== 'valid' ) {
				add_action( 'after_plugin_row_' . $this->plugin['slug'], array( $this, 'insert_license_row' ), 10, 3 );
			} else {
				add_action( 'after_plugin_row_' . $this->plugin['slug'], array( $this, 'insert_license_operation_row' ), 10, 3 );
			}

			add_filter( 'plugin_action_links_' . $this->plugin['slug'], array( $this, 'insert_license_link' ), 10, 2 );
			// Add Ajax Actions.
			add_action( 'wp_ajax_' . $this->plugin['machine_name'] . '-ccfwoo_pro-edd-client-operations', array( $this, 'edd_operations' ) );

			// Trigger Plugin Update.
			$this->plugin_updater();
		}

		/*
		 * A helper function to return if plugin is premium
		 */
		public function is_premium() {
			if ( $this->plugin['license_status'] === 'valid' ) {
				return true;
			} else {
				return false;
			}
		}

		/*
		 * Enqueue Scripts
		 */
		public function enqueue_scripts() {
			wp_register_script( 'ccfwoo_pro-edd-client', plugins_url( 'script.js', __FILE__ ), false, $this->plugin['version'] );
			wp_enqueue_script( 'ccfwoo_pro-edd-client' );

			wp_localize_script( 'ccfwoo_pro-edd-client', 'ccfwoo_pro_edd_client', $this->plugin );
		}

		/*
		 * Enqueue Styles
		 */
		public function enqueue_styles() {
			wp_register_style( 'ccfwoo_pro-edd-client', plugins_url( 'style.css', __FILE__ ), false, $this->plugin['version'] );
			wp_enqueue_style( 'ccfwoo_pro-edd-client' );
		}

		/*
		 * Add a License Link to Plugin
		 */
		public function insert_license_link( $links ) {
			// License GUI.
			if ( $this->plugin['license_status'] === 'valid' ) {

				$text_license = __( 'Manage License', 'checkout-countdown-pro' );

				$license_link = '<a href="javascript:void(0);" class="ccfwoo_pro-edd-client-cred-link">' . $text_license . ' </a>';

				array_unshift( $links, $license_link );
			}

			if ( $this->plugin['support_url'] ) {

				$text_support = __( 'Support', 'checkout-countdown-pro' );

				$support_link = '<a href="' . $this->plugin['support_url'] . '" target="_blank">' . $text_support . ' </a>';

				array_unshift( $links, $support_link );
			}

			return $links;
		}

		/*
		 * Adds row on the plugin table. Provides GUI to enter License
		 */
		public function insert_license_row() {
			$format = __( 'Click here to enter your license key for %s to get security updates.', 'checkout-countdown-pro' );
			$text_description = sprintf( $format, '<strong>' . $this->plugin['name'] . '</strong>' );

			$text_placeholder = __( 'Enter Your License', 'checkout-countdown-pro' );
			$text_button = __( 'Activate License', 'checkout-countdown-pro' );

			?>
			<tr class="plugin-update-tr active">
				<td class="plugin-update colspanchange" colspan="100%">
					<div class="update-message notice inline notice-error notice-alt ccfwoo_pro-edd-client-wrapper">
						<p><a href="javascript:void(0);" class="ccfwoo_pro-edd-client-cred-link"><?php echo $text_description; ?></a></p>
						<div class="ccfwoo_pro-edd-client-row" style="display:none">
							<input class="ccfwoo_pro-edd-client-license-key" value="<?php echo esc_html( $this->plugin['license'] ); ?>" type="text" placeholder="<?php echo $text_placeholder; ?>"/>
							<button class="button ccfwoo_pro-edd-client-button" data-action=<?php echo $this->plugin['machine_name'] . '-ccfwoo_pro-edd-client-operations'; ?> data-operation="activate_license" data-nonce="<?php echo wp_create_nonce( $this->plugin['machine_name'] . '-ccfwoo_pro-edd-client-operations' ); ?>"> <span class="dashicons dashicons-update"></span> <?php echo $text_button; ?></button>
						</div>
					</div>
					<div class="ccfwoo_pro-edd-client-message"></div>
				</td>
			</tr>
			<?php
		}

		/*
		 * Adds row on the plugin table. Provides GUI to deactivate, check Expiry of license etc.
		 */
		public function insert_license_operation_row() {

			$text_change_license = __( 'Change License', 'checkout-countdown-pro' );

			$beta = get_option( $this->plugin['machine_name'] . '_beta', false );

			if ( $beta ) {
				$text_change_beta = __( 'Beta Opt-out', 'checkout-countdown-pro' );
				$beta_style = 'background:#fff8e5;';
			} else {
				$text_change_beta = __( 'Beta Opt-in', 'checkout-countdown-pro' );
				$beta_style = '';
			}

			$text_check_expiry = __( 'Check Expiry Date', 'checkout-countdown-pro' );
			$text_deactive_license = __( 'Deactivate License', 'checkout-countdown-pro' );

			?>

			<tr class="ccfwoo_pro-edd-client-row plugin-update-tr active update" style="display: none">

			<td colspan="100%" class="plugin-update colspanchange">    
				  
					<div class="ccfwoo_pro-edd-client-row update-message inline notice-alt">
						<input class="ccfwoo_pro-edd-client-license-key" type="text" style="margin-right:-14px; border-top-right-radius:0px; border-bottom-right-radius:0px; border-right:0px;" value="<?php echo esc_html( $this->plugin['license'] ); ?>"/>

						<button class="button ccfwoo_pro-edd-client-button" data-action=<?php echo esc_html( $this->plugin['machine_name'] . '-ccfwoo_pro-edd-client-operations' ); ?> data-operation="change_license" data-nonce="<?php echo wp_create_nonce( $this->plugin['machine_name'] . '-ccfwoo_pro-edd-client-operations' ); ?>" style="margin-left:-4px; border-top-left-radius:0px; border-bottom-left-radius:0px;"> <span class="dashicons dashicons-update"></span> <?php echo esc_html( $text_change_license ); ?></button>

						<button class="button ccfwoo_pro-edd-client-button"  style="<?php echo esc_attr( $beta_style ); ?>" data-action=<?php echo esc_html( $this->plugin['machine_name'] . '-ccfwoo_pro-edd-client-operations' ); ?> data-operation="change_beta" data-nonce="<?php echo wp_create_nonce( $this->plugin['machine_name'] . '-ccfwoo_pro-edd-client-operations' ); ?>" style="margin-left:-4px; border-top-left-radius:0px; border-bottom-left-radius:0px;"> <span class="dashicons dashicons-hammer"></span> <?php echo esc_html( $text_change_beta ); ?></button>
						
						<button class="button ccfwoo_pro-edd-client-button" data-action=<?php echo esc_html( $this->plugin['machine_name'] . '-ccfwoo_pro-edd-client-operations' ); ?> data-operation="check_expiry" data-nonce="<?php echo wp_create_nonce( $this->plugin['machine_name'] . '-ccfwoo_pro-edd-client-operations' ); ?>"> <span class="dashicons dashicons-update"></span> <?php echo esc_html( $text_check_expiry ); ?></button>

						<button class="button ccfwoo_pro-edd-client-button" data-action=<?php echo $this->plugin['machine_name'] . '-ccfwoo_pro-edd-client-operations'; ?> data-operation="deactivate_license" data-nonce="<?php echo wp_create_nonce( $this->plugin['machine_name'] . '-ccfwoo_pro-edd-client-operations' ); ?>"> <span class="dashicons dashicons-update"></span> <?php echo esc_html( $text_deactive_license ); ?></button>

						<div class="ccfwoo_pro-edd-client-message"></div>
					</div>
				</td>
			</tr>
			<?php
		}

		/*
		 *  Display admin notice if plugin license key is not yet entered
		 */
		public function display_admin_notice() {

			$format = __( 'Almost done - Activate license to make %s properly work on your site', 'checkout-countdown-pro' );
			$text_description = sprintf( $format, '<strong>' . $this->plugin['name'] . '</strong>' );
			$text_placeholder = __( 'Enter Your License', 'checkout-countdown-pro' );
			$text_button = __( 'Activate License', 'checkout-countdown-pro' );

			?>
			<div class="ccfwoo_pro-edd-client-notice notice notice-warning is-dismissible">
				<p><?php echo $text_description; ?>
					<input class="ccfwoo_pro-edd-client-license-key" type="text" placeholder="<?php echo $text_placeholder; ?>"/>
					<button class="button ccfwoo_pro-edd-client-button" data-action=<?php echo $this->plugin['machine_name'] . '-ccfwoo_pro-edd-client-operations'; ?> data-operation="activate_license" data-nonce="<?php echo wp_create_nonce( $this->plugin['machine_name'] . '-ccfwoo_pro-edd-client-operations' ); ?>"> <span class="dashicons dashicons-update"></span> <?php echo $text_button; ?></button>
				</p>
			</div>
			<?php
		}

		/**
		 * Trigger Plugin Update
		 */
		public function plugin_updater() {
			new ccfwoo_pro_EDD_Client_Updater(
				$this->plugin['store_url'],
				$this->plugin['path'],
				array(
					'author'    => $this->plugin['author'],
					'version'   => $this->plugin['version'],
					'item_id'   => $this->plugin['item_id'],
					'license'   => $this->plugin['license'],
					'url'     => home_url(),
					'beta'    => get_option( $this->plugin['machine_name'] . '_beta', false ),
				)
			);

		}

		/*
		 * Different EDD Operations executed on Ajax call
		 */
		public function edd_operations() {
			if ( empty( $operation = $_POST['operation'] ) || wp_verify_nonce( $_POST['nonce'], $this->plugin['machine_name'] . '-ccfwoo_pro-edd-client-operations' ) === false ) {
				$this->send_json( __( 'Something went wrong', 'checkout-countdown-pro' ), 'error' );
			}

			switch ( $operation ) {
				case 'change_beta':
					$beta = get_option( $this->plugin['machine_name'] . '_beta', false );

					if ( ! $beta ) {
						update_option( $this->plugin['machine_name'] . '_beta', true );
						$this->send_json( __( 'You enabled beta updates. Warning beta updates are for testing purposes only. ', 'checkout-countdown-pro' ), false, false );
					} else {
						update_option( $this->plugin['machine_name'] . '_beta', false );
						$this->send_json( __( 'You have disabled beta updates.', 'checkout-countdown-pro' ), false, false );
					}
					break;
				case 'activate_license':
					$license = ! empty( $_POST['license'] ) ? $_POST['license'] : $this->send_json( __( 'License field can not be empty', 'checkout-countdown-pro' ) );
					$license = sanitize_text_field( $license );

					$license_data = $this->validate_license( $license, $this->plugin['item_id'], $this->plugin['store_url'] );
					if ( $license_data->license === 'valid' ) {
						update_option( $this->plugin['machine_name'] . '_license_status', $license_data->license );
						update_option( $this->plugin['machine_name'] . '_license_key', $license );
						// Force Update.
						set_site_transient( 'update_plugins', null );
						$this->send_json( __( 'License successfully activated', 'checkout-countdown-pro' ), false, true );
					}
					break;
				case 'deactivate_license':
					$license_data = $this->invalidate_license( $this->plugin['license'], $this->plugin['item_id'], $this->plugin['store_url'] );
					if ( $license_data->license === 'deactivated' || $license_data->license === 'failed' ) {
						delete_option( $this->plugin['machine_name'] . '_license_status' );
						delete_option( $this->plugin['machine_name'] . '_license_key' );
						$this->send_json( __( 'License deactivated for this site', 'checkout-countdown-pro' ), false, true );
					}
					break;
				case 'change_license':
					$new_license = ! empty( $_POST['license'] ) ? $_POST['license'] : wp_send_json_error( __( 'License field can not be empty', 'checkout-countdown-pro' ) );
					$new_license = sanitize_text_field( $new_license );
					$old_license = $this->plugin['license'];
					if ( $new_license !== $old_license ) {
						$license_data = $this->validate_license( $new_license, $this->plugin['item_id'], $this->plugin['store_url'] );
						if ( $license_data->license === 'valid' ) {
							$license_data = $this->invalidate_license( $old_license, $this->plugin['item_id'], $this->plugin['store_url'] );
							if ( $license_data->license === 'deactivated' || $license_data->license === 'failed' ) {
								update_option( $this->plugin['machine_name'] . '_license_key', $new_license );
								$this->send_json( __( 'License Successfully Changed.', 'checkout-countdown-pro' ) );
							}
						}
					} else {
						$this->send_json( __( 'You entered the same license, use another one.', 'checkout-countdown-pro' ), 'error' );
					}
					break;
				case 'check_expiry':
					$this->check_expiry( $this->plugin['license'], $this->plugin['item_id'], $this->plugin['store_url'] );
					break;
				default:
					$this->send_json( __( 'Something went wrong', 'checkout-countdown-pro' ), 'error' );
			}

		}

		/*
		 *  Validate License
		 */
		public function validate_license( $license, $plugin_id, $store_url ) {
			// data to send in our API request.
			$api_params = array(
				'edd_action' => 'activate_license',
				'license' => $license,
				'item_id' => $plugin_id,
				'url' => home_url(),
			);

			// Call the custom API.
			$response = wp_remote_post(
				$store_url,
				array(
					'timeout' => 15,
					'sslverify' => false,
					'body' => $api_params,
				)
			);

			// make sure the response came back okay.
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.', 'checkout-countdown-pro' );
				}
				$this->send_json( $message, 'error' );

			} else {

				$license_data = json_decode( wp_remote_retrieve_body( $response ) );

				if ( false === $license_data->success ) {

					switch ( $license_data->error ) {

						case 'expired':
							$message = sprintf(
								__( 'Your license key expired on %s.', 'checkout-countdown-pro' ),
								date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
							);
							break;

						case 'disabled':
						case 'revoked':
							$message = __( 'Your license key has been disabled.', 'checkout-countdown-pro' );
							;
							break;

						case 'missing':
							$message = __( 'Invalid license.', 'checkout-countdown-pro' );
							;
							break;

						case 'invalid':
						case 'site_inactive':
							$message = __( 'Your license is not active for this URL.', 'checkout-countdown-pro' );
							;
							break;

						case 'item_name_mismatch':
							$message = sprintf( __( 'This appears to be an invalid license key for %s.', 'checkout-countdown-pro' ), $this->plugin['name'] );
							break;

						case 'no_activations_left':
							$message = __( 'Your license key has reached its activation limit.' );
							break;

						default:
							$message = __( 'An error occurred, please try again.' );
							break;
					}

					 $this->send_json( $message, 'error' );
				} else {
					return $license_data;
				}
			}
		}

		/*
		 * Invalidate License for current website. This will decrease the site count
		 */
		public function invalidate_license( $license, $plugin_id, $store_url ) {
			// data to send in our API request.
			$api_params = array(
				'edd_action' => 'deactivate_license',
				'license'    => $license,
				'item_id'    => $plugin_id,
				'url'        => home_url(),
			);

			// Call the custom API.
			$response = wp_remote_post(
				$store_url,
				array(
					'timeout' => 15,
					'sslverify' => false,
					'body' => $api_params,
				)
			);

			// make sure the response came back okay.
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.', 'checkout-countdown-pro' );
					;
				}
				$this->send_json( $message, 'error' );
			}

			// decode the license data.
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			return $license_data;
		}

		/*
		 * Check License Expiry.
		 */
		public function check_expiry( $license, $plugin_id, $store_url ) {

			$api_params = array(
				'edd_action' => 'check_license',
				'license' => $license,
				'item_id' => $plugin_id,
				'url'       => home_url(),
			);

			// Call the custom API.
			$response = wp_remote_post(
				$store_url,
				array(
					'timeout' => 15,
					'sslverify' => false,
					'body' => $api_params,
				)
			);

			// Make sure the response came back okay.
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.', 'checkout-countdown-pro' );
				}
				$this->send_json( $message, 'error' );
			}

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( $license_data->license == 'valid' ) {
				$this->send_json( __( 'License expiry: ', 'checkout-countdown-pro' ) . $license_data->expires );
			} elseif ( $license_data->license == 'expired' ) {
				$this->send_json( __( 'License expired on: ', 'checkout-countdown-pro' ) . $license_data->expires );
			} elseif ( $license_data->license == 'disabled' ) {
				$this->send_json( __( 'license has been disabled.', 'checkout-countdown-pro' ) );
			} elseif ( $license_data->license == 'invalid' ) {
				$this->send_json( __( 'Invalid license key', 'checkout-countdown-pro' ) );
			} else {
				$this->send_json( $license_data->license, 'error' );
			}
		}

		public function send_json( $message, $type = false, $reload = false ) {

			$help_message = ' <a href="' . $this->plugin['store_url'] . '" target="_blank">' . __( 'Need help?', 'checkout-countdown-pro' ) . ' </a>';

			$message = $message . $help_message;

			$data = array();
			$data['message'] = $message;
			$data['reload'] = $reload;

			if ( $type === 'error' ) {
				wp_send_json_error( $data );
			} else {
				wp_send_json_success( $data );
			}
		}
		public function check() {

			$checked = get_option( $this->plugin['machine_name'] . '_license_checked', false );

			if ( $checked ) {
				if ( time() < ( $checked + 86400 ) ) {
					return;
				}
			}

			$api_params = array(
				'edd_action' => 'check_license',
				'license' => $this->plugin['license'],
				'item_id' => $this->plugin['item_id'],
				'url'       => home_url(),
			);

			// Call the custom API.
			$response = wp_remote_post(
				$this->plugin['store_url'],
				array(
					'timeout' => 15,
					'sslverify' => false,
					'body' => $api_params,
				)
			);

			// Make sure the response came back okay.
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.', 'checkout-countdown-pro' );
				}
				$this->send_json( $message, 'error' );
			}

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			update_option( $this->plugin['machine_name'] . '_license_status', $license_data->license );
			update_option( $this->plugin['machine_name'] . '_license_checked', time() );
		}

	}
endif;
