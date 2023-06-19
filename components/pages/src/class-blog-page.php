<?php namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Pages;

defined( 'ABSPATH' ) || exit;

/**
 * Страница "Пост"
 */
class Blog_Page extends Abstract_Page {
	protected function enqueue_styles() {
		wp_enqueue_style( "{$this->identifier}-blog" );
	}

	protected function enqueue_scripts() {
		wp_enqueue_script( "{$this->identifier}-blog" );
		wp_enqueue_script("{$this->identifier}-main");
	}
}
