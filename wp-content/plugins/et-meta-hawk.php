<?php
/**
 * Plugin name: Event Tickets Meta Hawk
 * Description: Lets site administrators soar over attendee meta data supplied by attendees then swoop down, hawk-like, to change it. Proof of concept targeting the WooCommerce provider.
 * Version:     2016-03-21
 * Author:      Barry Hughes
 *
 *     Experiment in making attendee meta editable via the attendee screen.
 *     Well worth bearing in mind that:
 *
 *         - It currently won't work for RSVP or providers besides WooCommerce
 *         - For checkbox type fields, if you try adding a new value that hasn't
 *           actually been defined, it will be rejected
 *         - Click on the field to edit, then hit Enter to save.
 *         - Only works for metadata, not for purchaser data.
 *         - Note: save file as ANSI encoding, not utf-8!
 *
 *                     /---\
 *                    < O   \
 *       ______________\    /______________
 *        ==============OOOO==============
 *         --------------uu--------------
 *                    _/    \_
 *
 */

class ET_Meta_Hawk {
	static function begin() {
		add_action( 'wp_ajax_update_attendee_meta_field', array( __CLASS__, 'update_meta' ) );
		add_action( 'admin_footer', array( __CLASS__, 'setup_meta_editing' ), 100 );
	}

	static function update_meta() {
		if ( ! wp_verify_nonce( @$_POST['check'], 'attendee_meta_live_edit' ) ) return;

		$field     = self::get_clean_field_name( @$_POST['field'] );
		$attendee  = self::get_clean_attendee_id( @$_POST['attendee'] );
		$ticket    = self::get_related_ticket_id( $attendee );
		$existing  = Tribe__Tickets_Plus__Main::instance()->meta()->get_meta_fields_by_ticket( $ticket );
		$value     = filter_var( @$_POST['value'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH );
		$meta_data = get_post_meta( $attendee, Tribe__Tickets_Plus__Meta::META_KEY, true );

		if ( ! $attendee || ! $field ) return;

		foreach ( $existing as $supported_field ) {
			if ( $supported_field->slug !== $field ) continue;

			// A field that allows multiple options to be selected?
			if ( $supported_field->type === 'checkbox' ) {
				$values = explode( ',', $value );
				$values = array_map( 'trim', $values );

				foreach ( $values as $value )
					$meta_data[ $field . '_' . strtolower( $value ) ] = $value;
			}
			// Normal field?
			else $meta_data[ $field ] = $value;
		}

		update_post_meta( $attendee, Tribe__Tickets_Plus__Meta::META_KEY, $meta_data );
	}

	/**
	 * Given the attendee identifier (expected to look like "123|provider_class")
	 * return the ID portion as an int.
	 *
	 * @param  string $attendee_string
	 * @return int
	 */
	static function get_clean_attendee_id( $attendee_string ) {
		$field = explode( '|', $attendee_string );
		if ( count( $field ) === 2 ) return absint( $field[0] );
		return 0;
	}

	/**
	 * Given the meta field identifier (actually drawn from a CSS class), return
	 * the meta field name segment only.
	 *
	 * @param  string $field_name_string
	 * @return string
	 */
	static function get_clean_field_name( $field_name_string ) {
		$field_name_prefix = 'event-tickets-meta-data_';
		$chunks = explode( ' ', $field_name_string );

		foreach ( $chunks as $single_chunk ) {
			if ( 0 === strpos( $single_chunk, $field_name_prefix ) )
				return substr( $single_chunk, strlen( $field_name_prefix ) );
		}

		return '';
	}

	/**
	 * Gets the ticket ID related to the specified attendee ID.
	 *
	 * Only works for WooCommerce Tickets at present. Could certainly iterate and
	 * improve so its not tied to one provider in this way.
	 *
	 * @param  int $attendee_id
	 * @return int
	 */
	static function get_related_ticket_id( $attendee_id ) {
		return absint( get_post_meta( $attendee_id, '_tribe_wooticket_product', true ) );
	}

	static function setup_meta_editing() {
		if ( get_current_screen()->id !== 'ticket-page_page_tickets-attendees' ) return;
		$nonce = wp_create_nonce( 'attendee_meta_live_edit' );

		echo '
			<script type="text/javascript">
				jQuery( document ).ready( function( $ ) {
					var $meta_rows    = $( ".wp-list-table tr.event-tickets-meta-row" );
					var $meta_fields  = $meta_rows.find( "dd" );

					function submit_change( event ) {
						// The submission trigger is the enter/return key
						if ( event.keyCode !== 13 ) return;

						var $this = $( this );
						var field = $this.parents( "dd" ).attr( "class" );
						var id    = $this.parents( "tr" ).prev( "tr" ).find( "th.check-column" ).find( "input" ).val();
						var value = $this.val();

						$this.prop( "readonly", true );

						$.post( ajaxurl, {
							"action":   "update_attendee_meta_field",
							"check":    "' . $nonce . '",
							"attendee": id,
							"field":    field,
							"value":    value
						}, function() {
							$this.parents( "dd" ).html( value );
						} );

						event.preventDefault();
						return false;
					}

					$meta_fields.click( function() {
						var $this = $( this );

						// Already editable? Do nothing more
						if ( $this.find( "input" ).length ) return;

						$this.html( "<input type=\'text\' value=\'" + $this.html() + "\'>" );
						var $field = $this.find( "input" );
						$field.focus();

						$field.keypress( submit_change );
					} );
				} );
			</script>
		';
	}
}

ET_Meta_Hawk::begin();