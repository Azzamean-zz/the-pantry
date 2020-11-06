<?php

if ( ! class_exists( 'Smart_Manager' ) ) {
	class Smart_Manager {

		static $text_domain, $prefix, $sku, $plugin_file, $sm_is_woo40, $sm_is_woo39, $sm_is_woo36, $sm_is_woo30, $sm_is_woo22, $sm_is_woo21;

		public  $plugin_path 	= '',
				$plugin_url 	= '',
				$plugin_info 	= '',
				$version 		= '',
				$updater 		= '',
				$error_message 	= '',
				$upgrade 		= '',
				$update_msg 	= '',
				$success_msg 	= '',
				$sm_dashboards_final = '';

		protected static $_instance = null;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {

			// require_once (ABSPATH . WPINC . '/default-constants.php');
			// include_once ABSPATH . 'wp-admin/includes/plugin.php';
			// include_once (ABSPATH . WPINC . '/functions.php');

			self::$text_domain = (defined('SM_TEXT_DOMAIN')) ? SM_TEXT_DOMAIN : 'smart-manager-for-wp-e-commerce';
			self::$prefix = (defined('SM_PREFIX')) ? SM_PREFIX : 'sa_smart_manager';
			self::$sku = (defined('SM_SKU')) ? SM_SKU : 'sm';
			self::$plugin_file = (defined('SM_PLUGIN_FILE')) ? SM_PLUGIN_FILE : '';
			self::$sm_is_woo40 = (defined('SM_IS_WOO40')) ? SM_IS_WOO40 : '';
			self::$sm_is_woo39 = (defined('SM_IS_WOO39')) ? SM_IS_WOO39 : '';
			self::$sm_is_woo36 = (defined('SM_IS_WOO36')) ? SM_IS_WOO36 : '';
			self::$sm_is_woo30 = (defined('SM_IS_WOO30')) ? SM_IS_WOO30 : '';
			self::$sm_is_woo22 = (defined('SM_IS_WOO22')) ? SM_IS_WOO22 : '';
			self::$sm_is_woo21 = (defined('SM_IS_WOO21')) ? SM_IS_WOO21 : '';

			$this->plugin_path  = untrailingslashit( plugin_dir_path( __FILE__ ) );
			$this->plugin_url   = untrailingslashit( plugins_url( '/', __FILE__ ) );
			$this->update_msg   = 'editing';

			$plugin_info = get_plugins();
			$this->plugin_info = $plugin_info [SM_PLUGIN_BASE_NM];
			
			if( function_exists( 'smart_manager_get_version' ) ) {
				$this->version = smart_manager_get_version();
			}
			
			$this->updater = rand(3.0,3.9);
			$this->dupdater = rand(25.0,25.9);
			$this->upgrade = (defined('SM_UPGRADE')) ? SM_UPGRADE : 3;
			$this->dupgrade = (defined('SM_DUPGRADE')) ? SM_DUPGRADE : 25;
			$this->success_msg   = (defined('SM_UPDATE')) ? SM_UPDATE : '';

			// $this->define_constants(); 
			$this->sm_includes();		// for adding necessary files

			if ( ! defined( 'SM_BETA_IMG_URL' ) ) {
				define( 'SM_BETA_IMG_URL', $this->plugin_url . '/assets/images/' );
			}

			// add_action ( 'admin_notices', array(&$this,'smart_admin_notices') );
			// add_action ( 'admin_head', array(&$this,'remove_help_tab') ); // For removing the help tab
			// add_action( 'admin_menu', array(&$this,'smart_add_menu_access'), 9 ); // for adding menu

			// Remove WP footer on SM pages
			add_filter( 'admin_footer_text', array( &$this,'footer_text') );
			add_filter( 'update_footer', array( &$this,'update_footer_text'), 99 );

			add_action( 'admin_footer', array( $this, 'smart_manager_support_ticket_content' ) );
			add_action( 'admin_menu', 'smart_woo_add_modules_admin_pages' );

			//for defining all the constants
			add_action( 'admin_init', array( $this, 'define_constants' ) );

			add_action( 'admin_footer', array( $this, 'manage_with_smart_manager' ) );
		}

		//Function for defining constants
		function define_constants() {

			global $wp_version, $wpdb;

			$post_types = get_post_types( array(), 'objects' ); //Code to get all the custom post types as dashboards

			$ignored_post_types = array('revision', 'product_variation', 'shop_order_refund');

			$this->sm_dashboards_final = array();
			$this->sm_public_dashboards = array();

			if( !empty( $post_types ) ) {
				foreach( $post_types as $post_type => $obj  ) {

					if( in_array($post_type, $ignored_post_types) ) {
						continue;
					}

					$this->sm_dashboards_final[$post_type] = $obj->label;
					if( !empty( $obj->public ) && $obj->public == 1 ) {
						$this->sm_public_dashboards[] = $post_type;
					}
				}
			}
			$this->sm_dashboards_final ['user'] = __(ucwords('users'), 'smart-manager-for-wp-e-commerce');

			if ( ! defined( 'SM_BETA_ALL_DASHBOARDS' ) ) {
				define( 'SM_BETA_ALL_DASHBOARDS', json_encode( $this->sm_dashboards_final ) );
			}

			$this->sm_dashboards_final = apply_filters('sm_active_dashboards', $this->sm_dashboards_final);
		} 

		// Function to include necessary files for SM
		function sm_includes() {

			global $current_user;

			include_once $this->plugin_path . '/classes/class-smart-manager-controller.php';
			new Smart_Manager_Controller();

			if ( is_admin() ) {
				if( file_exists( $this->plugin_path . '/classes/class-smart-manager-pricing.php' ) ) { 
					include_once $this->plugin_path . '/classes/class-smart-manager-pricing.php';
				}

				if( file_exists( $this->plugin_path . '/classes/class-storeapps-marketplace.php' ) ) { 
					include_once $this->plugin_path . '/classes/class-storeapps-marketplace.php';
				}

				if( file_exists( $this->plugin_path . '/classes/deactivation-survey/class-sa-smart-manager-deactivation.php' ) ) { 
					include_once $this->plugin_path . '/classes/deactivation-survey/class-sa-smart-manager-deactivation.php';		
				}

				
				if ( defined('SMBETAPRO') && true === SMBETAPRO ) {
					$sm_plugin_name = SM_PLUGIN_NAME . ' - Pro';
				} else {
					$sm_plugin_name = SM_PLUGIN_NAME . ' - Lite';
				}

				if ( class_exists( 'SA_Smart_Manager_Deactivation' ) ) {
					$sa_sm_deativate = new SA_Smart_Manager_Deactivation( SM_PLUGIN_BASE_NM, $sm_plugin_name );
				}

				if ( ! empty($_GET['page']) && $_GET['page'] == "smart-manager" && ! empty( $_GET['dashboard'] ) ) {
					update_option('sm_wp_dashboard_page_'.$current_user->ID, $_GET['dashboard'] );
					wp_safe_redirect( remove_query_arg('dashboard', wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
					exit;
				}

			}

		}

		function enqueue_admin_scripts() {

			global $wp_version, $wpdb, $current_user;

			if ( !wp_script_is( 'jquery' ) ) {
				wp_enqueue_script( 'jquery' );
			}

			if ( !wp_script_is( 'underscore' ) ) {
				wp_enqueue_script( 'underscore' );
			}

			if ( function_exists('wp_enqueue_editor') ) {
				wp_enqueue_editor();
			}
			
			$deps = array('jquery', 'jquery-ui-core' , 'jquery-ui-widget' , 'jquery-ui-accordion' , 'jquery-ui-autocomplete' , 'jquery-ui-button' , 'jquery-ui-datepicker' ,
						 'jquery-ui-dialog' , 'jquery-ui-draggable' , 'jquery-ui-droppable' , 'jquery-ui-menu' , 'jquery-ui-mouse' , 'jquery-ui-position' , 'jquery-ui-progressbar'
						 , 'jquery-ui-selectable' , 'jquery-ui-resizable' , 'jquery-ui-sortable' , 'jquery-ui-slider' , 'jquery-ui-tooltip' ,'jquery-ui-tabs' , 'jquery-ui-spinner' , 
						  'jquery-effects-core' , 'jquery-effects-blind' , 'jquery-effects-bounce' , 'jquery-effects-clip' , 'jquery-effects-drop' ,
						  'jquery-effects-explode' , 'jquery-effects-fade' , 'jquery-effects-fold' , 'jquery-effects-highlight' , 'jquery-effects-pulsate' , 'jquery-effects-scale' ,
						  'jquery-effects-shake' , 'jquery-effects-slide' , 'jquery-effects-transfer', 'underscore');

			if ( isset($_GET['page']) && $_GET['page'] == "smart-manager" && isset( $_GET['sm-settings'] ) ) {
				wp_register_script ( 'sm_select2', plugins_url ( '/assets/js/select2/select2.full.min.js', __FILE__ ), $deps, '4.0.5' );
				wp_enqueue_script( 'sm_select2' );
				return;
			}
						
			//Registering scripts for jqgrid lib.
	  //       wp_register_script ( 'sm_jquery_ui_multiselect', plugins_url ( '/assets/js/jqgrid/ui.multiselect.js', __FILE__ ), $deps, '1.10.2' );
			// wp_register_script ( 'sm_jqgrid_locale', plugins_url ( '/assets/js/jqgrid/grid.locale-en.js', __FILE__ ), array ('sm_jquery_ui_multiselect'), '1.10.2' );
			// wp_register_script ( 'sm_select2', plugins_url ( '/assets/js/select2/select2.full.min.js', __FILE__ ), $deps, '4.0.5' );
			// wp_register_script ( 'sm_jsoneditor', plugins_url ( '/assets/js/jsoneditor/jsoneditor.min.js', __FILE__ ), array ('sm_select2'), '5.29.1' );
			// wp_register_script ( 'sm_handsontable', plugins_url ( '/assets/js/handsontable/handsontable.full.min.js', __FILE__ ), array ('sm_jsoneditor'), '6.2.0' );
			// wp_register_script ( 'sm_handsontable_select2', plugins_url ( '/assets/js/handsontable/select2-editor.js', __FILE__ ), array ('sm_handsontable'), '6.2.0' );
			// wp_register_script ( 'sm_chosen', plugins_url ( '/assets/js/chosen/chosen.jquery.min.js', __FILE__ ), array ('sm_handsontable_select2'), '1.3.0' );
			// wp_register_script ( 'sm_sortable', plugins_url ( '/assets/js/sortable/sortable.min.js', __FILE__ ), array ('sm_chosen'), '1.8.1' );

			//Registering scripts for visualsearch lib.
			wp_register_script ( 'sm_visualsearch_dependencies_beta', plugins_url ( '/../visualsearch/backbone.js', __FILE__ ), $deps, $this->version );
			wp_register_script ( 'sm_search_beta', plugins_url ( '/../visualsearch/search.js', __FILE__ ), array ('sm_visualsearch_dependencies_beta'), $this->version );


			$last_reg_script = 'sm_search_beta';

			//Code for loading custom js automatically
			$custom_lib_js_lite = glob( $this->plugin_path .'/assets/js/*/*.js' );
			$custom_lib_js_pro = ( SMBETAPRO === true ) ? glob( $this->plugin_path .'/pro/assets/js/*/*.js' ) : array();
			$custom_lib_js = ( !empty( $custom_lib_js_pro ) && SMBETAPRO === true ) ? array_merge( $custom_lib_js_lite, $custom_lib_js_pro ) : $custom_lib_js_lite;

			if( !empty( $custom_lib_js ) ) {
				$index = 0;

				foreach ( $custom_lib_js as $file ) {

					$folder_path = substr($file, 0, (strrpos($file, '/', -3)));
					$folder_name = substr($folder_path, (strrpos($folder_path, '/', -3) + 1));

					$pro_flag = ( !empty( $custom_lib_js_pro ) && in_array($file, $custom_lib_js_pro) ) ? 'pro' : '';

					$file_nm = 'sm_'. ( !empty( $pro_flag ) ? $pro_flag.'_' : '' ) .'custom_'.preg_replace('/[\s\-.]/','_',substr($file, (strrpos($file, '/', -3) + 1)));

					if ( $file_nm == 'sm_pro_custom_smart_manager_js' ) {
						continue;
					}		
					
					wp_register_script ( $file_nm, plugins_url ( ( !empty( $pro_flag ) ? '/'.$pro_flag : '' ).'/assets/js/'.$folder_name.'/'.substr($file, (strrpos($file, '/', -3) + 1)), __FILE__ ), array ($last_reg_script), $this->version );
					
					$last_reg_script = $file_nm;
					$index++;
				}
			}

			wp_register_script ( 'sm_custom_smart_manager_js', plugins_url ( '/assets/js/smart-manager.js', __FILE__ ), array ($last_reg_script), $this->version );
			$last_reg_script = 'sm_custom_smart_manager_js';

			if( SMBETAPRO === true ) {
				wp_register_script ( 'sm_pro_custom_smart_manager_js', plugins_url ( '/pro/assets/js/smart-manager.js', __FILE__ ), array ($last_reg_script), $this->version );
				$last_reg_script = 'sm_pro_custom_smart_manager_js';
			}

			// Code for loading custom js automatically
			$custom_js = glob( $this->plugin_path .'/assets/js/*.js' );
			$index = 0;

			foreach ( $custom_js as $file ) {

				$file_nm = 'sm_custom_'.preg_replace('/[\s\-.]/','_',substr($file, (strrpos($file, '/', -3) + 1)));

				if ( $file_nm == 'sm_custom_smart_manager_js' ) {
					continue;
				}

				if ( empty($last_reg_script) && $index == 0 ) {
					wp_register_script ( $file_nm, plugins_url ( '/assets/js/'.substr($file, (strrpos($file, '/', -3) + 1)), __FILE__ ), array ('sm_custom_smart_manager_js'), $this->version );
				} else {	        		
					wp_register_script ( $file_nm, plugins_url ( '/assets/js/'.substr($file, (strrpos($file, '/', -3) + 1)), __FILE__ ), array ($last_reg_script), $this->version );
				}

				$last_reg_script = $file_nm;
				$index++;
			}

			//Updating The Files Recieved in SM Beta
			$successful = ($this->updater * $this->upgrade)/$this->updater;

			// Code for loading custom js for PRO automatically
			if( SMBETAPRO === true ) {
				$custom_js = glob( $this->plugin_path .'/pro/assets/js/*.js' );

				foreach ( $custom_js as $file ) {

					$file_nm = 'sm_pro_custom_'.preg_replace('/[\s\-.]/','_',substr($file, (strrpos($file, '/', -3) + 1)));

					if ( $file_nm == 'sm_pro_custom_smart_manager_js' ) {
						continue;
					}

					wp_register_script ( $file_nm, plugins_url ( '/pro/assets/js/'.substr($file, (strrpos($file, '/', -3) + 1)), __FILE__ ), array ($last_reg_script), $this->version );

					$last_reg_script = $file_nm;
					$index++;
				}
			}

			$sm_dashboard_keys = ( !empty( $this->sm_dashboards_final ) ) ? array_keys($this->sm_dashboards_final) : array('');

			// set the default dashboard
			$search_type = get_transient( 'sm_beta_'.$current_user->user_email.'_search_type' );
			
			$default_dashboard = get_option('sm_wp_dashboard_page_'.$current_user->ID, false);
			$is_redirect = false;

			if( empty( $default_dashboard ) ) {
				$default_dashboard = get_transient( 'sm_beta_'.$current_user->user_email.'_default_dashboard' );
			} else {
				$is_redirect = true;
				delete_option('sm_wp_dashboard_page_'.$current_user->ID);
			}
			
			$default_dashboard_index = ( $default_dashboard !== false ) ? array_search($default_dashboard, $sm_dashboard_keys) : '';

			//Updating The Files Recieved in SM Beta
			$deleted_sucessfull = ( ($this->dupdater * $this->dupgrade)/$this->dupdater ) * 2;

			$this->sm_dashboards_final ['default'] = ( $default_dashboard !== false && $default_dashboard_index >= 0 && ( SMBETAPRO === true || true === $is_redirect ) ) ? $sm_dashboard_keys[$default_dashboard_index] : ( (is_plugin_active( 'woocommerce/woocommerce.php' ) && !empty( $this->sm_dashboards_final['product'] ) ) ? 'product' : $sm_dashboard_keys[0] );
			$this->sm_dashboards_final ['default_dashboard_title'] = ( !empty( $this->sm_dashboards_final[$this->sm_dashboards_final ['default']] ) ) ? $this->sm_dashboards_final[$this->sm_dashboards_final ['default']] : '';

			$this->sm_dashboards_final ['sm_nonce'] = wp_create_nonce( 'smart-manager-security' );

			//setting limit for the records to be displayed
			$record_per_page = get_option( '_sm_beta_set_record_limit' );

			if( empty($record_per_page) ) {
				update_option('_sm_beta_set_record_limit', '50');
				$record_per_page = '50';
			}
 
			$batch_background_process = false;
			$background_process_name = '';

			if( SMBETAPRO === true ) {
				$batch_background_process = get_site_option('sm_beta_background_process_status', false);
				$background_process_params = get_transient('sm_beta_background_process_params');
				$background_process_name = (!empty($background_process_params['process_name'])) ? $background_process_params['process_name'] : '';
			}

			$lite_dashboards = array('product', 'shop_order', 'shop_coupon', 'post');

			$trash_enabled = true;
			if( defined('EMPTY_TRASH_DAYS') ){
				if( 0 == EMPTY_TRASH_DAYS ) {
					$trash_enabled = false;
				}
			}

			//Filter for disabling the 'Delete Permanently' functionality
			$disable_delete_permanently = apply_filters('sm_beta_disable_delete_permanently', false);
			$delete_permanently_disable_message = apply_filters('sm_beta_delete_permanently_disable_message', __( 'Delete Permanently is disabled. Please contact store administrator for enabling the same.', 'smart-manager-for-wp-e-commerce'));

			$sm_beta_params = array( 
								'sm_dashboards' => json_encode($this->sm_dashboards_final),
								'sm_dashboards_public' => json_encode($this->sm_public_dashboards),
								'SM_IS_WOO36' => self::$sm_is_woo36,
								'SM_IS_WOO30' => self::$sm_is_woo30,
								'SM_IS_WOO22' => self::$sm_is_woo22,
								'SM_IS_WOO21' => self::$sm_is_woo21,
								'SM_BETA_PRO' => SMBETAPRO,
								'record_per_page' => $record_per_page,
								'sm_admin_email' => get_option('admin_email'),
								'batch_background_process' => $batch_background_process,
								'background_process_name' => $background_process_name,
								'updated_sucessfull' => $successful,
								'deleted_sucessfull' => $deleted_sucessfull,
								'updated_msg' => $this->update_msg.' more',
								'success_msg' => $this->success_msg,
								'lite_dashboards' => json_encode($lite_dashboards),
								'is_settings_page' => ( isset( $_GET['sm-settings'] ) ? true : false ),
								'search_type' => ( ( !empty( $search_type ) ) ? $search_type : 'simple' ),
								'wpdb_prefix' => $wpdb->prefix,
								'trashEnabled' => $trash_enabled,
								'background_process_running_message' => __( 'In the meanwhile, you can use Smart Manager. But before using actions like ', 'smart-manager-for-wp-e-commerce') .' <strong>'. __( 'Bulk Edit', 'smart-manager-for-wp-e-commerce') .'</strong>/ <strong>'. __('Duplicate Records', 'smart-manager-for-wp-e-commerce') .'</strong>/ <strong>'. __( 'Delete Records', 'smart-manager-for-wp-e-commerce') .'</strong>, '. __('you will have to wait for the current background process to finish.', 'smart-manager-for-wp-e-commerce' ),
								'delete_permanently' => array( 'disable' => $disable_delete_permanently, 'error_message' => $delete_permanently_disable_message )
							);

			$active_plugins = (array) get_option( 'active_plugins', array() );

			if ( is_multisite() ) {
				$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
			}

			if ( ( in_array( 'woocommerce/woocommerce.php', $active_plugins, true ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins ) ) ) {
				$sm_beta_params['woo_price_decimal_places'] = wc_get_price_decimals();
				$sm_beta_params['woo_price_decimal_separator'] = wc_get_price_decimal_separator();
			}


			wp_localize_script( 'sm_custom_smart_manager_js', 'sm_beta_params', $sm_beta_params );

			wp_enqueue_script( $last_reg_script );

			// Including Scripts for using the wordpress new media manager
			if (version_compare ( $wp_version, '3.5', '>=' )) {
				if ( isset($_GET['page']) && ($_GET['page'] == "smart-manager" || $_GET['page'] == "smart-manager-settings")) {
					wp_enqueue_media();
					wp_enqueue_script( 'custom-header' );
				}
			}

			do_action('smart_manager_enqueue_scripts'); //action for hooking any scripts
		}

		function enqueue_admin_styles() {

			wp_enqueue_style( 'wp-jquery-ui-dialog' );
			
			//Registering styles for visualsearch lib.
			wp_register_style ( 'sm_beta_search_reset', plugins_url ( '/../visualsearch/reset.css', __FILE__ ), array (), $this->version );
			wp_register_style ( 'sm_beta_search_icons', plugins_url ( '/../visualsearch/icons.css', __FILE__ ), array ('sm_beta_search_reset'), $this->version );
			wp_register_style ( 'sm_beta_search_workspace', plugins_url ( '/../visualsearch/workspace.css', __FILE__ ), array ('sm_beta_search_icons'), $this->version );

			//Code for loading custom js for PRO automatically
			$custom_css_lite = glob( $this->plugin_path .'/assets/css/*/*.css' );
			$custom_css_pro = array();
			if( SMBETAPRO === true ) {
				$custom_css = glob( $this->plugin_path .'/pro/assets/css/*.css' );
				$custom_lib_css = glob( $this->plugin_path .'/pro/assets/css/*/*.css' );
				$custom_css_pro = array_merge($custom_lib_css,$custom_css);
			}

			$custom_css = ( !empty( $custom_css_pro ) ) ? array_merge($custom_css_lite, $custom_css_pro) : $custom_css_lite;

			if( !empty( $custom_css ) ) {
				$index = 0;
				$last_reg_script = 'sm_beta_search_workspace';
				foreach ( $custom_css as $file ) {

					$folder_name = '';

					$folder_path = substr($file, 0, (strrpos($file, '/', -3)));
					$folder_name = substr($folder_path, (strrpos($folder_path, '/', -3) + 1));

					$pro_flag = ( !empty( $custom_css_pro ) && in_array($file, $custom_css_pro) ) ? 'pro' : '';

					$file_nm = 'sm_'. ( !empty( $pro_flag ) ? $pro_flag.'_' : '' ) .'custom_'.preg_replace('/[\s\-.]/','_',substr($file, (strrpos($file, '/', -3) + 1)));

					if( $file_nm == 'sm_pro_custom_smart_manager_css' ) {
						continue;
					}

					wp_register_style ( $file_nm, plugins_url ( ( !empty( $pro_flag ) ? '/'.$pro_flag : '' ).'/assets/css/'.$folder_name.'/'.substr($file, (strrpos($file, '/', -3) + 1)), __FILE__ ), array($last_reg_script), $this->version );

					$last_reg_script = $file_nm;
					$index++;
				}
			}

			wp_register_style ( 'sm_main_style', plugins_url ( '/assets/css/smart-manager.css', __FILE__ ), array($last_reg_script), $this->version );			
			$last_reg_script = 'sm_main_style';

			if( SMBETAPRO === true ) {
				wp_register_style ( 'sm_pro_main_style', plugins_url ( '/pro/assets/css/smart-manager.css', __FILE__ ), array($last_reg_script), $this->version );			
				$last_reg_script = 'sm_pro_main_style';
			}

			wp_enqueue_style( $last_reg_script );

			do_action('smart_manager_enqueue_scripts');	//action for hooking any styles
		}

		function get_latest_version() {
			$sm_plugin_info = get_site_transient( 'update_plugins' );
			$latest_version = isset( $sm_plugin_info->response [SM_PLUGIN_BASE_NM]->new_version ) ? $sm_plugin_info->response [SM_PLUGIN_BASE_NM]->new_version : '';
			return $latest_version;
		}

		function get_user_sm_version() {
			$sm_plugin_info = get_plugins();
			$user_version = $sm_plugin_info [SM_PLUGIN_BASE_NM] ['Version'];
			return $user_version;
		}

		function is_pro_updated() {
			$user_version = $this->get_user_sm_version();
			$latest_version = $this->get_latest_version();
			return version_compare( $user_version, $latest_version, '>=' );
		}

		// function for removing the Help Tab
		function remove_help_tab(){
			//condition to remove the help tab only from SM pages
			if( !empty($_GET['page']) && 'smart-manager' === $_GET['page'] && !( !empty( $_GET['sm_old'] ) && ( 'woo' === $_GET['sm_old'] || 'wpsc' === $_GET['sm_old'] ) ) ) {
				$screen = get_current_screen();
				$screen->remove_help_tabs();
			}
		}

		//function for showing the sm page
		function show_console_beta() {
		
			global $wpdb;

			$latest_version = $this->get_latest_version();
			$is_pro_updated = $this->is_pro_updated();

			?>
			<div class="wrap" style="margin: 0!important;">
				<style>
					div#TB_window {
						background: lightgrey;
					}
				</style>    
				<?php if ( SMBETAPRO === true && function_exists( 'smart_support_ticket_content' ) ) smart_support_ticket_content();  ?>    
					
				<div id="sm_nav_bar" style="margin-bottom:1em;">
					<div class='sm_beta_left'>	
						<span class="sm-h2">
						<?php
								echo 'Smart Manager ';
								echo '<sup style="vertical-align: super;background-color: #EC8F1C;font-size: 0.6em !important;color: white;padding: 2px 3px;border-radius: 2px;font-weight: 600;"><span>'.((SMBETAPRO === true) ? 'PRO' : 'LITE').'</span></sup>';
								$plug_page = '';
								
						?>
						</span>
					</div>
					<span id="sm_nav_bar_right" style="float: right; line-height: 3.5em;"> <?php
						if ( SMBETAPRO === true && ! is_multisite() ) {
							$plug_page .= '<a href="admin.php?page=smart-manager&sm-settings">Settings</a> | ';
						} else {
							$plug_page = '';
						}
						
						$sm_old_link = '';

						// if ( !empty( $_GET['page'] ) && $_GET['page'] == "smart-manager" && !( !empty( $_GET['sm_old'] ) && ( 'woo' === $_GET['sm_old'] || 'wpsc' === $_GET['sm_old'] ) ) ) {

						// 	$sm_old = '';

						// 	if( ( is_plugin_active ( 'woocommerce/woocommerce.php' ) && is_plugin_active ( 'wp-e-commerce/wp-shopping-cart.php' ) ) || ( is_plugin_active ( 'woocommerce/woocommerce.php' ) ) ) {
						// 		$sm_old = 'woo';							
						// 	} elseif( is_plugin_active ( 'wp-e-commerce/wp-shopping-cart.php' ) ) {
						// 		$sm_old = 'wpsc';
						// 	}

						// 	if ( SMBETAPRO === true ) {
						// 		$sm_old_link = '<span class="sa_sm_beta_feedback_form" style="background-color: #ecddef;padding: 0.5em 0.5em 0.5em 0.5em;margin: 1.2em;border: 1px solid #4e4e8a;margin-top: 2em;">
						// 				<span class="dashicons dashicons-megaphone" style="font-size: 1.8em;color:#43438e;margin-left: -0.1em;margin-right: 0.2rem;margin-bottom: 0.45em;line-height: inherit;"></span> 
						// 			<a id="sm_beta_pro_feedback" class="thickbox" href="' . admin_url('#TB_inline?inlineId=sa_smart_manager_beta_post_query_form&height=450') .'" style="color:#43438e !important;" title="'. __( 'Submit your feedback', 'smart-manager-for-wp-e-commerce' ) .'">' .__( 'We would love to hear your feedback', 'smart-manager-for-wp-e-commerce' ) . '</a>
						// 			</span>';
						// 	} else {
						// 		$sm_old_link = '<a href="https://demo.storeapps.org/?demo=sm-woo&utm_source=in_app&utm_medium=sm_links&utm_campaign=lite_demo_link" target="_livedemo" title="'. __( 'Smart Manager Pro Demo', 'smart-manager-for-wp-e-commerce' ) .'"> ' . __( 'Pro Demo', 'smart-manager-for-wp-e-commerce' ) .'</a> ';	
						// 		' <a href="> ';
						// 	}

						// 	// if( !empty( $sm_old ) ) {
						// 	// 	$sm_old_link .= '<a href="'. admin_url('admin.php?page='. $_GET['page'] .'&sm_old='. $sm_old) .'" title="'. __( 'Switch back to Smart Manager Old', 'smart-manager-for-wp-e-commerce' ) .'"> ' . __( 'Switch back to Old', 'smart-manager-for-wp-e-commerce' ) .'</a>';	
						// 	// }
	
						// }

						$before_plug_page = '';

						if ( SMBETAPRO === true ) {
							if ( !wp_script_is( 'thickbox' ) ) {
								if ( !function_exists( 'add_thickbox' ) ) {
									require_once ABSPATH . 'wp-includes/general-template.php';
								}
								add_thickbox();
							}
							$before_plug_page = apply_filters( 'sm_before_plug_page', $before_plug_page );
							if (is_super_admin()) {
								// $before_plug_page .= ' | <a href="options-general.php?page=smart-manager&sm-settings">Settings</a>';
								$before_plug_page .= ' <a href="admin.php?page=smart-manager&sm-settings">Settings</a>';
							}
							
						}
						printf ( __ ( '%1s%2s' , 'smart-manager-for-wp-e-commerce'), $sm_old_link, $before_plug_page );
						?>
					</span>
				</div>
			</div>
			<?php
				if (! $is_pro_updated) {
					?> <?php
					$admin_url = SM_ADMIN_URL . "plugins.php";
					$update_link = __( 'An upgrade for Smart Manager Pro', 'smart-manager-for-wp-e-commerce' ) . " " . $latest_version . " " . __( 'is available.', 'smart-manager-for-wp-e-commerce' ) . " " . "<a align='right' href=$admin_url>" . __( 'Click to upgrade.', 'smart-manager-for-wp-e-commerce' ) . "</a>";
					$this->display_notice( $update_link );
					?> <?php
				}

				if( function_exists('smart_manager_upgrade_notifications') ) {
					smart_manager_upgrade_notifications();
				}
			?>

				<div id="sm_editor_grid" ></div>
				
				<div id="sm_pagging_bar"></div>
					
				<div id="sm_inline_dialog"></div>

				<div class="sm-loader-container">
					<div class="sm-loader">
						<div></div>
						<div></div>
						<div></div>
						<div></div>
						<div></div>
						<div></div>
						<div></div>
						<div></div>
					</div>
				</div>

				<?php
			
		}

		/**
		 * Smart Manager's Support Form
		 */
		function smart_manager_support_ticket_content() {

			if ( !( !empty( $_GET['page'] ) && ( 'smart-manager-woo' === $_GET['page'] || 'smart-manager-wpsc' === $_GET['page'] || ( !empty( $_GET['sm_old'] ) && ( 'woo' === $_GET['sm_old'] || 'wpsc' === $_GET['sm_old'] ) && 'smart-manager' === $_GET['page'] ) || 'smart-manager' === $_GET['page'] || 'smart-manager-settings' === $_GET['page'] ) ) ) {
				return;
			}

			if ( !wp_script_is('thickbox') ) {
				if (!function_exists('add_thickbox')) {
					require_once ABSPATH . 'wp-includes/general-template.php';
				}
				add_thickbox();
			}

			if ( ! method_exists( 'StoreApps_Upgrade_3_6', 'support_ticket_content' ) ) return;

			$plugin_data = get_plugin_data( self::$plugin_file );
			$license_key = get_site_option( self::$prefix.'_license_key' );

			StoreApps_Upgrade_3_6::support_ticket_content( 'sa_smart_manager_beta', self::$sku, $plugin_data, $license_key, 'smart-manager-for-wp-e-commerce' );
		}

		function footer_text( $sm_footer_text ) {
			if ( is_admin() && !empty( $_GET['page'] ) && ( 'smart-manager-woo' === $_GET['page'] || 'smart-manager-wpsc' === $_GET['page'] || ( !empty( $_GET['sm_old'] ) && ( 'woo' === $_GET['sm_old'] || 'wpsc' === $_GET['sm_old'] ) && 'smart-manager' === $_GET['page'] ) || 'smart-manager' === $_GET['page'] || 'smart-manager-settings' === $_GET['page'] || 'smart-manager-pricing' === $_GET['page'] || 'sm-storeapps-plugins' === $_GET['page'] ) ) {
				// $sm_footer_text = __( '<span style="color:#555d66;">Thank you for using <span style="color: #5850EC;">Smart Manager</span>. A huge thank you from <span style="color: #5850EC;">StoreApps</span></span>!', 'smart-manager-for-wp-e-commerce' );
				$sm_footer_text = '';
			}

			return $sm_footer_text;
		}

		function update_footer_text( $sm_version_text ) {

			$sm_plugin_data = get_plugin_data( WP_PLUGIN_DIR.'/smart-manager-for-wp-e-commerce/smart-manager.php' );
			$sm_current_version = $sm_plugin_data['Version'];

			if ( is_admin() && ! empty( $_GET['page'] ) && ( 'smart-manager-woo' === $_GET['page'] || 'smart-manager-wpsc' === $_GET['page'] || ( !empty( $_GET['sm_old'] ) && ( 'woo' === $_GET['sm_old'] || 'wpsc' === $_GET['sm_old'] ) && 'smart-manager' === $_GET['page'] ) || 'smart-manager' === $_GET['page'] || 'smart-manager-settings' === $_GET['page'] || 'smart-manager-pricing' === $_GET['page'] || 'sm-storeapps-plugins' === $_GET['page'] ) ) {
				// $sm_version_text = sprintf( __( 'Smart Manager version: <span style="color: #5850EC;">%s</span>', 'smart-manager-for-wp-e-commerce' ), $sm_current_version );
				$sm_version_text = '';
			}

			return $sm_version_text;
		}

		//Function for showing the sm-privilege settings
		function show_privilege_page() {
			if (file_exists( $this->plugin_path . '/pro/sm-privilege.php' )) {
				include_once ($this->plugin_path . '/pro/sm-privilege.php');
				return;
			} else {
				$error_message = __( "A required Smart Manager file is missing. Can't continue. ", 'smart-manager-for-wp-e-commerce' );
			}
		}

		//function to display notices
		function display_notice($notice) {
			echo "<div class='sm-upgrade-notice'>
					 <p>";
			echo _e( $notice, 'smart-manager-for-wp-e-commerce' );
			echo "</p></div>";
		}

		//function to error messages
		function display_err() {
			echo "<div id='notice' class='error'>";
			echo "<b>" . __( 'Error:', 'smart-manager-for-wp-e-commerce' ) . "</b>" . $this->error_message;
			echo "</div>";
		}

		function smart_manager_print_logo() {
			if (get_option('smart_manager_company_logo') != '') {
				return '<img src="' . get_option('smart_manager_company_logo') . '"/>';
			}
		}

		function manage_with_smart_manager() {
			$current_post_type = get_current_screen()->post_type;
			$current_screen_id = get_current_screen()->id;
			
			if ( ( ! empty( $current_post_type ) && 'edit-'.$current_post_type === $current_screen_id ) || 'users' === $current_screen_id  ) {
				$dashboard = ( ( ! empty( $current_post_type ) ) ? $current_post_type : 'user' );
			
				wp_register_script( 'manage_with_sm', plugins_url( '/assets/js/manage-with-smart-manager.js', __FILE__ ), array(), smart_manager_get_version(), true );
				wp_enqueue_script( 'manage_with_sm' );
				$sm_params = array(
					'url' => admin_url( 'admin.php?page=smart-manager' ) . '&dashboard=' . $dashboard,
					'string' => '<img src="' . SM_BETA_IMG_URL . 'menu-icon-16x16.png" /> Manage with Smart Manager',
					'active_dashboard' => $dashboard,
				);
				wp_localize_script( 'manage_with_sm', 'manage_with_sm', $sm_params );
				?>
				<style type="text/css">
					.page-title-action.edit-sm {
						background-color: #ffffff;
						border: 1px solid #0e9f6e;
						color: #0e9f6e;
					}
					.page-title-action.edit-sm img {
						vertical-align: sub;
					}
					.page-title-action.edit-sm:hover {
						background: #f1f1f1 !important;
						border-color: #0e9f6e !important;
						color: #0e9f6e !important;
					}
				</style>
				<?php
			}
		}

	}
}

$GLOBALS['smart_manager_beta'] = Smart_Manager::instance();
