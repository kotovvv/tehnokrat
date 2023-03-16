<?php namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Updater;

defined( 'ABSPATH' ) || exit;

final class Updater_2_2_8 extends Abstract_Updater {
	public function get_version() {
		return '2.2.8';
	}

	public function __invoke() {
		global $wpdb;

		/**
		 *
		 */
		$result = $wpdb->update( $wpdb->postmeta, [ 'meta_value' => 'yes' ], [ 'meta_key' => '_backorders' ] );
		if ( $wpdb->last_error ) {
			return null;
		}
		wp_log_debug( "'_backorders' обновлен для {$result} товаров." );

		return $this->get_version();
	}
}

return new Updater_2_2_8();
