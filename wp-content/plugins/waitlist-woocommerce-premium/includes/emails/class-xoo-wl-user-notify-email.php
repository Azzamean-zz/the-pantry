<?php

class Xoo_Wl_User_Notify extends Xoo_Wl_Email{

	public $body_text;

	public function __construct(){

		parent::__construct();

		$this->id 			= 'user_notify';
		$this->template 	= 'emails/xoo-wl-user-notify-email.php';
		$this->subject 		= xoo_wl_helper()->get_email_option( 'un-subject' );
		$this->body_text 	= xoo_wl_helper()->get_email_option( 'un-content' );
		$this->hooks();

	}


	public function hooks(){
		add_action( 'xoo_wl_email_head', array( $this, 'inline_style' ) );
		add_action( 'xoo_wl_email_user_notify_sent', array( $this, 'on_email_sent' ), 10, 2 );
	}



	public function on_email_sent( $sent, $obj ){

		if( !$sent ) return;

		//Update sent count
		$sent_count = (int) xoo_wl_db()->get_waitlist_meta( $this->row_id, '_notified_user' );
		$sent_count++;
		xoo_wl_db()->update_waitlist_meta( $this->row_id, '_notified_user', $sent_count );
	}


	public function validation(){

		//Notify admin only once
		if( xoo_wl_helper()->get_email_option( 'un-send-once' ) === "yes" ){
			$sent_count = (int) xoo_wl_db()->get_waitlist_meta( $this->row_id, '_notified_user' );
			if( $sent_count >= 1 ){
				return false;
			}
		}
		return true;
	}


	public function get_template(){

		$args = array(
			'body_text' 	=> $this->body_text,
			'fontSize' 		=> xoo_wl_helper()->get_email_style_option( 'un-fsize' ),
			'emailObj' 		=> $this
		);

		return xoo_wl_helper()->get_template( $this->template, $args, '', true );
	}

	public function get_recipient_emails(){
		$this->recipient_emails[] = $this->row->get_email();
		return $this->recipient_emails;
	}


	public function inline_style( $emailObj ){
		if( $emailObj->id !== $this->id ) return;
		?>
		<style type="text/css">
			
		</style>
		<?php
	}

}

return new Xoo_Wl_User_Notify();