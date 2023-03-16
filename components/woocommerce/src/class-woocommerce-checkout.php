<?php
/**
 * Class Woocommerce_Checkout
 *
 * Доработки страницы оформления заказа.
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Woocommerce
 */

// phpcs:disable Squiz.Commenting.FunctionComment.SpacingAfterParamType

declare( strict_types=1 );

namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Woocommerce;

/**
 * Class Woocommerce_Checkout
 *
 * Доработки страницы оформления заказа.
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Woocommerce
 */
final class Woocommerce_Checkout {
	/**
	 * Woocommerce_Cart constructor.
	 */
	public function __construct() {
		add_filter( 'woocommerce_checkout_fields', array( $this, 'limit_order_comment_maxlength' ) );
		add_filter( 'woocommerce_checkout_required_field_notice', array( $this, 'set_required_field_notice' ), 10, 3 );
	}

	/**
	 * Ограничить количество символов в примечании.
	 *
	 * @param array $fields Fields.
	 *
	 * @return array
	 */
	public function limit_order_comment_maxlength( array $fields ): array {
		if ( isset( $fields['order']['order_comments'] ) ) {
			$fields['order']['order_comments']['maxlength'] = 150;
		}

		return $fields;
	}

	/**
	 * Сформировать сообщение об ошибке в адресе email.
	 *
	 * @param string $notice Сообщение об ошибке.
	 * @param string $field_label Метка поля.
	 * @param string $key ID поля.
	 *
	 * @SuppressWarnings("unused")
	 *
	 * @return string
	 */
	public function set_required_field_notice( string $notice, string $field_label, string $key ): string {
		if ( 'billing_email' === $key ) {
			/* translators: %s: field label */
			$notice = sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>Email</strong>' );
		}

		return $notice;
	}
}
