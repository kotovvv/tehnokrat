<?php

namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Pages;

use BytePerfect\WordPress\Theme\Tehnokrat\Tehnokrat;

defined('ABSPATH') || exit;


abstract class Abstract_Page
{
	/**
	 * @var string
	 */
	protected $identifier;

	/**
	 * @var string
	 */
	private $url;

	/**
	 * @var string
	 */
	private $version;

	/**
	 * @var string
	 */
	private $suffix;

	public function __construct()
	{
		global $tehnokrat;

		$this->identifier = 'tehnokrat';
		$this->url        = str_replace(['http:', 'https:'], '', get_template_directory_uri());
		$this->version    = $tehnokrat->get_version();
		$this->suffix     = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

		$this->setup_globals();
		$this->setup_actions();
	}

	protected function setup_globals()
	{
		$this->register_styles();
		$this->register_scripts();
	}

	protected function setup_actions()
	{
		add_action('wp_enqueue_scripts', [$this, '_enqueue_styles']);
		add_action('wp_enqueue_scripts', [$this, '_enqueue_scripts']);
	}

	private function register_styles()
	{
		$styles = [
			"{$this->identifier}-google-fonts-Ubuntu" => [
				'//fonts.googleapis.com/css?family=Ubuntu:300,300i,400,400i,500,500i,700,700i',
			],
			"{$this->identifier}-style"               => [
				"{$this->url}/css/style{$this->suffix}.css",
				["{$this->identifier}-google-fonts-Ubuntu"],
			],
			"{$this->identifier}-home"                => [
				"{$this->url}/css/home{$this->suffix}.css",
				["{$this->identifier}-style"],
			],
			"{$this->identifier}-home-new"            => [
				"{$this->url}/css/home-new{$this->suffix}.css",
				["{$this->identifier}-style"],
			],
			"{$this->identifier}-contact"             => [
				"{$this->url}/css/contact{$this->suffix}.css",
				["{$this->identifier}-style"],
			],
			"{$this->identifier}-guarantee"           => [
				"{$this->url}/css/guarantee{$this->suffix}.css",
				["{$this->identifier}-style"],
			],
			"{$this->identifier}-trade-in"            => [
				"{$this->url}/css/trade-in{$this->suffix}.css",
				["{$this->identifier}-style"],
			],
			"{$this->identifier}-reviews"             => [
				"{$this->url}/css/reviews{$this->suffix}.css",
				["{$this->identifier}-style"],
			],
			"{$this->identifier}-icon-sl"             => ["{$this->url}/css/icon-sl{$this->suffix}.css"],
			"{$this->identifier}-del.css"             => [
				"{$this->url}/css/del{$this->suffix}.css",
				["{$this->identifier}-style"],
			],
			"{$this->identifier}-exchange.css"        => [
				"{$this->url}/css/exchange{$this->suffix}.css",
				["{$this->identifier}-style"],
			],
			"{$this->identifier}-text"                => [
				"{$this->url}/css/text{$this->suffix}.css",
				["{$this->identifier}-style"],
			],
			"{$this->identifier}-basket"              => [
				"{$this->url}/css/basket{$this->suffix}.css",
				["{$this->identifier}-style"],
			],
			"{$this->identifier}-ordering"            => [
				"{$this->url}/css/ordering{$this->suffix}.css",
				["{$this->identifier}-style"],
			],
			"{$this->identifier}-one-product"         => [
				"{$this->url}/css/one-product{$this->suffix}.css",
				["{$this->identifier}-style"],
			],
			"{$this->identifier}-blog"                => [
				"{$this->url}/css/blog{$this->suffix}.css",
				["{$this->identifier}-style"],
			],
			"{$this->identifier}-trade-in-pop-up"     => array(
				"{$this->url}/css/trade-in-pop-up.css",
				array("{$this->identifier}-style"),
			),
			"{$this->identifier}-my-account"     => array(
				"{$this->url}/css/my-account.css",
				array("{$this->identifier}-style"),
			),
		];

		foreach ($styles as $handle => $style) {
			$src   = $style[0];
			$deps  = $style[1] ?? [];
			$ver   = $style[2] ?? $this->version;
			$media = $style[3] ?? 'all';

			wp_register_style($handle, $src, $deps, $ver, $media);
			// wp_enqueue_style( $handle, $src, $deps, $ver, $media );
		}
	}

