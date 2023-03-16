<?php namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Widgets;

defined( 'ABSPATH' ) || exit;

class Widgets {
	/**
	 * Widgets constructor.
	 */
	public function __construct() {
		// Виджет "Оплата в рассрочку".
		new Widget_Installment_Payments();

		new Widget_Product_Variation_Selector();
		new Widget_One_Product();
	}
}
