<?php namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Pages;

use WP_Query;

defined( 'ABSPATH' ) || exit;

class Page_Factory {
	static public function initialize() {
		add_action( 'pre_get_posts', [ __CLASS__, 'create_page' ], - PHP_INT_MAX );
	}

	/**
	 * @param WP_Query $wp_query
	 *
	 * @return Abstract_Page|null
	 */
	public static function create_page( WP_Query $wp_query ) {
		if ( ! $wp_query->is_main_query() ) {
			return null;
		}

		/**
		 * Статические страницы
		 */
		// Главная страница
		if ( self::is_page( 'home', $wp_query ) ) {
			return new Home_Page();
		}

		// Страница "Программа обмена «MAC TRADE-IN»"
		if ( self::is_page( 'trade-in', $wp_query ) ) {
			return new Trade_In_Page();
		}

		// Страница "Адреса выдачи заказов"
		if ( self::is_page( 'contact', $wp_query ) ) {
			return new Contact_Page();
		}

		// Страница "Контакты и режим работы"
		if ( self::is_page( 'contacts', $wp_query ) ) {
			return new Contacts_Page();
		}

		// Страница "Условия гарантийного обслуживания"
		if ( self::is_page( 'guarantee', $wp_query ) ) {
			return new Guarantee_Page();
		}

		// Страница "Отзывы покупателей"
		if ( self::is_page( 'reviews', $wp_query ) ) {
			return new Reviews_Page();
		}

		// Страница "Способы доставки и оплаты товаров"
		if ( self::is_page( 'shipping-and-payment', $wp_query ) ) {
			return new Shipping_And_Payment_Page();
		}

		// Страница "Условия обмена и возврата товаров"
		if ( self::is_page( 'return-and-exchange', $wp_query ) ) {
			return new Return_And_Exchange_Page();
		}

		// Страница "Обмен и ремонт техники Apple"
		if ( self::is_page( 'exchange', $wp_query ) ) {
			return new Exchange_Page();
		}

		// Страница "Политика конфиденциальности"
		if ( self::is_page( 'privacy-policy', $wp_query ) ) {
			return new Privacy_Policy_Page();
		}

		// Страница "Пользовательское соглашение"
		if ( self::is_page( 'user-agreement', $wp_query ) ) {
			return new User_Agreement_Page();
		}

		/**
		 * WooCommerce
		 */
		// Страница одного товара
		if ( $wp_query->is_single() && 'product' == $wp_query->get( 'post_type' ) ) {
			return new Product_Page();
		}

		// Страница "Корзина"
		if ( self::is_page( 'cart', $wp_query ) ) {
			return new Cart_Page();
		}

		// Страница "Оформление заказа"
		if ( self::is_page( 'checkout', $wp_query ) ) {
			return new Checkout_Page();
		}

		// Страница "Личный кабинет".
		if ( self::is_page( 'my-account', $wp_query ) ) {
			return new My_Account_Page();
		}

		/**
		 * Динамические страницы
		 */
		// Страница списка товаров/категории
		if ( $wp_query->is_archive() && $wp_query->is_tax() && $wp_query->get( 'product_cat' ) ) {
			return new Archive_Page();
		}

		// Страница "Blog"
		if ( is_home() ) {
			return new Blog_Page();
		}

		// Страница "Post"
		if ( $wp_query->is_single() && $wp_query->get( 'day' ) && $wp_query->get( 'monthnum' ) && $wp_query->get( 'year' ) ) {
			return new Single_Post_Page();
		}

		return new Page_404();
	}

	private static function is_page( $page_name, $wp_query ) {
		switch ( $page_name ) {
			case 'home':
				$page_id = 16174;
				break;
			case 'trade-in':
				$page_id = 3832;
				break;
			case 'contact':
				$page_id = 3839;
				break;
			case 'contacts':
				$page_id = 12928;
				break;
			case 'guarantee':
				$page_id = 3843;
				break;
			case 'reviews':
				$page_id = 4086;
				break;
			case 'shipping-and-payment':
				$page_id = 22560;
				break;
			case 'return-and-exchange':
				$page_id = 22567;
				break;
			case 'exchange':
				$page_id = 13708;
				break;
			case 'privacy-policy':
				$page_id = 34202;
				break;
			case 'user-agreement':
				$page_id = 34205;
				break;
			case 'cart':
				$page_id = 5;
				break;
			case 'checkout':
				$page_id = 6;
				break;
			case 'my-account':
				$page_id = 7;
				break;
			default:
				$page_id = null;
		}

		return (
			$wp_query->is_page()
			&&
			intval( $wp_query->queried_object_id ?? $wp_query->get( 'page_id' ) ) === $page_id
		);
	}
}
