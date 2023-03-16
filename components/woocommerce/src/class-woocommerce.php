<?php
/**
 * Class Woocommerce.
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Woocommerce
 */

declare( strict_types=1 );

namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Woocommerce;

/**
 * Class Woocommerce.
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Woocommerce
 */
final class Woocommerce {
	/**
	 * Woocommerce constructor.
	 */
	public function __construct() {
		// Глобально разрешаю предзаказ.
		add_filter( 'woocommerce_product_backorders_allowed', '__return_true' );

		if ( is_admin() ) {
			// Подключаю дополнительные инструменты отладки WooCommerce.
			new Woocommerce_Tools();
		}

		// Подключаю короткие ссылки WooCommerce.
		new Woocommerce_Shortlinks();

		// Подключаю возможность оплаты по ссылке.
		new Woocommerce_Pay_For_Order();

		// Подключаю доработки корзины.
		new Woocommerce_Cart();

		// Подключаю доработки страницы оформления заказа.
		new Woocommerce_Checkout();

		// Подключаю доработки страницы 'Заказ принят'.
		new Woocommerce_ThankYou();

		// Подключаю доработки писем.
		new Woocommerce_Emails();

		// Подключаю класс для подготовки списка товаров категории.
		new Woocommerce_Category_Products();

		// Управление различными уведомлениями WooCommerce.
		new Woocommerce_Notifications();
	}
}
