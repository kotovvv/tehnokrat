<?php namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Pages;

defined( 'ABSPATH' ) || exit;

/**
 * Страница "Личный кабинет"
 */
class My_Account_Page extends Abstract_Page {
	protected function setup_actions() {
		parent::setup_actions();

		add_filter( 'woocommerce_account_menu_items', array( $this, 'filter_account_menu_items' ) );
	}

	protected function enqueue_styles() {
		wp_enqueue_style( "{$this->identifier}-my-account" );
	}

	protected function enqueue_scripts() {
		wp_enqueue_script( "{$this->identifier}-readmore" );
		wp_enqueue_script( "{$this->identifier}-main" );
	}

	public function filter_account_menu_items( $items ) {
		$items['edit-address'] = __( 'Address', 'woocommerce' );

		unset( $items['downloads'] );

		return $items;
	}
}
