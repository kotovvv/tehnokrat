<?php namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Pages;

use BytePerfect\WordPress\Theme\Tehnokrat\Components\Google_Recaptcha\Google_Recaptcha;
use BytePerfect\WordPress\Theme\Tehnokrat\Tehnokrat;

defined( 'ABSPATH' ) || exit;

class Trade_In_Page extends Abstract_Page {
	protected function enqueue_styles() {
		wp_enqueue_style( "{$this->identifier}-trade-in" );
		wp_enqueue_style( "{$this->identifier}-trade-in-pop-up" );
	}

	protected function enqueue_scripts() {
		/**
		 * @var $tehnokrat Tehnokrat
		 */
		global $tehnokrat;

		Google_Recaptcha::instance()->enqueue_script();
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( 'jquery-blockui' );
		wp_enqueue_script( "{$this->identifier}-jquery.mask" );
		wp_enqueue_script( "{$this->identifier}-readmore" );
		wp_enqueue_script( "{$this->identifier}-trade-in" );
		wp_enqueue_script( "{$this->identifier}-trade-in-pop-up" );
		wp_enqueue_script( "{$this->identifier}-jcf" );
		wp_enqueue_script( "{$this->identifier}-jcf.file" );
		wp_enqueue_script( "{$this->identifier}-jcf.checkbox" );

		wp_localize_script(
			"{$this->identifier}-trade-in",
			"{$this->identifier}",
			array(
				'ajax_url'         => admin_url( 'admin-ajax.php' ),
				'recaptchaSiteKey' => Google_Recaptcha::instance()->get_site_key(),
			)
		);

		wp_set_script_translations(
			"{$this->identifier}-trade-in-pop-up",
			'tehnokrat',
			$tehnokrat->get_path( '/languages' )
		);
	}
}
