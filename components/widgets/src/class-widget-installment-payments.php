<?php namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Widgets;

use BytePerfect\WordPress\Plaugin\WooCommerce_PrivatBank_PayParts\WC_Gateway_PPP;
use BytePerfect\WordPress\Theme\Tehnokrat\Tehnokrat;
use WC_Order_Item_Fee;

defined( 'ABSPATH' ) || exit;

class Widget_Installment_Payments {
	/**
	 * Widget_Installment_Payments constructor.
	 */
	public function __construct() {
		add_action( 'wp_ajax_create_order_in_installments', [ $this, 'create_order' ] );
		add_action( 'wp_ajax_nopriv_create_order_in_installments', [ $this, 'create_order' ] );

		add_action( 'wp_footer', [ $this, 'output' ] );
	}

	public function output() {
		/* @global Tehnokrat $tehnokrat */
		global $tehnokrat;

		if ( is_product_category() || is_product() ) {
			wp_enqueue_style(
				'widget-installment-payments',
				$tehnokrat->get_url( '/components/widgets/assets/css/widget-installment-payments.css' ),
				[],
				$tehnokrat->get_version()
			);
		}
	}

	public function create_order() {
		/* @global Tehnokrat $tehnokrat */
		global $tehnokrat;

		// проверка nonce.
		if ( ! wp_verify_nonce( wc_get_post_data_by_key( 'nonce' ), $tehnokrat->get_identifier() ) ) {
			wp_send_json_error( 'Ошибка: ' . __FUNCTION__ . '|' . __LINE__ );
		}

		$product_id      = (int) wc_get_post_data_by_key( 'product-id' );
		$username        = wc_get_post_data_by_key( 'fio' );
		$phone           = wc_get_post_data_by_key( 'tel' );
		$email           = wc_get_post_data_by_key( 'email' );
		$bank            = wc_get_post_data_by_key( 'bank' );
		$city            = wc_get_post_data_by_key( 'city' );
		$custom_city     = wc_get_post_data_by_key( 'custom-city' );
		$quantity        = (int) wc_get_post_data_by_key( 'quantity' );
		$parts_count     = wc_get_post_data_by_key( 'input_con' );
		$monthly_payment = wc_get_post_data_by_key( 'monthly-payment' );
		$total           = (float) wc_get_post_data_by_key( 'total' );

		// проверка ID товара.
		if ( ! $product_id ) {
			wp_send_json_error( 'Ошибка: ' . __FUNCTION__ . '|' . __LINE__ );
		}
		$product = wc_get_product( $product_id );
		if ( ! $product ) {
			wp_send_json_error( 'Ошибка: ' . __FUNCTION__ . '|' . __LINE__ );
		}

		if ( ! is_email( $email ) ) {
			wp_send_json_error( 'Ошибка: ' . __FUNCTION__ . '|' . __LINE__ );
		}

		// найти пользователя или создать нового.
		$user_id = email_exists( $email );
		if ( ! $user_id ) {
			$user_id = wc_create_new_customer( $email );
		}
		// проверка email.
		if ( is_wp_error( $user_id ) ) {
			wp_send_json_error( 'Ошибка: ' . __FUNCTION__ . '|' . __LINE__ );
		}

		// создать новый заказ.
		$order = wc_create_order( [ 'customer_id' => $user_id ] );
		if ( is_wp_error( $order ) ) {
			wp_send_json_error( 'Ошибка: ' . __FUNCTION__ . '|' . __LINE__ );
		}

		$address = [
			'first_name' => $username,
			'address_1'  => $custom_city ? $custom_city : $city,
			'email'      => $email,
			'phone'      => $phone,
		];
		$order->add_product( $product, $quantity );
		$order->set_address( $address, 'billing' );
		$order->set_address( $address, 'shipping' );

		if ( 'alf' === $bank ) {
			$payment_method_id = 'alfabank_payparts';
			$order->add_order_note( 'alfabank_payparts' );
		} else {
			$payment_method_id = 'privatbank_payparts';
			$order->add_order_note( 'privatbank_payparts' );
		}

		$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
		$payment_method     = isset( $available_gateways[ $payment_method_id ] ) ? $available_gateways[ $payment_method_id ] : $payment_method_id;
		$order->set_payment_method( $payment_method );

		$order->calculate_totals();

		if ( $total !== (float) $order->get_total() ) {
			$fee = new WC_Order_Item_Fee();
			$fee->set_props(
				[
					'tax_status' => '',
					'tax_class'  => 0,
					'name'       => 'Комиссия банка',
					'total'      => $total - (float) $order->get_total(),
					'order_id'   => $order->get_id(),
				]
			);
			$fee->save();
			$order->add_item( $fee );
			$order->calculate_totals();
		}

		$tehnokrat->woocommerce_checkout_order_processed( $order->get_id() );

		if ( 'alf' === $bank ) {
			$redirect_url = $order->get_checkout_order_received_url();
		} else {
			$amount = $product->get_price() * $quantity;

			$wc_gateway_ppp = new WC_Gateway_PPP();
			$redirect_url   = $wc_gateway_ppp->get_payment_url( $amount, $parts_count,
				$order->get_order_key() );
		}

		wp_send_json_success( $redirect_url );
	}
}
