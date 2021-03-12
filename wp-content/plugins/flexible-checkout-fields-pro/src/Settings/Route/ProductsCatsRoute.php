<?php
/**
 * .
 *
 * @package WPDesk\FPF\Pro
 */

namespace WPDesk\FCF\Pro\Settings\Route;

use WPDesk\FCF\Free\Settings\Route\RouteAbstract;
use WPDesk\FCF\Free\Settings\Route\RouteInterface;

/**
 * Supports settings for REST API route.
 */
class ProductsCatsRoute extends RouteAbstract implements RouteInterface {

	const REST_API_ROUTE = 'products-cats';

	/**
	 * Returns route of REST API endpoint.
	 *
	 * @return string Route name.
	 */
	public function get_endpoint_route(): string {
		return self::REST_API_ROUTE;
	}

	/**
	 * Returns data to be returned for endpoint.
	 *
	 * @param array $params Params for endpoint.
	 *
	 * @return mixed Response data.
	 *
	 * @throws \Exception .
	 */
	public function get_endpoint_response( array $params ) {
		$cats = get_terms(
			[
				'taxonomy' => 'product_cat',
				'orderby'  => 'name',
				'order'    => 'ASC',
			]
		);

		$values = [];
		foreach ( $cats as $cat ) {
			$values[ $cat->term_id ] = sprintf( '%s (#%d)', $cat->name, $cat->term_id );
		}
		return $values;
	}
}
