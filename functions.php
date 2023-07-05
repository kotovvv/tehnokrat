<?php

namespace BytePerfect\WordPress\Theme\Tehnokrat;

defined('ABSPATH') || exit;

require_once 'vendor/autoload.php';

use BytePerfect\WordPress\Theme\Tehnokrat\Components\Google_Recaptcha\Google_Recaptcha;
use BytePerfect\WordPress\Theme\Tehnokrat\Components\Pages\Page_Factory;
use BytePerfect\WordPress\Theme\Tehnokrat\Components\Shortcodes\Shortcodes;
use BytePerfect\WordPress\Theme\Tehnokrat\Components\SMS\SMS_Sender;
use BytePerfect\WordPress\Theme\Tehnokrat\Components\SMS\Bulk_SMS_Sender;
use BytePerfect\WordPress\Theme\Tehnokrat\Components\Telegram_Bot\Bot;
use BytePerfect\WordPress\Theme\Tehnokrat\Components\Updater\Updater;
use BytePerfect\WordPress\Theme\Tehnokrat\Components\Upload_Product_Translations\Upload_Product_Translations;
use BytePerfect\WordPress\Theme\Tehnokrat\Components\WC1C\WC1C;
use BytePerfect\WordPress\Theme\Tehnokrat\Components\Woocommerce\Woocommerce;
use Exception;
use Longman\TelegramBot\Entities\InputMedia\InputMediaPhoto;
use Longman\TelegramBot\Request;
use Puc_v4_Factory;
use Throwable;
use WC_Email;

if (!in_array(
	'woocommerce/woocommerce.php',
	apply_filters('active_plugins', get_option('active_plugins'))
)) :
	return;
endif;

final class Tehnokrat
{
	/**
	 * @var string Идентификатор темы.
	 */
	private $identifier;

	/**
	 * @var string Версия темы.
	 */
	private $version;

	/**
	 * @var SMS_Sender
	 */
	private $sms_sender;

	/**
	 * @var Bot
	 */
	private $telegram_bot;

	public static function instance()
	{
		static $instance = null;

		if (is_null($instance)) {
			$instance = new static();
		}

		return $instance;
	}

	private function __construct()
	{
		$this->setup_globals();
		$this->setup_actions();

		new Updater($this->get_identifier(), $this->get_version());

		$this->sms_sender = new SMS_Sender(
			apply_filters(
				'tehnokrat_settings.components.sms.api_key',
				$this->get_setting('sms', 'sms_api_key')
			),
			apply_filters(
				'tehnokrat_settings.components.sms.alpha_name',
				$this->get_setting('sms', 'sms_alpha_name')
			)
		);
		if (is_admin()) {
			new Bulk_SMS_Sender(
				$this->sms_sender,
				apply_filters(
					'tehnokrat_settings.components.sms.bulk_messages',
					$this->get_setting('sms', 'sms_bulk_messages')
				),
				19690661
			);
		}

		//		$this->telegram_bot = new Bot(
		//			apply_filters( 'tehnokrat_settings.components.telegram_bot.token',
		//				$this->get_setting( 'telegram', 'telegram_bot_token' ) )
		//		);

		new Shortcodes();

		Page_Factory::initialize();

		new Woocommerce();

		new WC1C();

		if (!wp_next_scheduled('ask_for_feedback')) {
			wp_schedule_single_event(strtotime('tomorrow noon'), 'ask_for_feedback');
		}
	}

	private function __clone()
	{
	}

	public function __wakeup()
	{
	}

	public function get_identifier()
	{
		return $this->identifier;
	}

	public function get_version()
	{
		return $this->version;
	}

	public function get_url($path = '')
	{
		return get_template_directory_uri() . ($path ?: '');
	}

	public function get_path($path = '')
	{
		return get_template_directory() . ($path ?: '');
	}

	public function display_logo()
	{
		printf(
			'<a href="%s"><img bp-pso-lazyload-skip src="%s" alt="tehnokrat.ua logo" /></a>',
			esc_url('ua' === get_request_locale() ? UA_SITE_URL : RU_SITE_URL),
			esc_url(get_template_directory_uri() . '/img/logo.svg')
		);
	}

