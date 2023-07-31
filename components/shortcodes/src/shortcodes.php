<?php

namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Shortcodes;

use BytePerfect\WordPress\Theme\Tehnokrat\Tehnokrat;

defined('ABSPATH') || exit;

use WC_Product;

class Shortcodes
{
	/**
	 * Shortcodes constructor.
	 */
	public function __construct()
	{
		$shortcodes = [
			'accessories',
			'bestsellers',
			'more_articles',
		];

		foreach ($shortcodes as $shortcode) {
			add_shortcode("tehnokrat_{$shortcode}", [$this, $shortcode]);
		}
	}

	public function bestsellers($atts, $content, $shortcode_tag)
	{
		/**
		 * @global Tehnokrat $tehnokrat
		 */
		global $tehnokrat;

		$product_ids = $tehnokrat->get_setting('shortcodes', 'shortcode_bestsellers_products');

		if (empty($product_ids)) {
			return '';
		}

		shuffle($product_ids);

		return $this->render_product_accessories($product_ids, $content);
	}

	public function accessories($atts, $content, $shortcode_tag)
	{
		$args = [
			'fields'         => 'ids',
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => '5',
			'tax_query'      => [
				[
					'taxonomy' => 'product_cat',
					'terms'    => 621,
				],
			],
			'meta_query'     => [
				[
					'key'   => '_stock_status',
					'value' => 'instock',
				],
			],
			'orderby'        => 'rand',
		];

		$product_ids = get_posts($args);

		return $this->render_product_accessories($product_ids, $content);
	}

	private function render_product_accessories($product_ids, $content)
	{
		/**
		 * @global Tehnokrat $tehnokrat
		 */
		global $tehnokrat;

		if (empty($product_ids)) {
			return '';
		}

		$products = [];
		foreach ($product_ids as $product_id) {
			$product       = wc_get_product($product_id);
			$product_image = wp_get_attachment_image_src($product->get_image_id(), 'full');
			$label_color   = '';
			$label         = $product->get_attribute('pa_yarlik-tovara');
			if ($label) {
				switch ($label) {
					case 'Лучшая цена':
					case 'Найкраща ціна':
						$label_color = '#E1306C';
						break;
					case 'Новинка':
						$label_color = '#93D500';
						break;
					case 'Скидка':
						$label_color = '#FD5958';
						break;
					case 'Топ продаж':
					case 'Топ продажів':
						$label_color = '#00AE43';
						break;
					case 'Акция':
						$label_color = '#F80126';
						break;
					case 'Рекомендуем':
					case 'Рекомендуємо':
						$label_color = '#F6C000';
						break;
					case 'Восстановленный':
						$label_color = '#4084b3';
						break;
					case 'Dual SIM':
						$label_color = '#000000';
						break;
					case 'Open Box':
						$label_color = '#0085FF';
						break;
				}
			}
			if ($product->get_stock_quantity() < 1) {
				continue;
			}

			$id = $product->get_id();
			$products[] = [
				'id'          => $id,
				'title'       => $this->trim_title($product->get_title()),
				'permalink'   => $product->get_permalink(),
				'image_src'   => $product_image[0],
				'price_uah'   => $product->get_price(),
				'price_usd'   => $tehnokrat->get_price_in_usd($product->get_price()),
				'label'       => $product->get_attribute('pa_yarlik-tovara'),
				'label_color' => $label_color,
				'part'				=> intval(get_field('part', $id)),
				'partprivat' => intval(get_field('partprivat', $id)),
				'description' => $this->get_description($product),
			];
			if (count($products) == 15) {
				break;
			}
		}

		wp_enqueue_style(
			'bestsellers',
			$tehnokrat->get_url('/components/shortcodes/src/assets/css/bestsellers.css'),
			[],
			$tehnokrat->get_version()
		);
		wp_enqueue_script(
			'bestsellers',
			$tehnokrat->get_url('/components/shortcodes/src/assets/js/bestsellers.min.js'),
			[],
			$tehnokrat->get_version(),
			true
		);
		wp_localize_script('bestsellers', 'bestsellers', $products);

		ob_start();

		require 'templates/bestsellers.php';

		return ob_get_clean();
	}

