<?php
/**
 * Class Woocommerce_ThankYou
 *
 * Доработки страницы 'Заказ принят'.
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Woocommerce
 */

// phpcs:disable Squiz.Commenting.FunctionComment.SpacingAfterParamType

declare( strict_types=1 );

namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Woocommerce;

/**
 * Class Woocommerce_ThankYou
 *
 * Доработки страницы 'Заказ принят'.
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Woocommerce
 */
final class Woocommerce_ThankYou {
	/**
	 * Woocommerce_Cart constructor.
	 */
	public function __construct() {
		add_filter( 'woocommerce_get_order_item_totals', array( $this, 'remove_subtotal' ) );
		add_action(
			'woocommerce_thankyou_cod',
			array( $this, 'remove_payment_method_description' ),
			- PHP_INT_MAX
		);
	}

	/**
	 * Remove subtotal row from totals.
	 *
	 * @param array $total_rows Total rows.
	 *
	 * @return array
	 */
	public function remove_subtotal( array $total_rows ): array {
		if ( isset( $total_rows['cart_subtotal'] ) ) {
			unset( $total_rows['cart_subtotal'] );
		}

		return $total_rows;
	}

	/**
	 * Remove second payment method description.
	 *
	 * @return void
	 */
	public function remove_payment_method_description() {
		remove_all_actions( current_action() );
	}
}
