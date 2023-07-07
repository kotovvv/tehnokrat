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
		<a class="big-link" href="<?= esc_url($product['permalink']) ?>" class="name"></a>
          <div class="product-img">
            <?php
            if ($product['part'] > 0 || $product['partprivat'] > 0) { ?>
              <div class="part">
                <?php if ($product['part'] > 0) { ?>
                  <span>
                    <div class="i">
                      <p class="h4">«Покупка частинами» від monobank</p><span>Для оформлення необхідно:</span>
                      <ul>
                        <li>1. Бути клієнтом monobank</li>
                        <li>2. Мати смартфон з додатком monobank</li>
                        <li>3. Перевірити доступний ліміт на розстрочку</li>
                        <li>4. Мати на карті суму для першого платежу</li>
                      </ul><a href="https://tehnokrat.ua/ua/shipping-and-payment/" target="_blank">Детальніше</a>
                    </div>
                    <img src="/wp-content/themes/tehnokrat/img/Mono.png" alt="monobank pay part">
                  </span>
                <?php }
                if ($product['partprivat'] > 0) { ?>
                  <span>
                    <div class="i">
                      <p class="h4">«Миттєва розстрочка» від ПриватБанк</p><span>Для оформлення необхідно:</span>
                      <ul>
                        <li>1. Мати картку «Універсальна»</li>
                        <li>2. <a href="https://paypartslimit.privatbank.ua/pp-limit/" target="_blank">Дізнатись свій
                            ліміт</a> «Миттєва розстрочка»</li>
                        <li>3. Мати на карті суму для першого платежу</li>
                      </ul><a href="https://tehnokrat.ua/ua/shipping-and-payment/" target="_blank">Детальніше</a>
                    </div><img src="/wp-content/themes/tehnokrat/img/Privat.png" alt="privatbank pay part">
                  </span>
                <?php } ?>
              </div>
            <?php } ?>
            <img class="prod-image" src="<?= esc_attr($product['image_src']) ?>" alt="<?= esc_attr($product['title']) ?>">
            <?php if ($product['label'] && $product['label_color']) : ?>
              <span class="pl" style="background: <?= $product['label_color'] ?>">
                <?= $product['label'] ?>
              </span>
            <?php endif; ?>
          </div>
          <a href="<?= esc_url($product['permalink']) ?>" class="name"><?= esc_html($product['title']) ?></a>
          <div class="sum-link clearfix">
            <div class="link w-cr">
              <a class="buy">
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