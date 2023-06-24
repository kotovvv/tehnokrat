<?php
/**
 * Class Woocommerce_Category_Products
 *
 * Получение списка товаров для отображения на странице категорий и одного товара.
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Woocommerce
 */

// phpcs:disable WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
// phpcs:disable WordPress.WP.AlternativeFunctions.file_system_read_fopen
// phpcs:disable WordPress.WP.AlternativeFunctions.file_system_read_fwrite
// phpcs:disable WordPress.WP.AlternativeFunctions.file_system_read_fclose
// phpcs:disable WordPress.Security.ValidatedSanitizedInput

declare( strict_types=1 );

namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Woocommerce;

use Exception;
use WC_Product;

/**
 * Class Woocommerce_Category_Products
 *
 * Получение списка товаров для отображения на странице категорий и одного товара.
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Woocommerce
 */
final class Woocommerce_Category_Products {
	protected const CACHE_DIR = WP_CONTENT_DIR . '/cache/category_products/';

	/**
	 * Cache chunks.
	 *
	 * @var array
	 */
	protected array $cache_chunks = array();

	/**
	 * Woocommerce_Category_Products constructor.
	 */
	public function __construct() {
		$this->check_cache_dir_exists();

		add_action( 'wp_ajax_nopriv_edi_finish', array( $this, 'clear_cache' ) );
		add_action( 'wp_ajax_edi_finish', array( $this, 'clear_cache' ) );
		add_filter( 'get_category_products', array( $this, 'get' ) );
	}

	/**
	 * Clear cache.
	 *
	 * @return void
	 */
	public function clear_cache(): void {
		check_ajax_referer( 'edi_finish' );

		$filename = empty( $_REQUEST['filename'] ) ? '' : $_REQUEST['filename'];

		if ( 0 !== strpos( $filename, 'offers' ) ) {
			return;
		}

		$files = (array) glob( self::CACHE_DIR . '*' );

		$found_files_count = count( $files );

		if ( 0 === $found_files_count ) {
			return;
		}

		$deleted_files_count = 0;

		foreach ( $files as $file ) {
			if ( is_file( $file ) && @unlink( $file ) ) {
				$deleted_files_count ++;
			} else {
				wp_log_warning( 'The cache file of products in the category was not deleted.' . PHP_EOL . $file );
			}
		}

		if ( $found_files_count === $deleted_files_count ) {
			wp_log_debug( 'The cache of products in the category was cleared successfully.' );
		} else {
			wp_log_warning( 'The cache of products in the category was not completely cleared.' );
		}

		// Delete WP Super Cache cache files.
		global $cache_path;

		if ( $cache_path && function_exists( 'prune_super_cache' ) ) {
			do_action( 'wp_cache_cleared' );

			prune_super_cache( $cache_path, true );
		}
	}

	/**
	 * Check cache directory exists.
	 *
	 * @return void
	 * @throws Exception Exception.
	 */
	protected function check_cache_dir_exists(): void {
		if ( ! is_dir( self::CACHE_DIR ) ) {
			$permissions = ( fileperms( ABSPATH ) & 0777 | 0755 );

			if ( ! mkdir( self::CACHE_DIR, $permissions, true ) ) {
				throw new Exception(
					sprintf(
					/* translators: %s: directory name. */
						__( 'Error create directory: %s.', 'edi' ),
						self::CACHE_DIR
					)
				);
			}
		}
	}

	/**
	 * Get cache filename.
	 *
	 * @param integer|string $queried_object_id Queried object ID.
	 *
	 * @return string
	 */
	protected function get_cache_filename( $queried_object_id ): string {
		return path_join( self::CACHE_DIR, get_request_locale() . $queried_object_id );
	}

	/**
	 * Get products for page.
	 *
	 * @return array
	 */
	public function get(): array {
		global $wp_query;

		if ( empty( $wp_query->queried_object_id ) ) {
			return array();
		}

		$file_name = $this->get_cache_filename( $wp_query->queried_object_id );

		if (
			current_user_can( 'manage_woocommerce' )
			||
			! is_readable( $file_name )
		) {
			$this->build_cache( $file_name );
		}

		if ( is_readable( $file_name ) ) {
			$products = json_decode( file_get_contents( $file_name ), true );

			if ( ! is_array( $products ) ) {
				$products = array();
			}
		} else {
			$products = array();
		}

		return $products;
	}

