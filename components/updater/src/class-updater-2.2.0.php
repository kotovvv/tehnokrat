<?php namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Updater;

defined( 'ABSPATH' ) || exit;

final class Updater_2_2_0 extends Abstract_Updater {
	public function get_version() {
		return '2.2.0';
	}

	public function __invoke() {
		global $wpdb;

		/**
		 *
		 */
		$wpdb->update( $wpdb->options, [ 'option_value' => 'yes' ], [ 'option_name' => 'woocommerce_manage_stock' ] );
		if ( $wpdb->last_error ) {
			return null;
		}
		wp_log_debug( 'Включено глобальное управление запасами.' );

		/**
		 *
		 */
		$result = $wpdb->delete( $wpdb->postmeta, [ 'meta_key' => '_wc1c_guid' ] );
		if ( $wpdb->last_error ) {
			return null;
		}
		wp_log_debug( "Удалено '_wc1c_guid' для {$result} товаров." );

		/**
		 *
		 */
		$query  = <<<EOQ
INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value)
SELECT src.post_id, '_wc1c_guid', src.meta_value
FROM {$wpdb->postmeta} AS src
WHERE src.meta_key='_woocommerce-moysklad-synchronization_id'
EOQ;
		$result = $wpdb->query( $query );
		if ( $wpdb->last_error ) {
			return null;
		}
		wp_log_debug( "'_wc1c_guid' установлен для {$result} товаров." );

		/**
		 *
		 */
		$result = $wpdb->update( $wpdb->postmeta, [ 'meta_value' => 0 ], [ 'meta_key' => '_stock' ] );
		if ( $wpdb->last_error ) {
			return null;
		}
		wp_log_debug( "'_stock' обнвлен для {$result} товаров." );

		/**
		 *
		 */
		$result = $wpdb->update( $wpdb->postmeta, [ 'meta_value' => 'outofstock' ], [ 'meta_key' => '_stock_status' ] );
		if ( $wpdb->last_error ) {
			return null;
		}
		wp_log_debug( "'_stock_status' обнвлен для {$result} товаров." );

		/**
		 *
		 */
		$result = $wpdb->update( $wpdb->postmeta, [ 'meta_value' => 'yes' ], [ 'meta_key' => '_backorders' ] );
		if ( $wpdb->last_error ) {
			return null;
		}
		wp_log_debug( "'_backorders' обнвлен для {$result} товаров." );

		/**
		 *
		 */
		$result = $wpdb->update( $wpdb->postmeta, [ 'meta_value' => 'yes' ], [ 'meta_key' => '_manage_stock' ] );
		if ( ! is_integer( $result ) ) {
			return null;
		}
		wp_log_debug( "'_manage_stock' обнвлен для {$result} товаров." );

		/**
		 *
		 */
		$query  = <<<EOQ
UPDATE {$wpdb->postmeta}
SET meta_value=0
WHERE meta_key IN ('_price', '_regular_price' )
EOQ;
		$result = $wpdb->query( $query );
		if ( $wpdb->last_error ) {
			return null;
		}
		wp_log_debug( "Цены установлены в 0 для {$result} товаров." );

		/**
		 *
		 */
		$result = $wpdb->update( $wpdb->postmeta, [ 'meta_value' => '' ], [ 'meta_key' => '_sale_price' ] );
		if ( $wpdb->last_error ) {
			return null;
		}
		wp_log_debug( "Скидки сброшены для {$result} товаров." );

		return $this->get_version();
	}
}

return new Updater_2_2_0();
