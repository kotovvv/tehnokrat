<?php

namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Pages;

use BytePerfect\WordPress\Theme\Tehnokrat\Tehnokrat;
use WC_Product;

defined('ABSPATH') || exit;

class Product_Page extends Abstract_Page
{
	protected function enqueue_styles()
	{
		wp_enqueue_style("{$this->identifier}-one-product");
	}

	protected function enqueue_scripts()
	{
		/* @global Tehnokrat $tehnokrat */
		global $tehnokrat;

		wp_enqueue_script("{$this->identifier}-one-product");
		wp_enqueue_script("{$this->identifier}-readmore");
		wp_enqueue_script("{$this->identifier}-match-height");
		wp_enqueue_script("{$this->identifier}-slick");
		wp_enqueue_script("{$this->identifier}-main");
		wp_enqueue_script('jquery-ui-accordion');
		//		wp_enqueue_script( "{$this->identifier}-js.cookie" );
		//		wp_enqueue_script( "{$this->identifier}-vue" );
		//		wp_enqueue_script( "{$this->identifier}-lodash" );
		//		wp_enqueue_script( "{$this->identifier}-vue-app" );

		wp_localize_script("{$this->identifier}-main", "{$this->identifier}_script", [
			'slickPrevArrow' => '<button type="button" class="slick-prev pull-left"><img src="' . get_template_directory_uri() . '/img/Arrow.png' . '" alt="" /></button>',
			'slickNextArrow' => '<button type="button" class="slick-next pull-right"><img src="' . get_template_directory_uri() . '/img/Arrow.png' . '" alt="" /></button>',
		]);

		wp_localize_script("{$this->identifier}-script", "{$this->identifier}", [
			'ajax_url'                  => admin_url('admin-ajax.php'),
			'nonce'                     => wp_create_nonce($this->identifier),
			'exchange_rate'             => $tehnokrat->get_setting('main', 'usd_to_uah'),
			'bank_transfer_fee'         => 1 + $tehnokrat->get_setting('main', 'bank_transfer_fee') / 100,
			'products_timestamp_before' => microtime(),
			'products'                  => wp_json_encode($tehnokrat->get_category_products()),
			'products_timestamp_after'  => microtime(),
			'cities'                    => $this->get_cities(),
			'strings'                   => $tehnokrat->get_translation_strings(),
			'label_colors'              => $tehnokrat->get_label_colors(),
		]);
	}

	private function get_gallery()
	{
		$product = wc_get_product();
		$gallery = [];
		foreach ($product->get_gallery_image_ids() as $attachment_id) {
			$gallery[] = wp_get_attachment_url($attachment_id);
		}

		return $gallery;
	}

	protected function setup_actions()
	{
		parent::setup_actions();

		//remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

		remove_all_actions('woocommerce_single_product_summary');
		add_action('woocommerce_single_product_summary', [$this, 'woocommerce_template_single_title'], 5);
		add_action('woocommerce_single_product_summary', [$this, 'woocommerce_template_single_description'], 10);
		add_action('woocommerce_single_product_summary', [WC()->structured_data, 'generate_product_data'], 60);

		remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
	}

	/**
	 * Output the product title.
	 */
	public function woocommerce_template_single_title()
	{
		/* @global WC_Product $product Product instance. */
		global $product;

		$categories       = get_the_terms($product->get_id(), 'product_cat');
		$category_level_1 = null;
		$category_level_2 = null;
		$category_level_3 = null;
		foreach ($categories as $category) {
			if (0 == $category->parent) {
				$category_level_1 = $category;
			} elseif (is_null($category_level_2)) {
				$category_level_2 = $category_level_3 = $category;
			} elseif ($category_level_2->term_id === $category->parent) {
				$category_level_3 = $category;
			} else {
				$category_level_2 = $category;
			}
		}

		$category_url = get_category_link($category_level_2);
		// @todo w8 Trying to get property of non-object
		$category_name = $category_level_3->name;
		if ('ua' === get_request_locale()) {
			$translation = get_field('translation', $category_level_3);
			if ($translation) {
				$category_name = $translation;
			}
		}

		$product_name  = trim(preg_replace('/\[.*\]/', '', $product->get_title()));
		if ('Use product name' == $category_name) {
			$category_name = $product_name;
			$product_name  = '';
		}

		wc_get_template(
			'single-product/title.php',
			compact('category_url', 'category_name', 'product_name')
		);
	}

	/**
	 * Output the product description.
	 */
	public function woocommerce_template_single_description()
	{
		/* @global Tehnokrat $tehnokrat */
		global $tehnokrat;

		/* @global WC_Product $product Product instance. */
		global $product;

		$product_name = trim(preg_replace('/\[.*\]/', '', $product->get_title()));
		$model        = $product->get_sku();
		$price        = floatval($product->get_price());
		$price_usd    = number_format($tehnokrat->get_price_in_usd($price), 0, '.', '&nbsp;');
		$price_uah    = number_format($price, 0, '.', ',');
		$description  = apply_filters('the_content', $product->get_description());
		$description  = str_replace(']]>', ']]&gt;', $description);
		$_params      = explode("\n", trim($product->get_short_description()));
		$params       = [];
		while (count($_params)) {
			// @todo w8 что-то здесь не так
			list($key, $value) = array_splice($_params, 0, 2);
			$params[trim($key)] = trim($value);
		}
		$gallery = [];
		foreach ($product->get_gallery_image_ids() as $attachment_id) {
			$gallery[] = wp_get_attachment_url($attachment_id);
		}

		wc_get_template(
			'single-product/description.php',
			compact('product_name', 'model', 'price_usd', 'price_uah', 'description', 'params', 'gallery')
		);
	}

	protected function get_cities()
	{
		/**
		 * @global Tehnokrat $tehnokrat
		 */
		global $tehnokrat;

		$cities = $tehnokrat->get_setting('installment_payments', 'installment_payments_cities');
		if (empty($cities)) {
			$cities = array();
		} else {
			$cities = preg_split('/\r\n|\r|\n/', $cities);
			$cities = array_map('trim', $cities);
		}

		return $cities;
	}
}
