<?php namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\WC1C;

use BytePerfect\WordPress\Theme\Tehnokrat\Tehnokrat;

defined( 'ABSPATH' ) || exit;

final class WC1C {
	/**
	 * WC1C constructor.
	 */
	public function __construct() {
		// Пересоздаю таблицы WooCommerce после импорта товаров из 1С.
		add_action( 'wc1c_post_orders', [ $this, 'wc_update_product_lookup_tables' ] );
		add_action( 'wc1c_post_import', [ $this, 'wc_update_product_lookup_tables' ] );
		add_action( 'wc1c_post_offers', [ $this, 'wc_update_product_lookup_tables' ] );

		// Отключаю импорт категорий.
		add_filter( 'wc1c_import_group_xml', '__return_false' );

		// Отключаю обновление категорий при импорте товаров.
		add_filter( 'wc1c_import_preserve_product_fields', [ $this, 'preserve_categories_import' ] );
		// Убираю "Доп. описание" из атрибутов.
		add_filter( 'wc1c_import_property_xml', [ $this, 'preserve_additional_description' ] );
		// Исправляю импорт полного и короткого описаний товара.
		add_filter( 'wc1c_import_product_xml', [ $this, 'fix_product_description' ] );

		// Транслитерирую наименования атрибутов.
		add_filter( 'sanitize_taxonomy_name', [ $this, 'transliterate_taxonomy_name' ], 10, 2 );

		add_action( 'wc1c_post_product', [ $this, 'set_product_status' ], 10, 4 );
	}

	/**
	 * Пересоздание таблиц WooCommerce после импорта товаров из 1С.
	 */
	public function wc_update_product_lookup_tables() {
		global $wpdb;

		if ( ! wc_update_product_lookup_tables_is_running() ) {
			wc_update_product_lookup_tables();

			$wpdb->update( $wpdb->postmeta, [ 'meta_value' => 'yes' ], [ 'meta_key' => '_backorders' ] );
		}
	}

	/**
	 * Отключаю обновление категорий при импорте товаров.
	 *
	 * @param array $preserve_fields
	 *
	 * @return array
	 */
	public function preserve_categories_import( $preserve_fields ) {
		$preserve_fields[] = 'categories';

		return $preserve_fields;
	}

	/**
	 * Убираю "Доп. описание" из атрибутов.
	 *
	 * @param array $property Атрибут товара.
	 *
	 * @return array
	 */
	public function preserve_additional_description( $property ) {
		if ( '4f48c2ea-8626-11ea-ab48-a60254c2d513' == $property['Ид'] ) {
			$property = [];
		}

		return $property;
	}

	/**
	 * Исправляю импорт полного и короткого описаний товара.
	 *
	 * @param array $product Параметры товара.
	 *
	 * @return array
	 */
	public function fix_product_description( $product ) {
		if ( $product['ЗначенияРеквизитов'] ) {
			$requisites = wp_list_pluck( $product['ЗначенияРеквизитов'], 'Наименование' );

			// Полное описание товара.
			$content = isset( $product['Описание'] ) ? $product['Описание'] : '';
			$index   = array_search( 'ОписаниеВФорматеHTML', $requisites, true );
			if ( false === $index ) {
				$product['ЗначенияРеквизитов'][] = [
					'Наименование' => 'ОписаниеВФорматеHTML',
					'Значение'     => [ $content ],
				];
			} else {
				$product['ЗначенияРеквизитов'][ $index ]['Значение'][0] = $content;
			}

			// Короткое описание товара - характеристики.
			if ( isset( $product['_АВТ_Характеристики'] ) ) {
				$excerpt = $product['_АВТ_Характеристики'];
				unset( $product['_АВТ_Характеристики'] );
			} else {
				$excerpt = '';
			}
			$product['Описание'] = $excerpt;

			$attribute_ids = wp_list_pluck( $product['ЗначенияСвойств'], 'Ид' );
			$index         = array_search( '4f48c2ea-8626-11ea-ab48-a60254c2d513', $attribute_ids, true );
			if ( false !== $index ) {
				unset( $product['ЗначенияСвойств'][ $index ] );
			}
		}

		return $product;
	}

	/**
	 * Транслитерирую наименования атрибутов.
	 *
	 * @param string $taxonomy_name Имя атрибута.
	 * @param string $original_taxonomy_name Первоначальное имя атрибута.
	 *
	 * @return string
	 */
	public function transliterate_taxonomy_name( $taxonomy_name, $original_taxonomy_name ) {
		if ( ! $taxonomy_name ) {
			$taxonomy_name = urldecode( sanitize_title( urldecode( str_replace(
				[
					'а',
					'б',
					'в',
					'г',
					'д',
					'е',
					'ё',
					'ж',
					'з',
					'и',
					'й',
					'к',
					'л',
					'м',
					'н',
					'о',
					'п',
					'р',
					'с',
					'т',
					'у',
					'ф',
					'х',
					'ц',
					'ч',
					'ш',
					'щ',
					'ъ',
					'ы',
					'ь',
					'э',
					'ю',
					'я',
					'А',
					'Б',
					'В',
					'Г',
					'Д',
					'Е',
					'Ё',
					'Ж',
					'З',
					'И',
					'Й',
					'К',
					'Л',
					'М',
					'Н',
					'О',
					'П',
					'Р',
					'С',
					'Т',
					'У',
					'Ф',
					'Х',
					'Ц',
					'Ч',
					'Ш',
					'Щ',
					'Ъ',
					'Ы',
					'Ь',
					'Э',
					'Ю',
					'Я',
				],
				[
					'a',
					'b',
					'v',
					'g',
					'd',
					'e',
					'io',
					'zh',
					'z',
					'i',
					'y',
					'k',
					'l',
					'm',
					'n',
					'o',
					'p',
					'r',
					's',
					't',
					'u',
					'f',
					'h',
					'ts',
					'ch',
					'sh',
					'sht',
					'a',
					'i',
					'y',
					'e',
					'yu',
					'ya',
					'A',
					'B',
					'V',
					'G',
					'D',
					'E',
					'Io',
					'Zh',
					'Z',
					'I',
					'Y',
					'K',
					'L',
					'M',
					'N',
					'O',
					'P',
					'R',
					'S',
					'T',
					'U',
					'F',
					'H',
					'Ts',
					'Ch',
					'Sh',
					'Sht',
					'A',
					'I',
					'Y',
					'e',
					'Yu',
					'Ya',
				],
				$original_taxonomy_name
			) ) ) );
		}

		return $taxonomy_name;
	}

	public function set_product_status( $post_id, $is_added, $product, $is_full ) {
		wp_update_post( [ 'ID' => $post_id, 'post_status' => 'publish' ] );
	}
}
