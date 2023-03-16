<?php namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Pages;

defined( 'ABSPATH' ) || exit;

/**
 * Главная страница
 */
class Home_Page extends Abstract_Page {
	protected function setup_actions() {
		parent::setup_actions();

		remove_filter( 'the_content', 'wpautop' );
		remove_filter( 'the_excerpt', 'wpautop' );
	}

	protected function enqueue_styles() {
		wp_enqueue_style( "{$this->identifier}-home" );
		wp_enqueue_style( "{$this->identifier}-home-new" );
		wp_enqueue_style( "{$this->identifier}-icon-sl" );
	}

	protected function enqueue_scripts() {
		wp_enqueue_script( "{$this->identifier}-anime.js" );
		wp_enqueue_script( "{$this->identifier}-main" );
		wp_enqueue_script( "{$this->identifier}-home" );
		wp_enqueue_script( "{$this->identifier}-lottie" );
		wp_enqueue_script( "{$this->identifier}-readmore" );
		wp_enqueue_script( "{$this->identifier}-slick" );
		wp_enqueue_script( "{$this->identifier}-match-height" );

		wp_localize_script( "{$this->identifier}-main", "{$this->identifier}_script", [
			'slickPrevArrow' => '<button type="button" class="slick-prev pull-left"><img src="' . get_template_directory_uri() . '/img/Arrow.png' . '" alt="" /></button>',
			'slickNextArrow' => '<button type="button" class="slick-next pull-right"><img src="' . get_template_directory_uri() . '/img/Arrow.png' . '" alt="" /></button>',
			'mapIcon'        => get_template_directory_uri() . '/img/map-icon.png',
		] );
	}
}
