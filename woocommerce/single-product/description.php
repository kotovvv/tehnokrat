<?php
/**
 * Single product description
 */

defined( 'ABSPATH' ) || exit;

/**
 * @var array $gallery
 * @var string $model
 * @var string $product_name
 * @var string $price_uah
 * @var string $price_usd
 * @var string $description
 * @var array $params
 */

?>

<ul class="prod-link">
	<li><a href="#what"><?= __( 'Description', 'tehnokrat' ) ?></a></li>
	<li><a href="#des"><?= __( 'Characteristics', 'tehnokrat' ) ?></a></li>
</ul>
<div class="about-product">
	<div class="img" id="widget-one-product"></div>
	<div class="about-product-desc">
		<div id="what" class="what">
			<?= $description ?>
		</div>
		<div id="des" class="prod-desc">
			<p class="ti"><?= __( 'Characteristics', 'tehnokrat' ) ?></p>
			<ul class="param">
				<?php foreach ( $params as $key => $value ) : ?>
					<li>
						<span><?= esc_html( $key ) ?>:</span>
						<p><?= esc_html( $value ) ?></p>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</div>
