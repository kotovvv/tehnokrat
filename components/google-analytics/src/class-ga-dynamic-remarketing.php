<?php
/**
 * Class GA_Dynamic_Remarketing
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Google_Analytics
 */

namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Google_Analytics;

use BytePerfect\WordPress\Theme\Tehnokrat\Tehnokrat;

defined( 'ABSPATH' ) || exit;

/**
 * Class GA_Dynamic_Remarketing
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Google_Analytics
 */
class GA_Dynamic_Remarketing {
	/**
	 * Параметр "Идентификатор".
	 *
	 * @var $ecomm_itemid
	 */
	protected $ecomm_itemid = array();

	/**
	 * Параметр "Тип страницы".
	 *
	 * @var $ecomm_pagetype
	 */
	protected $ecomm_pagetype = '';

	/**
	 * Параметр "Общая ценность".
	 *
	 * @var $ecomm_totalvalue
	 */
	protected $ecomm_totalvalue = '';

	/**
	 * GA_Dynamic_Remarketing constructor.
	 */
	public function __construct() {
		add_action( 'wp_head', array( $this, 'output' ), -PHP_INT_MAX );
	}

	/**
	 * Вывод данных на страницу.
	 */
	public function output() {
		if ( is_front_page() ) {
			$data = $this->get_front_page_data();
		} elseif ( is_product_category() ) {
			$data = $this->get_product_category_page_data();
		} elseif ( is_product() ) {
			$data = $this->get_product_page_data();
		} elseif ( is_wc_endpoint_url( 'order-received' ) ) {
			$data = $this->get_thank_you_page_data();
		} elseif ( is_cart() || is_checkout() ) {
			$data = $this->get_checkout_page_data();
		} else {
			$data = array(
				'',      // ecomm_prodid     - Идентификатор товара или товаров, представленных на странице.
				'other', // ecomm_pagetype   - Тип страницы, на которой установлен тег.
				'',      // ecomm_totalvalue - Итоговая сумма значений одного или нескольких товаров.
			);
		}

		if ( $data ) {
			list( $ecomm_itemid, $ecomm_pagetype, $ecomm_totalvalue ) = array_map( 'wp_json_encode', $data );

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo <<<EOT
<script type="text/javascript">
window.dataLayer = window.dataLayer || [];
dataLayer.push({
ecomm_itemid: {$ecomm_itemid},
ecomm_pagetype: {$ecomm_pagetype},
ecomm_totalvalue: {$ecomm_totalvalue}
});
</script>
EOT;
		}
	}

	/**
	 * Данные для главной страницы.
	 *
	 * @return string[]
	 */
	protected function get_front_page_data() {
		return array( '', 'home', '' );
	}

	/**
	 * Данные для страницы категории товаров.
	 *
	 * @return array
	 */
	protected function get_product_category_page_data() {
		/* @global Tehnokrat $tehnokrat */
		global $tehnokrat;

		$item_id     = array();
		$page_type   = 'catalog';
		$total_value = 0;

		$show_only_in_stock = empty( $_COOKIE['onlyInStockProducts'] );

		$products = $tehnokrat->get_category_products();
		foreach ( $products as $product ) {
			foreach ( $product['variations'] as $variation ) {
				if ( ! $show_only_in_stock || $variation['in_stock'] ) {
					$item_id[]    = $variation['id'];
					$total_value += $variation['priceUAH'];

					break;
				}
			}
		}

		return array( $item_id, $page_type, $total_value );
	}

	/**
	 * Данные для страницы товаров.
	 *
	 * @return array
	 */
	protected function get_product_page_data() {
		$product = wc_get_product( get_the_ID() );

		return array( $product->get_id(), 'offerdetail', $product->get_price() );
	}

	/**
	 * Данные для страницы "Спасибо за покупку".
	 *
	 * @return array
	 */
	protected function get_thank_you_page_data() {
		global $wp;

		$order       = wc_get_order( $wp->query_vars['order-received'] );
		if ( ! $wp->query_vars['order-received'] ) {
			return null;
		}

		$order_items = $order->get_items();

		$product_ids = array();
		foreach ( $order_items as $order_item ) {
			$data = $order_item->get_data();
			$product_ids[] = $data['product_id'];
		}

		return array( $product_ids, 'conversion', $order->get_total() );
	}

	/**
	 * Данные для страниц "Корзина" и "Оформление заказа".
	 *
	 * @return array
	 */
	protected function get_checkout_page_data() {
		return array(
			array_values( wp_list_pluck( WC()->cart->get_cart_contents(), 'product_id' ) ),
			'conversionintent',
			WC()->cart->get_totals()['total'],
		);
	}
}
