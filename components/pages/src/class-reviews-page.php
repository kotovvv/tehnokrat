<?php namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Pages;

use BytePerfect\WordPress\Theme\Tehnokrat\Components\Google_Recaptcha\Google_Recaptcha;

defined( 'ABSPATH' ) || exit;

/**
 * Страница "Отзывы покупателей"
 */
class Reviews_Page extends Abstract_Page {
	protected function enqueue_styles() {
		wp_enqueue_style( "{$this->identifier}-reviews" );
	}

	protected function enqueue_scripts() {
		Google_Recaptcha::instance()->enqueue_script();

		wp_enqueue_script( "{$this->identifier}-readmore" );
		wp_enqueue_script( "{$this->identifier}-vue" );
		wp_enqueue_script( "{$this->identifier}-reviews" );
		wp_enqueue_script( "{$this->identifier}-rating" );
		wp_localize_script(
			"{$this->identifier}-script",
			"{$this->identifier}",
			array(
				'ajax_url'         => admin_url( 'admin-ajax.php' ),
				'nonce'            => wp_create_nonce( $this->identifier ),
				'recaptchaSiteKey' => Google_Recaptcha::instance()->get_site_key(),
			)
		);
	}
}
