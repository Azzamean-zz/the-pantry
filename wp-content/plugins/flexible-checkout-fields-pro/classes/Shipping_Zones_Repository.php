<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Shipping_Zones_Repository {

	private static $flexible_shipping_methods = array(
		'paczkomaty_shipping_method',
		'polecony_paczkomaty_shipping_method',
		'enadawca',
		'paczka_w_ruchu',
		'dhl',
		'dpd',
		'furgonetka'
	);

	/**
	 * Get active shipping methods
	 *
	 * @return array [ 'id' => 'local_pickup:22',  'title' => 'Local pickup', 'flexible_shipping_methods' => [ 'flexible_shipping_15_9' => 'FS: DPD UK'... ]]
	 */
	public static function get_active_shipping_methods() {
		$active_methods   = array();
		$flexible_shipping_methods = [
			'flexible_shipping_methods' => self::flexible_shipping_methods()
		];
		$no_shipping_zone_methods = [
			'no_shipping_zone_methods' => self::no_shipping_zone_methods(),
		];
		foreach( self::get_shipping_zones() as $zones => $zone ){
			foreach ( $zone['shipping_methods'] as $method ) {
				if ( isset( $method->enabled ) && 'yes' === $method->enabled ) {
					if ( 0 !== $method->instance_id ) {
						$active_methods[] = [
							'id' => $method->id . ':' . $method->instance_id,
							'title' => $method->instance_settings['title'],
						];
						if ( $method->id === 'flexible_shipping' ) {
							$active_methods = array_merge( $active_methods, $flexible_shipping_methods );
						}
						if( ! empty( $no_shipping_zone_methods ) ) {
							$active_methods = array_merge( $active_methods, $no_shipping_zone_methods );
						}
					}
				}
			}
		}

		return $active_methods;
	}

	/**
	 * Get list of WooCommerce Shipping Zones
	 *
	 * @return array available shipping zones
	 */
	private static function get_shipping_zones() {
		if ( class_exists( WC_Shipping_Zones::class ) ) {
			if ( ! empty( WC_Shipping_Zones::get_zones()) ) {
				return WC_Shipping_Zones::get_zones();
			}
		}

		return array();
	}

	/**
	 * Gets the shipping methods with no shipping zone
	 *
	 * @return array active methods without shipping zone
	 */
	public static function get_active_shipping_methods_with_no_zone() {
		$shipping_methods = WC()->shipping->load_shipping_methods();
		$active_no_zone_methods = array();
		$shipping_methods_no_zone = array();
		foreach ( $shipping_methods as $id => $shipping_method ) {
			$zone_settings = false;
			foreach ( $shipping_method->supports as $supports ) {
				if ( in_array( $supports, [ 'flexible-shipping', 'shipping-zones' ] ) ) {
					$zone_settings = true;
				}
			}
			if ( ! $zone_settings ) {
				if ( ! in_array( $shipping_method->id, self::$flexible_shipping_methods ) ) {
					if ( isset( $shipping_method->id ) ) {
						$shipping_methods_no_zone[ $id ] = $shipping_method;
					}

					foreach ( $shipping_methods_no_zone as $no_zone => $method ) {
						$active_no_zone_methods[ $shipping_method->id ] = $shipping_method->title;
					}
				}
			}
		}

		return $active_no_zone_methods;
	}

	/**
	 * Flexible Shipping Methods
	 *
	 * @return array a list of flexible shipping methods [ 'flexible_shipping_15_9' => 'FS: DPD UK' ]
	 */
	private static function flexible_shipping_methods() {
		$methods = array();
		if ( count( self::get_flexible_shipping_methods() ) > 0 ) {
			foreach( self::get_flexible_shipping_methods() as $shipping_method => $method ) {
				$methods[] = [
					'id' => $shipping_method,
					'title' => $method,
				];
			}
		}

		return $methods;
	}

	/**
	 * No Shipping Zone Methods
	 *
	 * @return array a list of flexible shipping methods [ 'flexible_shipping_fedex' => 'FedEx' ]
	 */
	private static function no_shipping_zone_methods() {
		$methods = array();
		if ( count( self::get_active_shipping_methods_with_no_zone() ) > 0 ) {
			foreach( self::get_active_shipping_methods_with_no_zone() as $no_shipping_zone_method => $shipping_method ) {
				$methods[] = [
					'id' => $no_shipping_zone_method,
					'title' => $shipping_method,
				];
			}
		}

		return $methods;
	}

	/**
	 * Gets the Flexible Shipping shipping methods
	 *
	 * @return array Flexible Shipping Methods
	 */
	public static function get_flexible_shipping_methods() {
		$shipping_methods_data = array();
		$flexible_shipping_methods = array();
		$shipping_zones = self::get_shipping_zones();
		$worldwide = new WC_Shipping_Zone();
		$shipping_zones[0] = $worldwide->get_data();
		$shipping_zones[0]['shipping_methods'] = $worldwide->get_shipping_methods();
		foreach ( $shipping_zones as $shipping_zone_key => $zone ) {
			if ( isset( $zone['shipping_methods'] ) ) {
				foreach ( $zone['shipping_methods'] as $shipping_method ) {
					if ( $shipping_method->id === 'flexible_shipping' ) {
						$shipping_methods_data[ $shipping_method->shipping_methods_option ] = get_option( $shipping_method->shipping_methods_option, array() );
					}
				}
			}
		}
		foreach ( $shipping_methods_data as $method_data => $names ) {
			foreach ($names as $name => $method) {
				if ( isset( $method['id_for_shipping'] ) ) {
					$flexible_shipping_methods[ $method['id_for_shipping'] ] = 'Flexible Shipping: ' . $method['method_title'];
				}
			}
		}
		return $flexible_shipping_methods;
	}

	/**
	 * Get list of WooCommerce Shipping Zones by Name
	 *
	 * @return array ['2' => 'PL', '4' => 'USA']
	 */
	public static function get_shipping_zones_by_name() {
		$shipping_zones = array();
		foreach( self::get_shipping_zones() as $zones => $zone ){
			$shipping_zones[ $zone['zone_id'] ] = $zone['zone_name'];
		}

		return $shipping_zones;
	}

	/**
	 * Get Shipping methods by given id
	 *
	 * @param $zone_id
	 *
	 * @return array of available shipping methods
	 */
	private static function get_list_of_shipping_methods_by_zone( $zone_id = null ) {
		$methods = array();
		foreach( self::get_shipping_zones() as $shipping_methods => $method ) {
			if ( $method['zone_id'] === $zone_id ) {
				$methods[ $zone_id ] = $method['shipping_methods'];
			}
		}

		return $methods;
	}

	/**
	 * Get list of available shipping methods name
	 *
	 * @param $zone_id
	 *
	 * @return array available shipping methods name [  'flexible_shipping' => string 'Flexible Shipping',  'free_shipping' => string 'Free shipping' ]
	 */
	public static function get_list_of_shipping_methods_name_by_zone( $zone_id = null ) {
		$shipping_methods = self::get_list_of_shipping_methods_by_zone( $zone_id );
		$available_shipping_methods = array();
		if ( ! empty($shipping_methods) ) {
			foreach ($shipping_methods as $methods) {
				foreach($methods as $method) {
					if ( isset( $method->id ) ) {
						$available_shipping_methods[ $method->id . ':' . $method->instance_id ] = $method->title;
					}
				}
			}
		}

		return $available_shipping_methods;
	}
}
