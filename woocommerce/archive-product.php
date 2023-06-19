<?php

/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

get_header(); ?>

<?php $o_cat = get_queried_object(); ?>

<div class="container">
  <?php woocommerce_breadcrumb() ?>
</div>

<section class="page-title">
  <div class="container">
    <div class="page-title-cont">
      <h1><?php echo $o_cat->name ?></h1>
      <div class="for-product">
        <div id="type-display-switcher"></div>
        <div id="only-in-stock-products-switcher"></div>
      </div>
    </div>
  </div>
</section>

<section class="product">
  <div class="container">
    <div id="widget-product-variation-selector" class="product-item"></div>
  </div>
</section>
<section class="text">
  <div class="container">
    <div class="text-content">
      <?php if (have_rows('reqs', $o_cat)) : ?>
        <!-- section links под товарами над article -->
        <div class="links-section">
          <h2><?php the_field('r_title', $o_cat) ?></h2>
          <div class="links-items">
            <?php while (have_rows('reqs', $o_cat)) : the_row(); ?>
              <a href="<?php the_sub_field('url'); ?>"><?php the_sub_field('title'); ?></a>
            <?php endwhile; ?>
          </div>
        </div>
      <?php endif; ?>

      <?php if (have_rows('faq', $o_cat)) : ?>
        <!-- section faq под быстрыми ссылками над article -->
        <div class="faq">
          <h2><?php the_field('faq_title', $o_cat) ?></h2>
          <div class="faq-items">
            <?php while (have_rows('faq', $o_cat)) : the_row(); ?>
              <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                <p class="faq-item-title" itemprop="name">
                  <?php the_sub_field('q'); ?></p>
                <div class="faq-item-content" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                  <p itemprop="text"><?php the_sub_field('a'); ?></p>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
        </div>
      <?php endif; ?>

      <?php
      if ($o_cat->parent > 0) {
        $args = array(
          'posts_per_page' => -1, // Grabs all of the featured products. Limit if needed
          'post_type'      => 'product', // Ensures the post type is a product
          'post_status'    => 'publish', // Ensures the product is published
          // 'cat' => $o_cat->term_id,
          'meta_query' => array(
            array(
              'key' => '_stock_status',
              'value' => 'instock',
              'compare' => '=',
            )
          ),
          'tax_query'      => array(
            'relation' => 'AND',
            array(
              'taxonomy'  => 'product_cat', // required
              'terms'     =>  $o_cat->term_id
            ),
            array(
              'taxonomy' => 'product_visibility', // Does a meta query on product visibility
              'field'    => 'name',
              'terms'    => 'featured', // Makes sure we grab all products flagged as featured
              'operator' => 'IN',
            ),
          )
        );
        $featuredProducts = new WP_Query($args);
        if ($featuredProducts->have_posts()) : ?>
          <!-- section price table под быстрыми ссылками над article -->
          <div class="price-table">
            <h2><?php echo get_field('tabl_price', $o_cat) . ' ' . $o_cat->name; ?></h2>
            <table>
              <tr>
                <th><?php _e('Name', 'woocommerce') ?></th>
                <th><?php _e('Price', 'woocommerce') ?></th>
              </tr>
              <?php while ($featuredProducts->have_posts()) {
                $featuredProducts->the_post();
                $product = wc_get_product(get_the_ID());
              ?>
                <tr>
                  <td><a href="<?php the_permalink($post->ID); ?>"><?php echo trim(preg_replace('/\[.*\]/', '', get_the_title())); ?></a>
                  </td>
                  <td><?php echo $product->get_price(); ?> грн.</td>
                </tr>
              <?php }
              wp_reset_postdata(); ?>
            </table>
          </div>
      <?php endif;
      } ?>
    </div>

    <div class="text-content">
      <article>
        <?php
        if ($_term = get_term_by('slug', $GLOBALS['term'], 'product_cat')) {
          printf(
            '<div class="title">%s</div>%s',
            get_field('title_' . get_request_locale(), "product_cat_{$_term->term_id}"),
            get_field('text_' . get_request_locale(), "product_cat_{$_term->term_id}")
          );
        }
        ?>
      </article>
    </div>
  </div>
</section>

<script>
  var acc = document.getElementsByClassName("faq-item-title");
  var allpanel = document.getElementsByClassName("faq-item-content");
  var i;

  for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function() {
      this.classList.toggle("active");
      var panel = this.nextElementSibling;
      if (panel.style.maxHeight) {
        panel.style.maxHeight = null;
      } else {
        panel.style.maxHeight = panel.scrollHeight + "px";
      }
    });
  }
</script>

<?php get_footer(); ?>