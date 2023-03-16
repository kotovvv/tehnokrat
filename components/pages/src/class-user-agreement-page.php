<?php namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Pages;

defined( 'ABSPATH' ) || exit;

class User_Agreement_Page extends Abstract_Page {
	protected function enqueue_styles() {
		wp_enqueue_style( "{$this->identifier}-text" );
	}

	protected function enqueue_scripts() {
		wp_enqueue_script( "{$this->identifier}-match-height" );
		wp_enqueue_script( "{$this->identifier}-readmore" );
		wp_enqueue_script( "{$this->identifier}-script" );
	}
}
