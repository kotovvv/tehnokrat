<?php
/* @global $content */
/* @global $products */
?>
<div class="top-prod-new">
  <?= $content ?>
  <div class="product-items">
    <?php foreach ($products as $product) : ?>
    <div class="product-item">
      <div class="product-cont">
        <div class="product-img">
          <img class="prod-image" src="<?= esc_attr($product['image_src']) ?>" alt="<?= esc_attr($product['title']) ?>">
        </div>
        <a href="<?= esc_url($product['permalink']) ?>" class="name"><?= esc_html($product['title']) ?></a>
        <div class="sum-link clearfix">
          <div class="link w-cr">
            <a class="buy" href="<?= esc_url($product['permalink']) ?>">
              <p><?= __('Go', 'tehnokrat') ?></p>
            </a>
          </div>
          <div class="sum">
            <p><?= $product['price_uah'] ?><span> грн</span></p>
            <span>$<?= $product['price_usd'] ?></span>
          </div>
        </div>

        <?php
          if (is_array($product['description']) && count($product['description']) > 0) { ?>
        <div class="features">
          <ul>
            <?php
                for ($i = 0; $i < count($product['description']); $i += 2) {
                  echo '<li><span>' . $product['description'][$i] . ': </span>' . $product['description'][$i + 1] . '</li>';
                } ?>
          </ul>
        </div>
        <?php }  ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>