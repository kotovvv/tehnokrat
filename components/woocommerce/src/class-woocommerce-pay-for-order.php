<?php
/**
 * Class Woocommerce_Pay_For_Order
 *
 * Оплата заказа по ссылке.
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Woocommerce
 */

namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Woocommerce;

use Exception;

defined( 'ABSPATH' ) || exit;

/**
 * Class Woocommerce_Pay_For_Order
 *
 * Оплата заказа по ссылке.
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Woocommerce
 */
final class Woocommerce_Pay_For_Order {
	/**
	 * Woocommerce_Pay_For_Order constructor.
	 */
	public function __construct() {
		add_filter( 'user_has_cap', array( $this, 'add_pay_for_order_cap' ) );
		add_filter( 'woocommerce_checkout_update_order_review_expired', array( $this, 'fill_cart' ) );
		add_filter( 'woocommerce_create_order', array( $this, 'get_order_id' ) );
	}

	/**
	 * Платить могут все.
	 *
	 * @param bool[] $allcaps Array of key/value pairs where keys represent a capability name
	 *                        and boolean values represent whether the user has that capability.
	 *
	 * @return array
	 */
	public function add_pay_for_order_cap( array $allcaps ): array {
		$allcaps['pay_for_order'] = true;

		return $allcaps;
	}

	/**
	 * Заполняю корзину для оплаты.
	 *
	 * @param bool $is_expired Признак просроченных данных для оплаты.
	 *
	 * @return false
	 *
	 * @throws Exception Exception.
	 */
	public function fill_cart( bool $is_expired ): bool {
		// phpcs:ignore WordPress.Security
		parse_str( $_REQUEST['post_data'], $result );

		$order_id = $this->get_order_id_from_request( $result );
		if ( $order_id ) {
			$order = wc_get_order( $order_id );

			foreach ( $order->get_items() as $item ) {
				WC()->cart->add_to_cart( $item->get_product_id(), $item->get_quantity() );
			}

			add_filter(
				'woocommerce_create_order',
				function () use ( $order_id ) {
					return $order_id;
				}
			);

			$is_expired = false;
		}

		return $is_expired;
	}

	/**
	 * Получаю номер оплачиваемого заказа.
	 *
	 * @param int|null $order_id Order ID.
	 *
	 * @return int|null
	 */
	public function get_order_id( ?int $order_id ): ?int {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$_order_id = $this->get_order_id_from_request( $_REQUEST );
		if ( $_order_id ) {
			$order_id = $_order_id;
		}

		return $order_id;
	}

	/**
	 * Получаю ID заказа из параметров запроса.
	 *
	 * @param array $args Параметры запроса.
	 *
	 * @return int|null
	 */
	protected function get_order_id_from_request( array $args ): ?int {
		if (
			isset( $args['_wp_http_referer'] )
			&&
			(
				preg_match(
					'/^\/checkout\/order-pay\/\d+\/\?pay_for_order=true&key=(wc_order_[a-zA-Z0-9]{13})$/',
					$args['_wp_http_referer'],
					$matches
				)
				||
				preg_match(
					'/^\/pay\/(wc_order_[a-zA-Z0-9]{13})\/?$/',
					$args['_wp_http_referer'],
					$matches
				)
			)
		) {
			return wc_get_order_id_by_order_key( $matches[1] );
		} else {
			return null;
		}
	}
}
