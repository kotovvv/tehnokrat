<?php
/**
 * Class Bot
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Telegram_Bot
 */

namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Telegram_Bot;

// phpcs:disable Squiz.Commenting.FunctionComment.SpacingAfterParamType
// phpcs:disable Squiz.Commenting.FunctionComment.EmptyThrows

use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Throwable;

/**
 * Class Bot
 *
 * @method ServerResponse sendMediaGroup( array $data ) Use this method to send a group of photos or videos as an album. On success, an array of the sent Messages is returned.
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Telegram_Bot
 */
final class Bot {
	/**
	 * Telegram.
	 *
	 * @var Telegram
	 */
	protected $telegram;

	/**
	 * Bot constructor.
	 */
	public function __construct() {
		try {
			$this->telegram = new Telegram( $this->get_api_key(), $this->get_bot_username() );
		} catch ( TelegramException $e ) {
			$this->error_log( $e->getMessage() );
		}
	}

	/**
	 * Get Bot instance.
	 *
	 * @return Bot
	 */
	public static function instance() {
		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new Bot();
		}

		return $instance;
	}

	/**
	 * Initialize Bot instance.
	 *
	 * @return Bot
	 */
	public static function initialize() {
		return self::instance();
	}

	/**
	 * Bot::__clone
	 */
	protected function __clone() {
	}

	/**
	 * Bot::__wakeup
	 */
	protected function __wakeup() {
	}

	/**
	 * Get Telegram.
	 *
	 * @return Telegram
	 */
	protected function get_telegram() {
		return $this->telegram;
	}

	/**
	 * Get Telegram API key.
	 *
	 * @return string
	 */
	protected function get_api_key() {
		global $tehnokrat;

		return $tehnokrat->get_setting( 'telegram', 'telegram_bot_token' );
	}

	/**
	 * Get Telegram bot username.
	 *
	 * @return string
	 */
	protected function get_bot_username() {
		global $tehnokrat;

		return (string) $tehnokrat->get_setting( 'telegram', 'telegram_bot_username' );
	}

	/**
	 * Any called method should be relayed to the `send` method.
	 *
	 * @param string $action Method name.
	 * @param array $data Data.
	 *
	 * @return ServerResponse
	 */
	public function __call( $action, $data ) {
		// Only argument should be the data array, ignore any others.
		try {
			return Request::send( $action, reset( $data ) ? reset( $data ) : array() );
		} catch ( Throwable $error ) {
			return $this->error_response( $error );
		}
	}

	/**
	 * Use this method to send text messages. On success, the last sent Message is returned
	 *
	 * @param array $data Data.
	 * @param array|null $extras Extra data.
	 *
	 * @return ServerResponse
	 */
	public function sendMessage( $data, &$extras = array() ): ServerResponse {
		try {
			return Request::sendMessage( $data, $extras );
		} catch ( Throwable $error ) {
			return $this->error_response( $error );
		}
	}

	/**
	 * Get error response.
	 *
	 * @param Throwable $error Exception.
	 *
	 * @return ServerResponse
	 */
	protected function error_response( Throwable $error ): ServerResponse {
		$this->error_log( $error->getMessage() );

		return new ServerResponse( array( 'ok' => false, 'result' => $error->getMessage() ) );
	}

	/**
	 * Log error.
	 *
	 * @param string $message Error message.
	 *
	 * @return void
	 */
	protected function error_log( string $message ): void {
		// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		error_log( 'Telegram Bot Exception: ' . $message );
	}
}
