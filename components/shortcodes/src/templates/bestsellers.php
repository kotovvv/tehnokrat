<?php
/* @global $content */
/* @global $products */
?>

<section class="top-prod">
	<?= $content ?>
	<div class="top-slider">
		<?php foreach ( $products as $product ) : ?>
			<div>
				<div class="for-img">
					<?php if ( $product['label'] && $product['label_color'] ) : ?>
						<span class="pl" style="background: <?= $product['label_color'] ?>">
                            <?= $product['label'] ?>
                        </span>
					<?php endif; ?>
					<img src="<?= esc_attr( $product['image_src'] ) ?>" alt="<?= esc_attr( $product['title'] ) ?>"/>
				</div>
				<p class="name"><?= esc_html( $product['title'] ) ?></p>
				<div class="fot">
					<a class="bb-byu" href="<?= esc_url( $product['permalink'] ) ?>"><?= __( 'Go', 'tehnokrat' ) ?></a>
					<div class="pr">
						<p><?= $product['price_uah'] ?> грн</p>
						<span><?= $product['price_usd'] ?> $</span>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>