	public function more_articles($atts, $content, $shortcode_tag)
	{
		/**
		 * @global Tehnokrat $tehnokrat
		 */
		global $tehnokrat;

		$atts = shortcode_atts([
			'type'  => 'more',
			'count' => 4,
		], $atts);

		$posts = get_posts(['numberposts' => $atts['count']]);

		$articles = [];
		foreach ($posts as $article) {
			$product_image = wp_get_attachment_image_src(get_post_thumbnail_id($article), 'full');
			$articles[]    = [
				'title'     => $article->post_title,
				'permalink' => get_permalink($article->ID),
				'image_src' => $product_image[0],
				'date'      => get_post_time(get_option('date_format'), false, $article, true),
			];
		}

		if ('latest' === $atts['type']) {
			wp_enqueue_style(
				'more-articles',
				$tehnokrat->get_url('/components/shortcodes/src/assets/css/more-articles.css'),
				[],
				$tehnokrat->get_version()
			);
			wp_enqueue_script(
				'more-articles',
				$tehnokrat->get_url('/components/shortcodes/src/assets/js/more-articles.min.js'),
				[],
				$tehnokrat->get_version(),
				true
			);
		}

		ob_start();

		require 'templates/more-articles.php';

		return ob_get_clean();
	}

	/**
	 * Trim title.
	 *
	 * @param string $title Title.
	 *
	 * @return string
	 */
	protected function trim_title($title)
	{
		$max_length = 75;

		$title = trim(preg_replace('/\[.*\]/', '', $title));

		if (strlen($title) > $max_length) {
			$title = substr($title, 0, $max_length - 3) . '...';
		}

		return $title;
	}
	/**
	 * Разделить наименование товара на собственно наименование и строку с характеристиками.
	 *
	 * @param WC_Product $product Product.
	 *
	 * @return array
	 *
	 * @SuppressWarnings(PHPMD.ElseExpression)
	 */
	protected function get_titles(WC_Product $product): array
	{
		$position = strpos($product->get_title(), '[');
		if (false === $position) {
			$title1 = $product->get_title();
			$title2 = '';
		} else {
			$title1 = substr($product->get_title(), 0, $position);
			$title2 = substr($product->get_title(), $position);
		}

		return array_map('trim', array($title1, $title2));
	}
	/**
	 * Get attributes.
	 *
	 * @param WC_Product $product Product.
	 *
	 * @return array
	 */
	protected function get_attributes(WC_Product $product): array
	{
		global $tehnokrat;

		list(, $title2) = $this->get_titles($product);

		$attributes = $title2 ? explode('|', substr($title2, 1, -1)) : array();
		if (
			count($attributes) === 1
			||
			(count($attributes) > 1
				&&
				(mb_stripos($product->get_short_description(), 'цвет') === 0
					||
					mb_stripos($product->get_short_description(), 'колір') === 0
				)
			)
		) {
			$attributes[0] = $tehnokrat->color_to_hex($attributes[0]);
		}

		return $attributes;
	}

	/**
	 * Get attributes.
	 *
	 * @param WC_Product $product Product.
	 *
	 * @return string|array
	 *
	 * @SuppressWarnings(PHPMD.ElseExpression)
	 */
	protected function get_description(WC_Product $product)
	{
		if (count($this->get_attributes($product)) > 1) {
			$description = trim($product->get_short_description());
			$description = array_slice(explode("\n", $description), 0, 12);
		} else {
			$description = trim($product->get_description());
			$description = mb_substr($description, 0, 300);
			$description .= ('.' === substr($description, -1) ? '' : '.') . '.. ';
		}

		return $description;
	}
}