	private function register_scripts()
	{
		/**
		 * @global Tehnokrat $tehnokrat
		 */
		global $tehnokrat;
		//		wp_deregister_script( 'jquery' );

		$scripts = [
			//			'jquery'                                      => [ includes_url( '/js/jquery/jquery.js' ), [], '1.12.4' ],
			"{$this->identifier}-script"                  => array(
				"{$this->url}/js/script{$this->suffix}.js",
				array(
					'jquery',
					"{$this->identifier}-lottie",
				),
			),
			"{$this->identifier}-js.cookie"               => ["{$this->url}/js/js.cookie.js", [], '2.1.3'],
			"{$this->identifier}-vue"                     => ["{$this->url}/js/vue.min.js", [], '2.1.8'],
			"{$this->identifier}-lodash"                  => [
				"{$this->url}/js/lodash{$this->suffix}.js",
				[],
				'4.17.4',
			],
			"{$this->identifier}-vue-app"                 => [
				"{$this->url}/js/vue-app{$this->suffix}.js",
				["{$this->identifier}-vue"],
			],
			"{$this->identifier}-jcf"                     => ["{$this->url}/js/jcf.min.js", ['jquery']],
			"{$this->identifier}-jcf.select"              => [
				"{$this->url}/js/jcf.select.min.js",
				["{$this->identifier}-jcf"],
			],
			"{$this->identifier}-readmore"                => [
				"{$this->url}/js/readmore.min.js",
				['jquery'],
			],
			"{$this->identifier}-inputmask.dependencyLib" => [
				"{$this->url}/js/inputmask.dependencyLib.js",
				["{$this->identifier}-vue"],
			],
			"{$this->identifier}-inputmask"               => [
				"{$this->url}/js/inputmask.js",
				["{$this->identifier}-inputmask.dependencyLib"],
			],
			"{$this->identifier}-slick"                   => ["{$this->url}/js/slick.min.js", ['jquery']],
			"{$this->identifier}-match-height"            => [
				"{$this->url}/js/jquery.matchHeight.min.js",
				['jquery'],
			],
			"{$this->identifier}-main"                    => [
				"{$this->url}/js/main{$this->suffix}.js",
				['jquery', 'wp-i18n', "{$this->identifier}-script", "{$this->identifier}-jcf", "{$this->identifier}-jcf.radio", "{$this->identifier}-jcf.checkbox"],
			],
			"{$this->identifier}-recaptcha"               => [
				'https://www.google.com/recaptcha/api.js',
				[],
				null,
				false,
			],
			"{$this->identifier}-maps-googleapis"         => [
				'https://maps.googleapis.com/maps/api/js?key=AIzaSyA5JOUlAXleaCYImiGGe7T-SLe20tbGtPs',
				[],
				null,
			],
			"{$this->identifier}-contact"                 => [
				"{$this->url}/js/contact{$this->suffix}.js",
				["{$this->identifier}-script"],
			],
			"{$this->identifier}-trade-in"                => [
				"{$this->url}/js/trade-in{$this->suffix}.js",
				["{$this->identifier}-script"],
			],
			"{$this->identifier}-contacts-page"           => [
				"{$this->url}/js/contacts-page{$this->suffix}.js",
				["{$this->identifier}-script"],
			],
			"{$this->identifier}-guarantee"               => [
				"{$this->url}/js/guarantee{$this->suffix}.js",
				["{$this->identifier}-script"],
			],
			"{$this->identifier}-vue"                     => [
				"{$this->url}/js/vue{$this->suffix}.js",
				[],
				'2.1.8',
			],
			"{$this->identifier}-reviews"                 => [
				"{$this->url}/js/reviews{$this->suffix}.js",
				["{$this->identifier}-script"],
			],
			"{$this->identifier}-rating"                  => [
				"{$this->url}/js/rating.min.js",
				["{$this->identifier}-script"],
			],
			"{$this->identifier}-lottie"                  => [
				"{$this->url}/js/lottie.min.js",
			],
			"{$this->identifier}-home"                    => [
				"{$this->url}/js/home{$this->suffix}.js",
				['jquery', "{$this->identifier}-script"],
			],
			"{$this->identifier}-trade-in"                => [
				"{$this->url}/js/trade-in{$this->suffix}.js",
				["{$this->identifier}-script"],
			],
			"{$this->identifier}-jcf.number"              => [
				"{$this->url}/js/jcf.number.min.js",
				["{$this->identifier}-jcf"],
			],
			"{$this->identifier}-cart"                    => [
				"{$this->url}/js/cart{$this->suffix}.js",
				["{$this->identifier}-script", 'underscore'],
			],
			"{$this->identifier}-jcf.radio"               => [
				"{$this->url}/js/jcf.radio.min.js",
				["{$this->identifier}-jcf"],
			],
			"{$this->identifier}-jcf.range"               => [
				"{$this->url}/js/jcf.range.min.js",
				["{$this->identifier}-jcf"],
			],
			"{$this->identifier}-checkout"                => [
				"{$this->url}/js/checkout{$this->suffix}.js",
				['jquery', "{$this->identifier}-script"],
			],
			"{$this->identifier}-one-product"             => [
				"{$this->url}/js/one-product.min.js",
				["{$this->identifier}-script"],
			],
			"{$this->identifier}-blog"                    => [
				"{$this->url}/js/blog.min.js",
				[
					"{$this->identifier}-readmore",
					"{$this->identifier}-match-height",
					"{$this->identifier}-script",
				],
			],
			"{$this->identifier}-anime.js"                => [
				"https://cdnjs.cloudflare.com/ajax/libs/animejs/2.0.2/anime.js",
				[],
				'2.0.2',
			],
			"{$this->identifier}-trade-in-pop-up"         => array(
				"{$this->url}/js/trade-in-pop-up.min.js",
				array("{$this->identifier}-script", 'wp-i18n'),
			),
			"{$this->identifier}-jcf.file"                => array(
				"{$this->url}/js/jcf.file.min.js",
				array("{$this->identifier}-jcf"),
			),
			"{$this->identifier}-jcf.checkbox"            => array(
				"{$this->url}/js/jcf.checkbox.min.js",
				array("{$this->identifier}-jcf"),
			),
			"{$this->identifier}-jquery.mask"             => array(
				"{$this->url}/js/jquery.mask.min.js",
				array('jquery'),
			),
		];

		foreach ($scripts as $handle => $script) {
			$src       = $script[0];
			$deps      = $script[1] ?? [];
			$ver       = $script[2] ?? $this->version;
			$in_footer = $script[3] ?? true;

			// wp_register_script( $handle, $src, $deps, $ver, $in_footer );
			wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
		}
	}

	public function _enqueue_styles()
	{
		$this->enqueue_styles();
	}

	public function _enqueue_scripts()
	{
		$this->enqueue_scripts();
	}

	abstract protected function enqueue_styles();

	abstract protected function enqueue_scripts();
}
