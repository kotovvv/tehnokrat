<?php
/**
 * Class Google_Recaptcha
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Google_Recaptcha
 */

namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Google_Recaptcha;

// phpcs:disable Squiz.Commenting.FunctionComment.SpacingAfterParamType

use ReCaptcha\ReCaptcha;

/**
 * Class Google_Recaptcha
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Google_Recaptcha
 */
final class Google_Recaptcha {
	/**
	 * Get Google_Recaptcha instance.
	 *
	 * @return Google_Recaptcha|null
	 */
	public static function instance() {
		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new Google_Recaptcha();
		}

		return $instance;
	}

	/**
	 * Google_Recaptcha constructor.
	 */
	protected function __construct() {
		// phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		wp_register_script(
			'google-recaptcha',
			'https://www.google.com/recaptcha/api.js?render=' . $this->get_site_key(),
			array(),
			null,
			false
		);
	}

	/**
	 * Google_Recaptcha::__clone
	 */
	protected function __clone() {
	}

	/**
	 * Google_Recaptcha::__wakeup
	 */
	protected function __wakeup() {
	}

	/**
	 * Enqueue Google reCaptcha script.
	 */
	public function enqueue_script() {
		wp_enqueue_script( 'google-recaptcha' );
	}

	/**
	 * Calls the reCAPTCHA site verify API to verify whether the user passes CAPTCHA test.
	 *
	 * @param string $response The value of 'g-recaptcha-response' in the submitted form.
	 * @param string $hostname Expected hostname.
	 * @param string $action Expected action.
	 * @param float $threshold Expected threshold.
	 * @param int $timeout Expected hostname.
	 * @param string $remote_ip The end user's IP address.
	 *
	 * @return bool|array
	 */
	public function verify(
		$response,
		$hostname = null,
		$action = null,
		$threshold = null,
		$timeout = null,
		$remote_ip = null
	) {
		$recaptcha = new ReCaptcha( $this->get_secret_key() );
		if ( ! is_null( $hostname ) ) {
			$recaptcha->setExpectedHostname( $hostname );
		}
		if ( ! is_null( $action ) ) {
			$recaptcha->setExpectedAction( $action );
		}
		if ( ! is_null( $threshold ) ) {
			$recaptcha->setScoreThreshold( $threshold );
		}
		if ( ! is_null( $timeout ) ) {
			$recaptcha->setChallengeTimeout( $timeout );
		}

		$resp = $recaptcha->verify( $response, $remote_ip );
		if ( $resp->isSuccess() ) {
			return true;
		} else {
			return $resp->getErrorCodes();
		}
	}

	/**
	 * Get site key.
	 *
	 * @return string
	 */
	public function get_site_key() {
		global $tehnokrat;

		return $tehnokrat->get_setting( 'recaptcha', 'site_key' );
	}

	/**
	 * Get secret key.
	 *
	 * @return string
	 */
	public function get_secret_key() {
		global $tehnokrat;

		return $tehnokrat->get_setting( 'recaptcha', 'secret_key' );
	}
}
