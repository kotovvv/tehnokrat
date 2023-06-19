<?php namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Pages;

defined( 'ABSPATH' ) || exit;

class Contact_Page extends Abstract_Page {
	protected function enqueue_styles() {
		wp_enqueue_style( "{$this->identifier}-contact" );
	}

	protected function enqueue_scripts() {
		wp_enqueue_script( "{$this->identifier}-maps-googleapis" );
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( "{$this->identifier}-readmore" );
		wp_enqueue_script( "{$this->identifier}-contact" );
		wp_enqueue_script("{$this->identifier}-main");

		wp_localize_script( "{$this->identifier}-script", "{$this->identifier}_script", [
			'mapIcon' => get_template_directory_uri() . '/img/map-icon.png',
		] );
	}
}