	/**
	 * Выводит ссылку на страницу личного кабинета.
	 *
	 * @return void
	 */
	public function display_link_to_account_page()
	{
		$current_user = wp_get_current_user();

		if (is_user_logged_in()) {
			$login_name = $current_user->display_name;
			if (mb_strlen($current_user->display_name) >= 20) {
				$login_name = mb_substr($login_name, 0, 17) . '...';
			}

			$login_image_url = get_template_directory_uri() . '/img/003-user-white.png';
		} else {
			$login_name = wp_is_mobile() ? 'Личный кабинет' : '';

			$login_image_url = get_template_directory_uri() . '/img/003-user-2.png';
		}

		printf(
			'<a class="account" href="%s"><span class="login-name">%s</span><img src="%s" alt="avatar"/></a>',
			esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))),
			esc_html($login_name),
			esc_url($login_image_url)
		);
	}

	private function setup_globals()
	{
		$theme            = wp_get_theme();
		$this->identifier = $theme->get_stylesheet();
		$this->version    = $theme->get('Version');

		acf_add_options_page(
			[
				'page_title' => 'Технократ',
				'menu_slug'  => $this->identifier,
				'icon_url'   => 'dashicons-admin-generic',
				'post_id'    => $this->identifier,
				'autoload'   => true,
			]
		);

		$sub_pages = [
			'main'                 => 'Основные настройки',
			//			'recaptcha'            => 'reCAPTCHA',
			'telegram'             => 'Telegram',
			'sms'                  => 'СМС уведомления',
			'colors'               => 'Цвета',
			'delivery_cities'      => 'Города доставки',
			'redirect_map'         => 'Сопоставление адресов',
			'feeds'                => 'Настройки фидов',
			'shortcodes'           => 'Шорткоды',
			'installment_payments' => 'Купить в рассрочку',
			'various'              => 'Разное',
			'product_attributes'   => 'Атрибуты товаров',
			'emails'               => 'Email\'ы',
		];

		foreach ($sub_pages as $slug => $title) {
			acf_add_options_sub_page(
				[
					'page_title'  => "Технократ. {$title}",
					'menu_title'  => $title,
					'menu_slug'   => "{$this->identifier}_{$slug}",
					'parent_slug' => $this->identifier,
					'post_id'     => "{$this->identifier}_{$slug}",
					'autoload'    => true,
				]
			);
		}
	}

	private function setup_actions()
	{
		$slug = basename(__DIR__);
		Puc_v4_Factory::buildUpdateChecker(
			'https://wpdeploy.byteperfect.dev/checker.php?action=get_metadata&slug=' . $slug,
			__FILE__,
			$slug
		);

		add_filter('acf/pre_load_value', function ($value, $post_id, $field) {
			if ($post_id === $this->identifier) {
				WP_Log::instance()->error(wp_json_encode($field));
			}

			return $value;
		}, 10, 3);
		//kotovvv
		add_filter('woocommerce_get_breadcrumb', [$this, 'change_breadcrumb']);
		add_filter('woocommerce_breadcrumb_defaults', [$this, 'wcc_change_breadcrumb_delimiter']);
		add_filter('woocommerce_available_payment_gateways', [$this, 'payment_gateway_disable_product']);
		add_action('woocommerce_before_thankyou', [$this, 'put_html_in_order'], 50);

		//		add_filter( 'sanitize_title', [ $this, 'remove_cyrillic_symbols' ], - PHP_INT_MAX );
		add_action('after_setup_theme', [$this, 'setup_theme']);

		add_action('parse_request', [$this, 'get_query_vars_for_redirect_map']);
		if (is_admin()) {
			add_action('acf/save_post', [$this, 'on_save_tehnokrat_options'], 20);
			// update comment karma
			add_filter('acf/load_value/name=stars_count', [$this, 'get_comment_karma'], 10, 2);
			add_filter('acf/update_value/name=stars_count', [$this, 'set_comment_karma'], 10, 2);

			add_filter('rewrite_rules_array', [$this, 'rewrite_rules_for_404']);

			add_filter(
				'acf/settings/show_admin',
				$this->get_setting('main', 'show_acf') ? '__return_true' : '__return_false'
			);

			add_action("wp_ajax_{$this->identifier}_get_comments", [$this, 'get_comments']);
			add_action("wp_ajax_nopriv_{$this->identifier}_get_comments", [$this, 'get_comments']);

			add_action("wp_ajax_{$this->identifier}_post_comment", [$this, 'post_comment']);
			add_action("wp_ajax_nopriv_{$this->identifier}_post_comment", [$this, 'post_comment']);

			add_filter('manage_shop_order_posts_columns', [$this, 'manage_shop_order_columns'], 20);
			add_action('manage_shop_order_posts_custom_column', [$this, 'show_order_items']);
			add_filter(
				'woocommerce_get_formatted_order_total',
				[$this, 'get_formatted_order_total'],
				10,
				2
			);
			add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts'], 20);

			add_filter(
				'manage_product_cat_custom_column',
				[$this, 'fix_product_cat_reorder'],
				-PHP_INT_MAX
			);
		} else {
			add_action('wp_footer', [$this, 'load_vue_templates'], -PHP_INT_MAX);
			remove_action(
				'wp_footer',
				'woocommerce_demo_store'
			); // remove default woocommerce demo store notice

			add_action('pre_get_posts', [$this, 'show_only_featured_products_on_shop_page']);

			add_filter('loop_shop_per_page', '__return_zero');

			add_filter('wp_robots', array($this, 'set_meta_robots_for_ua_pages'), PHP_INT_MAX);
		}
		add_filter('woocommerce_add_to_cart_fragments', [$this, 'woocommerce_add_to_cart_fragments']);
		//		remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );
		//		add_filter( 'woocommerce_cart_totals_coupon_html', [ $this, 'woocommerce_cart_totals_coupon_html' ],
		//			10, 3 );

		add_filter('woocommerce_billing_fields', array($this, 'woocommerce_billing_fields'));

		add_action(
			'woocommerce_checkout_order_processed',
			[$this, 'woocommerce_checkout_order_processed']
		);

		add_action('ask_for_feedback', [$this, 'ask_for_feedback']);
		add_filter('woocommerce_new_order_note_data', [$this, 'filter_customer_note'], 10, 2);
		add_action('woocommerce_new_customer_note', [$this, 'send_customer_note_sms']);

		//		add_filter( 'gettext', [ $this, 'gettext' ], 10, 3 );

		add_action('wp', [$this, 'save_last_shop_page']);

		add_filter('wc_price_args', [$this, 'change_order_currency_symbol']);

		add_action("wp_ajax_{$this->identifier}_create_pre_order", [$this, 'create_pre_order']);
		add_action("wp_ajax_nopriv_{$this->identifier}_create_pre_order", [$this, 'create_pre_order']);
		// Schedule pre_order daily check
		if (!wp_next_scheduled("{$this->identifier}_check_pre_order")) {
			wp_schedule_event(time(), 'daily', "{$this->identifier}_check_pre_order");
		}
		add_action("{$this->identifier}_check_pre_order", [$this, 'check_pre_order']);

		//		add_filter( 'woocommerce_get_order_item_totals', [ $this, 'amend_order_item_totals' ] );
		add_action(
			'woocommerce_after_checkout_validation',
			[$this, 'validate_billing_phone'],
			PHP_INT_MAX,
			2
		);
		add_filter('woocommerce_checkout_posted_data', [$this, 'format_billing_phone_on_checkout']);

		add_action(
			'woocommerce_order_status_pending_to_processing_notification',
			[$this, 'disable_cod_processing_notification'],
			-PHP_INT_MAX,
			2
		);

		add_filter('wc_order_statuses', [$this, 'order_statuses']);

		add_action(
			"wp_ajax_{$this->identifier}_submit_trade_in_pop_up",
			[$this, 'submit_trade_in_pop_up']
		);
		add_action(
			"wp_ajax_nopriv_{$this->identifier}_submit_trade_in_pop_up",
			[$this, 'submit_trade_in_pop_up']
		);

		add_action(
			'woocommerce_before_template_part',
			function ($template_name, $template_path, $located, $args) {
				global $email_custom_text_variables;

				if (
					isset($args['email'])
					&&
					$template_name === $args['email']->template_html
				) {
					foreach ($args as $key => $value) {
						if (is_scalar($value)) {
							$email_custom_text_variables['{' . $key . '}'] = strval($value);
						}
					}

					if (isset($args['reset_key'], $args['user_id'])) {
						$email_custom_text_variables['{reset_password_url}'] = add_query_arg(
							array('key' => $args['reset_key'], 'id' => $args['user_id']),
							wc_get_endpoint_url('lost-password', '', wc_get_page_permalink('myaccount'))
						);
					}

					$email_custom_text_variables['{blogname}']      = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
					$email_custom_text_variables['{myaccount_url}'] = wc_get_page_permalink('myaccount');

					if (isset($args['order'])) {
						$email_custom_text_variables['{user_login}']                 = esc_html($args['order']->get_billing_first_name());
						$email_custom_text_variables['{order_number}']               = $args['order']->get_order_number();
						$email_custom_text_variables['{order_date_created}']         = wc_format_datetime($args['order']->get_date_created());
						$email_custom_text_variables['{order_checkout_payment_url}'] = $args['order']->get_checkout_payment_url();
					}
				}
			},
			10,
			4
		);

		add_filter('acf/load_field/name=label_colors', array($this, 'set_label_colors_field'));
		add_filter('acf/load_value/name=label_colors', array($this, 'set_label_colors_value'));
	}

	public function send_customer_note_sms($customer_note_data)
	{
		if ($order = wc_get_order($customer_note_data['order_id'])) {
			$this->send_sms($order->get_billing_phone(), $customer_note_data['customer_note']);
		}
	}

	private function send_sms($phone, $message)
	{
		$phone = '+380' . intval(substr($phone, -9));

		if ('development' !== wp_get_environment_type() && 13 === strlen($phone) && $message) {
			$this->sms_sender->send($phone, $message);
		}
	}

	public function filter_customer_note($comment_data, $customer_note_data)
	{
		if ($customer_note_data['is_customer_note']) {
			if ($order = wc_get_order($customer_note_data['order_id'])) {
				$comment_data['comment_content'] = str_replace(
					['{customer_name}', '{order_id}'],
					[$order->get_billing_first_name(), $order->get_order_number()],
					$comment_data['comment_content']
				);
			}
		}

		return $comment_data;
	}


	public
	function wcc_change_breadcrumb_delimiter($defaults)
	{
		// Change the breadcrumb delimeter from '/' to '>'
		$defaults['delimiter'] = ' <svg width="10" height="7" viewBox="0 0 10 7" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M6.47004 0.995698L8.20321 2.91665L0.526319 2.91665C0.235791 2.91665 2.13759e-07 3.17799 2.13759e-07 3.5C2.13759e-07 3.82201 0.235791 4.08335 0.526319 4.08335L8.20321 4.08335L6.47004 6.0043C6.36715 6.11805 6.31583 6.26739 6.31583 6.41673C6.31583 6.56606 6.36715 6.7154 6.47004 6.82915C6.67557 7.05695 7.00873 7.05695 7.21426 6.82915L9.84585 3.91243C10.0514 3.68492 10.0514 3.31508 9.84585 3.08757L7.21426 0.170847C7.00873 -0.0569491 6.67557 -0.0569491 6.47004 0.170847C6.26452 0.398352 6.26452 0.768193 6.47004 0.995698Z" fill="#77BC01"/> </svg> ';
		$defaults['wrap_before'] = '<ul class="breadcrumbs">';
		$defaults['wrap_after']  = '</ul>';
		$defaults['before']      = '<li>';
		$defaults['after']       = '</li>';

		return $defaults;
	}

	public function change_breadcrumb($crumbs)
	{
		foreach ($crumbs as $crumb) {
			$crumb[0] =	trim(preg_replace('/\[.*\]/', '', $crumb[0]));
		}

		return $crumbs;
	}
	public function payment_gateway_disable_product($available_gateways)
	{
		$uset_monobank = false;
		$uset_privatbank = false;
		// Make sure it's only on front end
		if (is_admin()) return false;
		if (!did_action('wp_loaded')) {
			return false;
		}
		if (!method_exists(WC()->cart, 'get_cart')) {
			return false;
		} else {
			foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
				if (get_field('part', $cart_item['product_id']) == 0 && $uset_monobank == false) {
					$uset_monobank = true;
					unset($available_gateways['monobank']);
				}
				if (get_field('partprivat', $cart_item['product_id']) == 0 && $uset_privatbank == false) {
					$uset_privatbank = true;
					unset($available_gateways['privat_join']);
				}
			}
		}
		return $available_gateways;
	}

	public function put_html_in_order($order)
	{
		if ($order->payment_method == "monobank") { ?>
<div class="mono-vid"><img class="iphone-mask" style="" src="/wp-content/uploads/2023/03/iphone-teh.png">
  <video style="width: 100%;z-index: 0;position: relative;" autoplay="" loop="" muted="" playsinline="" controls="">
    <source src="/wp-content/uploads/2023/03/Mono.mp4" type="video/mp4">
  </video>
</div>
<?php }
	}

	public function setup_theme()
	{
		/*
		 * Make theme available for translation.
		 */
		load_theme_textdomain('tehnokrat', get_template_directory() . '/languages');

		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support('title-tag');

		/*
		 * Enable support for custom logo.
		 *
		 *  @since Twenty Sixteen 1.2
		 */
		//		add_theme_support( 'custom-logo', array(
		//			'height'      => 240,
		//			'width'       => 240,
		//			'flex-height' => true,
		//		) );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support('post-thumbnails');
		//		set_post_thumbnail_size( 1200, 9999 );

		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus([
			'header_categories' => __('Хедер. Категории.', $this->identifier),
			'footer_left'       => __('Футер. Меню слева.', $this->identifier),
			'footer_middle'     => __('Футер. Меню посередине.', $this->identifier),
			'footer_right'      => __('Футер. Меню справа.', $this->identifier),
		]);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support('html5', [
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		]);

		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support('post-formats', [
			'aside',
			'image',
			'video',
			'quote',
			'link',
			'gallery',
			'status',
			'audio',
			'chat',
		]);

		add_theme_support('woocommerce');
	}

	public function load_vue_templates()
	{
		require_once('partials/vue-template-common.php');
		require_once('partials/vue-template-template0.php');
		require_once('partials/vue-template-template1.php');
		require_once('partials/vue-template-template2.php');
		require_once('partials/vue-template-template3.php');
		require_once('partials/vue-template-template4.php');
		require_once('partials/vue-template-template5.php');
		require_once('partials/vue-template-template6.php');
		require_once('partials/vue-template-template7.php');
		require_once('partials/vue-template-template8.php');
		require_once('partials/vue-template-template9.php');
		require_once('partials/vue-template-template10.php');
		require_once('partials/vue-template-template11.php');
		require_once('partials/vue-template-template12.php');
		require_once('partials/vue-template-template13.php');
		require_once('partials/vue-template-template14.php');
		require_once('partials/vue-template-template15.php');
		require_once('partials/vue-template-template16.php');
		require_once('partials/vue-template-template17.php');
		require_once('partials/vue-template-template18.php');
		require_once('partials/vue-template-template19.php');
		require_once('partials/vue-template-template20.php');
	}

	/**
	 * @param \WP_Query $query
	 */
	public function show_only_featured_products_on_shop_page($query)
	{
		if ($query->is_main_query() && is_product_taxonomy()) {
			$query->set('fields', 'ids');
			$query->set('cache_results', false);
			$query->set('update_post_meta_cache', false);
			$query->set('update_post_term_cache', false);
		}

		if ($query->is_main_query() && $query->is_post_type_archive() && is_shop()) {
			$query->set('tax_query', [
				[
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => ['featured-products'],
					'operator' => 'IN',
				],
			]);
		}
	}

	public function color_to_hex($color)
	{
		$hex_code = '#000000';
		foreach ($this->get_setting('colors', 'colors_mapping') as $color_map) {
			if (wc_strtolower($color) == wc_strtolower($color_map['color_name'])) {
				$hex_code = $color_map['hex_code'];
				break;
			}
		}

		return $hex_code;
	}

	public function get_category_products()
	{
		return apply_filters('get_category_products', array());
	}

	public function get_comments()
	{
		$page             = empty($_GET['page']) ? 0 : intval($_GET['page']);
		$comments_to_show = empty($_GET['commentsToShow']) ? 'all' : $_GET['commentsToShow'];
		$nonce            = empty($_GET['nonce']) ? '' : $_GET['nonce'];

		// проверка номера страницы
		if (!$page) {
			wp_send_json_error('Ошибка: ' . __FUNCTION__ . '|' . __LINE__);
		}

		// проверка nonce
		if (!wp_verify_nonce($nonce, $this->identifier)) {
			wp_send_json_error('Ошибка: ' . __FUNCTION__ . '|' . __LINE__);
		}

		$args     = [
			'hierarchical' => 'threaded',
			'number'       => 10,
			'offset'       => ($page - 1) * 10,
			'post_id'      => url_to_postid($_SERVER['HTTP_REFERER']),
		];
		$comments = [];
		if (in_array($comments_to_show, ['positive', 'negative'])) {
			add_filter('comments_clauses', [$this, 'filter_comments_by_karma_' . $comments_to_show]);
		}
		/** @var \WP_Comment $comment */
		foreach (get_comments($args) as $comment) {
			$comments[] = [
				'id'        => $comment->comment_ID,
				'karma'     => $comment->comment_karma,
				'content'   => $comment->comment_content,
				'author'    => $comment->comment_author,
				'date'      => date_i18n(get_option('date_format'), strtotime($comment->comment_date)),
				'is_answer' => false,
			];
			foreach ($comment->get_children() as $child_comment) {
				$comments[] = [
					'id'        => $child_comment->comment_ID,
					'karma'     => $child_comment->comment_karma,
					'content'   => $child_comment->comment_content,
					'author'    => $child_comment->comment_author,
					'date'      => date_i18n(
						get_option('date_format'),
						strtotime($child_comment->comment_date)
					),
					'is_answer' => true,
				];
			}
		}

		wp_send_json_success($comments);
	}

	public function filter_comments_by_karma_all($pieces)
	{
		remove_filter('comments_clauses', [$this, 'filter_comments_by_karma_all']);

		return $pieces;
	}

	public function filter_comments_by_karma_positive($pieces)
	{
		remove_filter('comments_clauses', [$this, 'filter_comments_by_karma_positive']);

		$pieces['where'] .= ' AND comment_karma > 2';

		return $pieces;
	}

	public function filter_comments_by_karma_negative($pieces)
	{
		remove_filter('comments_clauses', [$this, 'filter_comments_by_karma_negative']);

		$pieces['where'] .= ' AND comment_karma < 3';

		return $pieces;
	}

	public function post_comment()
	{
		$nonce   = empty($_POST['nonce']) ? '' : $_POST['nonce'];
		$name    = empty($_POST['name']) ? '' : sanitize_text_field($_POST['name']);
		$email   = empty($_POST['email']) && is_email($_POST['email']) ? '' : sanitize_email($_POST['email']);
		$content = empty($_POST['content']) ? '' : sanitize_text_field($_POST['content']);
		$karma   = empty($_POST['karma']) ? 0 : intval($_POST['karma']);

		// проверка имени пользователя
		if (!$name) {
			wp_send_json_error('Ошибка: ' . __FUNCTION__ . '|' . __LINE__);
		}

		// проверка коментария пользователя
		if (!$content) {
			wp_send_json_error('Ошибка: ' . __FUNCTION__ . '|' . __LINE__);
		}

		// проверка nonce
		if (!wp_verify_nonce($nonce, $this->identifier)) {
			wp_send_json_error('Ошибка: ' . __FUNCTION__ . '|' . __LINE__);
		}

		// Проверка рекаптчи.
		$resp = Google_Recaptcha::instance()->verify(
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			isset($_POST['token']) ? $_POST['token'] : ''
		);
		if (true !== $resp) {
			wp_send_json_error('Ошибка: Ты робот?');
		}

		$data = [
			'comment_post_ID'      => url_to_postid($_SERVER['HTTP_REFERER']),
			'comment_author'       => $name,
			'comment_author_email' => $email,
			'comment_content'      => $content,
			'comment_approved'     => 0,
			'comment_karma'        => $karma,
		];
		if (wp_insert_comment($data)) {
			$this->send_bot_message("<b>Новый коментарий: </b>{$content}");

			wp_send_json_success();
		} else {
			wp_send_json_error();
		}
	}

	public function on_save_tehnokrat_options($post_id)
	{
		global $wpdb;

		if (0 === strpos($post_id, 'tehnokrat_various')) {
			// Upload product translations.
			$attachment_id = $this->get_setting('various', 'translations');
			if ($attachment_id && is_integer($attachment_id)) {
				update_field('translations', false, $post_id);

				$filename = get_attached_file($attachment_id);
				if (false !== $filename) {
					try {
						$upload_product_translations = new Upload_Product_Translations();
						$upload_product_translations->process($filename);
					} catch (Throwable $e) {
						wp_log_error(
							'Upload Product Translations Exception: ' . $e->getMessage()
						);
					}
				} else {
					wp_log_warning(
						'Не удалось получить файл для импорта. $attachment_id=' . $attachment_id
					);
				}

				wp_delete_attachment($attachment_id, true);
			}

			// update SEO meta
			if ($attachment_id = $this->get_setting('various', 'seo_tags')) {
				update_field('seo_tags', false, $this->identifier);
				$filename = get_attached_file($attachment_id);
				if ($handle = fopen($filename, 'r')) {
					$get_post_id_query = "SELECT ID FROM {$wpdb->posts} WHERE post_title=%s";
					$get_meta_id_query = "SELECT meta_id FROM {$wpdb->postmeta} WHERE meta_key=%s AND post_id=%d";

					while ($data = fgetcsv($handle)) {
						$post_id = absint($wpdb->get_var($wpdb->prepare($get_post_id_query, $data[0])));
						if ($post_id) {
							foreach ([
								'_yoast_wpseo_title',
								'_yoast_wpseo_metadesc',
							] as $index => $meta_key) {
								$meta_value = $data[$index + 1];

								$meta_ids = $wpdb->get_var($wpdb->prepare(
									$get_meta_id_query,
									$meta_key,
									$post_id
								));
								if (empty($meta_ids)) {
									$wpdb->insert(
										$wpdb->postmeta,
										[
											'post_id'    => $post_id,
											'meta_key'   => $meta_key,
											'meta_value' => $meta_value,
										],
										['%d', '%s', '%s']
									);
								} else {
									$wpdb->update(
										$wpdb->postmeta,
										['meta_value' => $meta_value],
										['meta_key' => $meta_key, 'post_id' => $post_id],
										['%s'],
										['%s', '%d']
									);
								}
							}
						}
					}
					fclose($handle);
				}
				wp_delete_attachment($attachment_id, true);
			}

			// set/update products attributes
			if ($attachment_id = $this->get_setting('various', 'products_attributes')) {
				update_field('products_attributes', false, $this->identifier);

				$filename = get_attached_file($attachment_id);

				ini_set('auto_detect_line_endings', true);
				if ($handle = fopen($filename, 'r')) {
					$logger  = wc_get_logger();
					$context = ['source' => 'tehnokrat_import_products_attributes'];

					while ($data = fgetcsv($handle)) {
						$product_id = absint($wpdb->get_var($wpdb->prepare(
							"SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_woocommerce-moysklad-synchronization_id' AND meta_value=%s",
							$data[0]
						)));
						if ($product = wc_get_product($product_id)) {
							if ($product_attribute_slugs = array_filter(array_slice($data, 3))) {
								$attributes = [];
								$query      = sprintf(
									'SELECT t.slug, tt.taxonomy, tt.term_id, at.attribute_id
									FROM %s AS t
									JOIN %s AS tt ON t.term_id=tt.term_id
									JOIN %swoocommerce_attribute_taxonomies AS at ON SUBSTRING(tt.taxonomy, 4)=at.attribute_name
									WHERE t.slug IN ("%s")',
									$wpdb->terms,
									$wpdb->term_taxonomy,
									$wpdb->prefix,
									implode('","', array_map(
										'trim',
										array_map([$wpdb, '_real_escape'], $product_attribute_slugs)
									))
								);
								foreach ($wpdb->get_results($query) as $attribute) {
									$attributes[$attribute->slug] = new \WC_Product_Attribute();
									$attributes[$attribute->slug]->set_id($attribute->attribute_id);
									$attributes[$attribute->slug]->set_name($attribute->taxonomy);
									$attributes[$attribute->slug]->set_options([intval($attribute->term_id)]);
									$attributes[$attribute->slug]->set_position(array_search(
										$attribute->slug,
										$product_attribute_slugs
									));
									$attributes[$attribute->slug]->set_visible(true);
									$attributes[$attribute->slug]->set_variation(false);
								}
								$product->set_attributes($attributes);
								$product->save();

								if (count($product_attribute_slugs) != count($attributes)) {
									$logger->error(
										sprintf(
											"Product ID <%s> attributes mismatch. Expected: %s. Obtained: %s.",
											$product_id,
											wc_print_r($product_attribute_slugs, true),
											wc_print_r(array_keys($attributes), true)
										),
										$context
									);
								}
							} else {
								$logger->error(
									"Product ID <{$product_id}>: empty attributes list!",
									$context
								);
							}
						} else {
							$logger->error("UUID <{$data[0]}>: not found!", $context);
						}
					}

					fclose($handle);
				}
				ini_set('auto_detect_line_endings', false);
				wp_delete_attachment($attachment_id, true);
			}

			flush_rewrite_rules();
		}
	}

	public function get_query_vars_for_redirect_map($args)
	{
		if (!empty($_SERVER['HTTP_REDIRECTMAP'])) {
			echo 'index.php?' . urldecode(http_build_query($args->query_vars));
			die();
		}
	}

	public function rewrite_rules_for_404($rules)
	{
		$categories_links_map = [
			'g6622100-apple-imac$'        => 'index.php?product_cat=mac/imac',
			'g6622132-apple-mac-mini$'    => 'index.php?product_cat=mac/mac-mini',
			'g6622095-apple-macbook-pro$' => 'index.php?product_cat=mac/macbook-pro',
			'g9252940-apple-mac-pro$'     => 'index.php?product_cat=mac/mac-pro',
			'g6622114-apple-macbook$'     => 'index.php?product_cat=mac/macbook',
			'g6622131-apple-macbook-air$' => 'index.php?product_cat=mac/macbook-air',
			//			'product/apple-imac-21-retina-4k-z0tk000r6-mndy24-mid-2017-3-0ghz16gb1tb-hdd555$' => 'index.php?product_cat=mac/imac',
		];

		if ($options_redirect_map = $this->get_setting('redirect_map', 'redirect_map')) {
			$redirect_map  = get_option("{$this->identifier}__redirect_map", []);
			$_redirect_map = [];
			foreach ($options_redirect_map as $urls) {
				if (empty($redirect_map[$urls['url_new']])) {
					$response   = wp_remote_get(
						$urls['url_new'],
						['headers' => ['redirectmap' => true]]
					);
					$query_vars = is_wp_error($response) ? '' : wp_remote_retrieve_body($response);
				} else {
					$query_vars = $redirect_map[$urls['url_new']];
				}
				$url_404                           = trim(str_replace(
					site_url('/'),
					'',
					$urls['url_404']
				), '/') . '$';
				$_redirect_map[$urls['url_new']] = $query_vars;
				$rules                             = [$url_404 => $query_vars] + $rules;
			}
			update_option("{$this->identifier}__redirect_map", $_redirect_map, false);
		}

		return $categories_links_map + $rules;
	}

	public function get_price_in_usd($price)
	{
		$exchange_rate = $this->get_setting('main', 'usd_to_uah');
		$price         = ceil(floatval($price) / $exchange_rate);

		return $price;
	}

	private function send_bot_message($message)
	{
		$message = [
			'text'       => $message,
			'parse_mode' => 'HTML',
		];

		$chat_ids = apply_filters(
			'tehnokrat_settings.components.telegram_bot.user_ids',
			explode(',', $this->get_setting('telegram', 'telegram_user_id'))
		);

		foreach ($chat_ids as $chat_id) {
			$message['chat_id'] = $chat_id;
			Bot::instance()->sendMessage($message);
		}
	}

	/**
	 * Add purchased column
	 *
	 * @param $columns
	 *
	 * @return array
	 */
	public function manage_shop_order_columns($columns)
	{
		// https://wordpress.org/plugins/restore-purchased-items-column/

		$order_items = ['order_items' => 'Куплено'];
		$ref_pos     = 2;
		$columns     = array_slice($columns, 0, $ref_pos + 1, true) + $order_items + array_slice(
			$columns,
			$ref_pos + 1,
			count($columns) - 1,
			true
		);

		return $columns;
	}

	/**
	 * Purchased column render
	 *
	 * @param $column
	 */
	public function show_order_items($column)
	{
		// https://wordpress.org/plugins/restore-purchased-items-column/

		if ('order_items' == $column) {
			/** @global \WC_Order $the_order */
			global $post, $the_order;

			if (empty($the_order) || $the_order->get_id() != $post->ID) {
				$the_order = wc_get_order($post->ID);
			}

			echo '<a href="#" class="show_order_items">' . apply_filters(
				'woocommerce_admin_order_item_count',
				sprintf(
					_n('%d item', '%d items', $the_order->get_item_count(), 'woocommerce'),
					$the_order->get_item_count()
				),
				$the_order
			) . '</a>';

			if (sizeof($the_order->get_items()) > 0) {
				echo '<table class="order_items" cellspacing="0">';

				/** @var \WC_Order_Item_Product $item */
				foreach ($the_order->get_items() as $item) {
					$product        = apply_filters(
						'woocommerce_order_item_product',
						$item->get_product(),
						$item
					);
					$item_meta      = (WC()->version < '3.1.0') ? new \WC_Order_Item_Meta($item) : new \WC_Order_Item_Product;
					$item_meta_html = (WC()->version < '3.1.0') ? $item_meta->display(
						true,
						true
					) : $item_meta->get_product();
					//$item_meta      = new WC_Order_Item_Meta( $item, $product );
					//$item_meta_html = $item_meta->display( true, true );
			?>
<tr class="<?php echo apply_filters(
												'woocommerce_admin_order_item_class',
												'',
												$item,
												$the_order
											); ?>">
  <td class="qty"><?php echo esc_html($item->get_quantity()); ?></td>
  <td class="name">
    <?php if ($product) : ?>
    <?php echo (wc_product_sku_enabled() && $product->get_sku()) ? $product->get_sku() . ' - ' : ''; ?>
    <a href="<?php echo get_edit_post_link($product->get_id()); ?>">
      <?php
									echo apply_filters(
										'woocommerce_order_item_name',
										$item->get_name(),
										$item,
										false
									);
									?>
    </a>
    <?php else : ?>
    <?php echo apply_filters(
									'woocommerce_order_item_name',
									$item->get_name(),
									$item,
									false
								); ?>
    <?php endif; ?>
    <?php if (!empty($item_meta_html)) : ?>
    <?php echo wc_help_tip($item_meta_html); ?>
    <?php endif; ?>
  </td>
</tr>
<?php
				}

				echo '</table>';
			} else {
				echo '&ndash;';
			}
		}
	}

	/**
	 * Add inline script for purchased column
	 */
	public function admin_enqueue_scripts()
	{
		global $pagenow;
		if ('edit.php' == $pagenow && !empty($_GET['post_type']) && 'shop_order' == $_GET['post_type']) {
			$js = "jQuery( document.body ).on( 'click', '.show_order_items', function() {
					jQuery( this ).closest( 'td' ).find( 'table' ).toggle();
					return false;
				});";

			wp_add_inline_script('woocommerce_admin', $js);
		}
	}

	public function ask_for_feedback()
	{
		$query_date = getdate(strtotime('-3 days'));

		$args = [
			'date_query'  => [
				[
					'year'   => $query_date['year'],
					'month'  => $query_date['mon'],
					'day'    => $query_date['mday'],
					'column' => 'post_modified',
				],
			],
			'fields'      => 'ids',
			'numberposts' => -1,
			'post_type'   => wc_get_order_types(),
			'post_status' => 'wc-completed',
		];

		if ($orders = get_posts($args)) {
			if ($sms_message = $this->get_setting('sms', 'sms_ask_for_feedback')) {
				foreach ($orders as $order_id) {
					if ($order = wc_get_order($order_id)) {
						$order->add_order_note($sms_message, true);
					}
				}
			}
		}
	}

	public function get_comment_karma($value, $post_id)
	{
		$post_id = explode('_', $post_id);
		$comment = get_comment($post_id[1]);

		return $comment->comment_karma;
	}

	public function set_comment_karma($value, $post_id)
	{
		$post_id = explode('_', $post_id);
		wp_update_comment([
			'comment_ID'    => $post_id[1],
			'comment_karma' => $value,
		]);

		return $value;
	}

	/**
	 * @param string $formatted_total
	 * @param \WC_Order $order
	 *
	 * @return string
	 */
	public function get_formatted_order_total($formatted_total, $order)
	{
		if (!doing_filter('woocommerce_email_order_details')) {
			if ($rate = get_post_meta($order->get_id(), '_usd_to_uah', true)) {
				$order_date = $order->get_date_created();
				// если заказ был зделан после полночи с 8 на 9 декабря 2018 - заказы формиются в гривне
				if (1544310000 < $order_date->getTimestamp()) {
					$price_uah       = $order->get_total();
					$price_usd       = ceil($order->get_total() / $rate);
					$formatted_total = wc_price($price_usd, ['currency' => 'USD'])
						. '</br>' . wc_price($price_uah);
				} else {
					$formatted_total .= '</br>' . wc_price(
						ceil($order->get_total() * $rate),
						['currency' => 'UAH', 'price_format' => '%2$s&nbsp;%1$s']
					);
				}
			}
		}

		return $formatted_total;
	}

	public function remove_cyrillic_symbols($title)
	{
		$title = preg_replace(
			'/[А-Яа-я]/',
			'',
			$title
		);

		return $title;
	}

	public function gettext($translation, $text, $domain)
	{
		if ($domain == 'woocommerce') {
			if ('Subtotal' == $text) {
				$translation = 'Сумма';
			} elseif ('Subtotal:' == $text) {
				$translation = 'Сумма:';
			}
		}

		return $translation;
	}

	public function fix_product_cat_reorder($value)
	{
		remove_filter('manage_product_cat_custom_column', '__return_empty_string');

		return $value;
	}

	public function woocommerce_add_to_cart_fragments($fragments)
	{
		if (isset($fragments['div.widget_shopping_cart_content'])) {
			$fragments['div.widget_shopping_cart_content'] = str_replace(
				'widget_shopping_cart_content',
				'widget_shopping_cart_content for-hov-cont ' . (WC()->cart->is_empty() ? 'emp emp2' : ''),
				$fragments['div.widget_shopping_cart_content']
			);
		}

		$fragments['p.qu'] = sprintf(
			'<p class="qu %s" %s>%s</p>',
			WC()->cart->get_cart_contents_count() ? 'active' : '',
			WC()->cart->get_cart_contents_count() ? '' : 'style="display:none;"',
			WC()->cart->get_cart_contents_count() ? WC()->cart->get_cart_contents_count() : ''
		);

		return $fragments;
	}

	public function woocommerce_cart_totals_coupon_html($coupon_html, $coupon, $discount_amount_html)
	{
		if (function_exists('is_checkout') && is_checkout()) {
			$coupon_html = $discount_amount_html;
		}

		return $coupon_html;
	}

	/**
	 * Удаляю неиспользуемые при оформлении заказа поля.
	 *
	 * @param array $address_fields Address fields.
	 *
	 * @return array
	 */
	public function woocommerce_billing_fields($address_fields): array
	{
		$used_fields = array(
			'billing_city',
			'billing_company',
			'billing_email',
			'billing_first_name',
			'billing_last_name',
			'billing_phone',
			'billing_country',
		);

		foreach (array_keys($address_fields) as $field_name) {
			if (!in_array($field_name, $used_fields, true)) {
				unset($address_fields[$field_name]);
			} elseif ('billing_country' === $field_name) {
				$address_fields[$field_name]['label']    = '';
				$address_fields[$field_name]['required'] = false;
				$address_fields[$field_name]['type']     = 'hidden';
			}
		}

		return $address_fields;
	}

	public function woocommerce_checkout_order_processed($order_id)
	{
		// 35 Очищать выбранный способ оплаты после оформления заказа
		WC()->session->set('chosen_payment_method', null);

		$rate = $this->get_setting('main', 'usd_to_uah');

		$order = wc_get_order($order_id);

		update_post_meta($order_id, '_usd_to_uah', $rate);

		$date_now = getdate(current_time('timestamp'));
		if (0 < $date_now['wday'] && 10 <= $date_now['hours'] && $date_now['hours'] < 18) {
			$order->add_order_note(
				$this->get_setting('sms', 'sms_order_processing_text_working_hours'),
				true
			);
		} else {
			$order->add_order_note(
				$this->get_setting('sms', 'sms_order_processing_text_not_working_hours'),
				true
			);
		}

		$message = array("<b>📦 Заказ: №{$order_id}</b>");

		$message[] = '📅 ' . $order->get_date_created()->date_i18n('d.m.Y (H:i)');

		$message[] = '👤 ' . $order->get_formatted_billing_full_name();

		if ($order->get_billing_company()) {
			$message[] = '💼 ' . $order->get_billing_company();
		}

		if ($order->get_billing_phone()) {
			$message[] = '☎️ ' . $order->get_billing_phone();
		}

		if ($order->get_billing_city()) {
			$message[] = '📍 ' . $order->get_billing_city();
		}

		$items = array();
		foreach ($order->get_items('line_item') as $item) {
			$items[] = sprintf(
				'📝 %s x %d - %s ($%s)',
				$item->get_name(),
				$item->get_quantity(),
				number_format($item->get_subtotal(), 2),
				number_format($item->get_subtotal() / $rate, 2)
			);
		}
		$message[] = implode(PHP_EOL, $items);

		$total     = number_format($order->get_total(), 2);
		$total_usd = number_format($order->get_total() / $rate, 2);
		$message[] = "💵 Итого: {$total} (\${$total_usd})";

		$payment_method = $order->get_payment_method_title();
		if (!$payment_method) {
			$payment_method = $order->get_payment_method();
		}
		$message[] = "🏦 {$payment_method}";

		$message[] = '🌐 ' . site_url($order->get_order_key());

		if ($order->get_customer_note()) {
			$message[] = '🗒️ ' . $order->get_customer_note();
		}

		$this->send_bot_message(implode(PHP_EOL, $message));
	}

	public function save_last_shop_page()
	{
		if (!is_admin() && (is_product() || is_product_category())) {
			WC()->session->set('last_shop_page', $_SERVER["REQUEST_URI"]);
		}
	}

	public function create_pre_order()
	{
		$product_id      = empty($_POST['product_id']) ? 0 : intval($_POST['product_id']);
		$delivery_cities = empty($_POST['deliveryCity']) ? '' : $_POST['deliveryCity'];
		$username        = empty($_POST['name']) ? '' : $_POST['name'];
		$phone           = empty($_POST['phone']) ? '' : $_POST['phone'];

		// проверка ID товара
		if (!$product_id) {
			wp_send_json_error('Ошибка: неверно указан код товара.');
		}
		$product = wc_get_product($product_id);
		if (empty($product)) {
			wp_send_json_error('Ошибка: товар не найден.');
		}

		// проверка номера телефона
		$phone = preg_replace('/[^0-9]/', '', $phone);
		if (strlen($phone) < 10) {
			wp_send_json_error('Ошибка: неверно указан номер телефона.');
		}
		$phone = '+380' . substr($phone, -9);

		$meta_key = 'pre_order_' . $phone;

		update_post_meta($product_id, $meta_key, [$delivery_cities, $username]);

		wc_mail(
			get_option('admin_email'),
			'Получен предварительный заказ',
			sprintf(
				'Товар: %s<br>Город: %s<br>Заказчик: %s<br>Телефон: %s<br>',
				site_url() . '?p=' . $product_id,
				$delivery_cities,
				$username,
				$phone
			)
		);

		wp_send_json_success();
	}

	public function check_pre_order()
	{
		global $wpdb;

		$query      = "
			SELECT pm.post_id, pm.meta_key AS pre_order_key, pm.meta_value AS data, p.post_title
			FROM {$wpdb->postmeta} AS pm
			JOIN {$wpdb->postmeta} AS pm2 ON pm2.post_id=pm.post_id
			JOIN {$wpdb->posts} AS p ON p.ID=pm.post_id
			WHERE pm.meta_key LIKE 'pre_order_+380%'
			AND pm2.meta_key='_stock_status' AND pm2.meta_value='instock'";
		$pre_orders = $wpdb->get_results($query);
		if (empty($pre_orders)) {
			return;
		}

		$sms_pre_order_message = $this->get_setting('sms', 'sms_pre_order_message');

		foreach ($pre_orders as $pre_order) {
			delete_post_meta($pre_order->post_id, $pre_order->pre_order_key);

			$phone           = preg_replace('/[^0-9]/', '', $pre_order->pre_order_key);
			$pre_order->data = maybe_unserialize($pre_order->data);

			$message = str_replace(
				[
					'{customer_name}',
					'{customer_city}',
					'{customer_phone}',
					'{product_id}',
					'{product_name}',
					'{product_url}',
				],
				[
					$pre_order->data[1],
					$pre_order->data[0],
					$phone,
					$pre_order->post_id,
					$pre_order->post_title,
					site_url("?p={$pre_order->post_id}"),
				],
				$sms_pre_order_message
			);

			$this->send_sms($phone, $message);
		}
	}

	public function change_order_currency_symbol($args)
	{
		global $current_screen;

		//		$args['currency']     = 'NONE';
		//		$args['price_format'] = '%2$s&nbsp;грн';
		//
		if (is_wc_endpoint_url('order-received') || is_wc_endpoint_url('order-pay')) {
			$args['currency']     = 'NONE';
			$args['price_format'] = '%2$s&nbsp;грн';
		} elseif (!doing_filter('woocommerce_get_formatted_order_total') && !empty($current_screen) && 'shop_order' == $current_screen->id) {
			$args['currency']     = 'UAH';
			$args['price_format'] = '%2$s&nbsp;%1$s';
		}

		return $args;
	}

	public function amend_order_item_totals($total_rows)
	{
		unset(
			$total_rows['payment_method'],
			$total_rows['shipping']
		);
		// не показывать "Сумму", если нет скидки и комиссии
		if (count($total_rows) == 2) {
			unset($total_rows['cart_subtotal']);
		}

		$total_rows['order_total']['label'] = 'Итого:';

		foreach ($total_rows as $key => $row) {
			if (0 === strpos($key, 'fee_')) {
				$total_rows[$key]['value'] = '+' . $total_rows[$key]['value'];
				break;
			}
		}

		return $total_rows;
	}

	public function validate_billing_phone($data, $errors)
	{
		if (empty($errors->get_error_messages())) {
			try {
				$data['billing_phone'] = self::normalize_phone_number($data['billing_phone']);
			} catch (Exception $e) {
				$errors->add('validation', $e->getMessage());
			}
		}
	}

	public function format_billing_phone_on_checkout($data)
	{
		try {
			$data['billing_phone'] = self::normalize_phone_number($data['billing_phone']);
		} catch (Exception $e) {
		}

		return $data;
	}

	/**
	 * Нормализация номера телефона.
	 *
	 * @param string $phone_number Номер телефона.
	 *
	 * @return string
	 * @throws Exception Exception.
	 */
	public static function normalize_phone_number(string $phone_number): string
	{
		$phone_number = preg_replace('/[^0-9]/', '', $phone_number);
		if (strlen($phone_number) < 10) {
			throw new Exception(
				sprintf(
					__('%s is not a valid phone number.', 'tehnokrat'),
					$phone_number
				)
			);
		}

		return '+380' . substr($phone_number, -9);
	}

	public function disable_cod_processing_notification($order_id, $order)
	{
		if ('cod' == $order->get_payment_method()) {
			$emails = WC()->mailer()->get_emails();
			remove_action(
				'woocommerce_order_status_pending_to_processing_notification',
				[$emails['WC_Email_Customer_Processing_Order'], 'trigger'],
				10
			);

			WC()->mailer()->customer_invoice($order);
		}
	}

	public function order_statuses($order_statuses)
	{
		$order_statuses['wc-processing'] = 'В работе';

		return $order_statuses;
	}

	/**
	 * Возвращаю значение настроек.
	 *
	 * @param string $section Название секции.
	 * @param string $setting_name Наименование опции.
	 *
	 * @return mixed
	 */
	public function get_setting($section, $setting_name = '')
	{
		if ($setting_name) {
			$value = get_field($setting_name, "{$this->identifier}_{$section}");
		} else {
			$value = get_fields("{$this->identifier}_{$section}");
		}

		return $value;
	}

	/**
	 * @throws \Longman\TelegramBot\Exception\TelegramException
	 */
	public function submit_trade_in_pop_up()
	{
		$sent        = false;
		$proposal_id = 1 + get_option('trade_in_proposal_id', 0);

		// phpcs:ignore WordPress.Security
		$result = Google_Recaptcha::instance()->verify(isset($_POST['token']) ? $_POST['token'] : '');
		if (true === $result) {
			$args = [
				'proposal_id'     => $proposal_id,
				'description'     => '',
				'serial-number'   => '',
				'rechange-cycles' => '',
				'attr1'           => '',
				'attr2'           => '',
				'name'            => '',
				'phone'           => '',
				'email'           => '',
			];
			foreach ($args as $key => &$value) {
				if (isset($_POST[$key])) {
					$value = sanitize_text_field($_POST[$key]);
				}
			}

			// phpcs:ignore WordPress.Security
			// Формирую сообщение.
			ob_start();
			locate_template('partials/trade-in-telegram-message.php', true, true, $args);
			$text    = ob_get_clean();
			$message = [
				'text'       => $text,
				'parse_mode' => 'HTML',
			];
			// Формирую группу картинок.
			$media_group = ['media' => []];
			if (isset($_FILES, $_FILES['images'], $_FILES['images']['tmp_name'])) {
				// phpcs:ignore WordPress.Security
				foreach ($_FILES['images']['tmp_name'] as $index => $file_name) {
					$id = 'photo_' . $index;

					$media_group[$id]     = Request::encodeFile($file_name);
					$media_group['media'][] = new InputMediaPhoto(['media' => 'attach://' . $id]);

					// Картинка больше не нужна, удаляю.
					unlink($file_name);
				}
			}

			$chat_ids = explode(',', $this->get_setting('telegram', 'telegram_user_id'));
			foreach ($chat_ids as $chat_id) {
				$message['chat_id'] = $chat_id;
				$result             = Bot::instance()->sendMessage($message);

				if ($result->isOk()) {
					if (count($media_group['media'])) {
						$media_group['chat_id'] = $chat_id;
						Bot::instance()->sendMediaGroup($media_group);
					}

					$sent = true;
				} else {
					wp_log_error($result->printError(true), $message);
				}
			}
		} else {
			wp_send_json_error($result);
		}

		if (!$sent) {
			wp_send_json_error('Trade-in notification was not sent to the store admins.');
		}

		update_option('trade_in_proposal_id', $proposal_id);
		wp_send_json_success();
	}

	/**
	 * Вывожу переключатель языков сайта.
	 *
	 * @return void
	 */
	public function language_selector(): void
	{
		echo '<div class="lang">';
		the_widget('qTranslateXWidget', array('type' => 'custom', 'format' => '%c', 'hide-title' => true, 'widget-css-off' => true));
		echo '</div>';
		/* if ('ua' === get_request_locale()) {
			$ru_url = str_replace(UA_SITE_URL, RU_SITE_URL, REQUESTED_URL);
			$ua_url = REQUESTED_URL;

		} else {
			$ru_url = REQUESTED_URL;
			$ua_url = str_replace(RU_SITE_URL, UA_SITE_URL, REQUESTED_URL);
		}

		// Custom language layout selector
		echo '<div class="lang">';
		printf(
			'<a class="%s" href="%s">UA</a>',
			'ua' === get_request_locale() ? 'active' : '',
			$ua_url
		);
		printf(
			'<a class="%s" href="%s">RU</a>',
			'ua' !== get_request_locale() ? 'active' : '',
			$ru_url
		);
		echo '</div>'; */
	}

	/**
	 * Set meta robots tag for UA pages.
	 *
	 * @param array $robots Robot attributes.
	 *
	 * @return array
	 */
	public function set_meta_robots_for_ua_pages(array $robots): array
	{
		if ('ua' === get_request_locale()) {
			if (isset($robots['follow'])) {
				unset($robots['follow']);
			}
			if (isset($robots['index'])) {
				unset($robots['index']);
			}

			$robots['noindex']  = true;
			$robots['nofollow'] = true;
		}

		return $robots;
	}

	/**
	 * Get translation strings for React widgets.
	 *
	 * @return array
	 */
	public function get_translation_strings(): array
	{
		return array(
			'Buy item'         => __('Buy item', 'tehnokrat'),
			'In installments'  => __('In installments', 'tehnokrat'),
			'Report Admission' => __('Report Admission', 'tehnokrat'),
			'more'             => __('more', 'tehnokrat'),
			'Installment plan' => __('Installment plan', 'tehnokrat'),
			'Choose a bank'    => __('Choose a bank', 'tehnokrat'),
			'all products'     => __('all products', 'tehnokrat'),
			'in stock'         => __('in stock', 'tehnokrat'),
			'Product in stock' => __('Product in stock', 'tehnokrat'),
			'Not available'    => __('Not available', 'tehnokrat'),
			'Model'            => __('Model', 'tehnokrat'),
			'Choose a model'   => __('Choose a model', 'tehnokrat'),
			'Description'      => __('Description', 'tehnokrat'),
			'Configuration'    => __('Configuration', 'tehnokrat'),
			'all models'       => __('all models', 'tehnokrat'),
			'color'            => __('color', 'tehnokrat'),
			'Pay part'         => __('Pay part', 'tehnokrat'),
			'Mono Pay part'         => __('Mono Pay part', 'tehnokrat'),
			'Privat Pay part'         => __('Privat Pay part', 'tehnokrat'),
			//'curLang' => qtrans_getLanguage(),
			'underProduct' => '<ul class="info-links"><li><a target="_blank" href="' . get_permalink(22560) . '">' . get_the_title(22560) . '</a></li><li><a target="_blank" href="' . get_permalink(3843) . '">' . get_the_title(3843) . '</a></li></ul>',
			'You chose' => __('You chose','tehnokrat'),
			'Model range' => __('Model range','tehnokrat'),
			'Filter' => __('Filter','tehnokrat'),
			'Reset' => __('Reset','tehnokrat'),
		);
	}

	public function get_email_custom_text(string $email_id, string $variation = '')
	{
		$custom_text = $this->get_setting('emails', $email_id . ($variation ? "_$variation" : ''));

		if (!empty($custom_text)) {
			return str_replace(
				array_keys($GLOBALS['email_custom_text_variables']),
				array_values($GLOBALS['email_custom_text_variables']),
				$custom_text
			);
		} else {
			return '';
		}
	}

	/**
	 * Get label colors for React widgets.
	 *
	 * @return array
	 */
	public function get_label_colors(): array
	{
		return wp_list_pluck(
			(array) $this->get_setting('colors', 'label_colors'),
			'label_hex_code',
			'label_name'
		);
	}

	/**
	 * Set label_colors field.
	 *
	 * @param array $label_colors_field Label_colors field.
	 *
	 * @return array
	 */
	public function set_label_colors_field(array $label_colors_field): array
	{
		$all_labels = array_unique(
			get_terms(
				array('taxonomy' => 'pa_yarlik-tovara', 'fields' => 'names')
			)
		);

		$label_colors_field['min'] = count($all_labels);
		$label_colors_field['max'] = count($all_labels);

		$label_colors_field['sub_fields'][0]['readonly'] = 'readonly';

		return $label_colors_field;
	}

	/**
	 * Set label_colors field value.
	 *
	 * @param array $label_colors Label_colors field value.
	 *
	 * @return array
	 */
	public function set_label_colors_value(array $label_colors): array
	{
		$all_labels = array_unique(
			get_terms(
				array('taxonomy' => 'pa_yarlik-tovara', 'fields' => 'names')
			)
		);

		foreach ($label_colors as $i => $label_color) {
			$key = array_search($label_color['field_6399ae859ae21'], $all_labels, true);
			if (false === $key) {
				unset($label_colors[$i]);
			} else {
				unset($all_labels[$key]);
			}
		}

		foreach ($all_labels as $label) {
			$label_colors[] = array(
				'field_6399ae859ae21' => $label,
				'field_6399ae859ae22' => '#93D500'
			);
		}

		return $label_colors;
	}
}

$GLOBALS['tehnokrat'] = Tehnokrat::instance();

/*array(
	'email_heading'              => $this->get_heading(),
	'user_id'                    => $this->user_id,
	'user_login'                 => $this->user_login,
	'blogname'                   => $this->get_blogname(),
	'reset_key'                  => $this->reset_key,
	'user_pass'                  => $this->user_pass,
	'password_generated'         => $this->password_generated,
	'set_password_url'           => $this->set_password_url,
	'refund'                     => $this->refund,
	'partial_refund'             => $this->partial_refund,
	'additional_content'         => $this->get_additional_content(),
	'sent_to_admin'              => false,
	'plain_text'                 => false,
	'email'                      => $this,
	'order'                      => $this->object,
	'myaccount_url'              => wc_get_page_permalink( 'myaccount' ),
	'order_number'               => $order->get_order_number(),
	'order_date_created'         => wc_format_datetime( $order->get_date_created() ),
	'order_checkout_payment_url' => $order->get_checkout_payment_url(),
;*/