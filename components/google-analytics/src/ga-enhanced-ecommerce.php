<?php namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Google_Analytics;

use WC_Order;

defined( 'ABSPATH' ) || exit;

class GA_Enhanced_Ecommerce {
	/**
	 * GA_Enhanced_Ecommerce constructor.
	 */
	public function __construct() {
		add_filter( 'woocommerce_add_to_cart_fragments', [ $this, 'set_dynamic_data' ] );
		add_action( 'wp_footer', [ $this, 'output' ] );
	}

	public function set_dynamic_data( $fragments ) {
		ob_start();

		$this->dynamic_data();

		$fragments['#ga-ec-dynamic-data'] = ob_get_clean();

		return $fragments;
	}

	public function output() {
		$this->any_page();

		if ( is_cart() ) {
			$this->cart();
		} elseif ( is_wc_endpoint_url( 'order-received' ) ) {
			$this->order_received();
		} elseif ( is_checkout() ) {
			$this->checkout();
		}
	}

	protected function dynamic_data() {
		global $wp;

		if ( empty( $wp->query_vars['order-received'] ) ) {
			$order_id = '';
			$revenue  = '';
			$products = $this->get_products_from_cart();
		} else {
			$order = wc_get_order( $wp->query_vars['order-received'] );

			$order_id = $order->get_id();
			$revenue  = $order->get_total();
			$products = $this->get_products_from_order( $order );
		}

		require_once 'templates/ga-ec-dynamic-data.php';
	}

	protected function any_page() {
		$this->dynamic_data();

		require_once 'templates/ga-ec-goto-cart.php';
	}

	protected function cart() {
		require_once 'templates/ga-ec-checkout.php';
	}

	protected function checkout() {
		require_once 'templates/ga-ec-place-order.php';
	}

	protected function order_received() {
		require_once 'templates/ga-ec-order-received.php';
	}

	/**
	 * @return string
	 */
	protected function get_products_from_cart() {
		$products = [];

		foreach ( WC()->cart->get_cart_contents() as $item ) {
			$product = $item['data'];

			$products[] = sprintf(
				"{
					'id': %s,
					'name': '%s',
					'price': %s,
					'quantity': %s
				}",
				$product->get_id(),
				trim( preg_replace( '/\[.*\]/', '', $product->get_title() ) ),
				$product->get_price(),
				$item['quantity']
			);
		}
		$products = implode( ',', $products );

		return $products;
	}

	/**
	 * @param WC_Order $order
	 *
	 * @return string
	 */
	protected function get_products_from_order( $order ) {
		$products = [];

		foreach ( $order->get_items() as $item ) {
			$product = $item->get_data();

			$products[] = sprintf(
				"{
					'id': %s,
					'name': '%s',
					'price': %s,
					'quantity': %s
				}",
				$product["product_id"],
				trim( preg_replace( '/\[.*\]/', '', $product["name"] ) ),
				$product["total"] / $product["quantity"],
				$product["quantity"]
			);
		}
		$products = implode( ',', $products );

		return $products;
	}
}
