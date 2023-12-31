<?php

/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked wc_print_notices - 10
 */
do_action('woocommerce_before_single_product');

?>
<section id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>
	<div class="container">
		<div class="product-item">
			<div class="one-product">
				<div class="summary entry-summary">
					<?php
					/**
					 * Hook: woocommerce_single_product_summary.
					 *
					 * @hooked woocommerce_template_single_title - 5
					 * @hooked woocommerce_template_single_description - 10
					 * @hooked WC_Structured_Data::generate_product_data() - 60
					 */
					do_action('woocommerce_single_product_summary');
					?>
				</div>
			</div>
		</div>
	</div>
</section>

<div class="container">
	<?= do_shortcode('[tehnokrat_accessories]<p class="h4">' . __('Recommended products', 'tehnokrat') . '</p>[/tehnokrat_accessories]') ?>
</div>

<?php do_action('woocommerce_after_single_product'); ?>
<?php if (get_field('text_' . get_request_locale(), get_the_ID())) : ?>
	<section class="text">
		<div class="container">
			<div class="text-content">
				<article>
					<?php
					printf(
						'<div class="title">%s</div>%s',
						get_field('title_' . get_request_locale(), get_the_ID()),
						get_field('text_' . get_request_locale(), get_the_ID())
					);
					?>
				</article>
			</div>
		</div>
	</section>
<?php endif; ?>