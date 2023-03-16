<?php namespace BytePerfect\WordPress\Theme\Tehnokrat\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Modules_Loader {
	private function __construct() {
		// Список всех модулей.
		$modules = [
			'WC1C', // Обмен данным между WooCommerce и 1С:Предприятием
		];

		// Загружаю модули.
		/**
		 * @var $module Abstract_Module
		 */
		foreach ( $modules as $module ) {
			$module = __NAMESPACE__ . '\\' . $module;
			$module::instance();
		}
	}

	private function __clone() {
	}

	protected function __wakeup() {
	}

	public static function instance() {
		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new static();
		}

		return $instance;
	}
}
