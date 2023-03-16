<?php
/**
 * Class Google_Analytics

 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Google_Analytics
 */

namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Google_Analytics;

defined( 'ABSPATH' ) || exit;

/**
 * Class Google_Analytics

 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Google_Analytics
 */
class Google_Analytics {
	/**
	 * Shortcodes constructor.
	 */
	public function __construct() {
		if ( ! is_admin() ) {
			// Подключаю Enhanced Ecommerce.
			new GA_Enhanced_Ecommerce();

			// Подключаю динамический ремаркетинг.
			// new GA_Dynamic_Remarketing();
		}
	}
}
