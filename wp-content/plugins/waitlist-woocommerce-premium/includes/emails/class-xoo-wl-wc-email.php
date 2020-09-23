<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xoo_Uv_Wc_Email extends WC_Email{

	public function __construct() {
		$this->id             = 'xoo_uv_verification';
		$this->title          = __( 'Email Verification', 'woocommerce' );
		$this->description    = __( 'Verification email is sent on new customer registration.', 'woocommerce' );

		// Call parent constructor.
		parent::__construct();

		// Other settings.
		$this->content_html_args = array(
			'email_heading'      	=> $this->get_heading(),
			'additional_content' 	=> $this->get_additional_content(),
			'email' 				=> $this
		);

	}

	public function get_default_heading(){
		return __( 'Welcome to {site_title}', 'woocommerce' );
	}


	public function get_content_html(){
		return xoo_uv_helper()->get_template( 'xoo-uv-wc-verify-email.php', $this->content_html_args, '', true );
	}

	public function get_plain_html(){
		return xoo_uv_helper()->get_template( 'xoo-uv-wc-verify-email.php', $this->content_html_args, '', true );
	}

	public function get_default_subject(){
		return esc_attr( xoo_uv_helper()->get_email_option( 'm-subject-txt' ) );
	}

	public function trigger( $user_id, $args = array() ){
		$this->content_html_args = array_merge( $this->content_html_args, $args );
		$user = new WP_User( $user_id );
		$this->recipient = $user->user_email;
		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
	}
}

return new Xoo_Uv_Wc_Email();