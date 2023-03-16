<?php namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Updater;

defined( 'ABSPATH' ) || exit;

abstract class Abstract_Updater {
	abstract public function get_version();

	// Метод должен возвращать номер новой версии.
	abstract public function __invoke();
}
