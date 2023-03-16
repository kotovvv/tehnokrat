<?php namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Pages;

defined( 'ABSPATH' ) || exit;

class Contacts_Page extends Abstract_Page {
	protected function enqueue_styles() {
		wp_enqueue_style( "{$this->identifier}-contact" );
	}

	protected function enqueue_scripts() {
		wp_enqueue_script( "{$this->identifier}-maps-googleapis" );
		wp_enqueue_script( "{$this->identifier}-readmore" );
		wp_enqueue_script( "{$this->identifier}-contacts-page" );

		wp_localize_script( "{$this->identifier}-script", "{$this->identifier}_script", [
			'mapIcon' => get_template_directory_uri() . '/img/map-icon.png',
		] );
	}
}
