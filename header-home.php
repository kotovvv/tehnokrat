<?php

defined( 'ABSPATH' ) || exit;

global $tehnokrat;

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
	<?php wp_head(); ?>

	<!-- Google Tag Manager -->
	<script>(function (w, d, s, l, i) {
			w[l] = w[l] || [];
			w[l].push({
				'gtm.start':
					new Date().getTime(), event: 'gtm.js'
			});
			var f = d.getElementsByTagName(s)[0],
				j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
			j.async = true;
			j.src =
				'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
			f.parentNode.insertBefore(j, f);
		})(window, document, 'script', 'dataLayer', 'GTM-TLQM497');</script>
	<!-- End Google Tag Manager -->

	<script>
		(function (d) {
			var s = d.createElement('script');
			s.defer = true;
			s.src = 'https://multisearch.io/plugin/<?= 'ua' === get_request_locale() ? '10996' : '10995' ?>';
			if (d.head) d.head.appendChild(s);
		})(document);
	</script>
</head>
<body <?php body_class(); ?>>
<div id="react-app"></div>
<div id="vue-app">

	<div class="menuMibi">
		<div class="mob-menu-cont">
			<div class="mc">
				<div class="woocommerce-product-search search">
					<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
						<g>
							<path
								d="M21.7312 20.436L15.475 14.1798C16.6868 12.6829 17.4165 10.7808 17.4165 8.70918C17.4165 3.90772 13.5097 0.000915527 8.70822 0.000915527C3.90677 0.000915527 0 3.90768 0 8.70913C0 13.5106 3.90681 17.4174 8.70826 17.4174C10.7799 17.4174 12.682 16.6878 14.1789 15.4759L20.4351 21.7321C20.6138 21.9109 20.8484 22.0007 21.0831 22.0007C21.3178 22.0007 21.5525 21.9109 21.7312 21.7321C22.0896 21.3737 22.0896 20.7944 21.7312 20.436ZM8.70826 15.5841C4.91695 15.5841 1.83333 12.5004 1.83333 8.70913C1.83333 4.91782 4.91695 1.8342 8.70826 1.8342C12.4996 1.8342 15.5832 4.91782 15.5832 8.70913C15.5832 12.5004 12.4995 15.5841 8.70826 15.5841Z"
								fill="white"></path>
						</g>
					</svg>
					<span><?= __( 'search', 'tehnokrat' ) ?></span>
				</div>
				<?php if ( is_archive() ) : ?>
					<div id="only-in-stock-products-switcher-mobile"></div>
				<?php endif; ?>
				<?php
				wp_nav_menu( [
					'theme_location' => 'header_categories',
					'container'      => false,
					'menu_class'     => 'header-categories-menu',
					'link_after'     => '<span class="menu-close"></span>',
					'depth'          => 1,
				] );
				?>
				<?php if ( $phone_numbers = $tehnokrat->get_setting( 'main', 'phone_numbers' ) ) : ?>
					<div class="delivery">
						<ul>
							<li>
								<?php
								printf(
									'<a href="tel:+%s">%s</a>',
									preg_replace( '/[^0-9]/', '', $phone_numbers[0]['phone_number'] ),
									$phone_numbers[0]['phone_number']
								);
								array_shift( $phone_numbers );
								?>
								<?php if ( $phone_numbers ) : ?>
									<ul>
										<?php foreach ( $phone_numbers as $phone_number ) :
											printf(
												'<li><a href="tel:+%s">%s</a></li>',
												preg_replace( '/[^0-9]/', '', $phone_number['phone_number'] ),
												$phone_number['phone_number']
											);
										endforeach; ?>
									</ul>
								<?php endif; ?>
							</li>
						</ul>
					</div>
				<?php endif; ?>
				<?php $tehnokrat->display_link_to_account_page(); ?>
				<?php $tehnokrat->language_selector(); ?>
			</div>
		</div>
		<div class="emp-pr"></div>
	</div>

	<header>
		<?php if ( is_store_notice_showing() ) : ?>
			<div class="news-feed">
				<p><?php echo wp_kses_post( get_option( 'woocommerce_demo_store_notice', '' ) ); ?></p>
			</div>
		<?php endif; ?>
		<div class="hed-cont">
			<div class="logo mo">
				<?php $tehnokrat->display_logo(); ?>
			</div>
			<div class="left">
				<a href="javascript:void(0)" class="mobile-menu">
					<span></span>
				</a>
				<div class="logo">
					<?php $tehnokrat->display_logo(); ?>
				</div>
				<div class="woocommerce-product-search search">
					<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
						<g>
							<path
								d="M21.7312 20.436L15.475 14.1798C16.6868 12.6829 17.4165 10.7808 17.4165 8.70918C17.4165 3.90772 13.5097 0.000915527 8.70822 0.000915527C3.90677 0.000915527 0 3.90768 0 8.70913C0 13.5106 3.90681 17.4174 8.70826 17.4174C10.7799 17.4174 12.682 16.6878 14.1789 15.4759L20.4351 21.7321C20.6138 21.9109 20.8484 22.0007 21.0831 22.0007C21.3178 22.0007 21.5525 21.9109 21.7312 21.7321C22.0896 21.3737 22.0896 20.7944 21.7312 20.436ZM8.70826 15.5841C4.91695 15.5841 1.83333 12.5004 1.83333 8.70913C1.83333 4.91782 4.91695 1.8342 8.70826 1.8342C12.4996 1.8342 15.5832 4.91782 15.5832 8.70913C15.5832 12.5004 12.4995 15.5841 8.70826 15.5841Z"
								fill="white"></path>
						</g>
					</svg>
					<span><?= __( 'search', 'tehnokrat' ) ?></span>
				</div>
			</div>
			<div class="right">
				<div class="stock-delivery">
					<?php if ( is_archive() ) : ?>
						<div id="only-in-stock-products-switcher"></div>
					<?php endif; ?>
					<?php if ( $phone_numbers = $tehnokrat->get_setting( 'main', 'phone_numbers' ) ) : ?>
						<div class="delivery">
							<ul>
								<li>
									<?php
									printf(
										'<a href="tel:+%s">%s<span>%s</span></a>',
										preg_replace( '/[^0-9]/', '', $phone_numbers[0]['phone_number'] ),
										$phone_numbers[0]['phone_number'],
										$phone_numbers[0]['phone_description']
									);
									array_shift( $phone_numbers );
									?>
									<?php if ( $phone_numbers ) : ?>
										<ul>
											<?php foreach ( $phone_numbers as $phone_number ) :
												printf(
													'<li><a href="tel:+%s">%s<span>%s</span></a></li>',
													preg_replace( '/[^0-9]/', '', $phone_number['phone_number'] ),
													$phone_number['phone_number'],
													$phone_number['phone_description']
												);
											endforeach; ?>
										</ul>
									<?php endif; ?>
								</li>
							</ul>
						</div>
					<?php endif; ?>
				</div>
				<?php $tehnokrat->display_link_to_account_page(); ?>
				<div class="for-ba">
					<div class="bask">
						<img src="<?php echo get_template_directory_uri(); ?>/img/korzina.png">
						<p class="qu" style="display:none;"></p>
					</div>
					<div class="for-hov">
						<div class="widget_shopping_cart_content"></div>
					</div>
					<div class="backdrop"></div>
				</div>
				<?php $tehnokrat->language_selector(); ?>
			</div>
			<div class="numb-one ">
				<p>1</p>
			</div>
		</div>
	</header>

	<div class="wrapper wrapper-js">

		<div class="img-ban">
			<img src="<?= get_template_directory_uri() . '/img/ban-blog.png' ?>">
		</div>

		<?php if ( is_front_page() || ( is_woocommerce() && ! is_product() ) ) : ?>
			<div class="product">
				<div class="menu-desc">
					<?php
					wp_nav_menu( [
						'theme_location' => 'header_categories',
						'container'      => false,
						'menu_class'     => 'header-categories-menu',
						'link_after'     => '<span class="menu-close"></span>',
					] );
					?>
				</div>
			</div>
		<?php endif; ?>
