<?php namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\SMS;

defined( 'ABSPATH' ) || exit;

final class Bulk_SMS_Sender {
	/**
	 * SMS sender.
	 *
	 * @var SMS_Sender
	 */
	private $sms_sender;

	/**
	 * Bulk messages.
	 *
	 * @var array
	 */
	private $bulk_messages;

	/**
	 * Contact list ID.
	 *
	 * @var int
	 */
	private $list_id;

	/**
	 * Bulk_SMS_Sender constructor.
	 *
	 * @param SMS_Sender $sms_sender SMS sender.
	 * @param array $bulk_messages Bulk messages.
	 * @param integer $list_id Contact list ID.
	 */
	public function __construct( $sms_sender, $bulk_messages, $list_id ) {
		$this->sms_sender    = $sms_sender;
		$this->bulk_messages = $bulk_messages;
		$this->list_id       = $list_id;

		$this->setup_actions();
	}

	private function setup_actions() {
		// Массовая рассылка
		add_filter( 'bulk_actions-users', [ $this, 'register_send_sms_bulk_actions' ] );
		add_filter( 'handle_bulk_actions-users', [ $this, 'handle_send_sms_bulk_actions' ], 10, 3 );
		add_filter( 'admin_notices', [ $this, 'bulk_notice' ] );
	}

	/**
	 * Добавляю действие в список пакетных операций.
	 *
	 * @param array $actions
	 *
	 * @return array
	 */
	public function register_send_sms_bulk_actions( $actions = [] ) {
		if ( $this->bulk_messages ) {
			foreach ( $this->bulk_messages as $message ) {
				$actions[ 'send-sms-' . sanitize_title( $message['id'] ) ] = 'Отправить "' . $message['title'] . '"';
			}
		}

		return $actions;
	}

	/**
	 * Выполняю пакетную операцию.
	 *
	 * @param string $redirect_to The redirect URL.
	 * @param string $action The action being taken.
	 * @param array $user_ids The items to take the action on.
	 *
	 * @return mixed
	 */
	public function handle_send_sms_bulk_actions( $redirect_to, $action, $user_ids ) {
		// Bail if not a supported bulk action
		if ( empty( $this->bulk_messages ) ) {
			return $redirect_to;
		}

		$actions = [];
		foreach ( $this->bulk_messages as $message ) {
			$actions[] = 'send-sms-' . sanitize_title( $message['id'] );
		}
		if ( ! in_array( $action, $actions ) ) {
			return $redirect_to;
		}

		$sms_bulk_message_id  = substr( $action, 9 );
		$sms_bulk_message_ids = wp_list_pluck( $this->bulk_messages, 'id' );
		$sms_message_index    = array_search( $sms_bulk_message_id, $sms_bulk_message_ids );
		$sms_bulk_message     = $this->bulk_messages[ $sms_message_index ];
		$body                 = $sms_bulk_message['text'];
		if ( empty( $body ) ) {
			set_transient( 'create_campaign', 'no' );

			return $redirect_to;
		}

		// Получаю ИД рассылки.
		$message_id = $this->sms_sender->create_sms_message( $body, $this->list_id );
		if ( empty( $message_id ) ) {
			set_transient( 'create_campaign', 'no' );

			return $redirect_to;
		}

		// Список номеров клиентов для рассылки.
		$customer_phone_numbers = $this->get_customer_phone_numbers( $user_ids );

		$result = $this->sms_sender->create_campaign( $message_id, $customer_phone_numbers );
		set_transient( 'create_campaign', wc_bool_to_string( ! empty( $result->campaign_id ) ) );

		// Return redirection
		return $redirect_to;
	}

	/**
	 * Вывожу результат пакетной операции.
	 */
	public function bulk_notice() {
		$create_campaign = get_transient( 'create_campaign' );
		delete_transient( 'create_campaign' );

		switch ( $create_campaign ) {
			case 'yes' :
				$type = 'success';
				$text = 'СМС рассылка была создана успешно.';
				break;
			case 'no' :
				$type = 'warning';
				$text = 'Произошла ошибка создания СМС рассылки.';
				break;
		}

		if ( isset( $text ) ) {
			// Output message
			include "templates/notice.php";
		}
	}

	/**
	 * Получаю данные клиентов.
	 *
	 * @param array $user_ids
	 *
	 * @return array
	 */
	private function get_customers_data( $user_ids = [] ) {
		global $wpdb;

		// Данные клиентов.
		$data = [];

		// Получаю список всех клиентов.
		$query = <<<EOQ
SELECT u.ID AS id, u.user_email AS email, u.display_name AS name, um1.meta_value AS phone, um2.meta_value AS capabilities
FROM {$wpdb->users} AS u
LEFT JOIN {$wpdb->usermeta} AS um1 ON u.ID = um1.user_id AND um1.meta_key='billing_phone'
LEFT JOIN {$wpdb->usermeta} AS um2 ON u.ID = um2.user_id AND um2.meta_key='wp_capabilities'
WHERE um2.meta_value LIKE '%customer%'
EOQ;
		if ( ! empty( $user_ids ) ) {
			$_user_ids = implode( ',', $user_ids );
			$query     .= " AND u.ID IN ({$_user_ids})";
		}

		$users = $wpdb->get_results( $query );

		// Получаю необходимые данные всех клиентов.
		foreach ( $users as $user ) {
			// Для фейковых адресов почты установить статус "неактивный"
			$email_status = ( false === strpos( $user->email, '@dummy.email' ) ) ? 'active' : 'inactive';

			if ( empty( $user->phone ) ) {
				if ( false !== strpos( $user->email, '@dummy.email' ) ) {
					$phone = substr( $user->email, 0, - 12 );
				} else {
					$phone = '';
				}
			} else {
				$phone = $user->phone;
			}

			if ( empty( $phone ) ) {
				continue;
			}

			$phone = '+380' . intval( substr( $phone, - 9 ) );
			if ( 13 !== strlen( $phone ) ) {
				continue;
			}

			update_user_meta( $user->id, 'billing_phone', $phone );

			$data[] = [ $user->name, $user->email, $email_status, $phone, 'active', $this->list_id ];
		}

		return $data;
	}

	/**
	 * Получаю номера телефонов клиентов.
	 *
	 * @param array $user_ids
	 *
	 * @return array
	 */
	private function get_customer_phone_numbers( $user_ids = [] ) {
		// Данные клиентов.
		$data = $this->get_customers_data( $user_ids );

		return wp_list_pluck( $data, 3 );
	}
}
