<?php declare( strict_types=1 );
/**
 * Class Widget_Product_Variation_Selector.
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat
 */

namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Widgets;

use BytePerfect\WordPress\Theme\Tehnokrat\Tehnokrat;

/**
 * Class Widget_Product_Variation_Selector.
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat
 */
class Widget_Product_Variation_Selector {
	/**
	 * Widget_Product_Variation_Selector constructor.
	 */
	public function __construct() {
		add_action( 'wp_footer', [ $this, 'output' ] );
	}

	/**
	 * Вывожу виджет.
	 */
	public function output() {
		if ( is_product_category() ) {
			/* @global Tehnokrat $tehnokrat */
			global $tehnokrat;

			if ( 'development' === wp_get_environment_type() ) {
				wp_enqueue_script(
					'react-16.13.1',
					'https://unpkg.com/react@16.13.1/umd/react.development.js',
					[],
					'16.13.1',
					true
				);
				wp_enqueue_script(
					'react-dom-16.13.1',
					'https://unpkg.com/react-dom@17/umd/react-dom.development.js',
					[ 'react-16.13.1' ],
					'16.13.1',
					true
				);
				$deps = 'react-dom-16.13.1';
			} else {
				$deps = 'react-dom';
			}
			wp_enqueue_script(
				'widget-product-variation-selector',
				$tehnokrat->get_url( '/components/widgets/assets/js/widget-product-variation-selector.js' ),
				[ $deps ],
				$tehnokrat->get_version(),
				true
			);

			wp_set_script_translations(
				'widget-product-variation-selector',
				'tehnokrat',
				$tehnokrat->get_path( '/languages' )
			);

		}
	}
}
