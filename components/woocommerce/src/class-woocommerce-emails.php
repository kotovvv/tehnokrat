<?php
/**
 * Class Woocommerce_Emails
 *
 * Доработки писем.
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Woocommerce
 */

declare( strict_types=1 );

namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Woocommerce;

/**
 * Class Woocommerce_Emails
 *
 * Доработки писем.
 *
 * @package BytePerfect\WordPress\Theme\Tehnokrat\Components\Woocommerce
 */
final class Woocommerce_Emails {
	/**
	 * Woocommerce_Emails constructor.
	 */
	public function __construct() {
		add_filter( 'wp_mail', array( $this, 'add_ua_flag_fo_subject' ) );
		add_filter( 'woocommerce_email_styles', array( $this, 'update_email_styles' ) );
	}

	/**
	 * Добавить флаг Украины к теме письма.
	 *
	 * @param array $args Email args.
	 *
	 * @return array
	 */
	public function add_ua_flag_fo_subject( array $args ): array {
		$args['subject'] = $args['subject'] . ' 🇺🇦';

		return $args;
	}

	/**
	 * Update email styles.
	 *
	 * @param string $css Email CSS.
	 *
	 * @return string
	 */
	public function update_email_styles( string $css ): string {
		return $css . <<<EOCSS
h1{
	font-size: 22px;
	text-align: center;
	color: #fff;
	padding-top: 50px;
}
#header_wrapper{
	padding: 10px;
}
#wrapper{
	padding-top: 0;
	position: relative;
}
#template_header_image p{
	position: absolute;
	width: 200px;
	margin-bottom: 0;
	top: 10px;
	left: 50%;
	transform: translate(-50%,0);
}
#template_header_image p img{
	width: 100%;
}
EOCSS;
	}
}
