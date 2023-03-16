<?php
/**
 * Class Woocommerce_Cart
 *
 * Доработки корзины.
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Woocommerce
 */

// phpcs:disable Squiz.Commenting.FunctionComment.SpacingAfterParamType

declare( strict_types=1 );

namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Woocommerce;

/**
 * Class Woocommerce_Cart
 *
 * Доработки корзины.
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Woocommerce
 */
final class Woocommerce_Cart {
	/**
	 * Woocommerce_Cart constructor.
	 */
	public function __construct() {
		add_filter( 'woocommerce_cart_item_name', array( $this, 'prepare_product_name' ), 10, 2 );
	}

	/**
	 * Подготовить наименование товара для отображения на страницах оформления заказа.
	 *
	 * @param string $product_item_name Product item name.
	 * @param array $product_item Product item.
	 *
	 * @return string
	 */
	public function prepare_product_name( string $product_item_name, array $product_item ): string {
		$product      = $product_item['data'];
		$product_name = $product->get_name();

		$position = strpos( $product_name, '[' );
		if ( false === $position ) {
			$title1 = $product_name;
			$title2 = '';
		} else {
			$title1 = substr( $product_name, 0, $position - 1 );
			$title2 = substr( $product_name, $position + 1, - 1 );
		}

		return str_replace(
			$product_name,
			sprintf(
				'<p class="name">%1$s</p><p class="desc">%2$s</p>',
				$title1,
				is_cart() ? $title2 : ''
			),
			$product_item_name
		);
	}
}
