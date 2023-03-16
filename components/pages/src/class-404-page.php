<?php namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Pages;

defined( 'ABSPATH' ) || exit;

/**
 * Страница 404
 */
class Page_404 extends Abstract_Page {
	protected function enqueue_styles() {
		wp_enqueue_style( "{$this->identifier}-style" );
	}

	protected function enqueue_scripts() {}
}
