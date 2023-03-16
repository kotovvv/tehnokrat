<?php namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Pages;

defined( 'ABSPATH' ) || exit;

/**
 * Страница "Способы доставки и оплаты товаров"
 */
class Shipping_And_Payment_Page extends Abstract_Page {
	protected function enqueue_styles() {
		wp_enqueue_style( "{$this->identifier}-del.css" );
	}

	protected function enqueue_scripts() {
		wp_enqueue_script( "{$this->identifier}-readmore" );
		wp_enqueue_script( "{$this->identifier}-script" );
	}
}
