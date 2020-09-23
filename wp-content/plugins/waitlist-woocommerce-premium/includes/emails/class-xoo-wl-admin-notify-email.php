<?php

class Xoo_Wl_Admin_Notify_Email extends Xoo_Wl_Email{

	public function __construct(){

		parent::__construct();

		$this->id 			= 'admin_notify';
		$this->template 	= 'emails/xoo-wl-admin-notify-email.php';
		$this->subject 		= xoo_wl_helper()->get_email_option( 'an-subject' );
		$this->body_text 	= xoo_wl_helper()->get_email_option( 'an-content' );

		$recipient_emails 	= array_map(
			function( $email ){
				return trim( $email );
			},
			explode( ',', xoo_wl_helper()->get_email_option( 'an-emails' ) )
		);

		$this->recipient_emails = array_merge(
			$this->recipient_emails,
			$recipient_emails
		);

		$this->hooks();

	}

	public function hooks(){
		add_action( 'xoo_wl_email_admin_notify_sent', array( $this, 'on_email_sent' ), 10, 2 );
	}


	public function on_email_sent( $sent, $obj ){

		if( !$sent ) return;

		//Update sent count
		$sent_count = (int) xoo_wl_db()->get_waitlist_meta( $this->row_id, '_notified_admin' );
		$sent_count++;
		xoo_wl_db()->update_waitlist_meta( $this->row_id, '_notified_admin', $sent_count );
	}


	public function validation(){

		//Notify admin only once
		if( xoo_wl_helper()->get_email_option( 'an-send-once' ) === "yes" ){
			$sent_count = (int) xoo_wl_db()->get_waitlist_meta( $this->row_id, '_notified_admin' );
			if( $sent_count >= 1 ){
				return false;
			}
		}
		return true;
	}



	public function get_template(){

		$args = array(
			'body_text' 	=> $this->body_text,
			'fontSize' 		=> xoo_wl_helper()->get_email_style_option( 'an-fsize' ),
			'emailObj' 		=> $this
		);

		return xoo_wl_helper()->get_template( $this->template, $args, '', true );
	}

}

return new Xoo_Wl_Admin_Notify_Email();