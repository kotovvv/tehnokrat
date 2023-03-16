<?php

global $tehnokrat;

$exchange_rate = $tehnokrat->get_setting( 'main', 'usd_to_uah' );

$categories = get_categories( array(
	'orderby'  => 'term_id',
	'parent'   => 0,
	'taxonomy' => 'product_cat'
) );

$products_query_args = array(
	'posts_per_page'   => -1,
	'post_type'        => array('product'),
	'post_status'      => 'publish',
	'meta_query'       => array(
		array(
			'key'      => '_stock',
			'value'    => 0,
			'compare'  => '>',
			'type'     => 'NUMERIC'
		)
	),
	'orderby'          => 'ID',
	'order'            => 'DESC'
);

get_header(); ?>

<div class="pricelist">
	<div class="container">
		<div class="pricelist-content">
			<h1><?php the_title(); ?></h1>

			<?php foreach ( $categories as $category ) : ?>

				<?php
					if ( in_array( $category->slug, array( 'featured-products', 'without-publishing' ) ) ) {
						continue;
					}

					$products_query_args['tax_query'] = array(
						array(
							'taxonomy' => 'product_cat',
							'field'    => 'term_id',
							'terms'    => $category->term_id
						)
					);
					$query = new WP_Query( $products_query_args );
				?>
				<?php if ( $query->have_posts() ) : ?>
					<div class="content-panel">
						<h4><?php echo $category->name; ?></h4>
						<table>
							<thead>
								<tr>
									<th style="width:33%;">Наименование</th>
									<th style="width:53%;">Описание</th>
									<th style="width:7%;">USD</th>
									<th style="width:7%;">UAH</th>
								</tr>
							</thead>
							<tbody>
							<?php while ( $query->have_posts() ) : $query->the_post(); $product = wc_get_product( $query->post ); ?>
								<tr>
									<td>
										<?php echo $product->get_title(); ?>
									</td>
									<td>
										<?php echo $product->post->post_content; ?>
									</td>
									<td>
										<?php echo $product->regular_price; ?>
									</td>
									<td>
										<?php echo ( $exchange_rate * $product->regular_price ); ?>
									</td>
								</tr>
							<?php endwhile; ?>
						</tbody>
					</table>
				</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