	/**
	 * Build cache file.
	 *
	 * @param string $file_name Cache file name.
	 *
	 * @return void
	 */
	protected function build_cache( string $file_name ): void {
		$temp_file_name = $file_name . '.tmp';

		// Генерируется ли кэш в другом процессе?
		if ( file_exists( $temp_file_name ) ) {
			return;
		}

		touch( $temp_file_name );

		while ( have_posts() ) {
			the_post();

			$this->write_cache_chunk();
		}

		ksort( $this->cache_chunks );

		$cache_file = fopen( $temp_file_name, 'wb' );
		fwrite( $cache_file, '[' );

		foreach ( $this->cache_chunks as $index => $chunk ) {
			fwrite( $chunk['stream'], ']' );
			rewind( $chunk['stream'] );

			if ( ftell( $cache_file ) > 1 ) {
				fwrite( $cache_file, ',' );
			}

			fwrite(
				$cache_file,
				sprintf(
					// '{"name":%s,"variations":',
					// wp_json_encode( $chunk['name'] )
					'{"name":%s,"url":"%s","variations":',
					wp_json_encode( $chunk['name'] ),get_category_link((int)wp_json_encode( $chunk['url'] ))
				)
			);

			stream_copy_to_stream( $chunk['stream'], $cache_file );

			fwrite( $cache_file, '}' );

			fclose( $chunk['stream'] );
			unlink( $this->get_cache_filename( $index ) );
		}

		fwrite( $cache_file, ']' );
		fclose( $cache_file );

		rename( $temp_file_name, $file_name );
	}

	/**
	 * Write cache chunk.
	 *
	 * @return void
	 *
	 * @SuppressWarnings(PHPMD.ElseExpression)
	 */
	protected function write_cache_chunk(): void {
		global $wp_query, $post;

		$product = wc_get_product( $post );
		if ( ! $product ) {
			return;
		}

		$categories = $this->get_categories( $product->get_id() );
		if ( empty( $categories['level_3'] ) ) {
			return;
		}

		// Пропустить товары в категории "Без публикации".
		if ( 706 === $categories['level_3']->term_id ) {
			return;
		}

		$data = $this->get_product_data( $product );
		if ( empty( $data ) ) {
			return;
		}

		// Получаю категорию 3го уровня для текущего товара.
		$index = sprintf(
			'%09d%09d%09d%s',
			get_term_meta( $categories['level_3']->term_id, 'order', true ),
			$categories['level_3']->term_id,
			'Use product name' === $categories['level_3']->name ? $wp_query->current_post : 0,
			$_SERVER['REQUEST_TIME_FLOAT']
		);

		if ( empty( $this->cache_chunks[ $index ] ) ) {
			if ( 'Use product name' === $categories['level_3']->name ) {
				$product_name = $product->get_title();
			} else {
				$product_name = $categories['level_3']->name;

				if ( 'ua' === get_request_locale() ) {
					$translation = get_field( 'translation', $categories['level_3'] );
					if ( $translation ) {
						$product_name = $translation;
					}
				}
			}


			$this->cache_chunks[ $index ] = array(
				'name'   => $product_name,
				'url' => $categories['level_3']->term_id,
				'stream' => fopen( $this->get_cache_filename( $index ), 'w+' ),
			);

			fwrite( $this->cache_chunks[ $index ]['stream'], '[' );
		} else {
			fwrite( $this->cache_chunks[ $index ]['stream'], ',' );
		}

		fwrite( $this->cache_chunks[ $index ]['stream'], wp_json_encode( $data ) );
	}

	/**
	 * Получаю категории товара.
	 *
	 * @param integer $product_id Product ID.
	 *
	 * @return array
	 */
	protected function get_categories( int $product_id ): array {
		$level_1 = null;
		$level_2 = null;
		$level_3 = null;

		foreach ( get_the_terms( $product_id, 'product_cat' ) as $product_category ) {
			if ( 0 === $product_category->parent ) {
				$level_1 = $product_category;
			} elseif ( is_null( $level_2 ) ) {
				$level_2 = $product_category;
				$level_3 = $product_category;
			} elseif ( $level_2->term_id === $product_category->parent ) {
				$level_3 = $product_category;
			} else {
				$level_2 = $product_category;
			}
		}

		return compact( 'level_1', 'level_2', 'level_3' );
	}

	/**
	 * Get product data.
	 *
	 * @param WC_Product $product Product.
	 *
	 * @return array
	 */
	protected function get_product_data( WC_Product $product ): array {
		global $tehnokrat;

		list( $title1, $title2 ) = $this->get_titles( $product );

		return array(
			'id'           => $product->get_id(),
			'is_featured'  => $product->is_featured(),
			'title1'       => $title1,
			'title2'       => $title2,
			'model'        => $product->get_sku(),
			'description2' => $this->get_description( $product ),
			'modification' => strval( get_field( 'modification' ) ),
			'part' => intval(get_field('part', $product->get_id())),
			'partprivat' => intval(get_field('partprivat', $product->get_id())) ,
			'priceUAH'     => floatval( $product->get_price() ),
			'priceUSD'     => $tehnokrat->get_price_in_usd( $product->get_price() ),
			'in_stock'     => ( $product->get_price() && 'outofstock' !== $product->get_stock_status() ? 1 : 0 ),
			'image'        => $this->get_image_url( $product ),
			'gallery'      => $this->get_gallery( $product ),
			'attributes2'  => $this->get_attributes( $product ),
			'url'          => $product->get_permalink(),
			'label'        => $product->get_attribute( 'pa_yarlik-tovara' ),
			'tradeIn'      => $this->is_trade_in_product( $product ),
		);
	}

