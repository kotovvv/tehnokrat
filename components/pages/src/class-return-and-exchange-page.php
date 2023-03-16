<?php namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Pages;

defined( 'ABSPATH' ) || exit;

/**
 * Страница "Условия обмена и возврата товаров"
 */
class Return_And_Exchange_Page extends Abstract_Page {
	protected function enqueue_styles() {
		wp_enqueue_style( "{$this->identifier}-exchange.css" );
	}

	protected function enqueue_scripts() {
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( "{$this->identifier}-readmore" );
		wp_enqueue_script( "{$this->identifier}-trade-in" );
	}
}
