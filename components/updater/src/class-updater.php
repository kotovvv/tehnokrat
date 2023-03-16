<?php namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Updater;

defined( 'ABSPATH' ) || exit;

final class Updater {
	/**
	 * Идентификатор.
	 *
	 * @var string
	 */
	private $identifier;

	/**
	 * Новый номер плагина/темы.
	 *
	 * @var string
	 */
	private $new_version;

	/**
	 * Конструктор.
	 *
	 * @param string $identifier Идентификатор.
	 * @param string $new_version Новый номер плагина/темы.
	 */
	public function __construct( $identifier, $new_version ) {
		$this->identifier  = $identifier . '_updater';
		$this->new_version = $new_version;

		add_action( 'init', [ $this, 'maybe_force_update' ], 10 );
		add_action( 'init', [ $this, 'maybe_update' ], 11 );
	}

	/**
	 * Форсировать автообновление.
	 */
	public function maybe_force_update() {
		$param_name = $this->identifier . '_force';

		if (
			current_user_can( 'manage_options' )
			&&
			isset( $_GET[ $param_name ] )
		) {
			$current_version = $_GET[ $param_name ];
			if ( ! $this->is_valid_version_number_format( $current_version ) ) {
				$current_version = '0.0.0';
			}
			$this->set_current_version( $current_version );
		}
	}

	/**
	 * Запустить задачу обновления.
	 */
	public function maybe_update() {
		if (
			$this->is_busy()
			||
			defined( 'IFRAME_REQUEST' ) // Не знаю что это.
			||
			version_compare( $this->get_current_version(), $this->new_version, '>=' )
		) {
			return;
		}

		$this->set_busy();

		$updaters = [];

		// Загружаю все классы со скриптами обновления.
		foreach ( glob( __DIR__ . '/class-updater-*.php' ) as $filename ) {
			$updater         = require_once "{$filename}";
			$version_version = $updater->get_version();
			if ( empty( $updaters[ $version_version ] ) ) {
				$updaters[ $version_version ] = $updater;
			}
		}

		// Получаю следующий по-очереди скрипт обновления.
		$updater_versions = array_keys( $updaters );
		usort( $updater_versions, 'version_compare' );
		foreach ( $updater_versions as $version ) {
			if ( version_compare( $this->get_current_version(), $version, '<' ) ) {
				wp_log_debug( 'Found updater to ' . $version );

				wp_log_debug( 'Updating...' );
				$updater         = $updaters[ $version ];
				$current_version = $updater ? $updater() : '';
				wp_log_debug( 'Updated.' );

				if ( $this->is_valid_version_number_format( $current_version ) ) {
					$this->set_current_version( $current_version );
				} else {
					break;
				}
			}
		}

		$this->unset_busy();
	}

	/**
	 * Проверка флага выполнения скрипта обновления.
	 *
	 * @return bool
	 */
	private function is_busy() {
		return 'yes' === get_transient( $this->identifier . '_running' );
	}

	/**
	 * Установка флага выполнения скрипта обновления.
	 */
	private function set_busy() {
		set_transient( $this->identifier . '_running', 'yes', MINUTE_IN_SECONDS * 10 );

		wp_log_debug( 'The update is started.' );
	}

	/**
	 * Снятие флага выполнения скрипта обновления.
	 */
	private function unset_busy() {
		delete_transient( $this->identifier . '_running' );

		wp_log_debug( 'The update is completed.' );
	}

	/**
	 * Проверка валидности номера версии.
	 *
	 * @param string $version_number
	 *
	 * @return bool
	 */
	private function is_valid_version_number_format( $version_number ) {
		return (bool) preg_match( '/^\d+\.\d+\.\d+$/', $version_number );
	}

	/**
	 * Соханение номера текущей версии.
	 *
	 * @param string $version_number
	 */
	private function set_current_version( $version_number ) {
		update_option( $this->identifier . '_version', $version_number );

		wp_log_debug( 'Current version was set to ' . $version_number );
	}

	/**
	 * Получение номера текущей версии.
	 *
	 * @return string
	 */
	private function get_current_version() {
		return get_option( $this->identifier . '_version', '0.0.0' );
	}
}
