<?php
/**
 * Empty cart page
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

/*
 * @hooked wc_empty_cart_message - 10
 */
do_action( 'woocommerce_cart_is_empty' );

$image_url = get_template_directory_uri() . '/img/img-ba.png';
$goto_url  = WC()->session->get( 'last_shop_page', site_url() );

if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
	<section class="basket-page emp cart-empty">
		<div class="container">
			<div class="empty-bask">
				<p>Ваша корзина пуста</p>
				<p class="img">
					<img src="<?php echo esc_url( $image_url ); ?>">
				</p>
				<a href="<?php echo esc_url( $goto_url ); ?>"><i class="icon-left-open-big"></i>Продолжить покупки</a>
			</div>
		</div>
	</section>
<?php endif; ?>
