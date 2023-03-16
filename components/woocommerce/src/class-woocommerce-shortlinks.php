<?php
/**
 * Class Woocommerce_Shortlinks
 *
 * Короткие ссылки для WooCommerce.
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Woocommerce
 */

namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Woocommerce;

use WC_Order;
use WP;

defined( 'ABSPATH' ) || exit;

/**
 * Class Woocommerce_Shortlinks
 *
 * Короткие ссылки для WooCommerce.
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Woocommerce
 */
final class Woocommerce_Shortlinks {
	/**
	 * Woocommerce_Shortlinks constructor.
	 */
	public function __construct() {
		add_action(
			'woocommerce_admin_order_data_after_order_details',
			array( $this, 'display_shortlink_in_admin' ),
			- PHP_INT_MAX
		);

		add_action( 'parse_request', array( $this, 'process_shortlink' ), PHP_INT_MAX );
	}

	/**
	 * Обработка коротких ссылок.
	 *
	 * @param WP $request WP.
	 *
	 * @return void
	 */
	public function process_shortlink( WP $request ) {
		// Ссылка на заказ.
		if ( preg_match( '/^(wc_order_[a-zA-Z0-9]{13})$/', $request->request, $matches ) ) {
			/* https://site.url/checkout/order-received/41049/?key=wc_order_35ZSnRnoP0124 */
			$order_id = wc_get_order_id_by_order_key( $matches[1] );

			if ( $order_id ) {
				$request->query_vars = array(
					'order-received' => $order_id,
					'pagename'       => 'checkout',
				);

				$_GET['key'] = $matches[1];
			}
		} elseif ( preg_match( '/^pay\/(wc_order_[a-zA-Z0-9]{13})$/', $request->request, $matches ) ) {
			/* https://site.url/checkout/order-pay/41033/?pay_for_order=true&key=wc_order_Mh9IhOpw3ccPM */
			$order_id = wc_get_order_id_by_order_key( $matches[1] );

			if ( $order_id ) {
				$request->query_vars   = array(
					'order-pay' => $order_id,
					'pagename'  => 'checkout',
				);
				$_GET['pay_for_order'] = true;
				$_GET['key']           = $matches[1];
			}
		}
	}

	/**
	 * Показать короткую ссылку на заказ на странице заказа в админке.
	 *
	 * @param WC_Order $order Order.
	 *
	 * @return void
	 */
	public function display_shortlink_in_admin( WC_Order $order ) {
		?>
		<p class="form-field form-field-wide">
			<a href="<?php echo esc_url( site_url( $order->get_order_key() ) ); ?>" target="_blank">
				<strong><?php esc_html_e( 'Короткая ссылка на заказ', 'tehnokrat' ); ?></strong>
			</a>
		</p>
		<?php if ( $order->needs_payment() ) : ?>
			<p class="form-field form-field-wide">
				<a href="<?php echo esc_url( site_url( 'pay/' . $order->get_order_key() ) ); ?>" target="_blank">
					<strong><?php esc_html_e( 'Короткая ссылка на страницу оплаты', 'tehnokrat' ); ?></strong>
				</a>
			</p>
		<?php endif; ?>
		<?php
	}
}
