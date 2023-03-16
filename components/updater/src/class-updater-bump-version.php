<?php namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Updater;

use BytePerfect\WordPress\Theme\Tehnokrat\Tehnokrat;

defined( 'ABSPATH' ) || exit;

final class Updater_Bump_Version extends Abstract_Updater {
	public function get_version() {
		/* @global Tehnokrat $tehnokrat */
		global $tehnokrat;

		return $tehnokrat->get_version();
	}

	public function __invoke() {
		return $this->get_version();
	}
}

return new Updater_Bump_Version();
