<?php

/**
 * Single Product title
 *
 * @package    WooCommerce\Templates
 * @version    1.6.4
 */

defined('ABSPATH') || exit;

/**
 * @var string $category_url
 * @var string $category_name
 * @var string $product_name
 */

?>
<div class="page-title-cont">
	<a href="<?= esc_url($category_url) ?>" class="back-button">
		<svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
			<g clip-path="url(#clip0)">
				<path d="M15.1006 6.32368L15.1256 6.3288H4.41913L7.78485 3.16648C7.94966 3.01209 8.04007 2.80295 8.04007 2.58343C8.04007 2.36392 7.94966 2.15624 7.78485 2.00148L7.26114 1.51026C7.09646 1.35587 6.87701 1.27051 6.643 1.27051C6.40885 1.27051 6.18927 1.35526 6.02459 1.50965L0.255093 6.91807C0.0897596 7.07307 -0.000646921 7.27953 3.48506e-06 7.49917C-0.000646921 7.72002 0.0897596 7.92661 0.255093 8.08136L6.02459 13.4903C6.18927 13.6445 6.40872 13.7294 6.643 13.7294C6.87701 13.7294 7.09646 13.6444 7.26114 13.4903L7.78485 12.999C7.94966 12.8449 8.04007 12.639 8.04007 12.4195C8.04007 12.2001 7.94966 12.0051 7.78485 11.8509L4.38114 8.67087H15.1126C15.5948 8.67087 16 8.28124 16 7.82941V7.13465C16 6.68282 15.5828 6.32368 15.1006 6.32368Z" fill="#222222"></path>
			</g>
			<defs>
				<clipPath id="clip0">
					<rect width="16" height="15" fill="white"></rect>
				</clipPath>
			</defs>
		</svg>
		<?= __('all models', 'tehnokrat') ?>
	</a>
	<!-- <h1><?php //echo  esc_html($category_name) 
						?></h1> -->
	<h1><?= $product_name == '' ?  esc_html($category_name) : esc_html($product_name) ?></h1>
</div>