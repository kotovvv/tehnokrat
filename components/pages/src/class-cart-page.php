<?php namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Pages;

defined( 'ABSPATH' ) || exit;

/**
 * Страница "Корзина"
 */
class Cart_Page extends Abstract_Page {
	protected function enqueue_styles() {
		wp_enqueue_style( "{$this->identifier}-text" );
		wp_enqueue_style( "{$this->identifier}-home" );
		wp_enqueue_style( "{$this->identifier}-basket" );
	}

	protected function enqueue_scripts() {
		wp_enqueue_script( "{$this->identifier}-jcf.select" );
		wp_enqueue_script( "{$this->identifier}-jcf.number" );
		wp_enqueue_script( "{$this->identifier}-readmore" );
		wp_enqueue_script( "{$this->identifier}-slick" );
		wp_enqueue_script( "{$this->identifier}-match-height" );
		wp_enqueue_script( "{$this->identifier}-main" );
		wp_enqueue_script( "{$this->identifier}-cart" );

		wp_localize_script( "{$this->identifier}-cart", "{$this->identifier}_script", [
			'slickPrevArrow' => '<button type="button" class="slick-prev pull-left"><img src="' . get_template_directory_uri() . '/img/Arrow.png' . '" alt="" /></button>',
			'slickNextArrow' => '<button type="button" class="slick-next pull-right"><img src="' . get_template_directory_uri() . '/img/Arrow.png' . '" alt="" /></button>',
			'mapIcon'        => get_template_directory_uri() . '/img/map-icon.png',
		] );
	}
}
