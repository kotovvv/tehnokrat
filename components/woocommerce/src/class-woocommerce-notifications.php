<?php

namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Woocommerce;

use BytePerfect\WordPress\Theme\Tehnokrat\Tehnokrat;

defined( 'ABSPATH' ) || exit;

/**
 * Class Woocommerce_Notifications
 *
 * Управление различными уведомлениями WooCommerce
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Woocommerce
 */
final class Woocommerce_Notifications {
	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_action( 'woocommerce_order_status_completed', array( $this, 'schedule_feedback_request' ) );
		add_action( 'tehnokrat_ask_for_feedback', array( $this, 'ask_for_feedback' ) );
	}

	/**
	 * Schedule feedback request.
	 *
	 * @param int $order_id Order ID.
	 *
	 * @return void
	 */
	public function schedule_feedback_request( int $order_id ): void {
		WC()->queue()->schedule_single(
			strtotime( '+3 days noon' ),
			'tehnokrat_ask_for_feedback',
			array( 'order_id' => $order_id ),
			'tehnokrat'
		);
	}

	/**
	 * Ask for feedback.
	 *
	 * @param int $order_id Order ID.
	 *
	 * @return void
	 */
	public function ask_for_feedback( int $order_id ): void {
		/**
		 * @var Tehnokrat $tehnokrat
		 */
		global $tehnokrat;

		$sms_message = $tehnokrat->get_setting( 'sms', 'sms_ask_for_feedback' );
		if ( empty ( $sms_message ) ) {
			function_exists( 'wp_log_error' ) && wp_log_error( 'sms_ask_for_feedback: Message not found.' );

			return;
		}

		$order = wc_get_order( $order_id );
		if ( empty( $order ) ) {
			function_exists( 'wp_log_error' ) && wp_log_error( "sms_ask_for_feedback: Order not found: $order_id." );

			return;
		}

		$order->add_order_note( $sms_message, true );
	}
}
