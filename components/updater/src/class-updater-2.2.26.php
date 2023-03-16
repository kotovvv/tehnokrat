<?php
/**
 * Class Updater_2_2_26
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Updater
 */

namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Updater;

defined( 'ABSPATH' ) || exit;

/**
 * Class Updater_2_2_26
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Updater
 */
final class Updater_2_2_26 extends Abstract_Updater {
	/**
	 * Возвращает версию обновления.
	 *
	 * @return string
	 */
	public function get_version() {
		return '2.2.26';
	}

	/**
	 * Выполняет обновление.
	 *
	 * @return string|null
	 */
	public function __invoke() {
		$old_fields = get_fields( 'tehnokrat' );
		$fields_map = array(
			'usd_to_uah'                                  => 'tehnokrat_main',
			'phone_numbers'                               => 'tehnokrat_main',
			'show_acf'                                    => 'tehnokrat_main',
			'bank_transfer_fee'                           => 'tehnokrat_main',
			'delivery_cities'                             => 'tehnokrat_delivery_cities',
			'site_key'                                    => 'tehnokrat_recaptcha',
			'secret_key'                                  => 'tehnokrat_recaptcha',
			'telegram_bot_token'                          => 'tehnokrat_telegram',
			'telegram_user_id'                            => 'tehnokrat_telegram',
			'sms_api_key'                                 => 'tehnokrat_sms',
			'sms_api_url'                                 => 'tehnokrat_sms',
			'sms_alpha_name'                              => 'tehnokrat_sms',
			'sms_order_processing_text_working_hours'     => 'tehnokrat_sms',
			'sms_order_processing_text_not_working_hours' => 'tehnokrat_sms',
			'sms_bulk_messages'                           => 'tehnokrat_sms',
			'sms_ask_for_feedback'                        => 'tehnokrat_sms',
			'sms_pre_order_message'                       => 'tehnokrat_sms',
			'rozetka_all_available'                       => 'tehnokrat_feeds',
			'rozetka_set_price_to_2000'                   => 'tehnokrat_feeds',
			'rozeka_fee'                                  => 'tehnokrat_feeds',
			'categories_for_rozetka'                      => 'tehnokrat_feeds',
			'seo_tags'                                    => 'tehnokrat_various',
			'products_attributes'                         => 'tehnokrat_various',
			'installment_payments_cities'                 => 'tehnokrat_installment_payments',
			'installment_payments_alfabank_description'   => 'tehnokrat_installment_payments',
			'installment_payments_privatbank_description' => 'tehnokrat_installment_payments',
			'colors_mapping'                              => 'tehnokrat_colors',
			'redirect_map'                                => 'tehnokrat_redirect_map',
			'shortcode_bestsellers'                       => 'tehnokrat_shortcodes',
		);

		foreach ( $old_fields as $old_field => $value ) {
			$post_id = $fields_map[ $old_field ];
			if ( $post_id ) {
				update_field( $old_field, $value, $post_id );
			}
		}

		return $this->get_version();
	}
}

return new Updater_2_2_26();
