<?php namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Woocommerce;

defined( 'ABSPATH' ) || exit;

/**
 * Class Woocommerce_Tools
 *
 * Дополнительные инструменты отладки WooCommerce
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Woocommerce
 */
final class Woocommerce_Tools {
	/**
	 * Woocommerce_Tools constructor.
	 */
	public function __construct() {
		add_filter( 'woocommerce_debug_tools', [ $this, 'add_debug_tools' ] );
	}

	public function add_debug_tools( $tools ) {
		$tools['move_to_pending']      = [
			'name'     => 'Переместить товары в "На утверждении"',
			'button'   => 'Переместить',
			'callback' => [ $this, 'move_to_pending' ],
		];
		$tools['set_allow_backorders'] = [
			'name'     => 'Разрешить предзаказ для всех товаров.',
			'button'   => 'Разрешить',
			'callback' => [ $this, 'set_allow_backorders' ],
		];

		return $tools;
	}

	/**
	 * Изменяю статус всех товаров на "На утверждении"
	 */
	public function move_to_pending() {
		global $wpdb;

		$result = $wpdb->update(
			$wpdb->posts,
			[ 'post_status' => 'pending' ],
			[ 'post_type' => 'product', 'post_status' => 'publish' ]
		);

		return $result . ' товаров было перемещено в "На утверждении"';
	}

	public function set_allow_backorders() {
		global $wpdb;

		$result = $wpdb->update( $wpdb->postmeta, [ 'meta_value' => 'yes' ], [ 'meta_key' => '_backorders' ] );

		return "Предзаказ разрешен для {$result} товаров.";
	}
}