	/**
	 * Проверяю установлен ли признака trade-in для товара.
	 *
	 * @param WC_Product $product Product.
	 *
	 * @return boolean
	 *
	 * @SuppressWarnings(PHPMD.ElseExpression)
	 */
	protected function is_trade_in_product( WC_Product $product ): bool {
		$trade_in = get_field( 'trade-in', $product->get_id() );
		if ( 'yes' === $trade_in ) {
			$trade_in = true;
		} elseif ( 'no' === $trade_in ) {
			$trade_in = false;
		} else {
			$categories = $this->get_categories( $product->get_id() );

			$trade_in_level_1 = get_field( 'trade-in', $categories['level_1'] );
			$trade_in_level_2 = get_field( 'trade-in', $categories['level_2'] );
			$trade_in_level_3 = get_field( 'trade-in', $categories['level_3'] );
			if ( 'yes' === $trade_in_level_3 ) {
				$trade_in = true;
			} elseif ( 'no' === $trade_in_level_3 ) {
				$trade_in = false;
			} elseif ( 'yes' === $trade_in_level_2 ) {
				$trade_in = true;
			} elseif ( 'no' === $trade_in_level_2 ) {
				$trade_in = false;
			} elseif ( 'yes' === $trade_in_level_1 ) {
				$trade_in = true;
			} else {
				$trade_in = false;
			}
		}

		return $trade_in;
	}

	/**
	 * Get image url.
	 *
	 * @param WC_Product $product Product.
	 *
	 * @return string
	 *
	 * @SuppressWarnings(PHPMD.ElseExpression)
	 */
	protected function get_image_url( WC_Product $product ): string {
		$thumbnail_id = get_post_thumbnail_id( $product->get_id() );
		if ( $thumbnail_id ) {
			$image_url = wp_get_attachment_url( $thumbnail_id );
		} else {
			$image_url = wc_placeholder_img_src();
		}

		return apply_filters( 'pso_get_webp_url', $image_url );
	}

	/**
	 * Get image url.
	 *
	 * @param WC_Product $product Product.
	 *
	 * @return array
	 */
	protected function get_gallery( WC_Product $product ): array {
		$gallery = array();
		foreach ( $product->get_gallery_image_ids() as $attachment_id ) {
			$attachment_url = wp_get_attachment_url( $attachment_id );

			$gallery[] = apply_filters( 'pso_get_webp_url', $attachment_url );
		}

		return $gallery;
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
	protected function get_titles( WC_Product $product ): array {
		$position = strpos( $product->get_title(), '[' );
		if ( false === $position ) {
			$title1 = $product->get_title();
			$title2 = '';
		} else {
			$title1 = substr( $product->get_title(), 0, $position );
			$title2 = substr( $product->get_title(), $position );
		}

		return array_map( 'trim', array( $title1, $title2 ) );
	}

	/**
	 * Get attributes.
	 *
	 * @param WC_Product $product Product.
	 *
	 * @return array
	 */
	protected function get_attributes( WC_Product $product ): array {
		global $tehnokrat;

		list( , $title2 ) = $this->get_titles( $product );

		$attributes = $title2 ? explode( '|', substr( $title2, 1, - 1 ) ) : array();
		if (
			count( $attributes ) === 1
			||
			(
				count( $attributes ) > 1
				&&
				(
					mb_stripos( $product->get_short_description(), 'цвет' ) === 0
					||
					mb_stripos( $product->get_short_description(), 'колір' ) === 0
				)
			)
		) {
			$attributes[0] = $tehnokrat->color_to_hex( $attributes[0] );
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
	protected function get_description( WC_Product $product ) {
		if ( count( $this->get_attributes( $product ) ) > 1 ) {
			$description = trim( $product->get_short_description() );
			$description = array_slice( explode( "\n", $description ), 0, 12 );
		} else {
			$description = trim( $product->get_description() );
			$description = mb_substr( $description, 0, 300 );
			$description .= ( '.' === substr( $description, - 1 ) ? '' : '.' ) . '.. ';
		}

		return $description;
	}
}
