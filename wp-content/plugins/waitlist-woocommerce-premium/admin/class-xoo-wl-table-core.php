<?php

class Xoo_Wl_Table_Core{

	protected static $_instance = null;

	public static function get_instance(){
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	
	public function __construct(){
		$this->hooks();
	}


	public function hooks(){
		add_action( 'wp_ajax_xoo_wl_table_remove_row', array( $this, 'remove_row' ) );
		add_action( 'wp_ajax_xoo_wl_table_send_email', array( $this, 'send_email' ) );
		add_action( 'wp_ajax_xoo_wl_export_csv', array( $this, 'export_csv' ) );
	}


	public function send_email(){
		try {

			if( !isset( $_POST['productID'] ) && !isset( $_POST['rowID'] ) ){
				throw new Xoo_Exception( __( 'Product/Row ID not found', 'waitlist-woocommerce' ) );
			}

			$email_sent = false;

			$product_id = isset( $_POST['productID'] ) ? (int) $_POST['productID'] : false;

			$row_id = isset( $_POST['rowID'] ) ? (int) $_POST['rowID'] : false;

			if( $row_id ){
				$email_sent = xoo_wl_core()->send_back_in_stock_email( $row_id );
				$message 	= __( 'Email Sent', 'waitlist-woocommerce' );
			}

			if( $product_id ){
				$email_sent = xoo_wl_core()->trigger_back_in_stock_email_for_product( $product_id );
				$message 	= sprintf( __( 'Sending emails.... This may take few seconds to minutes depending on your list. You can check the status <a target="#blank" href="%s">here</a>' ), xoo_wl_urls( 'email_history' ) );
			}

			if( is_wp_error( $email_sent ) ){
				throw new Xoo_Exception( $email_sent );
			}


			if( $email_sent ){
				wp_send_json(array(
					'error' 		=> 0,
					'notice' 		=> xoo_wl_add_notice( $message, 'success'  ),
					'delete_row' 	=> xoo_wl_helper()->get_email_option( 'bis-keep-wl' ) !== "yes",
					'sent_count' 	=> $row_id ? xoo_wl_db()->get_waitlist_meta( $row_id , '_sent_count' ) : false
				));
			}
			else{
				throw new Xoo_Exception( __( 'Unable to send email', 'waitlist-woocommerce' ) );
			}
			
		} catch (Xoo_Exception $e) {
			
			wp_send_json(array(
				'error' 	=> 1,
				'notice' 	=> xoo_wl_add_notice( $e->getMessage(), 'error'  )
			));
		}
	}



	public function remove_row(){
		try {

			if( !isset( $_POST['productID'] ) ){
				throw new Xoo_Exception( __( 'Product ID not found', 'waitlist-woocommerce' ) );
			}

			if( $_POST['remove'] === 'user' && !isset( $_POST['rowID'] ) ){
				throw new Xoo_Exception( __( 'Row ID not found', 'waitlist-woocommerce' ) );
			}

			$row_id 	= (int) $_POST['rowID'];
			$product_id = (int) $_POST['productID'];

			if( $_POST['remove'] === 'user' ){
				$delete = xoo_wl_db()->delete_waitlist_row_by_id( $row_id );
			}
			else{
				$delete = xoo_wl_db()->delete_waitlist_by_product( $product_id );
			}

			if( is_wp_error( $delete ) ){
				throw new Xoo_Exception( $delete->get_error_message() );
			}

			wp_send_json(array(
				'error' 	=> 0,
				'notice' 	=> xoo_wl_add_notice( __( 'Deleted successfully', 'waitlist-woocommerce' ), 'success'  ),
				'count' 	=> xoo_wl_db()->get_waitlisted_count( $product_id )
			));
			
		} catch (Xoo_Exception $e) {
			wp_send_json(array(
				'error' 	=> 1,
				'notice' 	=> xoo_wl_add_notice( $e->getMessage(), 'error' )
			));
		}

		wp_die();

	}



	public function export_csv(){

		try {


			$checkboxes 	= (array) $_POST['checkboxes'];
			$tableType 		= $_POST['table_type']; 
			$exportFields 	= (array) include XOO_WL_PATH.'/admin/views/export-fields.php';
			$checkboxesData = !empty( $exportFields ) && isset( $exportFields[ $tableType ] ) ? $exportFields[ $tableType ] : array();
			
			if( $tableType === 'products_table' ){
				$rows 		= xoo_wl_db()->get_products_waitlist();
				$sub_dir 	= 'products';
			}
			else if( $tableType === 'users_table' ){
				$product_id = (int) $_POST['product_id'];
				$rows 		= xoo_wl_db()->get_waitlist_rows_by_product( $product_id );
				$sub_dir 	= 'users';
				$custom_fields = array_keys( xoo_wl()->aff->fields->get_fields_data() );
			}


			if( empty( $rows ) || empty( $checkboxes ) || empty($checkboxesData) ){
				throw new Xoo_Exception( __( 'No rows to export', 'waitlist-woocommerce' ) );
			}

			$csv = $csv_headings = array();

			foreach ( $checkboxesData as $checkbox_key => $chkData ) {
				if( !isset( $checkboxes[ $checkbox_key ] ) ) continue;
				$csv_headings[] = strtoupper( $chkData['title'] );
			}

			//Headings as first row
			$csv[] = apply_filters( 'xoo_wl_export_csv_heading', $csv_headings, $tableType );


			foreach ( $rows as $row ) {


				if( $tableType === 'products_table' ){

					$product_id 	= (int) $row->product_id;
					$product 		= wc_get_product( $product_id );

					if( !$product ) continue;

					$data = array(
						'product_id' 	=> $product_id,
						'product_sku' 	=> $product->get_sku(),
						'product_name' 	=> $product->get_name(),
						'stock_status' 	=> $product->get_stock_status(),
						'quantity' 		=> $row->quantity,
						'users_count' 	=> $row->entries,
						'product_link' 	=> $product->get_permalink()
					);

				}
				else if( $tableType === 'users_table' ){
					$data = array(
						'joined_on' 	=> $row->join_date,
						'email' 		=> $row->email,
						'quantity' 		=> $row->quantity,
						'is_registered' => $row->user_id ? 'yes' : 'no'
					);

					foreach ( $custom_fields as $custom_field_id ) {
						$data[ $custom_field_id ] = xoo_wl_db()->get_waitlist_meta( $row->xoo_wl_id, $custom_field_id );
					}
					
				}

				$csv_data = array_intersect_key( $data, $checkboxes );

				$csv[] = array_values( apply_filters( 'xoo_wl_export_csv_row', $csv_data, $row, $tableType )  );
				
			}


			$base_dir 	= wp_get_upload_dir()['basedir'];

			$main_dir 	= 'xootix-waitlist-csvs';

			if( !is_dir( $base_dir.'/'.$main_dir ) ){
				mkdir( $base_dir.'/'.$main_dir );
			}

			//Check if folder exists
			if( !is_dir( $base_dir.'/'.$main_dir.'/'.$sub_dir ) ){
				mkdir( $base_dir.'/'.$main_dir.'/'.$sub_dir );
			}

			$filename 			= '/'.$main_dir.'/'.$sub_dir.'/'.date('d-F-Y__his').'.csv';
			$fileLocationDir 	= $base_dir.$filename;
			$fileURL 			= wp_upload_dir()['baseurl'].$filename;

			$file = fopen( $fileLocationDir,"w" );

			foreach ( $csv as $csv_row ) {
			  fputcsv( $file, $csv_row );
			}

			fclose($file);

			$notice = '<a href="'.$fileURL.'" target="_blank">Download</a>';

			wp_send_json( array(
				'error' 	=> 0,
				'notice' 	=> $notice
			) );

			
		} catch ( Xoo_Exception $e ) {
			wp_send_json( array(
				'error' 	=> 1,
				'notice' 	=> xoo_wl_add_notice( $e->getMessage(), 'error' )
			) );
		}
		
	}


}

function xoo_wl_table_core(){
	return Xoo_Wl_Table_Core::get_instance();
}
xoo_wl_table_core();