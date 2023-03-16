<?php
/**
 * Class Options_Page
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Google_Recaptcha
 */

namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Google_Recaptcha;

/**
 * Class Options_Page
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Google_Recaptcha
 */
final class Options_Page {
	/**
	 * Options_Page constructor.
	 */
	public function __construct() {
		add_action( 'acf/init', array( $this, 'add_options_sub_page' ) );
		add_action( 'acf/init', array( $this, 'add_local_field_group' ) );
	}

	/**
	 * Get Options_Page instance.
	 *
	 * @return Options_Page
	 */
	public static function instance() {
		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new Options_Page();
		}

		return $instance;
	}

	/**
	 * Initialize Options_Page instance.
	 *
	 * @return Options_Page
	 */
	public static function initialize() {
		return self::instance();
	}

	/**
	 * Options_Page::__clone
	 */
	protected function __clone() {
	}

	/**
	 * Options_Page::__wakeup
	 */
	protected function __wakeup() {
	}

	/**
	 * Регистрация страницы настроек.
	 */
	public function add_options_sub_page() {
		acf_add_options_sub_page(
			array(
				'page_title'  => 'Технократ. reCaptcha',
				'menu_title'  => 'reCaptcha',
				'menu_slug'   => 'tehnokrat_recaptcha',
				'parent_slug' => 'tehnokrat',
				'post_id'     => 'tehnokrat_recaptcha',
				'autoload'    => true,
			)
		);
	}

	/**
	 * Регистрация группы полей.
	 */
	public function add_local_field_group() {
		acf_add_local_field_group(
			array(
				'key'                   => 'group_5f8b178855457',
				'title'                 => 'Технократ. reCAPTCHA',
				'fields'                => array(
					array(
						'key'               => 'field_5f8b17885e5af',
						'label'             => 'Ключ сайта',
						'name'              => 'site_key',
						'type'              => 'text',
						'instructions'      => '',
						'required'          => 1,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
						'maxlength'         => '',
						'placeholder'       => '',
						'prepend'           => '',
						'append'            => '',
					),
					array(
						'key'               => 'field_5f8b17885e5e5',
						'label'             => 'Секретный ключ',
						'name'              => 'secret_key',
						'type'              => 'text',
						'instructions'      => '',
						'required'          => 1,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
						'maxlength'         => '',
						'placeholder'       => '',
						'prepend'           => '',
						'append'            => '',
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'tehnokrat_recaptcha',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => '',
				'modified'              => 1602963320,
			)
		);
	}
}
