<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

get_header(); ?>

<section class="product">
	<div class="container">
		<div id="widget-product-variation-selector" class="product-item"></div>
	</div>
</section>

<section class="text">
	<div class="container">
		<div class="text-content">
			<article>
				<?php
				if ( $_term = get_term_by( 'slug', $GLOBALS['term'], 'product_cat' ) ) {
					printf(
						'<h1>%s</h1>%s',
						get_field( 'title_' . get_request_locale(), "product_cat_{$_term->term_id}" ),
						get_field( 'text_' . get_request_locale(), "product_cat_{$_term->term_id}" )
					);
				}
				?>
			</article>
		</div>
	</div>
</section>

<?php get_footer(); ?>
