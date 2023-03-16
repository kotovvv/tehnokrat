<?php namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\SMS;

use Unisender\ApiWrapper\UnisenderApi;
use WP_Log;

defined( 'ABSPATH' ) || exit;

/**
 * SMS API.
 *
 * @link https://www.unisender.com/ru/support/integration/api/
 *
 * @method sendSms( array $params ) It is a method for easy sending the one SMS to one or several recipients.
 * @method createSmsMessage( array $params ) It is a method to create SMS messages without sending them.
 * @method createCampaign( array $params ) This method is used to schedule or immediately start sending email
 * or SMS messages.
 */
class SMS_Sender {
	/**
	 * @var UnisenderApi
	 */
	private $unisender;

	/**
	 * @var string
	 */
	private $alpha_name;

	/**
	 * SMS_Sender constructor.
	 *
	 * @param string $api_key
	 * @param string $alpha_name
	 */
	public function __construct( $api_key, $alpha_name ) {
		$this->unisender  = new UnisenderApi( $api_key );
		$this->alpha_name = $alpha_name;
	}

	public function __call( $method, $parameters ) {
		$result = null;

		if ( $this->unisender instanceof UnisenderApi ) {
			$result = json_decode( call_user_func_array( [ $this->unisender, $method ], $parameters ) );
			$result = empty( $result->result ) ? $result : $result->result;

			wp_log(
				empty( $result->error ) ? WP_Log::DEBUG : WP_Log::ERROR,
				"{$method} result:",
				compact( 'parameters', 'result' )
			);
		} else {
			wp_log_error( 'UnisenderApi is not initialized.' );
		}

		return $result;
	}

	public function send( $phone, $text ) {
		$this->sendSms(
			[ 'phone' => $phone, 'sender' => $this->alpha_name, 'text' => $text ]
		);
	}

	public function create_sms_message( $body, $list_id ) {
		$result = $this->createSmsMessage(
			[ 'list_id' => $list_id, 'sender' => $this->alpha_name, 'body' => $body ]
		);

		return empty( $result->message_id ) ? null : $result->message_id;
	}

	public function create_campaign( $message_id, $contacts ) {
		return $this->createCampaign(
			[
				'message_id' => $message_id,
				'contacts'   => implode( ',', $contacts ),
			]
		);
	}
}
